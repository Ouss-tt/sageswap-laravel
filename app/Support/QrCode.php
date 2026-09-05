<?php

namespace App\Support;

use InvalidArgumentException;

/**
 * Minimal QR encoder: byte mode, versions 1-10, EC levels L and M.
 *
 * Written in-house on purpose. The deposit address must never be handed to a
 * third-party QR service, and the project ships without JavaScript, so the
 * matrix is built here and rendered as inline SVG.
 */
final class QrCode
{
    /** Alignment pattern centres per version. */
    private const ALIGNMENT = [
        1 => [], 2 => [6, 18], 3 => [6, 22], 4 => [6, 26], 5 => [6, 30],
        6 => [6, 34], 7 => [6, 22, 38], 8 => [6, 24, 42], 9 => [6, 26, 46], 10 => [6, 28, 50],
    ];

    /** [ec codewords per block, blocks in group 1, data per block, blocks in group 2, data per block] */
    private const EC_BLOCKS = [
        'L' => [
            1 => [7, 1, 19, 0, 0], 2 => [10, 1, 34, 0, 0], 3 => [15, 1, 55, 0, 0],
            4 => [20, 1, 80, 0, 0], 5 => [26, 1, 108, 0, 0], 6 => [18, 2, 68, 0, 0],
            7 => [20, 2, 78, 0, 0], 8 => [24, 2, 97, 0, 0], 9 => [30, 2, 116, 0, 0],
            10 => [18, 2, 68, 2, 69],
        ],
        'M' => [
            1 => [10, 1, 16, 0, 0], 2 => [16, 1, 28, 0, 0], 3 => [26, 1, 44, 0, 0],
            4 => [18, 2, 32, 0, 0], 5 => [24, 2, 43, 0, 0], 6 => [16, 4, 27, 0, 0],
            7 => [18, 4, 31, 0, 0], 8 => [22, 2, 38, 2, 39], 9 => [22, 3, 36, 2, 37],
            10 => [26, 4, 43, 1, 44],
        ],
    ];

    private const ECC_BITS = ['L' => 0b01, 'M' => 0b00];

    /** @var int[] */
    private array $exp = [];

    /** @var int[] */
    private array $log = [];

    private int $version;

    private int $size;

    /** @var array<int, array<int, bool>> */
    private array $modules = [];

    /** @var array<int, array<int, bool>> */
    private array $reserved = [];

    public function __construct(private string $data, private string $ecLevel = 'M')
    {
        if (! isset(self::EC_BLOCKS[$this->ecLevel])) {
            throw new InvalidArgumentException("Unsupported EC level [{$this->ecLevel}].");
        }

        $this->initGaloisField();
        $this->version = $this->chooseVersion();
        $this->size = 21 + ($this->version - 1) * 4;

        $this->buildFunctionPatterns();
        $this->placeData($this->buildBitStream());
        $this->placeFormatInfo($this->applyBestMask());
    }

    public static function svg(string $data, int $quiet = 4, string $ecLevel = 'M'): string
    {
        return (new self($data, $ecLevel))->toSvg($quiet);
    }

    /* ------------------------------------------------------------------ */
    /* Galois field                                                        */
    /* ------------------------------------------------------------------ */

    private function initGaloisField(): void
    {
        $x = 1;

        for ($i = 0; $i < 255; $i++) {
            $this->exp[$i] = $x;
            $this->log[$x] = $i;
            $x <<= 1;

            if ($x & 0x100) {
                $x ^= 0x11D;
            }
        }

        for ($i = 255; $i < 512; $i++) {
            $this->exp[$i] = $this->exp[$i - 255];
        }
    }

    private function mul(int $a, int $b): int
    {
        if ($a === 0 || $b === 0) {
            return 0;
        }

        return $this->exp[$this->log[$a] + $this->log[$b]];
    }

    /* ------------------------------------------------------------------ */
    /* Data encoding                                                       */
    /* ------------------------------------------------------------------ */

    private function dataCodewords(int $version): int
    {
        [, $blocks1, $data1, $blocks2, $data2] = self::EC_BLOCKS[$this->ecLevel][$version];

        return $blocks1 * $data1 + $blocks2 * $data2;
    }

    private function chooseVersion(): int
    {
        $length = strlen($this->data);

        foreach (array_keys(self::EC_BLOCKS[$this->ecLevel]) as $version) {
            $countBits = $version <= 9 ? 8 : 16;
            $needed = 4 + $countBits + $length * 8;

            if ($needed <= $this->dataCodewords($version) * 8) {
                return $version;
            }
        }

        throw new InvalidArgumentException('Payload too large for versions 1-10.');
    }

    private function buildBitStream(): string
    {
        $capacity = $this->dataCodewords($this->version) * 8;
        $countBits = $this->version <= 9 ? 8 : 16;

        $bits = '0100';
        $bits .= str_pad(decbin(strlen($this->data)), $countBits, '0', STR_PAD_LEFT);

        foreach (str_split($this->data) as $char) {
            $bits .= str_pad(decbin(ord($char)), 8, '0', STR_PAD_LEFT);
        }

        $bits .= str_repeat('0', min(4, $capacity - strlen($bits)));

        if (strlen($bits) % 8 !== 0) {
            $bits .= str_repeat('0', 8 - strlen($bits) % 8);
        }

        $padBytes = ['11101100', '00010001'];
        $index = 0;

        while (strlen($bits) < $capacity) {
            $bits .= $padBytes[$index % 2];
            $index++;
        }

        return $this->interleave($bits);
    }

    private function interleave(string $bits): string
    {
        [$ecLength, $blocks1, $data1, $blocks2, $data2] = self::EC_BLOCKS[$this->ecLevel][$this->version];

        $codewords = [];

        foreach (str_split($bits, 8) as $byte) {
            $codewords[] = bindec($byte);
        }

        $dataBlocks = [];
        $ecBlocks = [];
        $offset = 0;

        foreach ([[$blocks1, $data1], [$blocks2, $data2]] as [$count, $length]) {
            for ($i = 0; $i < $count; $i++) {
                $block = array_slice($codewords, $offset, $length);
                $offset += $length;

                $dataBlocks[] = $block;
                $ecBlocks[] = $this->errorCorrection($block, $ecLength);
            }
        }

        $out = '';
        $longest = max(array_map('count', $dataBlocks));

        for ($i = 0; $i < $longest; $i++) {
            foreach ($dataBlocks as $block) {
                if (isset($block[$i])) {
                    $out .= str_pad(decbin($block[$i]), 8, '0', STR_PAD_LEFT);
                }
            }
        }

        for ($i = 0; $i < $ecLength; $i++) {
            foreach ($ecBlocks as $block) {
                $out .= str_pad(decbin($block[$i]), 8, '0', STR_PAD_LEFT);
            }
        }

        return $out;
    }

    /** @param int[] $block @return int[] */
    private function errorCorrection(array $block, int $ecLength): array
    {
        $generator = $this->generatorPolynomial($ecLength);
        $remainder = array_merge($block, array_fill(0, $ecLength, 0));
        $dataLength = count($block);

        for ($i = 0; $i < $dataLength; $i++) {
            $factor = $remainder[$i];

            if ($factor === 0) {
                continue;
            }

            foreach ($generator as $j => $coefficient) {
                $remainder[$i + $j] ^= $this->mul($coefficient, $factor);
            }
        }

        return array_values(array_slice($remainder, $dataLength, $ecLength));
    }

    /** @return int[] */
    private function generatorPolynomial(int $degree): array
    {
        $poly = [1];

        for ($i = 0; $i < $degree; $i++) {
            $next = array_fill(0, count($poly) + 1, 0);

            foreach ($poly as $j => $coefficient) {
                $next[$j] ^= $coefficient;
                $next[$j + 1] ^= $this->mul($coefficient, $this->exp[$i]);
            }

            $poly = $next;
        }

        return $poly;
    }

    /* ------------------------------------------------------------------ */
    /* Matrix                                                              */
    /* ------------------------------------------------------------------ */

    private function setFunction(int $row, int $col, bool $dark): void
    {
        if ($row < 0 || $col < 0 || $row >= $this->size || $col >= $this->size) {
            return;
        }

        $this->modules[$row][$col] = $dark;
        $this->reserved[$row][$col] = true;
    }

    private function buildFunctionPatterns(): void
    {
        for ($r = 0; $r < $this->size; $r++) {
            for ($c = 0; $c < $this->size; $c++) {
                $this->modules[$r][$c] = false;
                $this->reserved[$r][$c] = false;
            }
        }

        foreach ([[0, 0], [0, $this->size - 7], [$this->size - 7, 0]] as [$row, $col]) {
            $this->placeFinder($row, $col);
        }

        for ($i = 8; $i < $this->size - 8; $i++) {
            $dark = $i % 2 === 0;
            $this->setFunction(6, $i, $dark);
            $this->setFunction($i, 6, $dark);
        }

        $centres = self::ALIGNMENT[$this->version];
        $last = empty($centres) ? 0 : end($centres);

        foreach ($centres as $row) {
            foreach ($centres as $col) {
                $isFinderCorner = ($row === 6 && $col === 6)
                    || ($row === 6 && $col === $last)
                    || ($row === $last && $col === 6);

                if ($isFinderCorner) {
                    continue;
                }

                $this->placeAlignment($row, $col);
            }
        }

        $this->reserveFormatAreas();
        $this->placeVersionInfo();
    }

    private function placeFinder(int $row, int $col): void
    {
        for ($r = -1; $r <= 7; $r++) {
            for ($c = -1; $c <= 7; $c++) {
                $onRing = ($r >= 0 && $r <= 6 && ($c === 0 || $c === 6))
                    || ($c >= 0 && $c <= 6 && ($r === 0 || $r === 6));
                $inCore = $r >= 2 && $r <= 4 && $c >= 2 && $c <= 4;

                $this->setFunction($row + $r, $col + $c, $onRing || $inCore);
            }
        }
    }

    private function placeAlignment(int $row, int $col): void
    {
        for ($r = -2; $r <= 2; $r++) {
            for ($c = -2; $c <= 2; $c++) {
                $this->setFunction($row + $r, $col + $c, max(abs($r), abs($c)) !== 1);
            }
        }
    }

    private function reserveFormatAreas(): void
    {
        for ($i = 0; $i <= 5; $i++) {
            $this->setFunction(8, $i, false);
            $this->setFunction($i, 8, false);
        }

        $this->setFunction(8, 7, false);
        $this->setFunction(8, 8, false);
        $this->setFunction(7, 8, false);

        for ($i = 0; $i < 7; $i++) {
            $this->setFunction($this->size - 1 - $i, 8, false);
        }

        for ($i = 0; $i < 8; $i++) {
            $this->setFunction(8, $this->size - 1 - $i, false);
        }

        // The dark module is fixed and is never masked.
        $this->setFunction($this->size - 8, 8, true);
    }

    private function placeVersionInfo(): void
    {
        if ($this->version < 7) {
            return;
        }

        $remainder = $this->version;

        for ($i = 0; $i < 12; $i++) {
            $remainder = ($remainder << 1) ^ ((($remainder >> 11) & 1) * 0x1F25);
        }

        $bits = ($this->version << 12) | $remainder;

        for ($i = 0; $i < 18; $i++) {
            $dark = (($bits >> $i) & 1) === 1;
            $a = intdiv($i, 3);
            $b = $i % 3;

            $this->setFunction($a, $this->size - 11 + $b, $dark);
            $this->setFunction($this->size - 11 + $b, $a, $dark);
        }
    }

    private function placeData(string $bits): void
    {
        $index = 0;
        $length = strlen($bits);
        $upward = true;

        for ($right = $this->size - 1; $right >= 1; $right -= 2) {
            if ($right === 6) {
                $right = 5;
            }

            for ($v = 0; $v < $this->size; $v++) {
                $row = $upward ? $this->size - 1 - $v : $v;

                for ($k = 0; $k < 2; $k++) {
                    $col = $right - $k;

                    if ($this->reserved[$row][$col]) {
                        continue;
                    }

                    $this->modules[$row][$col] = $index < $length && $bits[$index] === '1';
                    $index++;
                }
            }

            $upward = ! $upward;
        }
    }

    /* ------------------------------------------------------------------ */
    /* Masking                                                             */
    /* ------------------------------------------------------------------ */

    private function maskApplies(int $mask, int $row, int $col): bool
    {
        return match ($mask) {
            0 => ($row + $col) % 2 === 0,
            1 => $row % 2 === 0,
            2 => $col % 3 === 0,
            3 => ($row + $col) % 3 === 0,
            4 => (intdiv($row, 2) + intdiv($col, 3)) % 2 === 0,
            5 => ($row * $col) % 2 + ($row * $col) % 3 === 0,
            6 => (($row * $col) % 2 + ($row * $col) % 3) % 2 === 0,
            7 => (($row + $col) % 2 + ($row * $col) % 3) % 2 === 0,
        };
    }

    private function toggleMask(int $mask): void
    {
        for ($r = 0; $r < $this->size; $r++) {
            for ($c = 0; $c < $this->size; $c++) {
                if (! $this->reserved[$r][$c] && $this->maskApplies($mask, $r, $c)) {
                    $this->modules[$r][$c] = ! $this->modules[$r][$c];
                }
            }
        }
    }

    private function applyBestMask(): int
    {
        $bestMask = 0;
        $bestPenalty = PHP_INT_MAX;

        for ($mask = 0; $mask < 8; $mask++) {
            $this->toggleMask($mask);
            $this->placeFormatInfo($mask);
            $penalty = $this->penalty();
            $this->toggleMask($mask);

            if ($penalty < $bestPenalty) {
                $bestPenalty = $penalty;
                $bestMask = $mask;
            }
        }

        $this->toggleMask($bestMask);

        return $bestMask;
    }

    private function penalty(): int
    {
        $score = 0;
        $dark = 0;

        // Rule 1, runs of five or more; the dark tally feeds rule 4.
        foreach ([true, false] as $horizontal) {
            for ($a = 0; $a < $this->size; $a++) {
                $run = 1;

                for ($b = 0; $b < $this->size; $b++) {
                    $current = $horizontal ? $this->modules[$a][$b] : $this->modules[$b][$a];

                    if ($horizontal && $current) {
                        $dark++;
                    }

                    if ($b > 0) {
                        $previous = $horizontal ? $this->modules[$a][$b - 1] : $this->modules[$b - 1][$a];

                        if ($current === $previous) {
                            $run++;

                            continue;
                        }
                    }

                    if ($run >= 5) {
                        $score += 3 + ($run - 5);
                    }

                    $run = 1;
                }

                if ($run >= 5) {
                    $score += 3 + ($run - 5);
                }
            }
        }

        // Rule 2, blocks of one colour.
        for ($r = 0; $r < $this->size - 1; $r++) {
            for ($c = 0; $c < $this->size - 1; $c++) {
                $value = $this->modules[$r][$c];

                if ($value === $this->modules[$r][$c + 1]
                    && $value === $this->modules[$r + 1][$c]
                    && $value === $this->modules[$r + 1][$c + 1]) {
                    $score += 3;
                }
            }
        }

        // Rule 3, finder-like sequences.
        $patterns = ['10111010000', '00001011101'];

        for ($a = 0; $a < $this->size; $a++) {
            $row = '';
            $col = '';

            for ($b = 0; $b < $this->size; $b++) {
                $row .= $this->modules[$a][$b] ? '1' : '0';
                $col .= $this->modules[$b][$a] ? '1' : '0';
            }

            foreach ($patterns as $pattern) {
                $score += 40 * (substr_count($row, $pattern) + substr_count($col, $pattern));
            }
        }

        // Rule 4, proportion of dark modules.
        $total = $this->size * $this->size;
        $balance = (int) floor(abs($dark * 100 / $total - 50) / 5);

        return $score + $balance * 10;
    }

    private function placeFormatInfo(int $mask): void
    {
        $data = (self::ECC_BITS[$this->ecLevel] << 3) | $mask;
        $remainder = $data;

        for ($i = 0; $i < 10; $i++) {
            $remainder = ($remainder << 1) ^ ((($remainder >> 9) & 1) * 0x537);
        }

        $bits = (($data << 10) | $remainder) ^ 0x5412;

        for ($i = 0; $i < 15; $i++) {
            $dark = (($bits >> $i) & 1) === 1;

            if ($i < 6) {
                $this->modules[$i][8] = $dark;
            } elseif ($i === 6) {
                $this->modules[7][8] = $dark;
            } elseif ($i === 7) {
                $this->modules[8][8] = $dark;
            } elseif ($i === 8) {
                $this->modules[8][7] = $dark;
            } else {
                $this->modules[8][14 - $i] = $dark;
            }

            if ($i < 8) {
                $this->modules[8][$this->size - 1 - $i] = $dark;
            } else {
                $this->modules[$this->size - 15 + $i][8] = $dark;
            }
        }
    }

    /* ------------------------------------------------------------------ */
    /* Output                                                              */
    /* ------------------------------------------------------------------ */

    public function toSvg(int $quiet = 4): string
    {
        $dimension = $this->size + $quiet * 2;
        $path = '';

        for ($r = 0; $r < $this->size; $r++) {
            for ($c = 0; $c < $this->size; $c++) {
                if ($this->modules[$r][$c]) {
                    $path .= 'M'.($c + $quiet).' '.($r + $quiet).'h1v1h-1z';
                }
            }
        }

        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 '.$dimension.' '.$dimension.'"'
            .' width="100%" height="100%" shape-rendering="crispEdges" role="img"'
            .' aria-label="QR code for the deposit address">'
            .'<rect width="'.$dimension.'" height="'.$dimension.'" fill="#ffffff"/>'
            .'<path d="'.$path.'" fill="#000000"/>'
            .'</svg>';
    }
}

<?php

namespace App\Support;

/**
 * Reads the press kit manifest and fills in what can be measured.
 *
 * HANDOFF CONTRACT
 * ----------------
 * The /press view never touches config/press.php directly - it asks for the
 * arrays below. Replacing the config with uploaded records means returning the
 * same shape from here:
 *
 *   assets(): list of [
 *     'id', 'name', 'note',
 *     'preview'   => URL for the <img> on the page,
 *     'dimensions'=> "600 x 600" or null,
 *     'downloads' => list of ['url', 'label' (e.g. "PNG")],
 *   ]
 *
 * Pixel dimensions are measured off disk rather than written into the manifest,
 * so they can never disagree with the file being served. An entry whose file is
 * missing is dropped instead of rendered broken, which means a path may be
 * listed before the file exists.
 *
 * File sizes are deliberately not shown: they told a press user nothing they
 * act on and cost a line of small type next to every download.
 */
final class PressKit
{
    /**
     * One manifest section ('logos' or 'images'), enriched and filtered.
     *
     * @return list<array<string, mixed>>
     */
    public static function assets(string $section): array
    {
        $out = [];

        foreach (config("press.$section", []) as $asset) {
            $preview = $asset['preview'] ?? null;

            // No preview on disk means nothing worth showing: a press page with
            // a broken image plate is worse than one asset short.
            if ($preview === null || ! self::exists($preview)) {
                continue;
            }

            $downloads = [];

            foreach ($asset['downloads'] ?? [] as $path) {
                if (! self::exists($path)) {
                    continue;
                }

                $downloads[] = [
                    'url' => asset($path),
                    'label' => strtoupper(pathinfo($path, PATHINFO_EXTENSION)),
                ];
            }

            $out[] = [
                'id' => $asset['id'] ?? '',
                'name' => $asset['name'] ?? '',
                'note' => $asset['note'] ?? '',
                'preview' => asset($preview),
                'dimensions' => self::dimensions($preview),
                'downloads' => $downloads,
            ];
        }

        return $out;
    }

    /**
     * The whole-kit download, or null while the zip has not been built.
     *
     * @return array{url: string}|null
     */
    public static function kit(): ?array
    {
        $path = config('press.kit');

        if ($path === null || ! self::exists($path)) {
            return null;
        }

        return ['url' => asset($path)];
    }

    /** Absolute path for a manifest entry, which is always relative to public/. */
    private static function path(string $path): string
    {
        return public_path($path);
    }

    private static function exists(string $path): bool
    {
        return is_file(self::path($path));
    }

    /**
     * Pixel dimensions, or null for formats that have none.
     *
     * SVG carries no raster size, and getimagesize() returns false for it
     * rather than throwing, so the null simply means "do not print a size".
     */
    private static function dimensions(string $path): ?string
    {
        $info = @getimagesize(self::path($path));

        if ($info === false) {
            return null;
        }

        return $info[0].' × '.$info[1];
    }
}

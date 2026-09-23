<?php

namespace App\Support;

/**
 * The notices that sit in the top-right corner of the homepage and the
 * affiliate area.
 *
 * HANDOFF CONTRACT
 * ----------------
 * The layout never reads config/announcements.php - it asks this class what the
 * page being rendered should show. Publishing announcements from a database or
 * an admin screen means returning the same shape from visible(), and neither
 * the partial nor the stylesheet changes:
 *
 *   visible(): list of [
 *     'id'    => slug, unique within its surface. It becomes the id of the
 *                dismiss control, so changing it reopens a closed notice,
 *     'tone'  => 'neutral' | 'warning' | 'danger', which picks the colour,
 *     'title' => short heading, or null for a one-sentence notice,
 *     'body'  => the sentence the visitor reads,
 *   ]
 *
 * Dismissal is deliberately not stored anywhere. The notices close in CSS and
 * come back on the next page load, which is the right behaviour for an outage
 * that has not ended yet, and it keeps the feature free of both JavaScript and
 * a write path.
 */
final class Announcement
{
    /**
     * Which route names make up each surface.
     *
     * Both swap tabs count as the homepage: they are one panel in two modes,
     * and a visitor who flips to NO AML has not left the page an outage notice
     * was meant for. Drop 'aml-swap' from this list to change that.
     */
    private const SURFACES = [
        'home' => ['swap', 'aml-swap'],
        'affiliate' => ['affiliate.*'],
    ];

    /**
     * Tones with a fill behind them; anything else is shown in the site green.
     *
     * The fallback is the loud one on purpose. A notice only reaches the page
     * because someone published it, so a tone this class does not recognise
     * should still be seen - quietly dropping it to grey would hide the very
     * thing that was meant to interrupt.
     */
    private const TONES = ['accent', 'warning', 'danger'];

    /** How many notices render at once, before the stack starts eating the page. */
    private const LIMIT = 3;

    /**
     * The announcements for the page being rendered, ready to print.
     *
     * A page belonging to no surface - FAQ, support, a transaction - gets an
     * empty list, so the partial can be included once in the layout rather than
     * page by page.
     *
     * @return list<array<string, mixed>>
     */
    public static function visible(): array
    {
        $surface = self::surface();

        if ($surface === null) {
            return [];
        }

        $out = [];

        foreach (config("announcements.$surface", []) as $entry) {
            $notice = self::clean($entry);

            // A malformed entry is skipped rather than rendered half-empty: an
            // announcement with no text is a blank box floating over the page.
            if ($notice === null) {
                continue;
            }

            $out[] = $notice;

            if (count($out) === self::LIMIT) {
                break;
            }
        }

        return $out;
    }

    /**
     * The surface the current route belongs to, or null for a page with none.
     */
    public static function surface(): ?string
    {
        foreach (self::SURFACES as $surface => $routes) {
            if (request()->routeIs($routes)) {
                return $surface;
            }
        }

        return null;
    }

    /**
     * One config entry, normalised - or null when there is nothing to show.
     *
     * @param  mixed  $entry
     * @return array<string, mixed>|null
     */
    private static function clean($entry): ?array
    {
        if (! is_array($entry)) {
            return null;
        }

        $body = trim((string) ($entry['body'] ?? ''));
        $id = self::slug($entry['id'] ?? '');

        if ($body === '' || $id === '') {
            return null;
        }

        $title = trim((string) ($entry['title'] ?? ''));
        $tone = (string) ($entry['tone'] ?? 'accent');

        return [
            'id' => $id,
            'tone' => in_array($tone, self::TONES, true) ? $tone : 'accent',
            'title' => $title === '' ? null : $title,
            'body' => $body,
        ];
    }

    /**
     * An id safe to print into the markup.
     *
     * The id reaches the page as an HTML id and as a label's `for`, and a
     * backend may one day feed this whatever it stores, so it is narrowed here
     * rather than trusted.
     */
    private static function slug($id): string
    {
        return trim(preg_replace('/[^a-z0-9]+/', '-', strtolower((string) $id)), '-');
    }
}

@props(['coin', 'size' => 20])

@php
    /* Chain variants of the same token share one glyph: usdterc20, usdtsol and
       friends all draw the Tether mark. Order matters only in that "usdd" and
       "usdt" are distinct four-letter prefixes. */
    $glyph = match (true) {
        str_starts_with($coin, 'usdt') => 'usdt',
        str_starts_with($coin, 'usdc') => 'usdc',
        str_starts_with($coin, 'usdd') => 'usdd',
        default => $coin,
    };
@endphp

{{--
    Every coin sits in the same ring: r=9.25 at stroke 1.5, mark centred inside.
    The badge being uniform is the whole point, so a new coin copies the <svg>
    opening tag and the <circle> verbatim and swaps only what follows.

    Marks follow real brand geometry rather than a generic letter, which is what
    keeps the lookalikes apart: BTC is the tilted "B" the Bitcoin logo actually
    uses and BCH the upright one, while the three D-shaped stablecoins differ
    the way their own logos do -- USDD carries a bar to its left, DAI two bars
    through the bowl, DOGE the single stroke of the currency symbol.
--}}
@switch($glyph)
    @case('btc')
        <svg viewBox="0 0 24 24" width="{{ $size }}" height="{{ $size }}" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9.25"/><g transform="rotate(-13 12 12)"><path d="M10 8.1h3a1.9 1.9 0 0 1 0 3.8h-3"/><path d="M10 11.9h3.4a2 2 0 0 1 0 4H10"/><path d="M10 7.7v8.6"/><path d="M11.4 6.4v1.3M13.1 6.4v1.3M11.4 16.3v1.3M13.1 16.3v1.3"/></g></svg>
        @break

    @case('bch')
        <svg viewBox="0 0 24 24" width="{{ $size }}" height="{{ $size }}" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9.25"/><path d="M10 8.1h3a1.9 1.9 0 0 1 0 3.8h-3"/><path d="M10 11.9h3.4a2 2 0 0 1 0 4H10"/><path d="M10 7.7v8.6"/><path d="M12.2 6.4v1.3M12.2 16.3v1.3"/></svg>
        @break

    @case('eth')
        <svg viewBox="0 0 24 24" width="{{ $size }}" height="{{ $size }}" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9.25"/><path d="m12 5.2 4.3 6.5-4.3 2.5-4.3-2.5z"/><path d="m8 14.4 4 5.2 4-5.2-4 2.3z"/></svg>
        @break

    @case('dash')
        <svg viewBox="0 0 24 24" width="{{ $size }}" height="{{ $size }}" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9.25"/><path fill="currentColor" stroke="none" d="M14.4 7.7H9.8l-.7 2.5h4.6c1.1 0 1.5.5 1.3 1.5-.2 1.1-.9 1.7-2.2 1.7H8.6l-.7 2.6h5c2.7 0 4.2-1.4 4.7-4 .5-2.7-.7-4.3-3.2-4.3Z"/><path fill="currentColor" stroke="none" d="M6.1 10.3h2.6l-.7 2.5H5.4l.7-2.5Z"/></svg>
        @break

    @case('usdd')
        <svg viewBox="0 0 24 24" width="{{ $size }}" height="{{ $size }}" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9.25"/><path d="M9.2 7.7h2.2a4.3 4.3 0 0 1 0 8.6H9.2z"/></svg>
        @break

    @case('usdt')
        <svg viewBox="0 0 24 24" width="{{ $size }}" height="{{ $size }}" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9.25"/><path d="M7.7 8.3h8.6"/><path d="M12 8.3v8.1"/><ellipse cx="12" cy="11.5" rx="4.1" ry="1.9"/></svg>
        @break

    @case('usdc')
        <svg viewBox="0 0 24 24" width="{{ $size }}" height="{{ $size }}" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9.25"/><path d="M14.2 9.5a2.7 2.7 0 0 0-2.3-1.1c-1.5 0-2.5.7-2.5 1.9s1 1.7 2.6 2.1 2.6 1 2.6 2.1-1.1 2-2.7 2a3 3 0 0 1-2.5-1.2"/><path d="M12 6.8v10.4"/></svg>
        @break

    @case('trx')
        <svg viewBox="0 0 24 24" width="{{ $size }}" height="{{ $size }}" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9.25"/><path d="M5.7 7.7 17.9 10.1l-6.7 7.6z"/><path d="m5.7 7.7 5.5 4.6 6.7-2.2"/><path d="M11.2 12.3v5.4"/></svg>
        @break

    @case('ton')
        <svg viewBox="0 0 24 24" width="{{ $size }}" height="{{ $size }}" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9.25"/><path d="M8.1 8.3h7.8a1.4 1.4 0 0 1 1.1 2.2l-4.4 6.4a.7.7 0 0 1-1.2 0L7 10.5a1.4 1.4 0 0 1 1.1-2.2Z"/><path d="m9.6 8.3 2.4 3.4 2.4-3.4"/><path d="M12 11.7v5.6"/></svg>
        @break

    @case('xrp')
        <svg viewBox="0 0 24 24" width="{{ $size }}" height="{{ $size }}" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9.25"/><path d="M7.4 7.9c1.9 0 2.6 3.7 4.6 3.7s2.7-3.7 4.6-3.7"/><path d="M7.4 16.1c1.9 0 2.6-3.7 4.6-3.7s2.7 3.7 4.6 3.7"/></svg>
        @break

    @case('sol')
        <svg viewBox="0 0 24 24" width="{{ $size }}" height="{{ $size }}" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9.25"/><path fill="currentColor" stroke="none" d="M8.7 8h7.9l-2.3 1.9H6.4L8.7 8Z"/><path fill="currentColor" stroke="none" d="M6.4 12.05h7.9l2.3-1.9H8.7l-2.3 1.9Z"/><path fill="currentColor" stroke="none" d="M8.7 14.1h7.9L14.3 16H6.4l2.3-1.9Z"/></svg>
        @break

    @case('ltc')
        <svg viewBox="0 0 24 24" width="{{ $size }}" height="{{ $size }}" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9.25"/><path d="M13.6 6.9 11.2 15.8h4.6"/><path d="m8.2 12.7 6.3-1.9"/></svg>
        @break

    @case('dai')
        <svg viewBox="0 0 24 24" width="{{ $size }}" height="{{ $size }}" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9.25"/><path d="M10.1 7.7h1.8a4.3 4.3 0 0 1 0 8.6h-1.8z"/><path d="M6.5 10.4h8.3M6.5 13.6h8.3"/></svg>
        @break

    @case('doge')
        <svg viewBox="0 0 24 24" width="{{ $size }}" height="{{ $size }}" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9.25"/><path d="M10.1 7.7h1.8a4.3 4.3 0 0 1 0 8.6h-1.8z"/><path d="M7.6 12h4"/></svg>
        @break

    @case('bnb')
        <svg viewBox="0 0 24 24" width="{{ $size }}" height="{{ $size }}" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9.25"/><path d="M12 9.6 14.4 12 12 14.4 9.6 12z"/><path d="M12 5.9 13.5 7.4 12 8.9 10.5 7.4z"/><path d="M12 15.1 13.5 16.6 12 18.1 10.5 16.6z"/><path d="M7.4 10.5 8.9 12l-1.5 1.5L5.9 12z"/><path d="M16.6 10.5 18.1 12l-1.5 1.5L15.1 12z"/></svg>
        @break

    @case('zano')
        <svg viewBox="0 0 24 24" width="{{ $size }}" height="{{ $size }}" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9.25"/><path d="M8.5 8.4h7l-7 7.2h7"/></svg>
        @break

    @case('xmr')
        <svg viewBox="0 0 24 24" width="{{ $size }}" height="{{ $size }}" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9.25"/><path d="M6.9 15.7V8.8l5.1 5 5.1-5v6.9"/><path d="m9.6 15.7 2.4-2.4 2.4 2.4"/></svg>
        @break
@endswitch

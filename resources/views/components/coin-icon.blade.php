@props(['coin', 'size' => 20])

@switch($coin)
    @case('btc')
        <svg viewBox="0 0 24 24" width="{{ $size }}" height="{{ $size }}" fill="currentColor"><path d="M15.3 10.6c.2-1.4-.9-2.2-2.4-2.7l.5-2-1.2-.3-.5 1.9-1-.2.5-2-1.2-.3-.5 2-2.4-.6-.3 1.3s.9.2.9.2c.5.1.6.4.6.7l-1.4 5.6c-.1.2-.2.4-.5.3 0 0-.9-.2-.9-.2l-.6 1.4 2.3.6-.5 2 1.2.3.5-2 1 .2-.5 2 1.2.3.5-2c2.1.4 3.6.2 4.3-1.6.5-1.5 0-2.4-1.1-3 .8-.2 1.4-.7 1.5-1.9Zm-2.7 4.1c-.4 1.5-3 .7-3.8.5l.7-2.7c.8.2 3.5.6 3.1 2.2Zm.4-4.1c-.4 1.4-2.5.7-3.2.5l.6-2.4c.7.2 2.9.5 2.6 1.9Z"/></svg>
        @break

    @case('eth')
        <svg viewBox="0 0 24 24" width="{{ $size }}" height="{{ $size }}" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 3 6.5 12 12 15.2 17.5 12 12 3Z"/><path d="m6.5 13.4 5.5 7.6 5.5-7.6-5.5 3.2-5.5-3.2Z"/></svg>
        @break

    @case('ltc')
        <svg viewBox="0 0 24 24" width="{{ $size }}" height="{{ $size }}" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="12" r="9"/><path d="M13.6 6.6 11.1 16H16"/><path d="m8.4 12.7 6.4-1.9"/></svg>
        @break

    @case('usdt')
        <svg viewBox="0 0 24 24" width="{{ $size }}" height="{{ $size }}" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="12" r="9"/><path d="M8 8.6h8"/><path d="M12 8.6v8.8"/></svg>
        @break

    @case('xmr')
        <svg viewBox="0 0 24 24" width="{{ $size }}" height="{{ $size }}" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="12" r="9"/><path d="M7 15V9l5 4 5-4v6"/></svg>
        @break
@endswitch

{{--
  The deposit clock: TIME LEFT, then the time.

  While the swap still takes a deposit, the countdown runs in CSS alone: the
  server prints the seconds left as --tx-seconds and app.css ticks them down,
  then swaps in EXPIRED while the page's meta refresh loads the expired page. A
  refresh re-renders the number, so the clock re-syncs each time.

  Everywhere else - a window already shut, or a status that has lapsed - it is
  a plain EXPIRED with nothing animated.

  $standalone: set when the row stands on its own rather than inside the deposit
  instructions, which gives it the spacing of a section.
--}}
<p class="tx-deadline{{ ($standalone ?? false) ? ' tx-deadline--standalone' : '' }}">
  TIME LEFT
  @if ($transaction['status']['deposit'] && $transaction['seconds_left'] > 0)
    <span class="tx-deadline__value tx-timer" role="timer" style="--tx-seconds: {{ (int) $transaction['seconds_left'] }}">
      <span class="tx-timer__clock"></span>
      <span class="tx-timer__expired">EXPIRED</span>
    </span>
  @else
    <span class="tx-deadline__value tx-timer tx-timer--expired">
      <span class="tx-timer__expired">EXPIRED</span>
    </span>
  @endif
</p>

{{--
  The message that sits under the status line.

  Copy lives here rather than in TransactionStatus because most of these lines
  interpolate the swap itself; the class stays a pure lookup table.
--}}
@switch($transaction['status']['code'])
  @case('new')
    {{-- Nothing to say: the deposit block below carries its own warning. --}}
    @break

  @case('confirming')
    <div class="notice notice--neutral">
      <p class="notice__title">Deposit detected.</p>
      <p class="notice__body">
        Your transfer is on the network and we are waiting for it to confirm.
        Do not send again &mdash; the payout starts on its own once the deposit
        settles.
      </p>
    </div>
    @break

  @case('sending')
    <div class="notice notice--neutral">
      <p class="notice__title">Your payout is on its way.</p>
      <p class="notice__body">
        {{ $transaction['receive_amount'] }} {{ $transaction['receive_coin_label'] }}
        has left escrow and is being sent to your wallet address.
      </p>
    </div>
    @break

  @case('finished')
    <div class="notice">
      <p class="notice__title">Swap completed.</p>
      <p class="notice__body">
        {{ $transaction['receive_amount'] }} {{ $transaction['receive_coin_label'] }}
        was sent to your wallet address. Keep the transaction ID if you need a
        <a href="{{ route('transparency') }}" class="link-accent">Letter of Guarantee</a>.
      </p>
    </div>
    @break

  @case('expired')
    <div class="notice notice--danger">
      <p class="notice__title">This deposit window has closed.</p>
      <p class="notice__body">
        Do not send to the deposit address &mdash; the rate is no longer held.
        <a href="{{ route('swap') }}" class="link-accent">Start a new swap</a>
        to get a fresh address.
      </p>
    </div>
    @break

  @case('error')
    <div class="notice notice--danger">
      <p class="notice__title">This swap could not be completed.</p>
      <p class="notice__body">
        Nothing is lost. Send us the transaction ID above and we will either
        finish the swap or return your deposit.
      </p>
    </div>
    @break

  @case('refunded')
    <div class="notice notice--warning">
      <p class="notice__title">Your deposit has been returned.</p>
      <p class="notice__body">
        The {{ $transaction['send_coin_label'] }} went back to your refund
        address. If it has not arrived, quote the transaction ID above.
      </p>
    </div>
    @break

  @case('support')
    <div class="notice notice--warning">
      <p class="notice__title">This swap needs a manual review.</p>
      <p class="notice__body">
        Your funds are held in escrow and are not at risk. Get in touch with the
        transaction ID above and we will finish it with you.
      </p>
    </div>
    @break

  @default
    {{-- An unmapped status: say something true and ask for nothing. --}}
    <div class="notice notice--neutral">
      <p class="notice__title">This swap is processing.</p>
      <p class="notice__body">
        Nothing further is needed from you right now. Refresh for the latest
        status, or get in touch if it stays here.
      </p>
    </div>
@endswitch

@if ($transaction['status']['support'])
  <div class="tx-support">
    <a href="{{ route('support') }}" class="btn btn--wide">CONTACT SUPPORT</a>
  </div>
@endif

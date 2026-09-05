@extends('layouts.app')

@section('title', 'Transaction '.$transaction['id'].' &mdash; SageSwap')
@section('description', 'Send your deposit to complete the swap. The rate is held for the length of the deposit window and the payout follows automatically.')

@section('content')
  <main class="page-main">
    <div class="page-heading page-heading--centered">
      <p class="page-heading__eyebrow">TRANSACTION</p>
      <h1 class="page-heading__title">Awaiting your<br />deposit</h1>
      <div class="page-heading__subtitle">
        Send the exact amount shown below. The swap starts on its own as soon as
        your deposit is seen on the network.
      </div>
    </div>

    <div class="form-card">
      <div class="tx-legs">
        <div class="tx-leg">
          <p class="field-label">YOU SEND</p>
          <p class="tx-leg__amount">{{ $transaction['send_amount'] }}</p>
          <p class="tx-leg__coin">
            <span class="coin-badge coin-badge--sm">
              <x-coin-icon :coin="$transaction['send_coin']" :size="16" />
            </span>
            {{ $transaction['send_coin_label'] }}
          </p>
        </div>

        <span class="tx-legs__arrow" aria-hidden="true">
          <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 12h16m0 0-5-5m5 5-5 5"/></svg>
        </span>

        <div class="tx-leg tx-leg--end">
          <p class="field-label">YOU GET</p>
          <p class="tx-leg__amount tx-leg__amount--accent">{{ $transaction['receive_amount'] }}</p>
          <p class="tx-leg__coin">
            <span class="coin-badge coin-badge--sm">
              <x-coin-icon :coin="$transaction['receive_coin']" :size="16" />
            </span>
            {{ $transaction['receive_coin_label'] }}
          </p>
        </div>
      </div>

      <div class="form-card__spacer">
        <p class="field-label">YOUR WALLET ADDRESS ({{ $transaction['receive_coin_label'] }})</p>
        <div class="copy-row copy-row--address">
          <span class="copy-row__value copy-row__value--wrap">{{ $transaction['payout_address'] }}</span>
        </div>
      </div>

      <hr class="rule" />

      <div class="tx-status">
        <div>
          <p class="field-label">TRANSACTION STATUS</p>
          <p class="tx-status__value">
            <span class="dot-accent">&#9679;</span> {{ $transaction['status'] }}
          </p>
        </div>
        <div class="tx-status__meta">
          <p class="field-label">TRANSACTION ID</p>
          <p class="tx-status__id">{{ $transaction['id'] }}</p>
        </div>
      </div>

      @if ($transaction['status'] === 'EXPIRED')
        <div class="notice notice--danger">
          <p class="notice__title">This deposit window has closed.</p>
          <p class="notice__body">
            Do not send to the address below &mdash; the rate is no longer held.
            <a href="{{ route('swap') }}" class="link-accent">Start a new swap</a>
            to get a fresh address.
          </p>
        </div>
      @else
        <p class="tx-instruction">
          Send <span class="tx-instruction__amount">{{ $transaction['send_amount'] }}</span>
          {{ $transaction['send_coin_label'] }} to the following address:
        </p>

        <div class="copy-row copy-row--address">
          <span class="copy-row__value copy-row__value--wrap">{{ $transaction['deposit_address'] }}</span>
        </div>

        <p class="tx-deadline">
          TIME LEFT
          <span class="tx-deadline__value">{{ $transaction['minutes_left'] }} MINUTES</span>
        </p>

        <div class="tx-qr">
          <div class="tx-qr__plate">{!! $transaction['qr'] !!}</div>
          <p class="tx-qr__hint">Scan to pay from a wallet app</p>
        </div>
      @endif

      <div class="notice">
        <p class="notice__title">Send only {{ $transaction['send_coin_label'] }} to this address.</p>
        <p class="notice__body">
          Anything else is lost on arrival. Every swap is escrowed, and the fee is
          already inside the quoted rate.
        </p>
      </div>

      <div class="tx-actions">
        <a href="{{ route('transparency') }}" class="link-accent">Letter of Guarantee</a>
        <a href="{{ route('transaction', ['id' => $transaction['id']]) }}" class="btn btn--wide">REFRESH</a>
      </div>
    </div>

    <p class="form-footnote">
      Deposit not showing up?
      <a href="{{ route('support') }}" class="link-accent">Contact support</a>
    </p>
  </main>
@endsection

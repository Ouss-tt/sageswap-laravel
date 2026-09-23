@extends('layouts.app')

@section('title', 'Transaction '.$transaction['id'].' &mdash; SageSwap')
@section('description', 'Send your deposit to complete the swap. The rate is held for the length of the deposit window and the payout follows automatically.')

{{-- Reload onto the expired page the moment the deposit window closes. A meta
     refresh is plain HTML, so this needs no script; the server has already
     worked out how long is left, and it re-renders the status on arrival. --}}
@if ($transaction['status']['deposit'] && $transaction['seconds_left'] > 0)
  @push('head')
    <meta http-equiv="refresh" content="{{ (int) $transaction['seconds_left'] }}; url={{ $transaction['expired_url'] }}" />
  @endpush
@endif

@section('content')
  <main class="page-main">
    <div class="page-heading page-heading--centered">
      <p class="page-heading__eyebrow tx-heading">
        TRANSACTION
        {{-- $transaction['mode'] is validated against MODE_LABELS in the controller, so
             the only classes this can build are --standard and --aml. --}}
        <span class="tx-badge tx-badge--{{ $transaction['mode'] }}">
          <span class="tx-badge__dot" aria-hidden="true">&#9679;</span>
          {{ $transaction['mode_label'] }}
        </span>
      </p>
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
          {{-- The tone comes from TransactionStatus, so the only classes this
               can build are the .tx-state--* set defined in app.css. --}}
          <p class="tx-status__value tx-state tx-state--{{ $transaction['status']['tone'] }}">
            <span class="tx-state__dot" aria-hidden="true">&#9679;</span>
            {{ $transaction['status']['label'] }}
          </p>
        </div>
        <div class="tx-status__meta">
          <p class="field-label">TRANSACTION ID</p>
          <p class="tx-status__id">{{ $transaction['id'] }}</p>
        </div>
      </div>

      {{-- On a lapsed swap the clock stays on the page, stopped at EXPIRED,
           so the visitor can see why the deposit instructions are gone. --}}
      @if ($transaction['status']['lapsed'])
        @include('partials.tx-deadline', ['standalone' => true])
      @endif

      @include('partials.tx-status-notice')

      {{-- The payout hash, on a settled swap only. Driven by the status flag
           rather than a status code so this stays consistent with how the
           deposit block is gated, and guarded on a value so a settled swap
           with no hash yet shows nothing instead of an empty box. --}}
      @if ($transaction['status']['payout'] && $transaction['payout_txid'])
        <div class="form-card__spacer">
          <p class="field-label">PAYOUT TRANSACTION ID (TXID)</p>
          <div class="copy-row copy-row--address">
            <span class="copy-row__value copy-row__value--wrap tx-txid">{{ $transaction['payout_txid'] }}</span>
          </div>
          {{-- The coin label is deliberately not interpolated here: several
               entries in config/coins.php repeat the name instead of the
               ticker ("Monero (Monero)"), which reads badly mid-sentence. --}}
          <p class="tx-txid__hint">
            Look this up on a block explorer to confirm the payout on-chain.
          </p>
        </div>
      @endif

      {{-- Deposit instructions are an allowlist, not an exception list: they
           belong to the one status that is still waiting to be paid. Any other
           status - including one this front end has never heard of - must not
           show an amount, an address or a QR code, because the visitor has
           either paid already or has nothing left to pay. --}}
      @if ($transaction['status']['deposit'])
        <p class="tx-instruction">
          Send <span class="tx-instruction__amount">{{ $transaction['send_amount'] }}</span>
          {{ $transaction['send_coin_label'] }} to the following address:
        </p>

        <div class="copy-row copy-row--address">
          <span class="copy-row__value copy-row__value--wrap">{{ $transaction['deposit_address'] }}</span>
        </div>

        @include('partials.tx-deadline')

        <div class="tx-qr">
          <div class="tx-qr__plate">{!! $transaction['qr'] !!}</div>
          <p class="tx-qr__hint">Scan to pay from a wallet app</p>
        </div>

        {{-- Part of the deposit instructions: there is no "this address" to
             warn about once the address is gone. --}}
        <div class="notice">
          <p class="notice__title">Send only {{ $transaction['send_coin_label'] }} to this address.</p>
          <p class="notice__body">
            Anything else is lost on arrival. Every swap is escrowed, and the fee is
            already inside the quoted rate.
          </p>
        </div>
      @endif

      <div class="tx-actions">
        <a href="{{ route('transparency') }}" class="link-accent">Letter of Guarantee</a>
        <a href="{{ route('transaction', ['id' => $transaction['id']]) }}" class="btn btn--wide btn--accent">REFRESH</a>
      </div>
    </div>

    @if ($transaction['status']['deposit'])
      <p class="form-footnote">
        Deposit not showing up?
        <a href="{{ route('support') }}" class="link-accent">Contact support</a>
      </p>
    @endif

    {{-- A swap that is over: nothing above will change again, so offer to clear
         it. Last thing on the page on purpose - it is the one irreversible
         control here, and it has no business sitting next to REFRESH. Which
         statuses get it is TransactionStatus's call, not this view's. --}}
    @if ($transaction['status']['delete'])
      <form
        action="{{ route('transaction.destroy', ['id' => $transaction['id']]) }}"
        method="POST"
        class="tx-delete"
      >
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn--wide btn--danger">DELETE TRANSACTION</button>
        <p class="tx-delete__hint">
          Clears this swap from your browser and returns you to the swap form.
          It cannot be undone, so copy the transaction ID above first if you
          might still need it.
        </p>
      </form>
    @endif
  </main>
@endsection

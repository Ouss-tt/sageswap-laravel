@extends('layouts.app')

@section('title', 'SageSwap &mdash; Start a private crypto swap, no KYC')
@section('description', 'Swap BTC to Monero privately. Escrow on every trade, fees inside the quoted rate, no KYC, no accounts, no data kept.')

@section('content')
  <main class="swap-layout">
    @include('partials.swap-hero')

    <div class="swap-panel-col">
      <div class="swap-panel">
        @include('partials.swap-tabs')

        <form class="swap-panel__body" method="POST" action="{{ route('swap.store') }}">
          @csrf
          <div class="swap-panel__content">
            <x-form-errors />
            <div class="rate-tabs">
              <input type="radio" name="rate" id="rate-floating" value="floating" class="sr-only" @checked(old('rate', 'floating') === 'floating') />
              <input type="radio" name="rate" id="rate-fixed" value="fixed" class="sr-only" @checked(old('rate') === 'fixed') />
              <label for="rate-floating" class="rate-tabs__label rate-tabs__label--floating">
                <span><span class="rate-tabs__marker">&#9642; </span>FLOATING RATE</span>
                <span class="rate-tabs__help" tabindex="0" aria-describedby="tip-floating">?</span>
                <span class="rate-tip" id="tip-floating" role="tooltip">
                  <b class="rate-tip__title">Floating rate</b>
                  The rate is settled when your deposit confirms, so the amount you receive
                  follows the market between now and then &mdash; it can land above or below
                  the quote. Lower fee, less certainty.
                </span>
              </label>
              <label for="rate-fixed" class="rate-tabs__label rate-tabs__label--fixed">
                <span><span class="rate-tabs__marker">&#9642; </span>FIXED RATE</span>
                <span class="rate-tabs__help" tabindex="0" aria-describedby="tip-fixed">?</span>
                <span class="rate-tip" id="tip-fixed" role="tooltip">
                  <b class="rate-tip__title">Fixed rate</b>
                  The quote is locked for you, so you receive exactly the amount shown as long
                  as you send within the window. Costs a little more &mdash; that premium covers
                  the price risk.
                </span>
              </label>
            </div>

            <input type="checkbox" id="flip" class="sr-only" />
            <div class="swap-rows">
              <div class="amount-row">
                <input class="amount-row__value amount-row__input" type="number" inputmode="decimal"
                       name="send_amount" value="{{ old('send_amount') }}" placeholder="0.0000" min="0" step="any"
                       autocomplete="off" aria-label="Amount to send" />
                <x-coin-select name="send_coin" selected="btc" label="Coin to send" />
              </div>

              <label for="flip" class="swap-flip" title="Switch coins">
                <span class="sr-only">Switch coins</span>
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M7 4v16m0 0-3-3m3 3 3-3M17 20V4m0 0-3 3m3-3 3 3"/></svg>
              </label>

              <div class="amount-row">
                <input class="amount-row__value amount-row__input" type="number" inputmode="decimal"
                       name="receive_amount" value="{{ old('receive_amount') }}" placeholder="0.0000" min="0" step="any"
                       autocomplete="off" aria-label="Amount to receive" />
                <x-coin-select name="receive_coin" selected="xmr" label="Coin to receive" />
              </div>
            </div>

            <div class="swap-rate">
              <button type="submit" formaction="{{ route('swap.quote') }}" class="btn btn--compact">GET RATE</button>
              <span class="swap-rate__status">FETCHING RATE ...</span>
            </div>

            @include('partials.swap-fields')
          </div>

          @include('partials.swap-footer')
        </form>
      </div>
    </div>
  </main>
@endsection

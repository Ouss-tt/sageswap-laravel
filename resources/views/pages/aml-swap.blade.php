@extends('layouts.app')

@section('title', 'NO AML Swap &mdash; SageSwap')
@section('description', 'NO AML mode accepts any coins regardless of history or source, with Monero as the output. Extra 2.2% fee, no KYC, no data kept.')

@section('content')
  <main class="swap-layout">
    @include('partials.swap-hero')

    <div class="swap-panel-col">
      <div class="swap-panel">
        @include('partials.swap-tabs')

        <form class="swap-panel__body" method="POST" action="{{ route('aml-swap.store') }}">
          @csrf
          <div class="swap-panel__content">
            <x-form-errors />
            <div class="swap-rows">
              <div class="amount-row">
                <input class="amount-row__value amount-row__input" type="number" inputmode="decimal"
                       name="send_amount" value="{{ old('send_amount') }}" placeholder="0.0000" min="0" step="any"
                       autocomplete="off" aria-label="Amount to send" />
                <x-coin-select name="send_coin" selected="btc" label="Coin to send" />
              </div>

              <span class="swap-flip swap-flip--static" title="Receive side is fixed to Monero">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M7 4v16m0 0-3-3m3 3 3-3M17 20V4m0 0-3 3m3-3 3 3"/></svg>
              </span>

              <div class="amount-row">
                <input class="amount-row__value amount-row__input" type="number" inputmode="decimal"
                       name="receive_amount" value="{{ old('receive_amount') }}" placeholder="0.0000" min="0" step="any"
                       autocomplete="off" aria-label="Amount to receive" />
                <x-coin-locked coin="xmr" label="Monero (Mainnet)" />
              </div>
            </div>

            <div class="swap-rate">
              <button type="submit" formaction="{{ route('aml-swap.quote') }}" class="btn btn--compact">GET RATE</button>
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

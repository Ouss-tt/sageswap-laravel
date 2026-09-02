@extends('layouts.app')

@section('title', 'Swap Help &mdash; SageSwap')
@section('description', 'Answers about Standard Mode vs NO AML Mode, Monero output, total fees, KYC and data collection on SageSwap.')

@section('content')
  <main class="swap-layout">
    @include('partials.swap-hero')

    <div class="swap-panel-col">
      <div class="swap-panel">
        @include('partials.swap-tabs')

        <div class="swap-panel__body">
          <div class="swap-panel__content">
            <div class="qa">
              <x-qa-item open question="What’s the main difference between Standard Mode and NO AML Mode?">
                Standard Mode is designed for transactions that meet our standard risk criteria. NO AML Mode accepts any coins regardless of history or source. Extra 2.2% fee for taking on the AML/compliance risk. We don’t care where the coins come from.
              </x-qa-item>

              <x-qa-item question="Why is Monero the only output option for NO AML Mode?">
                Monero provides enhanced transaction privacy by design. It is currently the only supported output asset for this swap mode.
              </x-qa-item>

              <x-qa-item question="How much will the total fee be?">
                Standard Mode: approximately 1-2%.<br />
                NO AML Mode: the standard fee plus an additional 2.2% risk-processing fee, typically resulting in a total fee of around 3.0-4.0%.<br />
                The applicable fee is reflected in the quoted rate, so you can see the expected amount before confirming the transaction.
              </x-qa-item>

              <x-qa-item question="What if I don’t know whether my coins are considered high risk?">
                You can start with Standard Mode. If our automated risk checks identify an issue, we will provide further information and explain the available options. Depending on your decision, additional fees may apply.
              </x-qa-item>

              <x-qa-item question="Do you require KYC?">
                Never.
              </x-qa-item>

              <x-qa-item question="Do you collect personal data such as IP addresses?">
                Never.
              </x-qa-item>
            </div>
          </div>

          <div class="swap-panel__footer">
            <p class="swap-panel__note">
              FOR MORE INFORMATION, PLEASE VISIT OUR
              <a href="{{ route('faq') }}" class="link-plain">FAQ</a>.
            </p>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection

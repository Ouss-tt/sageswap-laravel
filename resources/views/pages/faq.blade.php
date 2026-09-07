@extends('layouts.app')

@section('title', 'FAQ &mdash; Fees, timings and privacy | SageSwap')
@section('description', 'Everything about your swap: fees, timings, privacy and AML modes in plain words. No KYC, no accounts, no data kept.')

@section('content')
  <main class="page-main">
    <div class="page-heading">
      <p class="page-heading__eyebrow">FREQUENTLY ASKED QUESTIONS</p>
      <h1 class="page-heading__title">Everything about<br />your swap</h1>
      <div class="page-heading__subtitle">
        Fees, timings, privacy and AML modes &mdash; in plain words.<br />
        No KYC, no accounts, no data kept.
      </div>
    </div>

    <div class="accordion faq-accordion">
      <x-accordion-item open question="What kind of exchange do you offer?">
        We are a crypto to crypto exchange. We do not support fiat currencies, and no account or verification is required. Simply choose the assets you want to exchange and complete the swap.
      </x-accordion-item>

      <x-accordion-item question="What are your fees?">
        We charge a fee from 1% to 2% plus transaction gas fees. Please note that for amounts below $50, additional fees of up to 6% may apply due to the low value of the transaction.
      </x-accordion-item>

      <x-accordion-item question="What happens if I send the wrong amount?">
        Nothing to worry about. If you send a different amount than the one specified, the transaction amount will automatically update, provided that the amount meets the minimum swap requirement and we have sufficient reserves to complete the exchange.
      </x-accordion-item>

      <x-accordion-item question="What if something goes wrong?">
        If you experience any issues during your swap, our
        <a href="{{ route('support') }}" class="link-accent">support team</a> is available to help and resolve the problem as quickly as possible.
      </x-accordion-item>

      <x-accordion-item question="Why is Monero the only output option for NO AML Mode?">
        Monero provides enhanced transaction privacy by design. It is currently the only supported output asset for this swap mode.
      </x-accordion-item>

      <x-accordion-item question="Do you require KYC?">
        No. We do not require KYC or account verification. Our service is designed to be simple, fast, and privacy-focused.
      </x-accordion-item>

      <x-accordion-item question="Do you accept coins with a high AML risk score?">
        Yes, but only through our <a href="{{ route('aml-swap') }}" class="link-accent">NO AML Mode</a>. For more information, see the explanation next to each mode on the swap page.
      </x-accordion-item>

      <x-accordion-item question="How long are swap records stored?">
        Successful swaps are automatically and permanently removed from our database after 7 days. Once deleted, the records cannot be recovered.
      </x-accordion-item>

      <x-accordion-item question="Do you have an affiliate program?">
        Yes. Our affiliate program allows you to earn commissions by referring new users. Visit the
        <a href="{{ route('affiliate.dashboard') }}" class="link-accent">Affiliate section</a> for more information.
      </x-accordion-item>

      <x-accordion-item question="Do you have your own reserves and infrastructure?">
        Yes. We operate our own cryptocurrency reserves and infrastructure.
      </x-accordion-item>

      <x-accordion-item question="Do you collect any personal or device information?">
        No.
      </x-accordion-item>

      <x-accordion-item question="I lost my UUID. Can you help me recover it?">
        Unfortunately, we cannot recover lost UUIDs.
      </x-accordion-item>

      <x-accordion-item question="How long does a swap take?">
        Most swaps are completed within 5-30 minutes. Processing time depends mainly on blockchain confirmations and current network conditions. Some transactions may take longer during periods of network congestion.
      </x-accordion-item>

      <x-accordion-item question="How can I contact support?">
        You can contact our support team via Telegram, Session, or email. Visit the
        <a href="{{ route('support') }}" class="link-accent">Support section</a> to find our current contact details.
      </x-accordion-item>
    </div>

    <div class="faq-cta">
      <p class="faq-cta__text">
        If you have any more questions, feel free to reach out to our support section.<br />
        we&rsquo;re here to help!
      </p>
      <a href="{{ route('support') }}" class="btn">CONTACT SUPPORT</a>
    </div>
  </main>
@endsection

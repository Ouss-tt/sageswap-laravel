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
        We are a crypto-to-crypto exchange - we do not support fiat currencies. You can easily swap one cryptocurrency for another without the need for creating an account or going under verification.
      </x-accordion-item>

      <x-accordion-item question="What are your fees?">
        We charge a fee from 1% to 2% plus transaction gas fees. Please note that for amounts below $50, additional fees of up to 6% may apply due to the low value of the transaction.
      </x-accordion-item>

      <x-accordion-item question="What happens if I send the wrong amount?">
        Always try to send the same amount as the transaction was created for. If you accidentally sent too much or too little, don&rsquo;t worry. If the transaction does not update or confirm within 10 minutes, contact support and we will definitely be able to solve your problem.
      </x-accordion-item>

      <x-accordion-item question="What if something goes wrong?">
        If you experience any issues during your swap, our
        <a href="{{ route('support') }}" class="link-accent">support team</a> is ready to help.
      </x-accordion-item>

      <x-accordion-item question="Do you require KYC (Know Your Customer)?">
        No. We never require KYC under any circumstances. Your privacy is important to us, and we designed our service to be fast, anonymous, and simple.
      </x-accordion-item>

      <x-accordion-item question="Do you accept stolen money?">
        Yes and no. We accept coins with a high AML score only if the swap is carried out using <a href="{{ route('aml-swap') }}" class="link-accent">AML Swap</a> mode. If the swap is carried out using <a href="{{ route('swap') }}" class="link-accent">Standard Swap</a> mode, we will not be able to process transactions with a high AML score. For more information about swaps, we recommend visit the <a href="{{ route('help') }}" class="link-accent">Help</a> tab.
      </x-accordion-item>

      <x-accordion-item question="Do you collect any data, such as IP addresses or anything else?">
        No, we do not collect any data - including IP addresses or any other identifying information.
      </x-accordion-item>

      <x-accordion-item question="Do successful swaps stay in your DB forever?">
        No. For maximum privacy and security, every successful swap is automatically and permanently deleted from our database after 7 days. After this period, the transaction record is gone forever and cannot be recovered.
      </x-accordion-item>

      <x-accordion-item question="Do you have an affiliate program?">
        Yes! We offer a crypto affiliate program where you can earn (0.5%) commissions for referring users. Reach out the <a href="{{ route('affiliate.dashboard') }}" class="link-accent">Affiliate section</a> to get started.
      </x-accordion-item>

      <x-accordion-item question="Do you have your own reserves and infrastructure?">
        Yes, we have our own coin reserves and our own infrastructure.
      </x-accordion-item>

      <x-accordion-item question="I lost my UUID. Can you help me?">
        Unfortunately, we cannot help you recover your account. We do not have the tools to assist you in this matter.
      </x-accordion-item>

      <x-accordion-item question="How long does a swap take?">
        Most swaps are completed within 5-30 minutes, depending on network conditions and confirmation times. Some assets may take longer due to chain congestion or technical issues.
      </x-accordion-item>

      <x-accordion-item question="Why does the website look so basic? There are no fancy colors, notifications, or anything.">
        Our website does not require JavaScript! On our website, you are safe from any tracking or annoying notifications. You come in, swap, and leave!
      </x-accordion-item>

      <x-accordion-item question="How can I contact with you?">
        You can reach us via Telegram, Session and E-mail.
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

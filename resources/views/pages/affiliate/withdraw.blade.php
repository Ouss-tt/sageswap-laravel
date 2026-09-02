@extends('layouts.app')

@section('title', 'Withdrawal Request &mdash; SageSwap Affiliate')
@section('description', 'Request a Monero withdrawal of your SageSwap affiliate earnings. Withdrawals start at 0.2 XMR and are usually processed within 24 hours.')

@section('content')
  <main class="page-main">
    <p class="section-eyebrow">AFFILIATE</p>
    <h1 class="section-title">Withdrawal Request</h1>
    <hr class="rule" />

    <form class="form-card form-card--narrow" action="{{ route('affiliate.withdraw.store') }}" method="POST">
      @csrf
      <x-form-errors />
      <div class="withdraw-summary">
        <span class="withdraw-summary__label">WITHDRAWING:</span>
        <span class="withdraw-summary__value">
          {{ $balance }}
          <span class="coin-badge coin-badge--sm"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M7 16V8l5 4 5-4v8"/></svg></span>
        </span>
      </div>

      <p class="withdraw-field-label">WITHDRAWING</p>
      <input class="field field--filled" name="address" type="text" value="{{ old('address') }}"
             placeholder="Monero withdrawal adress" autocomplete="off" />
      <button type="submit" class="btn btn--block form-card__submit form-card__submit--sm">REQUEST WITHDRAWAL</button>
    </form>

    <section class="aff-faq aff-faq--tight">
      <h2 class="section-h2">Affiliate FAQ</h2>
      <div class="accordion">
        <x-accordion-item open question="What is the minimum amount I can withdraw?">
          Withdrawals start at 0.2 XMR.
        </x-accordion-item>

        <x-accordion-item question="When will I receive my Monero?">
          Withdrawals are processed manually, usually within 24 hours of the request.
        </x-accordion-item>

        <x-accordion-item question="What if I have issues with my withdrawal?">
          Contact support on Telegram at @SageSwap_Support with your referral code and the time of the request.
        </x-accordion-item>
      </div>
    </section>
  </main>
@endsection

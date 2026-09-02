@extends('layouts.app')

@section('title', 'Affiliate Login &mdash; SageSwap')
@section('description', 'Log in to your SageSwap affiliate account with your UUID. No e-mail, no KYC &mdash; your UUID is the only key to your account.')

@section('content')
  <main class="page-main">
    <div class="page-heading page-heading--centered">
      <p class="page-heading__eyebrow">AFFILIATE</p>
      <h1 class="page-heading__title">Affiliate login</h1>
      <div class="page-heading__subtitle">
        No e-mail, no KYC. Your UUID is the only key to your affiliate account &mdash; keep it
        safe, it cannot be recovered.
      </div>
    </div>

    <form class="form-card" action="{{ route('affiliate.login.attempt') }}" method="POST">
      @csrf
      <x-form-errors />
      <label class="field-label" for="uuid">AFFILIATE UUID</label>
      <input class="field" id="uuid" name="uuid" type="text" value="{{ old('uuid') }}"
             placeholder="0000-0000-0000-0000-0000" autocomplete="off" />
      <label class="field-label field-label--spaced" for="password">PASSWORD</label>
      <input class="field" id="password" name="password" type="password" placeholder="*********" />
      <button type="submit" class="btn btn--block form-card__submit">LOGIN</button>
    </form>

    <p class="form-footnote">
      Don&rsquo;t have an affiliate account yet?
      <a href="{{ route('affiliate.register') }}" class="link-accent">Sign up</a>
    </p>

    <section class="aff-faq aff-faq--tight">
      <h2 class="section-h2">Frequently Asked Questions (FAQ)</h2>
      <div class="accordion">
        <x-accordion-item open question="I lost my UUID. Can you help me?">
          Unfortunately, we cannot help you recover your account. We do not have the tools to assist
          you in this matter.
        </x-accordion-item>

        <x-accordion-item question="How does the affiliate program work?">
          It’s simple; you create an account, receive your own affiliate code, generate traffic =
          earn money!
        </x-accordion-item>

        <x-accordion-item question="How much can I earn from this?">
          We offer up to 0.5% for new partners. This may change with longer-term cooperation.
        </x-accordion-item>

        <x-accordion-item question="How can I withdraw money and how long will I have to wait?">
          The only withdrawal option is Monero, which takes up to 24 hours (usually sooner).
        </x-accordion-item>

        <x-accordion-item question="How do I bring up collaboration details?">
          You can reach us through the <a href="{{ route('support') }}" class="link-accent">support section</a> or by Hello@SageSwap.io
        </x-accordion-item>
      </div>
    </section>
  </main>
@endsection

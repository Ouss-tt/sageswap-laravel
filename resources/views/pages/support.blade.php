@extends('layouts.app')

@section('title', 'Support Center &mdash; SageSwap')
@section('description', 'Something went wrong? Reach SageSwap support via Telegram, Session, Signal or e-mail. We respond to all messages within 24 hours.')

@section('content')
  <main class="support-main">
    <div class="page-heading">
      <p class="page-heading__eyebrow">SUPPORT CENTER</p>
      <h1 class="page-heading__title">Something<br />went wrong?</h1>
      <div class="page-heading__subtitle">
        If you have any questions about our services or need help with your transaction, feel
        free to contact us. We respond to all messages within 24 hours (usually sooner). You can
        reach us via Telegram, Session, Signal or E-mail.
      </div>
    </div>

    <div class="support-col">
      <div class="support-field">
        <p class="support-field__label">TELEGRAM</p>
        <div class="support-field__value">@SageSwap_Support</div>
      </div>
      <div class="support-field">
        <p class="support-field__label">SESSION ID</p>
        <div class="support-field__value">
          <span class="is-accent">05dd9cc7a8f9cf039411b2eb90ecd10c2debcdd75248b2fa2b009bee197bfaf401d</span>
        </div>
      </div>
      <div class="support-field">
        <p class="support-field__label">SUPPORT E-MAIL</p>
        <div class="support-field__value">Support@SageSwap.io</div>
      </div>
      <div class="support-field">
        <p class="support-field__label">PGP KEY</p>
        <div class="support-field__value">
          <a href="#" class="support-file">SageSwap.txt <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8l-5-5Z"/><path d="M14 3v5h5"/></svg></a>
        </div>
      </div>

      <div class="support-notice">
        <p class="support-notice__title">NO REPLY TO YOUR E-MAIL?</p>
        <p class="support-notice__body">
          If you haven't received a reply to your e-mail from us within 24 hours, it's likely
          that we're unable to respond because your e-mail provider has blocked us. Please try
          contacting us again using another available method of communication.
        </p>
      </div>

      <div class="support-notice">
        <p class="support-notice__title">LAW ENFORCEMENT NOTICE</p>
        <p class="support-notice__body">
          For Law Enforcement, please be advised that we will not be providing any information.
          Do not contact us, as we will not respond or share any data.
        </p>
        <p class="support-notice__body">
          <a href="{{ route('transparency') }}" class="link-accent">Read our transparency report</a>
        </p>
      </div>

      <p class="support-foot">
        For quicker communication, provide all details about your transaction.
      </p>
    </div>
  </main>
@endsection

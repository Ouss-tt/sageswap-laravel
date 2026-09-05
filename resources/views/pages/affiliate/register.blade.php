@extends('layouts.app')

@section('title', session('registered')
    ? 'Affiliate Registration Successful &mdash; SageSwap'
    : 'Create an Affiliate Account &mdash; SageSwap')
@section('description', 'Create a SageSwap affiliate account with just a password. A UUID is generated for you and used as your username &mdash; it cannot be reset.')

@section('content')
  <main class="page-main">
    <div class="page-heading page-heading--centered">
      <p class="page-heading__eyebrow">AFFILIATE</p>
      <h1 class="page-heading__title">Create an affiliate<br />account</h1>
      <div class="page-heading__subtitle">
        @if (session('registered'))
          Affiliate registration successful
        @else
          Do not lose this password, it cannot be reset. Affiliate UUID will be generated for you,
          which you&rsquo;ll use as username.
        @endif
      </div>
    </div>

    @if (session('registered'))
      <div class="form-card">
        <label class="field-label">UUID</label>
        <div class="copy-row">
          <span class="copy-row__value">{{ session('uuid') }}</span>
        </div>
        <div class="form-card__spacer">
          <label class="field-label">PASSWORD</label>
          <div class="copy-row">
            <span class="copy-row__value">{{ session('password') }}</span>
          </div>
        </div>

        <div class="notice">
          <p class="notice__title">Do not lose your affiliate UUID. It cannot be recovered.</p>
          <p class="notice__body">Please confirm you&rsquo;ve saved your affiliate UUID.</p>
        </div>

        <a href="{{ route('affiliate.dashboard') }}" class="btn btn--block form-card__submit form-card__submit--lg">CONFIRM</a>
      </div>
    @else
      <form class="form-card" action="{{ route('affiliate.register.store') }}" method="POST">
        @csrf
        <x-form-errors />
        <label class="field-label" for="password">PASSWORD</label>
        <input class="field" id="password" name="password" type="password" placeholder="*********" />
        <label class="field-label field-label--spaced" for="password_confirmation">CONFIRM PASSWORD</label>
        <input class="field" id="password_confirmation" name="password_confirmation" type="password" placeholder="*********" />
        <button type="submit" class="btn btn--block form-card__submit">REGISTER</button>
      </form>
    @endif

    <p class="form-footnote">
      Already have an affiliate account?
      <a href="{{ route('affiliate.login') }}" class="link-accent">Log in</a>
    </p>
  </main>
@endsection

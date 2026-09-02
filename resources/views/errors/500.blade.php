@extends('layouts.app')

@section('title', 'Server Error &mdash; SageSwap')
@section('description', 'Something broke on our end. Try the request again, or head back to the SageSwap homepage.')

@section('content')
  <main class="error-main">
    <div class="error-layout">
      <img src="{{ asset('assets/500.png') }}" alt="" width="526" height="511" class="error-mascot" />

      <div class="error-copy">
        <p class="error-code">500</p>
        <h1 class="error-title">SERVER ERROR</h1>
        <p class="error-text">
          Something broke on our end. The request couldn't be completed. Try again, or
          head back to the homepage.
        </p>
        <div class="error-actions">
          <a href="{{ url()->current() }}" class="btn">TRY AGAIN</a>
          <a href="{{ route('swap') }}" class="btn btn--ghost">GO HOME</a>
        </div>

        <p class="error-ref">
          Correlation ID: <span class="error-ref__value">7F3A9C21-4E08-4B6D-9A15-C4E82D07B3F1</span>
        </p>
      </div>
    </div>
  </main>
@endsection

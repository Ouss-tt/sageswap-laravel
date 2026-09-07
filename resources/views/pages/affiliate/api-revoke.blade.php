@extends('layouts.app')

@section('title', 'Revoke API Token &mdash; SageSwap Affiliate')
@section('description', 'Confirm revoking a SageSwap affiliate API token.')

@section('content')
  <main class="page-main">
    <p class="section-eyebrow">AFFILIATE</p>
    <h1 class="section-title">Revoke API Token</h1>
    <hr class="rule" />

    <div class="form-card form-card--narrow">
      <h2 class="form-card__title">Revoke Token &ldquo;{{ $key['name'] }}&rdquo;</h2>

      <p class="form-card__text">
        WARNING: If you click the &ldquo;CONFIRM&rdquo; button, you will permanently revoke this
        API token. Making further API calls will not be possible with it.
      </p>

      <div class="form-actions">
        <form action="{{ route('affiliate.api.destroy', $key['id']) }}" method="POST">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn">CONFIRM</button>
        </form>
        <a href="{{ route('affiliate.api') }}" class="btn btn--ghost">CANCEL</a>
      </div>
    </div>
  </main>
@endsection

@extends('layouts.app')

@section('title', 'Create API Key &mdash; SageSwap Affiliate')
@section('description', 'Create a new SageSwap affiliate API key.')

@section('content')
  <main class="page-main">
    <p class="section-eyebrow">AFFILIATE</p>
    <h1 class="section-title">Create API Key</h1>
    <hr class="rule" />

    <form class="form-card form-card--narrow" action="{{ route('affiliate.api.store') }}" method="POST">
      @csrf
      <x-form-errors />
      <label class="field-label" for="name">NAME</label>
      <input class="field" id="name" name="name" type="text" value="{{ old('name') }}"
             placeholder="Default API Token" autocomplete="off" />
      <button type="submit" class="btn btn--block form-card__submit">CREATE NEW</button>
    </form>

    <p class="form-footnote">
      <a href="{{ route('affiliate.api') }}" class="link-accent">Back to API keys</a>
    </p>
  </main>
@endsection

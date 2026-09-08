@extends('layouts.app')

@section('title', 'API &mdash; SageSwap Affiliate')
@section('description', 'Manage your SageSwap API tokens. Create, review and revoke tokens from here.')

@section('content')
  <main class="page-main">
    <p class="section-eyebrow">AFFILIATE</p>
    <h1 class="section-title">API</h1>
    <hr class="rule" />

    <p class="api-meta api-meta--first">
      API DOCS:
      <a href="https://docs.sageswap.io/" class="link-accent api-meta__link">Sageswap - API docs</a>
    </p>

    <p class="api-note">
      If you encounter any errors, please report them to
      <a href="{{ route('support') }}" class="link-accent">support</a> - thank you!
    </p>

    <p class="api-meta">API TOKENS</p>

    <table class="data-table data-table--ruled">
      <thead>
        <tr>
          <th>NAME</th>
          <th>LAST USED</th>
          <th>ACTIONS</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($keys as $key)
          <tr>
            <td>{{ $key['name'] }}</td>
            <td class="is-muted">{{ $key['last_used'] }}</td>
            <td>
              <a href="{{ route('affiliate.api.revoke', $key['id']) }}" class="table-action">Revoke</a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="3" class="is-muted">No API tokens yet.</td>
          </tr>
        @endforelse
      </tbody>
    </table>

    <a href="{{ route('affiliate.api.create') }}" class="btn btn--wider btn--spaced">CREATE NEW</a>
  </main>
@endsection

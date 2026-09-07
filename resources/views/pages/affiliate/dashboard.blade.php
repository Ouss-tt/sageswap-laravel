@extends('layouts.app')

@section('title', 'Affiliate Dashboard &mdash; SageSwap')
@section('description', 'Track your SageSwap referral code, referral links, available XMR funds and transactions since your last withdrawal.')

@section('content')
  <main class="page-main">
    <p class="section-eyebrow">AFFILIATE</p>
    <h1 class="section-title">Dashboard</h1>
    <hr class="rule" />

    <div class="aff-status">
      <p class="aff-status__label"><span class="dot-accent">&#9679;</span> LOGGED IN AS</p>
      <span class="aff-status__uuid">{{ $affiliate['uuid'] }}</span>
    </div>

    <div class="aff-grid">
      <div class="aff-card">
        <p class="aff-card__label"><span class="dot-accent">&#9679;</span> YOUR REFERRAL CODE</p>
        <p class="aff-card__code">{{ $affiliate['referral_code'] }}</p>
        <p class="aff-card__links">
          <a href="{{ route('affiliate.api') }}" class="aff-card__api-link">
            <span class="link-plain link-plain--wide">API TOKENS</span>
            <span class="aff-card__hint">Sageswap - API Tokens &rarr;</span>
          </a>
        </p>
      </div>

      <div class="aff-card aff-links">
        <div>
          <p class="field-label">REFERRAL LINK</p>
          <div class="copy-row copy-row--tight">
            <span class="copy-row__value copy-row__value--link">{{ $affiliate['referral_link'] }}</span>
          </div>
        </div>
        <div>
          <p class="field-label">ONION REFERRAL LINK</p>
          <div class="copy-row copy-row--tight">
            <span class="copy-row__value copy-row__value--link">{{ $affiliate['onion_link'] }}</span>
          </div>
        </div>
      </div>
    </div>

    <div class="aff-funds">
      <div>
        <p class="aff-funds__label">FUNDS AVAILABLE</p>
        <p class="aff-funds__amount">
          <span class="coin-badge"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M7 16V8l5 4 5-4v8"/></svg></span>
          {{ $affiliate['balance'] }} XMR
        </p>
      </div>
      <a href="{{ route('affiliate.withdraw') }}" class="btn btn--wide">WITHDRAW FUNDS</a>
    </div>

    <div class="aff-history">
      <p class="aff-history__label">TRANSACTIONS SINCE LAST WITHDRAWAL</p>
      <table class="data-table">
        <thead>
          <tr>
            <th>SEND AMOUNT</th>
            <th>RECEIVE AMOUNT</th>
            <th>VALUE (USDT)</th>
            <th>PROFIT (XMR)</th>
            <th>TIME</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($transactions as $transaction)
            <tr>
              <td>{{ $transaction['send_amount'] }}</td>
              <td class="is-muted">{{ $transaction['receive_amount'] }}</td>
              <td>{{ $transaction['value_usdt'] }}</td>
              <td class="is-accent">{{ $transaction['profit_xmr'] }}</td>
              <td class="is-muted">{{ $transaction['time'] }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="is-muted">Nothing since your last withdrawal.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <section class="aff-faq">
      <h2 class="section-h2">Affiliate FAQ</h2>
      <div class="accordion">
        <x-accordion-item open question="How is the 0.5% profit calculated?">
          The 0.5% profit is calculated based on the final “Receive amount”. It is not
          calculated on the amount you send.
        </x-accordion-item>

        <x-accordion-item question="Why have some of my past transactions disappeared from the history even though I haven’t made a withdrawal yet?">
          For privacy and security, all completed transactions are automatically removed from
          our database 7 days after they are successfully finished. This is a standard
          protection measure. Additionally, users have the option to manually delete a
          transaction immediately after completion if they prefer - this choice is entirely up
          to the person performing the swap.
        </x-accordion-item>
      </div>
    </section>
  </main>
@endsection

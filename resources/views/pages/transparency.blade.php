@extends('layouts.app')

@section('title', 'Transparency Report &mdash; SageSwap')
@section('description', 'SageSwap transparency report: government requests, data requests and subpoenas received since 2025, and how many were answered.')

@section('content')
  <main class="transparency-main">
    <h1 class="transparency__title">TRANSPARENCY REPORT</h1>
    <p class="transparency__subtitle">Government Requests, Data Requests &amp; Subpoenas (2025&ndash;Present)</p>
    <p class="transparency__updated">Last updated: August 2026</p>

    <div class="transparency-stats">
      <div class="transparency-stat">
        <p class="transparency-stat__label">TOTAL REQUESTS RECEIVED</p>
        <p class="transparency-stat__value is-accent">0</p>
      </div>
      <div class="transparency-stat">
        <p class="transparency-stat__label">DATA PROVIDED</p>
        <p class="transparency-stat__value is-danger">NEVER</p>
      </div>
    </div>

    <div class="transparency-statement">
      <p>
        AND IF YOU SOMEHOW MISSED THE VERY CLEAR INFORMATION IN OUR SUPPORT SECTION,
        WE&rsquo;LL SAY IT AGAIN: WE AUTOMATICALLY REJECT THESE REQUESTS AND DO NOT
        RESPOND TO THEM.
      </p>
      <p>
        IF YOU&rsquo;RE AN AGENCY, GOVERNMENT AUTHORITY, OR ANYONE ELSE HOPING TO GET
        YOUR HANDS ON USER DATA, DON&rsquo;T EVEN WASTE YOUR TIME.
      </p>
      <p class="transparency-statement__punch">
        SAVE YOURSELF THE PAPERWORK. WE&rsquo;RE NOT INTERESTED.
      </p>
    </div>
  </main>
@endsection

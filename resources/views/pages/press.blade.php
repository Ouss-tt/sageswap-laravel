@extends('layouts.app')

@section('title', 'Press Kit &mdash; SageSwap')
@section('description', 'Download official SageSwap logos, brand colours and product imagery, and reach the team for press enquiries.')

@section('content')
  @php
    // Sections are numbered as they render, so hiding an empty one never
    // leaves a gap in the sequence.
    $section = 0;
  @endphp

  <main class="page-main press-main">
    <div class="page-heading">
      <p class="page-heading__eyebrow">PRESS KIT</p>
      <h1 class="page-heading__title">Brand &amp;<br />media assets</h1>
      <div class="page-heading__subtitle">
        Official logos, colours and product imagery for editorial use. Everything
        here is free to publish unmodified. If you need something that is not on
        this page, ask us for it.
      </div>
    </div>

    <dl class="press-spec">
      <div class="press-spec__item">
        <dt class="press-spec__key">VERSION</dt>
        <dd class="press-spec__value">{{ config('press.version') }}</dd>
      </div>
      <div class="press-spec__item">
        <dt class="press-spec__key">UPDATED</dt>
        <dd class="press-spec__value">{{ config('press.updated') }}</dd>
      </div>
      <div class="press-spec__item">
        <dt class="press-spec__key">LICENCE</dt>
        <dd class="press-spec__value">{{ config('press.licence') }}</dd>
      </div>
      <div class="press-spec__item">
        <dt class="press-spec__key">ASSETS</dt>
        <dd class="press-spec__value">{{ count($logos) + count($images) }} FILES</dd>
      </div>
    </dl>

    @if ($kit)
      {{-- This needs no backend: the zip is a static file under public/, and the
           download attribute works because it is same-origin. --}}
      <a href="{{ $kit['url'] }}" download class="press-kit">
        <span class="press-kit__text">
          <span class="press-kit__label">DOWNLOAD FULL KIT</span>
          <span class="press-kit__meta">ZIP archive &middot; logos, mascot and usage notes</span>
        </span>
        <svg class="press-kit__icon" viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
          <path d="M12 3v12m0 0-5-5m5 5 5-5" />
          <path d="M4 20h16" />
        </svg>
      </a>
    @endif

    <section class="press-section">
      <header class="press-section__head">
        <span class="press-section__index">{{ str_pad(++$section, 2, '0', STR_PAD_LEFT) }}</span>
        <h2 class="press-section__title">CONTACT</h2>
      </header>

      <div class="press-fields">
        @foreach (config('press.contact', []) as $field)
          <div class="press-field">
            <p class="field-label">{{ $field['label'] }}</p>
            <p @class(['press-field__value', 'is-accent' => $field['accent'] ?? false])>
              {{ $field['value'] }}
            </p>
          </div>
        @endforeach
      </div>
    </section>

    <section class="press-section">
      <header class="press-section__head">
        <span class="press-section__index">{{ str_pad(++$section, 2, '0', STR_PAD_LEFT) }}</span>
        <h2 class="press-section__title">LOGOS</h2>
      </header>
      {{-- Outside the header on purpose: as a flex sibling of the title, a
           max-width on this note clamps its flex base size and it stops
           wrapping onto its own line. --}}
      <p class="press-section__note">Shown on both grounds &mdash; pick the one that matches your layout.</p>

      @if ($logos)
        <div class="press-grid">
          @foreach ($logos as $logo)
            <article class="press-asset">
              <header class="press-asset__head">
                <span class="press-asset__id">{{ $logo['id'] }}</span>
                @if ($logo['dimensions'])
                  <span class="press-asset__dims">{{ $logo['dimensions'] }}</span>
                @endif
              </header>

              {{-- The site is dark, so someone putting this mark on white paper
                   has to be able to see it on white before downloading it. --}}
              <div class="press-plates">
                <div class="press-plate press-plate--dark">
                  <img src="{{ $logo['preview'] }}" alt="{{ $logo['name'] }} on a dark background" loading="lazy" />
                  <span class="press-plate__tag">ON #0A0A0A</span>
                </div>
                <div class="press-plate press-plate--light">
                  <img src="{{ $logo['preview'] }}" alt="{{ $logo['name'] }} on a light background" loading="lazy" />
                  <span class="press-plate__tag">ON #FFFFFF</span>
                </div>
              </div>

              <div class="press-asset__body">
                <h3 class="press-asset__name">{{ $logo['name'] }}</h3>
                <p class="press-asset__note">{{ $logo['note'] }}</p>
              </div>

              <div class="press-asset__downloads">
                @foreach ($logo['downloads'] as $file)
                  <a href="{{ $file['url'] }}" download class="press-download">
                    <span class="press-download__format">{{ $file['label'] }}</span>
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                      <path d="M12 4v11m0 0-4-4m4 4 4-4" />
                      <path d="M5 19h14" />
                    </svg>
                  </a>
                @endforeach
              </div>
            </article>
          @endforeach
        </div>
      @else
        <p class="press-empty">No logo files have been added yet.</p>
      @endif
    </section>

    <section class="press-section">
      <header class="press-section__head">
        <span class="press-section__index">{{ str_pad(++$section, 2, '0', STR_PAD_LEFT) }}</span>
        <h2 class="press-section__title">COLOURS</h2>
      </header>
      <p class="press-section__note">Hex and RGB, converted from the site&rsquo;s oklch tokens.</p>

      <div class="press-swatches">
        @foreach (config('press.colors', []) as $color)
          <div class="press-swatch">
            {{-- The hex is manifest-controlled and only ever fills a swatch
                 chip, so it cannot reach anything but this background. --}}
            <div class="press-swatch__chip" style="background-color: {{ $color['hex'] }}"></div>
            <div class="press-swatch__body">
              <p class="press-swatch__name">{{ $color['name'] }}</p>
              <p class="press-swatch__hex">{{ $color['hex'] }}</p>
              <p class="press-swatch__rgb">RGB {{ $color['rgb'] }}</p>
              <p class="press-swatch__token">{{ $color['token'] }}</p>
            </div>
          </div>
        @endforeach
      </div>
    </section>

    <section class="press-section">
      <header class="press-section__head">
        <span class="press-section__index">{{ str_pad(++$section, 2, '0', STR_PAD_LEFT) }}</span>
        <h2 class="press-section__title">TYPOGRAPHY</h2>
      </header>

      <div class="press-type">
        @foreach (config('press.typography', []) as $face)
          <div class="press-type__row">
            <p class="press-type__role">{{ $face['role'] }}</p>
            <p class="press-type__sample" style="font-family: {{ $face['css'] }}">{{ $face['sample'] }}</p>
            <div class="press-type__meta">
              <p class="press-type__family">{{ $face['family'] }}</p>
              <p class="press-type__note">{{ $face['note'] }}</p>
              <p class="press-type__stack">{{ $face['stack'] }}</p>
            </div>
          </div>
        @endforeach
      </div>
    </section>

    @if ($images)
      <section class="press-section">
        <header class="press-section__head">
          <span class="press-section__index">{{ str_pad(++$section, 2, '0', STR_PAD_LEFT) }}</span>
          <h2 class="press-section__title">PRODUCT IMAGERY</h2>
        </header>

        <div class="press-grid">
          @foreach ($images as $image)
            <article class="press-asset">
              <header class="press-asset__head">
                <span class="press-asset__id">{{ $image['id'] }}</span>
                @if ($image['dimensions'])
                  <span class="press-asset__dims">{{ $image['dimensions'] }}</span>
                @endif
              </header>

              <div class="press-shot">
                <img src="{{ $image['preview'] }}" alt="{{ $image['name'] }}" loading="lazy" />
              </div>

              <div class="press-asset__body">
                <h3 class="press-asset__name">{{ $image['name'] }}</h3>
                <p class="press-asset__note">{{ $image['note'] }}</p>
              </div>

              <div class="press-asset__downloads">
                @foreach ($image['downloads'] as $file)
                  <a href="{{ $file['url'] }}" download class="press-download">
                    <span class="press-download__format">{{ $file['label'] }}</span>
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                      <path d="M12 4v11m0 0-4-4m4 4 4-4" />
                      <path d="M5 19h14" />
                    </svg>
                  </a>
                @endforeach
              </div>
            </article>
          @endforeach
        </div>
      </section>
    @endif

    <section class="press-section">
      <header class="press-section__head">
        <span class="press-section__index">{{ str_pad(++$section, 2, '0', STR_PAD_LEFT) }}</span>
        <h2 class="press-section__title">USAGE</h2>
      </header>

      <div class="press-rules">
        <div class="press-rules__col">
          <p class="press-rules__title press-rules__title--do">DO</p>
          <ul class="press-rules__list">
            @foreach (config('press.rules.do', []) as $rule)
              <li>{{ $rule }}</li>
            @endforeach
          </ul>
        </div>
        <div class="press-rules__col">
          <p class="press-rules__title press-rules__title--dont">DO NOT</p>
          <ul class="press-rules__list press-rules__list--dont">
            @foreach (config('press.rules.dont', []) as $rule)
              <li>{{ $rule }}</li>
            @endforeach
          </ul>
        </div>
      </div>
    </section>
  </main>
@endsection

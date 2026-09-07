<header class="site-header">
  <div class="site-header__inner">
    <div class="site-header__bar">
      <a href="{{ route('swap') }}" class="site-header__logo">
        <img src="{{ asset('assets/nav-logo.png') }}" alt="SageSwap logo" width="600" height="600" class="site-header__mascot" />
        <span class="site-header__wordmark">SAGESWAP</span>
      </a>
      <a href="{{ route('affiliate.login') }}" class="btn btn--sm site-header__cta">AFFILIATE LOGIN</a>
    </div>
    <nav class="site-nav">
      <a href="{{ route('swap') }}" @class(['site-nav__link', 'is-active' => request()->routeIs('swap', 'aml-swap')])>SWAP</a>
      <a href="{{ route('faq') }}" @class(['site-nav__link', 'is-active' => request()->routeIs('faq')])>FAQ</a>
      <a href="{{ route('support') }}" @class(['site-nav__link', 'is-active' => request()->routeIs('support')])>SUPPORT</a>
    </nav>
  </div>
</header>

<div class="swap-panel__tabs">
  <a href="{{ route('swap') }}" @class(['swap-panel__tab', 'is-active' => request()->routeIs('swap')])>SWAP</a>
  <a href="{{ route('aml-swap') }}" @class(['swap-panel__tab', 'is-active' => request()->routeIs('aml-swap')])>NO AML SWAP</a>
  <a href="{{ route('help') }}" @class(['swap-panel__tab', 'is-active' => request()->routeIs('help')])>HELP</a>
</div>

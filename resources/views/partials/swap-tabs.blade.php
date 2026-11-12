<div class="swap-panel__tabs">
  {{-- Same lesson as the rate tips: the "?" is a sibling of the tab, never a
       child of it. Nested in the link, every tap on "?" navigated away
       instead of explaining the mode. Each "?" shares its tab's grid cell. --}}
  <input type="checkbox" id="tab-tip-standard" class="sr-only" aria-label="Explain Standard Swap" aria-describedby="tip-standard" />
  <label for="tab-tip-standard" class="tip-backdrop" aria-hidden="true"></label>
  <input type="checkbox" id="tab-tip-noaml" class="sr-only" aria-label="Explain NO AML Mode" aria-describedby="tip-noaml" />
  <label for="tab-tip-noaml" class="tip-backdrop" aria-hidden="true"></label>
  <input type="checkbox" id="tab-tip-direct" class="sr-only" aria-label="Explain Direct from Exchange" aria-describedby="tip-direct" />
  <label for="tab-tip-direct" class="tip-backdrop" aria-hidden="true"></label>

  <a href="{{ route('swap') }}" @class(['swap-panel__tab', 'swap-panel__tab--standard', 'is-active' => request()->routeIs('swap')])>STANDARD SWAP</a>
  <a href="{{ route('aml-swap') }}" @class(['swap-panel__tab', 'swap-panel__tab--noaml', 'is-active' => request()->routeIs('aml-swap')])>NO AML SWAP</a>
  <a href="{{ route('direct-exchange') }}" @class(['swap-panel__tab', 'swap-panel__tab--direct', 'is-active' => request()->routeIs('direct-exchange')])>DIRECT FROM EXCHANGE</a>

  <label for="tab-tip-standard"
         @class(['swap-panel__help', 'swap-panel__help--standard', 'is-on-active' => request()->routeIs('swap')])>?</label>
  <label for="tab-tip-noaml"
         @class(['swap-panel__help', 'swap-panel__help--noaml', 'is-on-active' => request()->routeIs('aml-swap')])>?</label>
  <label for="tab-tip-direct"
         @class(['swap-panel__help', 'swap-panel__help--direct', 'is-on-active' => request()->routeIs('direct-exchange')])>?</label>

  <span class="swap-tip swap-tip--standard" id="tip-standard" role="tooltip">
    <b class="swap-tip__title">Standard Swap</b>
    For coins that meet SageSwap&rsquo;s normal risk criteria. Standard swaps typically
    have a <b>1-2% fee</b>. If the transaction is flagged as high AML risk, we may stop
    the swap and provide options for resolving it, such as refund or process it for
    additional fee via NO AML Mode.
  </span>
  <span class="swap-tip swap-tip--noaml" id="tip-noaml" role="tooltip">
    <b class="swap-tip__title">NO AML Mode</b>
    For coins that may not meet the normal AML score criteria, regardless of their
    transaction history or source. This mode adds an <b>extra 2.2% fee</b>. Monero is
    currently the only supported output for this mode.
  </span>
  <span class="swap-tip swap-tip--direct" id="tip-direct" role="tooltip">
    <b class="swap-tip__title">Direct from Exchange</b>
    You send us Monero. Once it confirms, the coin you picked is sent to your address
    straight from a licensed exchange, not from our own wallets.
  </span>
</div>

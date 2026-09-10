<div class="swap-panel__tabs">
  {{-- Same lesson as the rate tips: the "?" is a sibling of the tab, never a
       child of it. Nested in the link, every tap on "?" navigated away
       instead of explaining the mode. --}}
  <input type="checkbox" id="tab-tip-standard" class="sr-only" aria-label="Explain Standard Swap" aria-describedby="tip-standard" />
  <input type="checkbox" id="tab-tip-noaml" class="sr-only" aria-label="Explain NO AML Mode" aria-describedby="tip-noaml" />

  <a href="{{ route('swap') }}" @class(['swap-panel__tab', 'is-active' => request()->routeIs('swap')])>STANDARD SWAP</a>
  <a href="{{ route('aml-swap') }}" @class(['swap-panel__tab', 'is-active' => request()->routeIs('aml-swap')])>NO AML SWAP MODE</a>

  <label for="tab-tip-standard"
         @class(['swap-panel__help', 'swap-panel__help--standard', 'is-on-active' => request()->routeIs('swap')])>?</label>
  <label for="tab-tip-noaml"
         @class(['swap-panel__help', 'swap-panel__help--noaml', 'is-on-active' => request()->routeIs('aml-swap')])>?</label>

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
</div>

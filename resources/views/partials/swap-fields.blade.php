<input type="checkbox" id="refund" class="sr-only" />
<div class="swap-fields">
  <input class="field" type="text" name="address" value="{{ old('address') }}"
         placeholder="Receiving wallet address" autocomplete="off" />
  <label for="refund" class="refund-toggle">
    <span class="refund-toggle__box" aria-hidden="true"></span>
    INCLUDE REFUND ADDRESS (OPTIONAL)
  </label>
  <input class="field refund-field" type="text" name="refund_address" value="{{ old('refund_address') }}"
         placeholder="Optional refund wallet address" autocomplete="off" />
</div>

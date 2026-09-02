@props(['name', 'selected' => 'btc', 'label' => 'Coin'])

<div class="coin-field">
    @foreach (config('coins') as $value => $title)
        <span class="coin-field__icon" data-coin="{{ $value }}" aria-hidden="true"><x-coin-icon :coin="$value" /></span>
    @endforeach

    <select class="coin-field__select" name="{{ $name }}" aria-label="{{ $label }}">
        @foreach (config('coins') as $value => $title)
            <option value="{{ $value }}" @selected(old($name, $selected) === $value)>{{ $title }}</option>
        @endforeach
    </select>

    <span class="coin-field__caret" aria-hidden="true"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg></span>
</div>

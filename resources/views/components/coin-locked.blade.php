@props(['coin' => 'xmr', 'label' => 'Monero (Mainnet)'])

<div class="coin-field coin-field--locked">
    <span class="coin-field__icon" data-coin="{{ $coin }}" aria-hidden="true"><x-coin-icon :coin="$coin" /></span>
    <span>{{ $label }}</span>
</div>

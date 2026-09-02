@props(['question', 'open' => false])

<details class="accordion__item" @if ($open) open @endif>
    <summary class="accordion__summary">
        <span>{{ $question }}</span>
        <span class="accordion__sign" aria-hidden="true"></span>
    </summary>
    <p class="accordion__panel">{{ $slot }}</p>
</details>

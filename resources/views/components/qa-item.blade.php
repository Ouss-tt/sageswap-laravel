@props(['question', 'open' => false])

<details class="qa__item" @if ($open) open @endif>
    <summary class="qa__summary">
        <span class="qa__q">Q</span>
        <span class="qa__text">{{ $question }}</span>
        <span class="qa__sign" aria-hidden="true"></span>
    </summary>
    <div class="qa__panel">
        <span class="qa__a">A</span>
        <p class="qa__answer">{{ $slot }}</p>
    </div>
</details>

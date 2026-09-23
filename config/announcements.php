<?php

/*
|--------------------------------------------------------------------------
| Site announcements
|--------------------------------------------------------------------------
|
| The notices that appear in the top-right corner, one list per surface. The
| homepage and the affiliate area are read separately and never share copy, so
| an affiliate-only notice stays out of a visitor's way and vice versa.
|
| An empty list means the surface shows nothing, so emptying both arrays turns
| the feature off without touching a view. THE ENTRIES BELOW ARE PLACEHOLDERS
| for review - clear them before this goes live, or the outage notice greets
| every visitor.
|
| Shape, per entry:
|
|   id    - stable slug, unique within its surface. It becomes the id of the
|           dismiss control, so changing it makes a closed notice reappear.
|   tone  - 'accent' (site green), 'warning' (amber) or 'danger' (red). Each
|           one is a solid fill, so it is seen from across the page. Anything
|           else renders in the green.
|   title - short heading, or null for a notice that is one sentence.
|   body  - the sentence the visitor reads.
|
| Order is priority: the first three of a surface render, the rest wait.
|
*/

return [

    'home' => [
        [
            'id' => 'technical-outage',
            'tone' => 'danger',
            'title' => 'Temporary technical outage',
            'body' => "We're experiencing a temporary technical outage. We'll try to be back as soon as possible.",
        ],
        [
            'id' => 'xrp-maintenance',
            'tone' => 'warning',
            'title' => null,
            'body' => 'XRP deposits are paused for wallet maintenance. Every other coin is unaffected.',
        ],
    ],

    'affiliate' => [
        [
            'id' => 'payout-schedule',
            'tone' => 'accent',
            'title' => null,
            'body' => 'Withdrawal requests submitted this week are processed within 24 hours.',
        ],
    ],

];

<?php

/*
|--------------------------------------------------------------------------
| Press kit manifest
|--------------------------------------------------------------------------
|
| Everything the /press page shows comes from this file. It is the seam the
| upload work lands on: swap this array for a query that returns the same
| shape and the page needs no changes.
|
| Adding an asset by hand today is two steps:
|   1. drop the file into public/assets/press/
|   2. add an entry below
|
| App\Support\PressKit reads the real file size and pixel dimensions off disk,
| so no metadata is written here and nothing goes stale. Entries whose file is
| missing are skipped rather than rendered broken, which means a path can be
| listed before the file exists.
|
| Paths are relative to public/ and are passed through asset().
|
*/

return [

    /*
    | Shown in the header spec strip so press users can tell whether they are
    | looking at the current kit.
    */
    'version' => '1.0',
    'updated' => 'September 2026',
    'licence' => 'Editorial use, unmodified',

    /*
    | The whole kit as one download. Trust Wallet's press page is only this;
    | we keep it alongside the per-asset downloads for people who want it all.
    */
    'kit' => 'assets/press/sageswap-press-kit.zip',

    'contact' => [
        ['label' => 'PRESS E-MAIL', 'value' => 'Press@SageSwap.io', 'accent' => true],
        ['label' => 'TELEGRAM', 'value' => '@SageSwap_Support', 'accent' => false],
        ['label' => 'RESPONSE TIME', 'value' => 'Within 24 hours', 'accent' => false],
    ],

    /*
    | Logos. 'preview' is what the page renders on both a dark and a light
    | plate; 'downloads' is every format offered for that asset. Add the SVG
    | path to 'downloads' as soon as there is one - vector is what publications
    | actually ask for.
    */
    'logos' => [
        [
            'id' => 'SS-MARK-01',
            'name' => 'Primary Mark',
            'note' => 'The default mark. Do not use below 24px.',
            'preview' => 'assets/press/sageswap-mark.png',
            'downloads' => [
                'assets/press/sageswap-mark.png',
                // 'assets/press/sageswap-mark.svg',
            ],
        ],
        [
            'id' => 'SS-MASCOT-01',
            'name' => 'Full Mascot',
            'note' => 'Editorial and product use. Never crop the cape.',
            'preview' => 'assets/press/sageswap-mascot.png',
            'downloads' => [
                'assets/press/sageswap-mascot.png',
                // 'assets/press/sageswap-mascot.svg',
            ],
        ],

        /*
        | Still missing, and worth chasing before this page goes live:
        |   - the wordmark on its own
        |   - a single-colour (mono) mark for print
        |   - SVG for everything above
        */
    ],

    /*
    | Product screenshots and photography. Same shape as 'logos' minus the
    | light plate, since screenshots carry their own background.
    */
    'images' => [
        // [
        //     'id' => 'SS-SHOT-01',
        //     'name' => 'Swap Interface',
        //     'note' => 'Standard swap, desktop.',
        //     'preview' => 'assets/press/sageswap-swap-desktop.png',
        //     'downloads' => ['assets/press/sageswap-swap-desktop.png'],
        // ],
    ],

    /*
    | Brand colours, converted from the oklch() tokens in public/css/app.css.
    | Press users need hex and RGB, so both are written out here; the token
    | name is kept so the two files can be checked against each other.
    |
    | Note: --warning sits outside the sRGB gamut, so it is deliberately left
    | out - a hex value for it would be an approximation, not the colour.
    */
    'colors' => [
        ['name' => 'Void Black', 'token' => '--background', 'hex' => '#0A0A0A', 'rgb' => '10, 10, 10'],
        ['name' => 'Panel', 'token' => '--surface', 'hex' => '#111111', 'rgb' => '17, 17, 17'],
        ['name' => 'Divider', 'token' => '--border', 'hex' => '#333333', 'rgb' => '51, 51, 51'],
        ['name' => 'Muted', 'token' => '--muted-foreground', 'hex' => '#989898', 'rgb' => '152, 152, 152'],
        ['name' => 'Signal White', 'token' => '--foreground', 'hex' => '#FFFFFF', 'rgb' => '255, 255, 255'],
        ['name' => 'Sage Acid', 'token' => '--accent', 'hex' => '#C4EF20', 'rgb' => '196, 239, 32'],
    ],

    'typography' => [
        [
            'role' => 'DISPLAY',
            'family' => 'Questrial',
            'note' => 'Headings and amounts. Regular weight only.',
            'stack' => '"Questrial", "Poppins", sans-serif',
            'sample' => 'SAGESWAP',
            'css' => 'var(--font-display)',
        ],
        [
            'role' => 'MONO',
            'family' => 'JetBrains Mono',
            'note' => 'Labels, addresses, metadata. 300-700.',
            'stack' => '"JetBrains Mono", ui-monospace, monospace',
            'sample' => '0.00418294 BTC',
            'css' => 'var(--font-mono)',
        ],
    ],

    'rules' => [
        'do' => [
            'Use the mark at its original proportions.',
            'Keep clear space of at least 25% of the mark height.',
            'Place the mark on #0A0A0A or #FFFFFF.',
            'Use the supplied files rather than screenshots of them.',
        ],
        'dont' => [
            'Recolour, rotate, skew or stretch the mark.',
            'Add shadows, glows, outlines or gradients.',
            'Place the mark on a busy photograph.',
            'Redraw the mascot or alter its features.',
        ],
    ],

];

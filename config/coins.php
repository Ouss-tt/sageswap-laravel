<?php

/*
 * The swap menu, in display order.
 *
 * Keys double as the icon lookup (resources/views/components/coin-icon) and as
 * the deposit-address lookup in SwapController, so a token that lives on more
 * than one chain gets one entry per chain. Network names are kept short enough
 * to survive the closed coin picker; the chain is what disambiguates them.
 */

return [

    'btc' => 'Bitcoin (BTC)',
    'bch' => 'Bitcoin Cash (BCH)',
    'eth' => 'Ethereum (ERC20)',
    'dash' => 'Dash (DASH)',

    'usddtrc20' => 'USDD (Tron)',
    'usdderc20' => 'USDD (Ethereum)',

    'usdttrc20' => 'USDT (Tron)',
    'usdterc20' => 'USDT (Ethereum)',
    'usdtsol' => 'USDT (Solana)',
    'usdtbsc' => 'USDT (BSC)',
    'usdtarb' => 'USDT (Arbitrum)',
    'usdtmatic' => 'USDT (Polygon)',

    'trx' => 'TRON (ERC20)',

    'usdcerc20' => 'USDC (Ethereum)',
    'usdcsol' => 'USDC (Solana)',
    'usdcbsc' => 'USDC (BSC)',
    'usdcarb' => 'USDC (Arbitrum)',
    'usdcmatic' => 'USDC (Polygon)',

    'ton' => 'TON (TON)',
    'xrp' => 'XRP (XRP)',
    'sol' => 'Solana (Solana)',
    'ltc' => 'Litecoin (Litecoin)',
    'dai' => 'DAI (Ethereum)',
    'doge' => 'Dogecoin (Dogecoin)',
    'bnb' => 'BNB (BSC)',
    'zano' => 'ZANO (ZANO)',
    'xmr' => 'Monero (Monero)',

];

<?php

namespace App\Http\Controllers;

use App\Support\QrCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SwapController extends Controller
{
    /** How long a deposit window stays open. */
    private const DEPOSIT_WINDOW_MINUTES = 60;

    /** The two swap modes, as they are labelled on the swap tabs. */
    private const MODE_LABELS = [
        'standard' => 'STANDARD SWAP',
        'aml' => 'NO AML SWAP MODE',
    ];

    /**
     * Which chain each menu entry settles on.
     *
     * A token that trades on several networks needs a deposit address per
     * network, so the coin keys from config/coins.php collapse to a chain here
     * and the address, scheme and QR payload all hang off that.
     */
    private const COIN_CHAINS = [
        'btc' => 'bitcoin',
        'bch' => 'bitcoincash',
        'eth' => 'ethereum',
        'dash' => 'dash',
        'usddtrc20' => 'tron',
        'usdderc20' => 'ethereum',
        'usdttrc20' => 'tron',
        'usdterc20' => 'ethereum',
        'usdtsol' => 'solana',
        'usdtbsc' => 'bsc',
        'usdtarb' => 'arbitrum',
        'usdtmatic' => 'polygon',
        'trx' => 'ethereum',
        'usdcerc20' => 'ethereum',
        'usdcsol' => 'solana',
        'usdcbsc' => 'bsc',
        'usdcarb' => 'arbitrum',
        'usdcmatic' => 'polygon',
        'ton' => 'ton',
        'xrp' => 'xrp',
        'sol' => 'solana',
        'ltc' => 'litecoin',
        'dai' => 'ethereum',
        'doge' => 'dogecoin',
        'bnb' => 'bsc',
        'zano' => 'zano',
        'xmr' => 'monero',
    ];

    /** Demo deposit addresses, one per chain the swap form accepts. */
    private const DEPOSIT_ADDRESSES = [
        'bitcoin' => 'bc1qhwwe3cpdfz99t9tgdks2j3pnfqqqy3q0ltcckp',
        'bitcoincash' => 'qzm47qz5ue99y9yl4aca7jnz7dwgdenl85jkfx3znl',
        'ethereum' => '0x71C7656EC7ab88b098defB751B7401B5f6d8976F',
        'bsc' => '0x8894E0a0c962CB723c1976a4421c95949bE2D4E3',
        'arbitrum' => '0x489A8756C18C0b8B24EC2a2b9FF3D4d447F79BEc',
        'polygon' => '0xF977814e90dA44bFA03b6295A0616a897441aceC',
        'dash' => 'XdTLBRbnQTGnrxQAn7VtGmqLpm4WcZZTjq',
        'tron' => 'TQ5NMqJjrDkxT4h1u1Vd8Pu5aiZLhWDPpF',
        'solana' => '7xKXtg2CW87d97TXJSDpbD5jBkheTqA83TZRuJosgAsU',
        'ton' => 'UQAvDfWFG0oYX19jwNDNBBL1rKNT9XfaGP9HyTb5nb2Eml6y',
        'xrp' => 'rEb8TK3gBgk5auZkwc6sHnwrGVJH8DuaLh',
        'litecoin' => 'ltc1qd6xyu9wzhuwrxr3vwq8dxewsdmt6dnp9vzjsxc',
        'dogecoin' => 'DH5yaieqoZN36fDVciNyRueRGvGLR3mr7L',
        'zano' => 'ZxCbT9dJrRhtP5aVKmQ8yLnW3fEuHgSdX2qYkA6NpMvZ4TjB7cRwUeF1iGoK9sLxDn5YhQaW8ZmVbCtJrP3XeNdS2AkGuHy',
        'monero' => '4B6obxRSasr81nBZPEZPg3inzPoXBYtn2RPjSZmtTVrb2HXynnM9W61GKCGsKySRe4d3hZnDzdymGdATY',
    ];

    /** URI schemes used when building the deposit QR payload, keyed by chain. */
    private const URI_SCHEMES = [
        'bitcoin' => 'bitcoin',
        'bitcoincash' => 'bitcoincash',
        'ethereum' => 'ethereum',
        'dash' => 'dash',
        'litecoin' => 'litecoin',
        'dogecoin' => 'dogecoin',
        'monero' => 'monero',
    ];
    public function index(): View
    {
        return view('pages.swap');
    }

    public function aml(): View
    {
        return view('pages.aml-swap');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'rate' => ['required', 'in:floating,fixed'],
            'send_amount' => ['required', 'numeric', 'min:0'],
            'send_coin' => ['required', 'in:'.implode(',', array_keys(config('coins')))],
            'receive_coin' => ['required', 'in:'.implode(',', array_keys(config('coins')))],
            'address' => ['required', 'string', 'max:255'],
            'refund_address' => ['nullable', 'string', 'max:255'],
        ], [], [
            'send_amount' => 'amount',
            'send_coin' => 'send coin',
            'receive_coin' => 'receive coin',
            'address' => 'payout address',
            'refund_address' => 'refund address',
        ]);

        return $this->openTransaction($request->only([
            'send_amount', 'send_coin', 'receive_amount', 'receive_coin', 'address',
        ]) + ['mode' => 'standard']);
    }

    public function quote(Request $request): RedirectResponse
    {
        $request->validate([
            'rate' => ['required', 'in:floating,fixed'],
            'send_amount' => ['required', 'numeric', 'min:0'],
            'send_coin' => ['required', 'in:'.implode(',', array_keys(config('coins')))],
            'receive_coin' => ['required', 'in:'.implode(',', array_keys(config('coins')))],
        ], [], [
            'send_amount' => 'amount',
            'send_coin' => 'send coin',
            'receive_coin' => 'receive coin',
        ]);

        return back()->withInput()->with('status', 'Rate quotes are not wired up yet.');
    }

    public function quoteAml(Request $request): RedirectResponse
    {
        $request->validate([
            'send_amount' => ['required', 'numeric', 'min:0'],
            'send_coin' => ['required', 'in:'.implode(',', array_keys(config('coins')))],
        ], [], [
            'send_amount' => 'amount',
            'send_coin' => 'send coin',
        ]);

        return back()->withInput()->with('status', 'Rate quotes are not wired up yet.');
    }

    public function storeAml(Request $request): RedirectResponse
    {
        $request->validate([
            'send_amount' => ['required', 'numeric', 'min:0'],
            'send_coin' => ['required', 'in:'.implode(',', array_keys(config('coins')))],
            'address' => ['required', 'string', 'max:255'],
            'refund_address' => ['nullable', 'string', 'max:255'],
        ], [], [
            'send_amount' => 'amount',
            'send_coin' => 'send coin',
            'receive_coin' => 'receive coin',
            'address' => 'payout address',
            'refund_address' => 'refund address',
        ]);

        return $this->openTransaction($request->only([
            'send_amount', 'send_coin', 'address',
        ]) + ['receive_coin' => 'xmr', 'mode' => 'aml']);
    }

    /**
     * Hand the swap over to a freshly created transaction page.
     *
     * Nothing is persisted yet, so the submitted values travel in the session
     * and the page falls back to demo values when they are gone.
     */
    private function openTransaction(array $swap): RedirectResponse
    {
        return redirect()
            ->route('transaction', ['id' => (string) Str::uuid()])
            ->with('swap', array_filter($swap, static fn ($value) => $value !== null && $value !== ''));
    }

    public function show(string $id): View
    {
        abort_unless(Str::isUuid($id), 404);

        return view('pages.transaction', [
            'transaction' => $this->transaction($id, session('swap', [])),
        ]);
    }

    /**
     * Build the transaction shown on the status page.
     *
     * The deposit window is anchored in the session so the countdown actually
     * moves when the visitor hits refresh.
     */
    private function transaction(string $id, array $swap): array
    {
        $coins = config('coins');

        $sendCoin = $swap['send_coin'] ?? 'btc';
        $receiveCoin = $swap['receive_coin'] ?? 'xmr';
        $sendAmount = $swap['send_amount'] ?? '0.001';
        $receiveAmount = $swap['receive_amount'] ?? '0.14437924';

        $mode = isset(self::MODE_LABELS[$swap['mode'] ?? '']) ? $swap['mode'] : 'standard';

        $openedAt = session()->get("transactions.$id", now()->timestamp);
        session()->put("transactions.$id", $openedAt);

        $elapsed = (int) floor((now()->timestamp - $openedAt) / 60);
        $minutesLeft = max(0, self::DEPOSIT_WINDOW_MINUTES - $elapsed);

        $sendChain = self::COIN_CHAINS[$sendCoin] ?? 'bitcoin';
        $depositAddress = self::DEPOSIT_ADDRESSES[$sendChain];

        return [
            'id' => $id,
            'status' => $minutesLeft > 0 ? 'NEW' : 'EXPIRED',
            'mode' => $mode,
            'mode_label' => self::MODE_LABELS[$mode],
            'send_amount' => $sendAmount,
            'send_coin' => $sendCoin,
            'send_coin_label' => $coins[$sendCoin] ?? $coins['btc'],
            'receive_amount' => $receiveAmount,
            'receive_coin' => $receiveCoin,
            'receive_coin_label' => $coins[$receiveCoin] ?? $coins['xmr'],
            'payout_address' => $swap['address'] ?? self::DEPOSIT_ADDRESSES[self::COIN_CHAINS[$receiveCoin] ?? 'monero'],
            'deposit_address' => $depositAddress,
            'minutes_left' => $minutesLeft,
            'qr' => QrCode::svg($this->depositUri($sendChain, $depositAddress, $sendAmount)),
        ];
    }

    /**
     * A wallet-friendly payment URI, falling back to the bare address for
     * chains without a registered scheme.
     */
    private function depositUri(string $chain, string $address, string $amount): string
    {
        if (! isset(self::URI_SCHEMES[$chain])) {
            return $address;
        }

        return self::URI_SCHEMES[$chain].':'.$address.'?amount='.$amount;
    }
}

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

    /** Demo deposit addresses, one per coin the swap form accepts. */
    private const DEPOSIT_ADDRESSES = [
        'btc' => 'bc1qhwwe3cpdfz99t9tgdks2j3pnfqqqy3q0ltcckp',
        'eth' => '0x71C7656EC7ab88b098defB751B7401B5f6d8976F',
        'ltc' => 'ltc1qd6xyu9wzhuwrxr3vwq8dxewsdmt6dnp9vzjsxc',
        'usdt' => '0x71C7656EC7ab88b098defB751B7401B5f6d8976F',
        'xmr' => '4B6obxRSasr81nBZPEZPg3inzPoXBYtn2RPjSZmtTVrb2HXynnM9W61GKCGsKySRe4d3hZnDzdymGdATY',
    ];

    /** URI schemes used when building the deposit QR payload. */
    private const URI_SCHEMES = [
        'btc' => 'bitcoin',
        'eth' => 'ethereum',
        'ltc' => 'litecoin',
        'xmr' => 'monero',
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
        ]));
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
        ]) + ['receive_coin' => 'xmr']);
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

        $openedAt = session()->get("transactions.$id", now()->timestamp);
        session()->put("transactions.$id", $openedAt);

        $elapsed = (int) floor((now()->timestamp - $openedAt) / 60);
        $minutesLeft = max(0, self::DEPOSIT_WINDOW_MINUTES - $elapsed);

        $depositAddress = self::DEPOSIT_ADDRESSES[$sendCoin] ?? self::DEPOSIT_ADDRESSES['btc'];

        return [
            'id' => $id,
            'status' => $minutesLeft > 0 ? 'NEW' : 'EXPIRED',
            'send_amount' => $sendAmount,
            'send_coin' => $sendCoin,
            'send_coin_label' => $coins[$sendCoin] ?? $coins['btc'],
            'receive_amount' => $receiveAmount,
            'receive_coin' => $receiveCoin,
            'receive_coin_label' => $coins[$receiveCoin] ?? $coins['xmr'],
            'payout_address' => $swap['address'] ?? self::DEPOSIT_ADDRESSES['xmr'],
            'deposit_address' => $depositAddress,
            'minutes_left' => $minutesLeft,
            'qr' => QrCode::svg($this->depositUri($sendCoin, $depositAddress, $sendAmount)),
        ];
    }

    /**
     * A wallet-friendly payment URI, falling back to the bare address for
     * coins without a registered scheme.
     */
    private function depositUri(string $coin, string $address, string $amount): string
    {
        if (! isset(self::URI_SCHEMES[$coin])) {
            return $address;
        }

        return self::URI_SCHEMES[$coin].':'.$address.'?amount='.$amount;
    }
}

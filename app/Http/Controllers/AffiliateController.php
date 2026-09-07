<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AffiliateController extends Controller
{
    private function demoAffiliate(): array
    {
        return [
            'uuid' => '2228CCFB-F3DB-4730-8F24-01ED9DA97DE6',
            'referral_code' => 'eirFwjOgJV',
            'referral_link' => 'https://sageswap.io/?utm_source=eirFwjOgJV',
            'onion_link' => 'https://sageswap4ygi7k5e5gbnvhz2ldbprrelkkuourlp5vx6fl..',
            'balance' => '0.04812',
        ];
    }

    public function dashboard(): View
    {
        return view('pages.affiliate.dashboard', [
            'affiliate' => $this->demoAffiliate(),
            'transactions' => [
                [
                    'send_amount' => '0.0000',
                    'receive_amount' => '0.0000',
                    'value_usdt' => '0.00',
                    'profit_xmr' => '0.00',
                    'time' => '2026-08-26',
                ],
            ],
        ]);
    }

    public function showLogin(): View
    {
        return view('pages.affiliate.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'uuid' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [], [
            'uuid' => 'affiliate UUID',
        ]);

        return redirect()->route('affiliate.dashboard');
    }

    public function showRegister(): View
    {
        return view('pages.affiliate.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        return redirect()->route('affiliate.register')->with([
            'registered' => true,
            'uuid' => '2228ccfb-f3db-4730-8f24-01ed9da97de6',
            'password' => 'LVTxLVGQY',
        ]);
    }

    public function showWithdraw(): View
    {
        return view('pages.affiliate.withdraw', [
            'balance' => $this->demoAffiliate()['balance'],
        ]);
    }

    public function withdraw(Request $request): RedirectResponse
    {
        $request->validate([
            'address' => ['required', 'string', 'max:255'],
        ], [], [
            'address' => 'Monero address',
        ]);

        return redirect()->route('affiliate.dashboard')
            ->with('status', 'Withdrawal requests are not wired up yet.');
    }

    private function demoApiKeys(): array
    {
        return [
            ['id' => 43797, 'name' => 'Default API Token', 'last_used' => '2026-08-26'],
        ];
    }

    public function api(): View
    {
        return view('pages.affiliate.api', [
            'keys' => $this->demoApiKeys(),
        ]);
    }

    public function showRevokeApiKey(string $key): View
    {
        $match = collect($this->demoApiKeys())->firstWhere('id', (int) $key);

        abort_if($match === null, 404);

        return view('pages.affiliate.api-revoke', ['key' => $match]);
    }

    public function showCreateApiKey(): View
    {
        return view('pages.affiliate.api-create');
    }

    public function createApiKey(Request $request): RedirectResponse
    {
        return back()->with('status', 'API token creation is not wired up yet.');
    }

    public function revokeApiKey(string $key): RedirectResponse
    {
        return back()->with('status', 'API token revocation is not wired up yet.');
    }
}

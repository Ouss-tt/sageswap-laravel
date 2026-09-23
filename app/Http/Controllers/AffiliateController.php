<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class AffiliateController extends Controller
{
    /** Rows of referral history per page of the dashboard table. */
    private const PER_PAGE = 10;

    /**
     * The affiliate every page in this area is rendered from.
     *
     * HANDOFF CONTRACT
     * ----------------
     * Replacing this with the authenticated affiliate needs no Blade changes,
     * as long as the same keys come back:
     *
     *   balance             - everything earned since the last withdrawal.
     *   available           - what can be withdrawn right now. A withdrawal
     *                         takes the whole balance, so this is zero while
     *                         one is in flight and the views say so.
     *   pending_withdrawal  - null, or ['amount', 'address', 'requested_at']
     *                         for the request still being processed.
     *
     * ?pending=1 fakes that request so both states can be reviewed before a
     * backend can produce one. Local only, the same way the transaction
     * preview fakes its clock.
     */
    private function demoAffiliate(): array
    {
        $balance = '0.04812';

        $pending = app()->environment('local') && request()->boolean('pending')
            ? [
                'amount' => $balance,
                'address' => '4B6obxRSasr81nBZPEZPg3inzPoXBYtn2RPjSZmtTVrb2HXynnM9W61GKCGsKySRe4d3hZnDzdymGdATY',
                'requested_at' => '2026-09-23 14:12',
            ]
            : null;

        return [
            'uuid' => '2228CCFB-F3DB-4730-8F24-01ED9DA97DE6',
            'referral_code' => 'eirFwjOgJV',
            'referral_link' => 'https://sageswap.io/?utm_source=eirFwjOgJV',
            'onion_link' => 'https://sageswap4ygi7k5e5gbnvhz2ldbprrelkkuourlp5vx6fl..',
            'balance' => $balance,
            'available' => $pending === null ? $balance : '0.00000',
            'pending_withdrawal' => $pending,
        ];
    }

    public function dashboard(): View
    {
        return view('pages.affiliate.dashboard', [
            'affiliate' => $this->demoAffiliate(),
            'transactions' => $this->paginate($this->demoTransactions()),
        ]);
    }

    /**
     * Referral history, newest first.
     *
     * Enough rows to page through; the real list is a query and arrives with
     * whatever the backend orders it by.
     *
     * @return list<array<string, string>>
     */
    private function demoTransactions(): array
    {
        $rows = [
            ['0.0412', '2.94117647', '128.40', '0.00147', '2026-08-26'],
            ['1.2500', '0.00981204', '82.15', '0.00049', '2026-08-25'],
            ['0.0080', '0.57142857', '24.90', '0.00028', '2026-08-25'],
            ['320.00', '1.14285714', '320.00', '0.00571', '2026-08-24'],
            ['0.5000', '0.03571428', '15.60', '0.00017', '2026-08-23'],
            ['12.400', '0.08857142', '38.70', '0.00044', '2026-08-22'],
            ['0.0025', '0.17857142', '7.80', '0.00008', '2026-08-21'],
            ['88.000', '0.31428571', '88.00', '0.00157', '2026-08-20'],
            ['0.1900', '13.5714285', '592.60', '0.00678', '2026-08-19'],
            ['4.7000', '0.03357142', '14.65', '0.00016', '2026-08-18'],
            ['0.0600', '4.28571428', '187.10', '0.00214', '2026-08-17'],
            ['210.00', '0.75000000', '210.00', '0.00375', '2026-08-16'],
            ['0.0034', '0.24285714', '10.60', '0.00012', '2026-08-15'],
            ['1.0000', '0.00784963', '65.70', '0.00039', '2026-08-14'],
            ['0.7200', '0.05142857', '22.45', '0.00025', '2026-08-13'],
        ];

        return array_map(static fn (array $row): array => [
            'send_amount' => $row[0],
            'receive_amount' => $row[1],
            'value_usdt' => $row[2],
            'profit_xmr' => $row[3],
            'time' => $row[4],
        ], $rows);
    }

    /**
     * One page of the referral history.
     *
     * The rows are an array today and a query tomorrow, so the view is handed a
     * paginator either way: swapping this for ->paginate() on a builder leaves
     * both the table and its pager alone.
     *
     * @param  list<array<string, string>>  $rows
     */
    private function paginate(array $rows): LengthAwarePaginator
    {
        $page = LengthAwarePaginator::resolveCurrentPage();

        return (new LengthAwarePaginator(
            Collection::make($rows)->forPage($page, self::PER_PAGE)->values(),
            count($rows),
            self::PER_PAGE,
            $page,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        ))->withQueryString();
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

    public function showWithdraw(): View|RedirectResponse
    {
        $affiliate = $this->demoAffiliate();

        // A withdrawal takes the whole balance, so a second one while the first
        // is in flight is a request for money that has already left. The
        // dashboard hides the button; this stops the URL being typed around it.
        if ($affiliate['pending_withdrawal'] !== null) {
            return redirect()->route('affiliate.dashboard');
        }

        return view('pages.affiliate.withdraw', [
            'balance' => $affiliate['balance'],
        ]);
    }

    public function withdraw(Request $request): RedirectResponse
    {
        // The same guard on the way in: a form left open in a tab must not be
        // able to post a second request either.
        if ($this->demoAffiliate()['pending_withdrawal'] !== null) {
            return redirect()->route('affiliate.dashboard');
        }

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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SwapController extends Controller
{
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

        return back()->withInput()->with('status', 'Swap creation is not wired up yet.');
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

        return back()->withInput()->with('status', 'Swap creation is not wired up yet.');
    }
}

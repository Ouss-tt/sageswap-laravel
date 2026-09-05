<?php

use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\SwapController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SwapController::class, 'index'])->name('swap');
Route::post('/', [SwapController::class, 'store'])->name('swap.store');
Route::post('/quote', [SwapController::class, 'quote'])->name('swap.quote');

Route::get('/transaction/{id}', [SwapController::class, 'show'])->name('transaction');

Route::get('/aml_swap', [SwapController::class, 'aml'])->name('aml-swap');
Route::post('/aml_swap', [SwapController::class, 'storeAml'])->name('aml-swap.store');
Route::post('/aml_swap/quote', [SwapController::class, 'quoteAml'])->name('aml-swap.quote');

Route::view('/help', 'pages.help')->name('help');
Route::view('/faq', 'pages.faq')->name('faq');
Route::view('/support', 'pages.support')->name('support');
Route::view('/transparency', 'pages.transparency')->name('transparency');

Route::prefix('affiliate')->name('affiliate.')->group(function () {
    Route::get('/', [AffiliateController::class, 'dashboard'])->name('dashboard');

    Route::get('/login', [AffiliateController::class, 'showLogin'])->name('login');
    Route::post('/login', [AffiliateController::class, 'login'])->name('login.attempt');

    Route::get('/register', [AffiliateController::class, 'showRegister'])->name('register');
    Route::post('/register', [AffiliateController::class, 'register'])->name('register.store');


    Route::get('/withdraw', [AffiliateController::class, 'showWithdraw'])->name('withdraw');
    Route::post('/withdraw', [AffiliateController::class, 'withdraw'])->name('withdraw.store');

    Route::get('/api', [AffiliateController::class, 'api'])->name('api');
    Route::get('/api/create', [AffiliateController::class, 'showCreateApiKey'])->name('api.create');
    Route::get('/api/revoke/{key}', [AffiliateController::class, 'showRevokeApiKey'])->name('api.revoke');
    Route::post('/api', [AffiliateController::class, 'createApiKey'])->name('api.store');
    Route::delete('/api/{key}', [AffiliateController::class, 'revokeApiKey'])->name('api.destroy');
});

if (app()->environment('local')) {
    Route::view('/_preview/500', 'errors.500');
}

# SageSwap

Laravel 12 / Blade front end for the SageSwap exchange.

The UI ships as server-rendered Blade with a single hand-written stylesheet. There is
no build step: no `package.json`, no bundler, no JavaScript. Every interactive element
— accordions, rate tabs, coin pickers, the swap flip, the refund-address reveal — is
driven by CSS selectors alone.


## Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
```

The app is then served at http://localhost:8000.

## Routes

Defined in `routes/web.php`.

| Method | URI | Name | Handler |
| --- | --- | --- | --- |
| GET | `/` | `swap` | `SwapController@index` |
| POST | `/` | `swap.store` | `SwapController@store` |
| POST | `/quote` | `swap.quote` | `SwapController@quote` |
| GET | `/aml_swap` | `aml-swap` | `SwapController@aml` |
| POST | `/aml_swap` | `aml-swap.store` | `SwapController@storeAml` |
| POST | `/aml_swap/quote` | `aml-swap.quote` | `SwapController@quoteAml` |
| GET | `/help` | `help` | `pages.help` |
| GET | `/faq` | `faq` | `pages.faq` |
| GET | `/support` | `support` | `pages.support` |
| GET | `/transparency` | `transparency` | `pages.transparency` |
| GET | `/affiliate` | `affiliate.dashboard` | `AffiliateController@dashboard` |
| GET | `/affiliate/login` | `affiliate.login` | `AffiliateController@showLogin` |
| POST | `/affiliate/login` | `affiliate.login.attempt` | `AffiliateController@login` |
| GET | `/affiliate/register` | `affiliate.register` | `AffiliateController@showRegister` |
| POST | `/affiliate/register` | `affiliate.register.store` | `AffiliateController@register` |
| GET | `/affiliate/withdraw` | `affiliate.withdraw` | `AffiliateController@showWithdraw` |
| POST | `/affiliate/withdraw` | `affiliate.withdraw.store` | `AffiliateController@withdraw` |
| GET | `/affiliate/api` | `affiliate.api` | `AffiliateController@api` |
| POST | `/affiliate/api` | `affiliate.api.store` | `AffiliateController@createApiKey` |
| GET | `/affiliate/api/create` | `affiliate.api.create` | `AffiliateController@showCreateApiKey` |
| GET | `/affiliate/api/revoke/{key}` | `affiliate.api.revoke` | `AffiliateController@showRevokeApiKey` |
| DELETE | `/affiliate/api/{key}` | `affiliate.api.destroy` | `AffiliateController@revokeApiKey` |

There is no separate registration confirmation page. On success `register` flashes the
generated UUID and password and redirects back to `/affiliate/register`, which renders
them in place of the form. The values are a session flash, so they appear once and are
gone on refresh.

`/affiliate/api/create` and `/affiliate/api/revoke/{key}` are the confirmation screens
for the two API key actions; both submit to the `POST` and `DELETE` routes above.

The `quote` routes back the **GET RATE** button. It is a submit button with a
`formaction`, so it posts the panel to a route that validates only the amount and coins
rather than the whole swap.

`GET /_preview/500` renders the error page for design work. It is registered only when
`APP_ENV=local` and can be removed from `routes/web.php` at any time.

## Layout

```
app/Http/Controllers/
  SwapController.php          swap + NO AML swap
  AffiliateController.php     affiliate area

config/
  coins.php                   supported coins

resources/views/
  layouts/app.blade.php       document shell, head, header, footer
  errors/500.blade.php        server error page
  partials/
    header.blade.php          logo, nav, affiliate CTA
    footer.blade.php
    swap-hero.blade.php       mascot, headline, stats
    swap-tabs.blade.php       SWAP / NO AML SWAP / HELP
    swap-fields.blade.php     payout address, refund reveal
    swap-footer.blade.php     submit + escrow note
  components/
    coin-select.blade.php     coin dropdown with leading icon
    coin-locked.blade.php     fixed Monero receive side
    coin-icon.blade.php       per-coin SVG
    accordion-item.blade.php  FAQ entry
    qa-item.blade.php         Help entry
    form-errors.blade.php     validation summary, rendered above every form
  pages/
    swap · aml-swap · help · faq · support · transparency
    affiliate/dashboard · login · register · withdraw
    affiliate/api · api-create · api-revoke

public/
  css/app.css                 the entire stylesheet
  assets/                     logo, mascot, error artwork, favicon
```

Pages set `@section('title')` and `@section('description')`; the layout builds the
document title, meta description and OpenGraph tags from those two values.

## Active navigation state

Header links and swap tabs resolve their own highlight from the current route name,
so no page passes a flag down:

```blade
<a href="{{ route('faq') }}" @class(['site-nav__link', 'is-active' => request()->routeIs('faq')])>FAQ</a>
```

The SWAP link matches `swap`, `aml-swap` and `help`, keeping it lit across all three
panel tabs.

## Adding a coin

`config/coins.php` is the source of truth for every coin dropdown. Three edits:

1. add the key and label to `config/coins.php`
2. add a matching `@case` in `resources/views/components/coin-icon.blade.php`
3. add the key to the `:has()` icon rule in `public/css/app.css`

## Error pages

`resources/views/errors/500.blade.php` is resolved automatically by Laravel. It is
shown only when `APP_DEBUG=false`; with debug enabled the stack trace is rendered
instead. Set `APP_DEBUG=false` in production.

## Not yet implemented

Read paths are complete. Write paths validate their input and return without
persisting anything:

- `SwapController::store()` and `storeAml()` — validate, then redirect back. Connect
  these to the exchange backend and redirect to a deposit screen.
- `AffiliateController` — `demoAffiliate()` supplies placeholder UUID, referral code,
  links and balance. Login accepts any credentials; registration returns a fixed UUID.
  Replacing that method with the authenticated affiliate requires no Blade changes,
  as the views already read from variables.
- **GET RATE** posts to `swap.quote` / `aml-swap.quote`, which validate the amount and
  coins but return no rate. Fetch the quote there and pass it back to the view.
- `createApiKey()` does not validate its `name` field, so the create form cannot fail yet.
- Withdrawals have a 0.2 XMR minimum that is not yet enforced in validation.

## Form validation

Every form renders `<x-form-errors />`, which prints the validation summary above the
fields when the request is redirected back. Field names are set through the third
argument of `validate()`, so messages read "The affiliate UUID field is required."
rather than naming the raw column. `old()` is threaded through every input, so values
survive the round trip.

This is server-side only, which is why the message appears after a reload rather than
as you type.

## Notes

Two structural constraints in the swap panel:

- The `<form>` carries the `swap-panel__body` class itself rather than wrapping the
  panel; the stylesheet expects content and footer to be its direct children.
- `#refund` and `#flip` must remain siblings of `.swap-fields` / `.swap-rows`. The
  toggles are reached with `~`, so moving them into a wrapper breaks both.


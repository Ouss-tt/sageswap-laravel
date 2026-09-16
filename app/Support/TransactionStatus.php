<?php

namespace App\Support;

/**
 * The transaction lifecycle, as the client specified it.
 *
 * HANDOFF CONTRACT
 * ----------------
 * The front end reads exactly one value from the backend: a raw status string.
 * Everything the transaction page shows - the label, the colour, whether the
 * deposit instructions render at all - is derived from the table below, so
 * wiring up a real exchange means editing this file and nothing else.
 *
 * The array keys are our best guess at the provider's codes; only `new` and
 * `error` were handed to us. When the real spellings land, add them to ALIASES
 * rather than renaming a key, so both spellings keep working during rollout.
 * Anything that matches neither falls through to fallback(), which shows a
 * neutral state and - the important part - never renders deposit instructions.
 */
final class TransactionStatus
{
    /**
     * label   - what the customer reads. Printed as written; nothing uppercases it.
     * tone    - picks the .tx-state--* and .notice--* colour.
     * deposit - the visitor still has to pay, so show amount, address, QR and clock.
     * support - offer the contact-support call to action.
     * payout  - the swap has settled, so show the payout transaction hash.
     * lapsed  - the deposit window ran out, so show the clock stopped at EXPIRED.
     */
    private const STATES = [
        'new' => [
            'label' => 'Waiting For Deposit',
            'tone' => 'accent',
            'deposit' => true,
            'support' => false,
            'payout' => false,
            'lapsed' => false,
        ],
        'confirming' => [
            'label' => 'Awaiting Confirmation',
            'tone' => 'neutral',
            'deposit' => false,
            'support' => false,
            'payout' => false,
            'lapsed' => false,
        ],
        'sending' => [
            'label' => 'Sending',
            'tone' => 'neutral',
            'deposit' => false,
            'support' => false,
            'payout' => false,
            'lapsed' => false,
        ],
        'finished' => [
            'label' => 'Completed',
            'tone' => 'accent',
            'deposit' => false,
            'support' => false,
            'payout' => true,
            'lapsed' => false,
        ],
        'expired' => [
            'label' => 'Expired',
            'tone' => 'danger',
            'deposit' => false,
            'support' => false,
            'payout' => false,
            'lapsed' => true,
        ],
        'error' => [
            'label' => 'Failed',
            'tone' => 'danger',
            'deposit' => false,
            'support' => true,
            'payout' => false,
            'lapsed' => false,
        ],
        'refunded' => [
            'label' => 'Refunded',
            'tone' => 'warning',
            'deposit' => false,
            'support' => true,
            'payout' => false,
            'lapsed' => false,
        ],
        'support' => [
            'label' => 'Contact Support',
            'tone' => 'warning',
            'deposit' => false,
            'support' => true,
            'payout' => false,
            'lapsed' => false,
        ],
    ];

    /**
     * Provider spellings that mean one of the statuses above.
     *
     * This is the seam the backend work lands on: a code we have not seen yet
     * only needs a line here.
     */
    private const ALIASES = [
        'waiting' => 'new',
        'waiting_for_deposit' => 'new',
        'awaiting_deposit' => 'new',
        'created' => 'new',

        'confirm' => 'confirming',
        'confirmation' => 'confirming',
        'awaiting_confirmation' => 'confirming',
        'confirmations' => 'confirming',
        'exchanging' => 'confirming',

        'send' => 'sending',
        'sending_funds' => 'sending',
        'payout' => 'sending',

        'complete' => 'finished',
        'completed' => 'finished',
        'success' => 'finished',
        'done' => 'finished',

        'timeout' => 'expired',
        'timed_out' => 'expired',

        'failed' => 'error',
        'failure' => 'error',
        'cancelled' => 'error',
        'canceled' => 'error',

        'refund' => 'refunded',
        'refunding' => 'refunded',

        'hold' => 'support',
        'on_hold' => 'support',
        'verifying' => 'support',
        'verification' => 'support',
        'review' => 'support',
        'action_required' => 'support',
    ];

    /**
     * Everything the view needs for one status, keyed code included.
     *
     * Unknown and missing codes resolve to fallback() so a page never leaks a
     * raw provider string to a visitor.
     */
    public static function resolve(?string $code): array
    {
        $key = self::normalise($code);

        if ($key === null) {
            return self::fallback();
        }

        return ['code' => $key] + self::STATES[$key];
    }

    /**
     * The status key a raw code maps to, or null when we do not recognise it.
     */
    public static function normalise(?string $code): ?string
    {
        $code = strtolower(trim((string) $code));

        if ($code === '') {
            return null;
        }

        // Providers are inconsistent about separators, so flatten them all.
        $code = str_replace([' ', '-'], '_', $code);

        if (isset(self::STATES[$code])) {
            return $code;
        }

        return self::ALIASES[$code] ?? null;
    }

    /**
     * Shown when the backend sends a code this table has never heard of.
     *
     * Deliberately neutral rather than "Contact Support": during the backend
     * rollout an unmapped code is far more likely to be our gap than a real
     * problem with the swap, and it should not send anyone to the help desk.
     */
    public static function fallback(): array
    {
        return [
            'code' => 'processing',
            'label' => 'Processing',
            'tone' => 'neutral',
            'deposit' => false,
            'support' => false,
            'payout' => false,
            'lapsed' => false,
        ];
    }

    /**
     * Every status, in lifecycle order - used by the local preview route.
     *
     * @return list<string>
     */
    public static function codes(): array
    {
        return array_keys(self::STATES);
    }
}

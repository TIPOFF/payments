<?php

use Tipoff\Payments\Models\LocationPaymentSetting;

return [
    'stripe_keys' => [
        /**
         * Stripe Key resolution priority is
         * - LocationPaymentSetting DB fields
         * - location specific config setting
         * - default config setting
         *
         * Location settings are keyed by location abbreviation.  If a default is configured
         * and a location should NOT use the default, it MUST have an entry here.
         */
        'locations' => [
            // 'ABCD' => LocationPaymentSetting::stripeEnvKeyPair('ABCD_'),
            // 'EFGH' => LocationPaymentSetting::stripeEnvKeyPair('EFGH_'),
        ],

        /**
         * Default keys are used if location does not have an explicit setting, either directly
         * in the DB record or in the locations specific key configuration.
         *
         * If no prefix is provided, STRIPE_PUBLISHABLE_KEY, STRIPE_SECRET_KEY env values are used
         */
        'default' => LocationPaymentSetting::stripeEnvKeyPair(),
    ],
];

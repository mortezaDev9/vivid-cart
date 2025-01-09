<?php

return [
    'name'            => 'Payment',
    'payment_gateway' => env('PAYMENT_GATEWAY', 'zarinpal'),

    'zarinpal'        => [
        'merchant_id' => env('ZARINPAL_MERCHANT_ID', ''),
        'sandbox'     => env('ZARINPAL_SANDBOX', false),

        'urls' => [
            'request'  => [
                'sandbox'    => 'https://sandbox.zarinpal.com/pg/v4/payment/request.json',
                'production' => 'https://api.zarinpal.com/pg/v4/payment/request.json',
            ],
            'verify'   => [
                'sandbox'    => 'https://sandbox.zarinpal.com/pg/v4/payment/verify.json',
                'production' => 'https://api.zarinpal.com/pg/v4/payment/verify.json',
            ],
            'start_pay' => [
                'sandbox'    => 'https://sandbox.zarinpal.com/pg/StartPay/',
                'production' => 'https://www.zarinpal.com/pg/StartPay/',
            ],
        ],
    ],

    'callback' => 'payments.callback',
];

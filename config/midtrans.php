<?php

return [
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),

    'produksi' => (bool) env('MIDTRANS_PRODUKSI', false),

    'signature_key' => env('MIDTRANS_SIGNATURE_KEY', env('MIDTRANS_SERVER_KEY')),

    'snap_url' => env('MIDTRANS_PRODUKSI', false)
        ? 'https://app.midtrans.com/snap/v1/transactions'
        : 'https://app.sandbox.midtrans.com/snap/v1/transactions',

    'api_url' => env('MIDTRANS_PRODUKSI', false)
        ? 'https://api.midtrans.com'
        : 'https://api.sandbox.midtrans.com',
];

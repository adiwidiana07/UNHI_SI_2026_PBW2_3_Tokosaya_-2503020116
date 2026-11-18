<?php

return [
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),

    'produksi' => (bool) env('MIDTRANS_PRODUKSI', false),

    // URL webhook yang dikirim lewat header X-Override-Notification.
    // Kosong = Midtrans memakai Notification URL di dashboard.
    'notifikasi_url' => env('MIDTRANS_NOTIFIKASI_URL'),

    'signature_key' => env('MIDTRANS_SIGNATURE_KEY') ?: env('MIDTRANS_SERVER_KEY'),

    'snap_url' => env('MIDTRANS_PRODUKSI', false)
        ? 'https://app.midtrans.com/snap/v1/transactions'
        : 'https://app.sandbox.midtrans.com/snap/v1/transactions',

    'api_url' => env('MIDTRANS_PRODUKSI', false)
        ? 'https://api.midtrans.com'
        : 'https://api.sandbox.midtrans.com',
];

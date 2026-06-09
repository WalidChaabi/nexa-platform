<?php

return [

    'bulksms' => [
        'token' => env('BULKSMS_TOKEN'),
        'endpoint' => env('BULKSMS_ENDPOINT', 'https://bulksms.ma/developer/sms/send'),
        'timeout' => (int) env('BULKSMS_TIMEOUT', 15),
    ],

    'cmi' => [
        'client_id' => env('CMI_CLIENT_ID'),
        'store_key' => env('CMI_STORE_KEY'),
        'gateway_url' => env('CMI_GATEWAY_URL', 'https://testpayment.cmi.co.ma/fim/est3Dgate'),
        'currency' => env('CMI_CURRENCY', '504'),
    ],

];

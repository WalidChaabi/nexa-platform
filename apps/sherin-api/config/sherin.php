<?php

return [

    'tva_rate' => (float) env('SHERIN_TVA', 0.20),

    'sms_price' => (float) env('SHERIN_SMS_PRICE', 0.5),

    'sms_cross_selling_price' => (float) env('SHERIN_SMS_CROSS_PRICE', 1.0),

    'email_price' => (float) env('SHERIN_EMAIL_PRICE', 0.1),

    'exceptional_discount' => (float) env('SHERIN_EXCEPTIONAL_DISCOUNT', 0),

];

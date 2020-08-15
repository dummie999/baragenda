<?php

return [

    'contact' => [
        'bar' => env('MENSA_BAR_PHONE', ''),
        'mail' => env('MENSA_CONTACT_MAIL', ''),
        'printer' => env('MENSA_PRINTER_MAIL', ''),
    ],


    'ldap' => [
        'admin_group' => env('MENSA_LDAP_ADMIN_GROUP'),
        'allowed_group' => env('MENSA_LDAP_ALLOWED_GROUP'),
        'user_base' => env('MENSA_LDAP_USER_BASEDN'),
    ],

    'url' => [
        'forgot_password' => env('MENSA_URL_FORGOT_PASSWORD'),
    ],

    'service_users' => [
        'users' => [
            [
                'lidnummer' => 'bar_soos',
                'name' => 'Bar soos',
                'token' => env('MENSA_ACCOUNTURL_BAR001'),
            ],
            [
                'lidnummer' => 'bar_fz',
                'name' => 'Bar filmzaal',
                'token' => env('MENSA_ACCOUNTURL_BAR002'),
            ]
        ],
        'whitelisted_ips' => explode(',', env('MENSA_ACCOUNT_WHITELIST_IPS')),
    ],

];
<?php

    return [

        'paths' => ['api/*', 'sanctum/csrf-cookie', 'verification/*'],

        'allowed_methods' => ['*'],

        'allowed_origins' => ['*'], // یا دامنه خاصی مانند 'http://localhost:3000'

        'allowed_origins_patterns' => [],

        'allowed_headers' => ['*'],

        'exposed_headers' => [],

        'max_age' => 0,

        'supports_credentials' => false,

    ];

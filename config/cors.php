<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie', 'storage/*'], // Add 'storage/*' if images are served from storage

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];

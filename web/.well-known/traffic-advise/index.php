<?php

header('Content-Type: application/trafficadvice+json');

echo json_encode([[
    'user_agent' => 'prefetch-proxy',
    'google_prefetch_proxy_eap' => [
        'fraction' => 1.0
    ]
]]);

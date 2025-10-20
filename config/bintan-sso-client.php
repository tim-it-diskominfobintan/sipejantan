<?php

return [
    'endpoint' => env('BINTAN_SSO_ENDPOINT', 'https://sso.bintankab.go.id'),
    'client_id' => env('BINTAN_SSO_CLIENT_ID', ''),
    'client_secret' => env('BINTAN_SSO_CLIENT_SECRET', ''),
    'scopes' => env('BINTAN_SSO_SCOPES', 'view-user'),
    'callback_uri' => env('BINTAN_SSO_CLIENT_CALLBACK_URI'),
    'logout_callback_uri' => env('BINTAN_SSO_CLIENT_LOGOUT_CALLBACK_URI'),
];

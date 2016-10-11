<?php

return [
    'dependencies' => [
        'factories' => [
            League\OAuth2\Server\AuthorizationServer::class => Starcode\Staff\Service\AuthorizationServerFactory::class,
            League\OAuth2\Server\Grant\PasswordGrant::class => Starcode\Staff\Service\PasswordGrantFactory::class,
        ],
    ],
    'authorization' => [
        'access_token_ttl' => 'PT1H',
        'refresh_token_ttl' => 'P1M',

        'private_key' => 'data/private.key',
        'public_key' => 'data/public.key',
        'private_key_pass_phrase' => 'secret',

        'number_bits' => 1024,
    ],
];
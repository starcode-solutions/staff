<?php

return [
    'dependencies' => [
        'invokables' => [
            League\OAuth2\Server\Grant\ClientCredentialsGrant::class => League\OAuth2\Server\Grant\ClientCredentialsGrant::class,
        ],
        'factories' => [
            League\OAuth2\Server\AuthorizationServer::class => Starcode\Staff\Service\AuthorizationServerFactory::class,
            League\OAuth2\Server\ResourceServer::class => Starcode\Staff\Service\ResourceServerFactory::class,

            // grants
            League\OAuth2\Server\Grant\PasswordGrant::class => Starcode\Staff\Service\PasswordGrantFactory::class,
            League\OAuth2\Server\Grant\RefreshTokenGrant::class => \Starcode\Staff\Service\RefreshTokenGrantFactory::class,
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
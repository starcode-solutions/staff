<?php

return [
    'dependencies' => [
        'invokables' => [
            Zend\Expressive\Router\RouterInterface::class => Zend\Expressive\Router\FastRouteRouter::class,
            App\Action\PingAction::class => App\Action\PingAction::class,
        ],
        'factories' => [
            App\Action\HomePageAction::class => App\Action\HomePageFactory::class,
            League\OAuth2\Server\Middleware\ResourceServerMiddleware::class => Starcode\Staff\Service\ResourceServerMiddlewareFactory::class,

            // API actions
            Starcode\Staff\Action\Auth\TokenAction::class => Starcode\Staff\Action\Auth\TokenActionFactory::class,
            Starcode\Staff\Action\Auth\InfoAction::class => Starcode\Staff\Action\Auth\InfoActionFactory::class,
            Starcode\Staff\Action\User\RegistrationAction::class => Starcode\Staff\Action\User\RegistrationActionFactory::class,
        ],
    ],

    'routes' => [
        [
            'name' => 'home',
            'path' => '/',
            'middleware' => App\Action\HomePageAction::class,
            'allowed_methods' => ['GET'],
        ],
        [
            'name' => 'api.ping',
            'path' => '/api/ping',
            'middleware' => App\Action\PingAction::class,
            'allowed_methods' => ['GET'],
        ],
        [
            'name' => 'auth.token',
            'path' => '/auth/token',
            'middleware' => Starcode\Staff\Action\Auth\TokenAction::class,
            'allowed_methods' => ['POST'],
        ],
        [
            'name' => 'auth.info',
            'path' => '/auth/info',
            'middleware' => [
                League\OAuth2\Server\Middleware\ResourceServerMiddleware::class,
                Starcode\Staff\Action\Auth\InfoAction::class,
            ],
            'allowed_method' => ['GET'],
        ],
        [
            'name' => 'user.registration',
            'path' => '/user/registration',
            'middleware' => [
                League\OAuth2\Server\Middleware\ResourceServerMiddleware::class,
                Starcode\Staff\Action\User\RegistrationAction::class,
            ],
            'allowed_method' => ['POST'],
        ],
    ],
];

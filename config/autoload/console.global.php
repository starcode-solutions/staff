<?php

return [
    'dependencies' => [
        'factories' => [
            Symfony\Component\Console\Application::class => Starcode\Staff\Service\ConsoleApplicationFactory::class,
        ]
    ],
    'console' => [
        'name' => 'Starcode Staff CLI',
        'version' => '1.0.0',
        'commands' => [

        ],
    ],
];
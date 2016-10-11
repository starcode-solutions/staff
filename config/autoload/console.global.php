<?php

use Starcode\Staff\Command;

return [
    'dependencies' => [
        'factories' => [
            Symfony\Component\Console\Application::class => Starcode\Staff\Service\ConsoleApplicationFactory::class,

            // commands
            Command\User\GenerateCommand::class => Command\User\GenerateCommandFactory::class,
            Command\Client\GenerateCommand::class => Command\Client\GenerateCommandFactory::class,
        ]
    ],
    'console' => [
        'name' => 'Starcode Staff CLI',
        'version' => '1.0.0',
        'commands' => [
            Command\User\GenerateCommand::class,
            Command\Client\GenerateCommand::class,
        ],
    ],
];
<?php

use Starcode\Staff\Command;

return [
    'dependencies' => [
        'factories' => [
            Symfony\Component\Console\Application::class => Starcode\Staff\Service\ConsoleApplicationFactory::class,

            // commands
            Command\User\GenerateCommand::class => Command\User\GenerateCommandFactory::class,
            Command\Client\GenerateCommand::class => Command\Client\GenerateCommandFactory::class,
            Command\Auth\GenerateKeysCommand::class => Command\Auth\GenerateKeysCommandFactory::class,
            Command\Scope\InitCommand::class => Command\Scope\InitCommandFactory::class,
        ]
    ],
    'console' => [
        'name' => 'Starcode Staff CLI',
        'version' => '1.0.0',
        'commands' => [
            Command\User\GenerateCommand::class,
            Command\Client\GenerateCommand::class,
            Command\Auth\GenerateKeysCommand::class,
            Command\Scope\InitCommand::class,
        ],
    ],
];
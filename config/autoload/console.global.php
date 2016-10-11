<?php

return [
    'dependencies' => [
        'factories' => [
            Symfony\Component\Console\Application::class => Starcode\Staff\Service\ConsoleApplicationFactory::class,

            // commands
            Starcode\Staff\Command\User\GenerateCommand::class => Starcode\Staff\Command\User\GenerateCommandFactory::class,
        ]
    ],
    'console' => [
        'name' => 'Starcode Staff CLI',
        'version' => '1.0.0',
        'commands' => [
            Starcode\Staff\Command\User\GenerateCommand::class,
        ],
    ],
];
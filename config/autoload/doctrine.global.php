<?php

return [
    'dependencies' => [
        'factories' => [
            Doctrine\ORM\EntityManager::class => Starcode\Staff\Service\EntityManagerFactory::class,
        ],
    ],
    'doctrine' => [
        'entities_paths' => [
            __DIR__ . '/../../src/Starcode/Staff/Entity',
        ],
        'proxy_dir' => __DIR__ . '/../../data/Doctrine/Proxy',
        'connection' => [
            'driver' => 'pdo_pgsql',
            'host' => $_ENV['DB_HOST'] ?? 'localhost',
            'port' => $_ENV['DB_PORT'] ?? 5432,
            'dbname' => $_ENV['DB_NAME'] ?? 'postgres',
            'user' => $_ENV['DB_USER'] ?? 'postgres',
            'password' => $_ENV['DB_PASS'] ?? '',
        ],
    ],
];
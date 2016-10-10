<?php

namespace Starcode\Staff\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Interop\Container\ContainerInterface;
use Starcode\Staff\Exception\InvalidConfigException;
use Zend\ServiceManager\Factory\FactoryInterface;

class ConsoleApplicationFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');

        $consoleConfig = $config['console'] ?? null;
        if (!$consoleConfig) {
            throw new InvalidConfigException('Console config not set');
        }

        $name = $consoleConfig['name'] ?? 'Starcode Staff CLI';
        $version = $consoleConfig['version'] ?? '0.0.1';

        $entityManager = $container->get(EntityManager::class);
        $application = ConsoleRunner::createApplication(ConsoleRunner::createHelperSet($entityManager));
        $application->setName($name);
        $application->setVersion($version);

        $commands = $consoleConfig['commands'] ?? [];
        foreach ($commands as $command) {
            $application->add($container->get($command));
        }

        return $application;
    }
}

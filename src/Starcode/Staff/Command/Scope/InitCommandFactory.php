<?php

namespace Starcode\Staff\Command\Scope;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Starcode\Staff\Exception\InvalidConfigException;
use Starcode\Staff\Util\Console\ProgressBarBuilder;
use Zend\ServiceManager\Factory\FactoryInterface;

class InitCommandFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $scopesConfig = $config['authorization']['scopes'] ?? null;

        if (!$scopesConfig) {
            throw new InvalidConfigException('Scopes config not found');
        }

        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);
        return new InitCommand($entityManager, $scopesConfig, new ProgressBarBuilder());
    }
}
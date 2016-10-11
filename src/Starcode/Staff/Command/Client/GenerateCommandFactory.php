<?php

namespace Starcode\Staff\Command\Client;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Starcode\Staff\Util\Console\ProgressBarBuilder;
use Zend\ServiceManager\Factory\FactoryInterface;

class GenerateCommandFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new GenerateCommand(
            $container->get(EntityManager::class),
            new ProgressBarBuilder()
        );
    }
}
<?php

namespace Starcode\Staff\Action\Auth;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Starcode\Staff\Entity\User;
use Zend\ServiceManager\Factory\FactoryInterface;

class InfoActionFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);

        return new InfoAction(
            $entityManager->getRepository(User::class)
        );
    }
}
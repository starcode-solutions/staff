<?php

namespace Starcode\Staff\Service;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use League\OAuth2\Server\ResourceServer;
use Starcode\Staff\Entity\AccessToken;
use Starcode\Staff\Exception\InvalidConfigException;
use Zend\ServiceManager\Factory\FactoryInterface;

class ResourceServerFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');

        $authorizationConfig = $config['authorization'] ?? null;
        if (!$authorizationConfig) {
            throw new InvalidConfigException('Authorization config not set');
        }

        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);

        $accessTokenRepository = $entityManager->getRepository(AccessToken::class);

        $publicKey = 'file://' . realpath($authorizationConfig['public_key'] ?? 'data/public.key');

        return new ResourceServer($accessTokenRepository, $publicKey);
    }
}
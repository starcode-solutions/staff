<?php

namespace Starcode\Staff\Service;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use Starcode\Staff\Entity\RefreshToken;
use Starcode\Staff\Exception\InvalidConfigException;
use Starcode\Staff\Exception\InvalidRefreshTokenTTLException;
use Zend\ServiceManager\Factory\FactoryInterface;

class RefreshTokenGrantFactory implements FactoryInterface
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

        $refreshTokenRepository = $entityManager->getRepository(RefreshToken::class);

        $refreshTokenGrant = new RefreshTokenGrant($refreshTokenRepository);

        $refreshTokenTTLFormat = $authorizationConfig['refresh_token_ttl'] ?? 'P1M';
        try {
            $refreshTokenTTL = new \DateInterval($refreshTokenTTLFormat);
        } catch (\Exception $exception) {
            throw new InvalidRefreshTokenTTLException($refreshTokenTTLFormat);
        }

        $refreshTokenGrant->setRefreshTokenTTL($refreshTokenTTL);

        return $refreshTokenGrant;
    }
}
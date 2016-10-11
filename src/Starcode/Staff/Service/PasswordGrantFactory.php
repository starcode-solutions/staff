<?php

namespace Starcode\Staff\Service;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use League\OAuth2\Server\Grant\PasswordGrant;
use Starcode\Staff\Entity\RefreshToken;
use Starcode\Staff\Entity\User;
use Starcode\Staff\Exception\InvalidConfigException;
use Starcode\Staff\Exception\InvalidRefreshTokenTTLException;
use Zend\ServiceManager\Factory\FactoryInterface;

class PasswordGrantFactory implements FactoryInterface
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

        $userRepository = $entityManager->getRepository(User::class);
        $refreshTokenRepository = $entityManager->getRepository(RefreshToken::class);

        $passwordGrant = new PasswordGrant(
            $userRepository,
            $refreshTokenRepository
        );

        $refreshTokenTTLFormat = $authorizationConfig['refresh_token_ttl'] ?? 'P1M';
        try {
            $refreshTokenTTL = new \DateInterval($refreshTokenTTLFormat);
        } catch (\Exception $exception) {
            throw new InvalidRefreshTokenTTLException($refreshTokenTTLFormat);
        }

        $passwordGrant->setRefreshTokenTTL($refreshTokenTTL);

        return $passwordGrant;
    }
}

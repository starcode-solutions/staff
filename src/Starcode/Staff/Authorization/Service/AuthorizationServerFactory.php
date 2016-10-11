<?php

namespace Starcode\Staff\Authorization\Service;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Grant\PasswordGrant;
use Starcode\Staff\Entity\AccessToken;
use Starcode\Staff\Entity\Client;
use Starcode\Staff\Entity\Scope;
use Starcode\Staff\Exception\InvalidAccessTokenTTLException;
use Starcode\Staff\Exception\InvalidConfigException;
use Zend\ServiceManager\Factory\FactoryInterface;

class AuthorizationServerFactory implements FactoryInterface
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

        $clientRepository = $entityManager->getRepository(Client::class);
        $accessTokenRepository = $entityManager->getRepository(AccessToken::class);
        $scopeRepository = $entityManager->getRepository(Scope::class);

        $privateKey = 'file://' . realpath($authorizationConfig['private_key'] ?? 'data/public.key');
        $publicKey = 'file://' . realpath($authorizationConfig['public_key'] ?? 'data/public.key');
        $privateKeyPassPhrase = $authorizationConfig['private_key_pass_phrase'] ?? null;

        if (!empty($privateKeyPassPhrase)) {
            $privateKey = new CryptKey($privateKey, $privateKeyPassPhrase);
        }

        $authorizationServer = new AuthorizationServer(
            $clientRepository,
            $accessTokenRepository,
            $scopeRepository,
            $privateKey,
            $publicKey
        );

        $passwordGrant = $container->get(PasswordGrant::class);

        $accessTokenTTLFormat = $authorizationConfig['access_token_ttl'] ?? 'PT1H';
        try {
            $accessTokenTTL = new \DateInterval($accessTokenTTLFormat);
        } catch (\Exception $exception) {
            throw new InvalidAccessTokenTTLException($accessTokenTTLFormat);
        }

        $authorizationServer->enableGrantType($passwordGrant, $accessTokenTTL);

        return $authorizationServer;
    }
}

<?php

namespace StarcodeTest\Staff\Service;

use Doctrine\ORM\EntityManager;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use Prophecy\Prophecy\ObjectProphecy;
use Starcode\Staff\Service\AuthorizationServerFactory;
use Starcode\Staff\Entity\AccessToken;
use Starcode\Staff\Entity\Client;
use Starcode\Staff\Entity\Scope;
use Starcode\Staff\Exception\InvalidAccessTokenTTLException;
use Starcode\Staff\Exception\InvalidConfigException;
use Starcode\Staff\Repository\AccessTokenRepository;
use Starcode\Staff\Repository\ClientRepository;
use Starcode\Staff\Repository\ScopeRepository;
use StarcodeTest\Staff\FactoryTestCase;

class AuthorizationServerFactoryTest extends FactoryTestCase
{
    public function testFactoryFailWhenAuthorizationConfigNotSet()
    {
        $this->setExpectedException(InvalidConfigException::class, 'Authorization config not set');

        $authorizationServerFactory = new AuthorizationServerFactory();

        $authorizationServerFactory($this->container->reveal(), AuthorizationServer::class);
    }

    public function testFactoryFailWhenAccessTokenTTLInvalidFormat()
    {
        $this->container->get('config')->willReturn([
            'authorization' => [
                'access_token_ttl' => 'BAD',
            ],
        ]);

        /** @var EntityManager|ObjectProphecy $entityManager */
        $entityManager = $this->prophesize(EntityManager::class);
        $entityManager->getRepository(Client::class)->willReturn($this->prophesize(ClientRepository::class)->reveal());
        $entityManager->getRepository(AccessToken::class)->willReturn($this->prophesize(AccessTokenRepository::class)->reveal());
        $entityManager->getRepository(Scope::class)->willReturn($this->prophesize(ScopeRepository::class)->reveal());

        $this->container->get(EntityManager::class)->willReturn($entityManager->reveal());

        $passwordGrant = $this->prophesize(PasswordGrant::class);
        $refreshTokenGrant = $this->prophesize(RefreshTokenGrant::class);
        $clientCredentialsGrant = $this->prophesize(ClientCredentialsGrant::class);

        $this->container->get(PasswordGrant::class)->willReturn($passwordGrant->reveal());
        $this->container->get(RefreshTokenGrant::class)->willReturn($refreshTokenGrant->reveal());
        $this->container->get(ClientCredentialsGrant::class)->willReturn($clientCredentialsGrant->reveal());

        $this->setExpectedException(InvalidAccessTokenTTLException::class, sprintf(InvalidAccessTokenTTLException::MESSAGE_PATTERN, 'BAD'));

        $authorizationServerFactory = new AuthorizationServerFactory();

        $authorizationServerFactory($this->container->reveal(), AuthorizationServer::class);
    }

    public function testFactorySuccessCreateAuthorizationService()
    {
        $this->container->get('config')->willReturn([
            'authorization' => [
                'access_token_ttl' => 'PT1H',
            ],
        ]);

        /** @var EntityManager|ObjectProphecy $entityManager */
        $entityManager = $this->prophesize(EntityManager::class);
        $entityManager->getRepository(Client::class)->willReturn($this->prophesize(ClientRepository::class)->reveal());
        $entityManager->getRepository(AccessToken::class)->willReturn($this->prophesize(AccessTokenRepository::class)->reveal());
        $entityManager->getRepository(Scope::class)->willReturn($this->prophesize(ScopeRepository::class)->reveal());

        $this->container->get(EntityManager::class)->willReturn($entityManager->reveal());

        $passwordGrant = $this->prophesize(PasswordGrant::class);
        $refreshTokenGrant = $this->prophesize(RefreshTokenGrant::class);
        $clientCredentialsGrant = $this->prophesize(ClientCredentialsGrant::class);

        $this->container->get(PasswordGrant::class)->willReturn($passwordGrant->reveal());
        $this->container->get(RefreshTokenGrant::class)->willReturn($refreshTokenGrant->reveal());
        $this->container->get(ClientCredentialsGrant::class)->willReturn($clientCredentialsGrant->reveal());

        $authorizationServerFactory = new AuthorizationServerFactory();

        /** @var AuthorizationServer $authorizationServer */
        $authorizationServer = $authorizationServerFactory($this->container->reveal(), AuthorizationServer::class);

        $this->assertInstanceOf(AuthorizationServer::class, $authorizationServer);
    }

    public function testServiceManagerReturnAuthorizationServer()
    {
        $authorizationServer = $this->getRealContainer()->get(AuthorizationServer::class);

        $this->assertInstanceOf(AuthorizationServer::class, $authorizationServer);
    }
}
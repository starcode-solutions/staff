<?php

namespace StarcodeTest\Staff\Authorization\Service;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\PasswordGrant;
use Prophecy\Prophecy\ObjectProphecy;
use Starcode\Staff\Authorization\Service\AuthorizationServerFactory;
use Starcode\Staff\Entity\AccessToken;
use Starcode\Staff\Entity\Client;
use Starcode\Staff\Entity\Scope;
use Starcode\Staff\Exception\InvalidAccessTokenTTLException;
use Starcode\Staff\Exception\InvalidConfigException;
use Starcode\Staff\Repository\AccessTokenRepository;
use Starcode\Staff\Repository\ClientRepository;
use Starcode\Staff\Repository\ScopeRepository;

class AuthorizationServerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    public function setUp()
    {
        parent::setUp();
        $this->container = $this->prophesize(ContainerInterface::class);
    }

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

        $this->container->get(PasswordGrant::class)->willReturn($passwordGrant->reveal());

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

        $this->container->get(PasswordGrant::class)->willReturn($passwordGrant->reveal());

        $authorizationServerFactory = new AuthorizationServerFactory();

        /** @var AuthorizationServer $authorizationServer */
        $authorizationServer = $authorizationServerFactory($this->container->reveal(), AuthorizationServer::class);

        $this->assertInstanceOf(AuthorizationServer::class, $authorizationServer);
    }

    public function testServiceManagerReturnAuthorizationServer()
    {
        /** @var ContainerInterface $container */
        $container = require(__DIR__ . '/../../../../../config/container.php');

        $authorizationServer = $container->get(AuthorizationServer::class);

        $this->assertInstanceOf(AuthorizationServer::class, $authorizationServer);
    }
}
<?php

namespace StarcodeTest\Staff\Service;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use League\OAuth2\Server\ResourceServer;
use Prophecy\Prophecy\ObjectProphecy;
use Starcode\Staff\Entity\AccessToken;
use Starcode\Staff\Exception\InvalidConfigException;
use Starcode\Staff\Repository\AccessTokenRepository;
use Starcode\Staff\Service\ResourceServerFactory;

class ResourceServerFactoryTest extends \PHPUnit_Framework_TestCase
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

        $resourceServerFactory = new ResourceServerFactory();

        $resourceServerFactory($this->container->reveal(), ResourceServer::class);
    }

    public function testFactorySuccessCreateResourceServer()
    {
        $this->container->get('config')->willReturn([
            'authorization' => [
                'publicKey' => 'mypublickey',
            ],
        ]);

        /** @var EntityManager|ObjectProphecy $entityManager */
        $entityManager = $this->prophesize(EntityManager::class);
        $entityManager->getRepository(AccessToken::class)->willReturn(
            $this->prophesize(AccessTokenRepository::class)->reveal()
        );
        $this->container->get(EntityManager::class)->willReturn($entityManager->reveal());

        $resourceServerFactory = new ResourceServerFactory();

        $resourceServer = $resourceServerFactory($this->container->reveal(), ResourceServer::class);

        $this->assertInstanceOf(ResourceServer::class, $resourceServer);
    }

    public function testServiceManagerReturnResourceServer()
    {
        /** @var ContainerInterface $container */
        $container = require(__DIR__ . '/../../../../config/container.php');

        $resourceServer = $container->get(ResourceServer::class);

        $this->assertInstanceOf(ResourceServer::class, $resourceServer);
    }
}
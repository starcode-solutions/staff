<?php

namespace StarcodeTest\Staff\Service;

use Interop\Container\ContainerInterface;
use League\OAuth2\Server\Middleware\ResourceServerMiddleware;
use League\OAuth2\Server\ResourceServer;
use Prophecy\Prophecy\ObjectProphecy;
use Starcode\Staff\Service\ResourceServerMiddlewareFactory;

class ResourceServerMiddlewareFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    public function setUp()
    {
        parent::setUp();
        $this->container = $this->prophesize(ContainerInterface::class);
    }

    public function testFactorySuccessCreateResourceServerMiddleware()
    {
        $this->container->get(ResourceServer::class)->willReturn(
            $this->prophesize(ResourceServer::class)->reveal()
        );

        $resourceServerMiddlewareFactory = new ResourceServerMiddlewareFactory();

        $resourceServerMiddleware = $resourceServerMiddlewareFactory($this->container->reveal(), ResourceServerMiddleware::class);

        $this->assertInstanceOf(ResourceServerMiddleware::class, $resourceServerMiddleware);
    }

    public function testServiceManagerReturnResourceServerMiddleware()
    {
        /** @var ContainerInterface $container */
        $container = require(__DIR__ . '/../../../../config/container.php');

        $resourceServerMiddleware = $container->get(ResourceServerMiddleware::class);

        $this->assertInstanceOf(ResourceServerMiddleware::class, $resourceServerMiddleware);
    }
}
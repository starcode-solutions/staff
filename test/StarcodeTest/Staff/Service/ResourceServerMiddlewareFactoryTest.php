<?php

namespace StarcodeTest\Staff\Service;

use League\OAuth2\Server\Middleware\ResourceServerMiddleware;
use League\OAuth2\Server\ResourceServer;
use Starcode\Staff\Service\ResourceServerMiddlewareFactory;
use StarcodeTest\Staff\FactoryTestCase;

class ResourceServerMiddlewareFactoryTest extends FactoryTestCase
{
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
        $resourceServerMiddleware = $this->getRealContainer()->get(ResourceServerMiddleware::class);

        $this->assertInstanceOf(ResourceServerMiddleware::class, $resourceServerMiddleware);
    }
}
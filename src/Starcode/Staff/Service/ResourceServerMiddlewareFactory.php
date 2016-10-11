<?php

namespace Starcode\Staff\Service;

use Interop\Container\ContainerInterface;
use League\OAuth2\Server\Middleware\ResourceServerMiddleware;
use League\OAuth2\Server\ResourceServer;
use Zend\ServiceManager\Factory\FactoryInterface;

class ResourceServerMiddlewareFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ResourceServerMiddleware(
            $container->get(ResourceServer::class)
        );
    }
}
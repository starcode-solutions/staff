<?php

namespace Starcode\Staff\Action\Auth;

use Interop\Container\ContainerInterface;
use League\OAuth2\Server\AuthorizationServer;
use Zend\ServiceManager\Factory\FactoryInterface;

class TokenActionFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $authorizationServer = $container->get(AuthorizationServer::class);

        return new TokenAction($authorizationServer);
    }
}
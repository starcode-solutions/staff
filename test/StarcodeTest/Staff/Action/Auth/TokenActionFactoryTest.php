<?php

namespace StarcodeTest\Staff\Action\Auth;

use League\OAuth2\Server\AuthorizationServer;
use Starcode\Staff\Action\Auth\TokenAction;
use Starcode\Staff\Action\Auth\TokenActionFactory;
use StarcodeTest\Staff\FactoryTestCase;

class TokenActionFactoryTest extends FactoryTestCase
{
    public function testFactorySuccessReturnTokenAction()
    {
        $authorizationServer = $this->prophesize(AuthorizationServer::class);

        $this->container->get(AuthorizationServer::class)->willReturn($authorizationServer->reveal());

        $tokenActionFactory = new TokenActionFactory();

        $tokenAction = $tokenActionFactory($this->container->reveal(), TokenAction::class);

        $this->assertInstanceOf(TokenAction::class, $tokenAction);
    }

    public function testServiceManagerReturnTokenAction()
    {
        $tokenAction = $this->getRealContainer()->get(TokenAction::class);

        $this->assertInstanceOf(TokenAction::class, $tokenAction);
    }
}
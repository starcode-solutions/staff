<?php

namespace StarcodeTest\Staff\Action\User;

use League\OAuth2\Server\AuthorizationServer;
use Starcode\Staff\Action\User\RegistrationAction;
use Starcode\Staff\Action\User\RegistrationActionFactory;
use StarcodeTest\Staff\FactoryTestCase;

class RegistrationActionFactoryTest extends FactoryTestCase
{
    public function testFactorySuccessReturnTokenAction()
    {
        $authorizationServer = $this->prophesize(AuthorizationServer::class);

        $this->container->get(AuthorizationServer::class)->willReturn($authorizationServer->reveal());

        $registrationActionFactory = new RegistrationActionFactory();

        $tokenAction = $registrationActionFactory($this->container->reveal(), RegistrationAction::class);

        $this->assertInstanceOf(RegistrationAction::class, $tokenAction);
    }

    public function testServiceManagerReturnTokenAction()
    {
        $tokenAction = $this->getRealContainer()->get(RegistrationAction::class);

        $this->assertInstanceOf(RegistrationAction::class, $tokenAction);
    }
}
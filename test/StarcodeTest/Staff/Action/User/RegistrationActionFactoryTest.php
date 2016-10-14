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
        $registrationActionFactory = new RegistrationActionFactory();

        $registrationAction = $registrationActionFactory($this->container->reveal(), RegistrationAction::class);

        $this->assertInstanceOf(RegistrationAction::class, $registrationAction);
    }

    public function testServiceManagerReturnTokenAction()
    {
        $tokenAction = $this->getRealContainer()->get(RegistrationAction::class);

        $this->assertInstanceOf(RegistrationAction::class, $tokenAction);
    }
}
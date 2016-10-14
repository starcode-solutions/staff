<?php

namespace StarcodeTest\Staff\Action\Client;

use Doctrine\ORM\EntityManager;
use Starcode\Staff\Action\Client\RegistrationAction;
use Starcode\Staff\Action\Client\RegistrationActionFactory;
use StarcodeTest\Staff\FactoryTestCase;

class RegistrationActionFactoryTest extends FactoryTestCase
{
    public function testFactorySuccessReturnTokenAction()
    {
        $entityManager = $this->prophesize(EntityManager::class);

        $this->container->get(EntityManager::class)->willReturn($entityManager->reveal());

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
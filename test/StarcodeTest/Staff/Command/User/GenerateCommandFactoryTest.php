<?php

namespace StarcodeTest\Staff\Command\User;

use Doctrine\ORM\EntityManager;
use Starcode\Staff\Command\User\GenerateCommand;
use Starcode\Staff\Command\User\GenerateCommandFactory;
use StarcodeTest\Staff\FactoryTestCase;

class GenerateCommandFactoryTest extends FactoryTestCase
{
    public function testFactorySuccessCreateGenerateCommand()
    {
        $entityManager = $this->prophesize(EntityManager::class);

        $this->container->get(EntityManager::class)->willReturn($entityManager->reveal());

        $generateCommandFactory = new GenerateCommandFactory();

        $generateCommand = $generateCommandFactory($this->container->reveal(), GenerateCommand::class);

        $this->assertInstanceOf(GenerateCommand::class, $generateCommand);
    }

    public function testServiceManagerReturnGenerateCommand()
    {
        $generateCommand = $this->getRealContainer()->get(GenerateCommand::class);

        $this->assertInstanceOf(GenerateCommand::class, $generateCommand);
    }
}

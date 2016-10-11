<?php

namespace StarcodeTest\Staff\Command\Client;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Prophecy\Prophecy\ObjectProphecy;
use Starcode\Staff\Command\Client\GenerateCommand;
use Starcode\Staff\Command\Client\GenerateCommandFactory;

class GenerateCommandFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    public function setUp()
    {
        parent::setUp();
        $this->container = $this->prophesize(ContainerInterface::class);
    }

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
        /** @var ContainerInterface $container */
        $container = require(__DIR__ . '/../../../../../config/container.php');

        $generateCommand = $container->get(GenerateCommand::class);

        $this->assertInstanceOf(GenerateCommand::class, $generateCommand);
    }
}

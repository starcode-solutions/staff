<?php

namespace StarcodeTest\Staff\Service;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Prophecy\Prophecy\ObjectProphecy;
use Starcode\Staff\Exception\InvalidConfigException;
use Starcode\Staff\Service\ConsoleApplicationFactory;
use StarcodeTest\Staff\FactoryTestCase;
use Symfony\Component\Console\Application;

class ConsoleApplicationFactoryTest extends FactoryTestCase
{
    public function testFactoryFailWhenConsoleConfigNotSet()
    {
        $this->setExpectedException(InvalidConfigException::class, 'Console config not set');

        $consoleApplicationFactory = new ConsoleApplicationFactory();

        $consoleApplicationFactory($this->container->reveal(), Application::class);
    }

    public function testFactorySuccessCreateConsoleApplication()
    {
        $this->container->get('config')->willReturn([
            'console' => [
                'name' => 'Test',
                'version' => '1.0.0',
            ],
        ]);

        /** @var EntityManager|ObjectProphecy $entityManager */
        $entityManager = $this->prophesize(EntityManager::class);
        $entityManager->getConnection()->willReturn($this->prophesize(Connection::class)->reveal());
        $this->container->get(EntityManager::class)->willReturn($entityManager->reveal());

        $consoleApplicationFactory = new ConsoleApplicationFactory();

        /** @var Application $consoleApplication */
        $consoleApplication = $consoleApplicationFactory($this->container->reveal(), Application::class);

        $this->assertInstanceOf(Application::class, $consoleApplication);
        $this->assertEquals('Test', $consoleApplication->getName());
        $this->assertEquals('1.0.0', $consoleApplication->getVersion());
    }

    public function testServiceManagerReturnConsoleApplication()
    {
        $consoleApplication = $this->getRealContainer()->get(Application::class);

        $this->assertInstanceOf(Application::class, $consoleApplication);
    }
}
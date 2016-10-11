<?php

namespace StarcodeTest\Staff\Command\Auth;

use Interop\Container\ContainerInterface;
use Prophecy\Prophecy\ObjectProphecy;
use Starcode\Staff\Command\Auth\GenerateKeysCommand;
use Starcode\Staff\Command\Auth\GenerateKeysCommandFactory;
use Starcode\Staff\Exception\InvalidConfigException;

class GenerateKeysCommandFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    public function setUp()
    {
        parent::setUp();
        $this->container = $this->prophesize(ContainerInterface::class);
    }

    public function testFactoryFailWhenAuthorizationConfigSectionNotSet()
    {
        $this->setExpectedException(InvalidConfigException::class, 'Authorization config not set');

        $generateKeysCommandFactory = new GenerateKeysCommandFactory();

        $generateKeysCommandFactory($this->container->reveal(), GenerateKeysCommand::class);
    }

    public function testFactorySuccessCreateGenerateKeysCommand()
    {
        $this->container->get('config')->willReturn([
            'authorization' => [
                'private_key' => 'data/private.key',
                'public_key' => 'data/public.key',
            ],
        ]);

        $generateKeysCommandFactory = new GenerateKeysCommandFactory();

        $generateKeysCommand = $generateKeysCommandFactory($this->container->reveal(), GenerateKeysCommand::class);

        $this->assertInstanceOf(GenerateKeysCommand::class, $generateKeysCommand);
    }

    public function testServiceManagerReturnGenerateKeysCommand()
    {
        /** @var ContainerInterface $container */
        $container = require(__DIR__ . '/../../../../../config/container.php');

        $generateKeysCommand = $container->get(GenerateKeysCommand::class);

        $this->assertInstanceOf(GenerateKeysCommand::class, $generateKeysCommand);
    }
}
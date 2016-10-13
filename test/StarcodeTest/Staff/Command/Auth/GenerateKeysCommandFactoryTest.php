<?php

namespace StarcodeTest\Staff\Command\Auth;

use Starcode\Staff\Command\Auth\GenerateKeysCommand;
use Starcode\Staff\Command\Auth\GenerateKeysCommandFactory;
use Starcode\Staff\Exception\InvalidConfigException;
use StarcodeTest\Staff\FactoryTestCase;

class GenerateKeysCommandFactoryTest extends FactoryTestCase
{
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
        $generateKeysCommand = $this->getRealContainer()->get(GenerateKeysCommand::class);

        $this->assertInstanceOf(GenerateKeysCommand::class, $generateKeysCommand);
    }
}
<?php

namespace StarcodeTest\Staff\Command\Scope;

use Doctrine\ORM\EntityManager;
use Starcode\Staff\Command\Scope\InitCommand;
use Starcode\Staff\Command\Scope\InitCommandFactory;
use Starcode\Staff\Exception\InvalidConfigException;
use StarcodeTest\Staff\FactoryTestCase;

class InitCommandFactoryTest extends FactoryTestCase
{
    public function testFactoryFailWhenScopesConfigNotSet()
    {
        $this->setExpectedException(InvalidConfigException::class, 'Scopes config not found');

        $initCommandFactory = new InitCommandFactory();

        $initCommandFactory($this->container->reveal(), InitCommand::class);
    }

    public function testFactorySuccessCreateInitCommand()
    {
        $this->container->get('config')->willReturn([
            'authorization' => [
                'scopes' => [
                    'scope:a' => 'A scope description',
                    'scope:b' => 'B scope description',
                ],
            ],
        ]);

        $this->container->get(EntityManager::class)->willReturn(
            $this->prophesize(EntityManager::class)->reveal()
        );

        $initCommandFactory = new InitCommandFactory();

        $initCommand = $initCommandFactory($this->container->reveal(), InitCommand::class);

        $this->assertInstanceOf(InitCommand::class, $initCommand);
    }

    public function testServiceManagerReturnInitCommand()
    {
        $initCommand = $this->getRealContainer()->get(InitCommand::class);

        $this->assertInstanceOf(InitCommand::class, $initCommand);
    }
}

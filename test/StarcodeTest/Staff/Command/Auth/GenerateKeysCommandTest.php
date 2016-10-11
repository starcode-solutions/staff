<?php

namespace StarcodeTest\Staff\Command\Auth;

use Prophecy\Prophecy\ObjectProphecy;
use Starcode\Staff\Command\Auth\GenerateKeysCommand;
use Starcode\Staff\Exception\ShellCommandNotFoundException;
use Starcode\Staff\Util\Console\ShellExecutor;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Output\Output;

class GenerateKeysCommandTest extends \PHPUnit_Framework_TestCase
{
    /** @var ShellExecutor|ObjectProphecy */
    private $shellExecutor;
    /** @var Input|ObjectProphecy */
    private $input;
    /** @var Output|ObjectProphecy */
    private $output;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();
        $this->shellExecutor = $this->prophesize(ShellExecutor::class);
        $this->input = $this->prophesize(Input::class);
        $this->output = $this->prophesize(Output::class);
    }

    public function testGenerateKeysCommandExeuteFailWhenOpensslCommandNotFound()
    {
        $this->shellExecutor->exist('openssl')->willReturn(false);

        $this->setExpectedException(ShellCommandNotFoundException::class,
            sprintf(ShellCommandNotFoundException::MESSAGE_PATTERN, 'openssl'));

        $this->invokeExecuteMethod();
    }

    public function testGenerateKeysCommandExecuteCreateKeys()
    {
        $privateKey = 'myprivatekey.key';
        $publicKey = 'mypublickey.key';
        $passPhrase = '';
        $numBits = 1024;

        $this->shellExecutor->exist('openssl')->willReturn(true);

        $this->shellExecutor->execute(sprintf(
            GenerateKeysCommand::SHELL_GENERATE_PRIVATE_KEY,
            $privateKey,
            $numBits
        ))->shouldBeCalled();

        $this->shellExecutor->execute(sprintf(
            GenerateKeysCommand::SHELL_GENERATE_PUBLIC_KEY,
            $privateKey,
            $publicKey
        ))->shouldBeCalled();

        $this->invokeExecuteMethod($privateKey, $publicKey, $passPhrase, $numBits);
    }

    public function testGenerateKeysCommandExecuteCreateKeysWithPassPhrase()
    {
        $privateKey = 'myprivatekey.key';
        $publicKey = 'mypublickey.key';
        $passPhrase = 'secret';
        $numBits = 1024;

        $this->shellExecutor->exist('openssl')->willReturn(true);

        $this->shellExecutor->execute(sprintf(
            GenerateKeysCommand::SHELL_GENERATE_PRIVATE_KEY_WITH_PASS_PHRASE,
            $passPhrase,
            $privateKey,
            $numBits
        ))->shouldBeCalled();

        $this->shellExecutor->execute(sprintf(
            GenerateKeysCommand::SHELL_GENERATE_PUBLIC_KEY_WITH_PASS_PHRASE,
            $privateKey,
            $passPhrase,
            $publicKey
        ))->shouldBeCalled();

        $this->invokeExecuteMethod($privateKey, $publicKey, $passPhrase, $numBits);
    }

    private function invokeExecuteMethod(
        $privateKey = 'data/private.key',
        $publicKey = 'data/public.key',
        $passPhrase = '',
        $numBits = 1024
    ) {
        $generateCommand = new \ReflectionClass(GenerateKeysCommand::class);

        $executeMethod = $generateCommand->getMethod('execute');
        $executeMethod->setAccessible(true);
        $executeMethod->invoke(
            new GenerateKeysCommand($this->shellExecutor->reveal(), $privateKey, $publicKey, $passPhrase, $numBits),
            $this->input->reveal(),
            $this->output->reveal()
        );
    }
}
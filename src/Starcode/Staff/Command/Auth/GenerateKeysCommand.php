<?php

namespace Starcode\Staff\Command\Auth;

use Starcode\Staff\Exception\ShellCommandNotFoundException;
use Starcode\Staff\Util\Console\ShellExecutor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateKeysCommand extends Command
{
    const SHELL_GENERATE_PRIVATE_KEY = 'openssl genrsa -out %s %s';
    const SHELL_GENERATE_PRIVATE_KEY_WITH_PASS_PHRASE = 'openssl genrsa -passout pass:%s -out %s %s';
    const SHELL_GENERATE_PUBLIC_KEY = 'openssl rsa -in %s -pubout -out %s';
    const SHELL_GENERATE_PUBLIC_KEY_WITH_PASS_PHRASE = 'openssl rsa -in %s -passin pass:%s -pubout -out %s';

    /** @var ShellExecutor */
    private $shellExecutor;
    private $privateKey;
    private $publicKey;
    private $privateKeyPassPhrase;
    private $numberBits;

    /**
     * GenerateKeysCommand constructor.
     * @param ShellExecutor $shellExecutor
     * @param $privateKey
     * @param $publicKey
     * @param $privateKeyPassPhrase
     * @param $numberBits
     */
    public function __construct(
        ShellExecutor $shellExecutor,
        $privateKey,
        $publicKey,
        $privateKeyPassPhrase,
        $numberBits
    ) {
        parent::__construct();
        $this->shellExecutor = $shellExecutor;
        $this->privateKey = $privateKey;
        $this->publicKey = $publicKey;
        $this->privateKeyPassPhrase = $privateKeyPassPhrase;
        $this->numberBits = $numberBits;
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('auth:generate-keys')
            ->setDescription('Generate public and private keys');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->shellExecutor->exist('openssl')) {
            throw new ShellCommandNotFoundException('openssl');
        }

        if (!empty($this->privateKeyPassPhrase)) {
            $output->write('Generate private key ... ');
            $this->shellExecutor->execute(sprintf(
                self::SHELL_GENERATE_PRIVATE_KEY_WITH_PASS_PHRASE,
                $this->privateKeyPassPhrase,
                $this->privateKey,
                $this->numberBits
            ));
            $output->writeln('OK');

            $output->write('Generate public key ... ');
            $this->shellExecutor->execute(sprintf(
                self::SHELL_GENERATE_PUBLIC_KEY_WITH_PASS_PHRASE,
                $this->privateKey,
                $this->privateKeyPassPhrase,
                $this->publicKey
            ));
            $output->writeln('OK');
        } else {
            $output->write('Generate private key (with pass phrase) ... ');
            $this->shellExecutor->execute(sprintf(
                self::SHELL_GENERATE_PRIVATE_KEY,
                $this->privateKey,
                $this->numberBits
            ));
            $output->writeln('OK');

            $output->write('Generate public key ... ');
            $this->shellExecutor->execute(sprintf(
                self::SHELL_GENERATE_PUBLIC_KEY,
                $this->privateKey,
                $this->publicKey
            ));
            $output->writeln('OK');
        }
    }
}
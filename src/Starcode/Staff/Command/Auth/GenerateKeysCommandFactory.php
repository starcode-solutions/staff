<?php

namespace Starcode\Staff\Command\Auth;

use Interop\Container\ContainerInterface;
use Starcode\Staff\Exception\InvalidConfigException;
use Starcode\Staff\Util\Console\ShellExecutor;
use Zend\ServiceManager\Factory\FactoryInterface;

class GenerateKeysCommandFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');

        $authorizationConfig = $config['authorization'] ?? null;
        if (!$authorizationConfig) {
            throw new InvalidConfigException('Authorization config not set');
        }

        $privateKey = $authorizationConfig['private_key'] ?? 'data/public.key';
        $publicKey = $authorizationConfig['public_key'] ?? 'data/public.key';
        $privateKeyPassPhrase = $authorizationConfig['private_key_pass_phrase'] ?? null;
        $numberBits = $authorizationConfig['number_bits'] ?? 1024;

        $shellExecutor = new ShellExecutor();

        return new GenerateKeysCommand($shellExecutor, $privateKey, $publicKey, $privateKeyPassPhrase, $numberBits);
    }
}
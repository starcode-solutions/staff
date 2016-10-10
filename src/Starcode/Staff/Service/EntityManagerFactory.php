<?php

namespace Starcode\Staff\Service;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Interop\Container\ContainerInterface;
use Starcode\Staff\Exception\InvalidConfigException;
use Zend\ServiceManager\Factory\FactoryInterface;

class EntityManagerFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');

        $doctrineConfig = $config['doctrine'] ?? null;
        if (!$doctrineConfig) {
            throw new InvalidConfigException('Doctrine config not found');
        }

        $paths = $doctrineConfig['entities_paths'] ?? null;
        if (!$paths) {
            throw new InvalidConfigException('Entities paths not found in doctrine configuration');
        }

        $conn = $doctrineConfig['connection'] ?? null;
        if (!$conn) {
            throw new InvalidConfigException('Database connection not found in doctrine configuration');
        }

        $isDevMode = $doctrineConfig['is_dev_mode'] ?? false;
        $proxyDir = $doctrineConfig['proxy_dir'] ?? null;
        $cache = new ArrayCache();

        return EntityManager::create(
            $conn,
            Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, $proxyDir, $cache, false)
        );
    }
}
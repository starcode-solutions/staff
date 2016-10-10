<?php

namespace StarcodeTest\Staff\Service;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Prophecy\Prophecy\ObjectProphecy;
use Starcode\Staff\Exception\InvalidConfigException;
use Starcode\Staff\Service\EntityManagerFactory;

class EntityManagerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();
        $this->container = $this->prophesize(ContainerInterface::class);
    }

    public function testFactoryFailWhenDoctrineConfigNotSet()
    {
        $this->setExpectedException(InvalidConfigException::class, 'Doctrine config not found');

        $entityManagerFactory = new EntityManagerFactory();

        $entityManagerFactory($this->container->reveal(), EntityManager::class);
    }

    public function testFactoryFailWhenEntitiesPathsNotSet()
    {
        $this->setExpectedException(InvalidConfigException::class, 'Entities paths not found in doctrine configuration');

        $this->container->get('config')->willReturn([
            'doctrine' => [
                'is_dev_mode' => true,
            ],
        ]);

        $entityManagerFactory = new EntityManagerFactory();

        $entityManagerFactory($this->container->reveal(), EntityManager::class);
    }

    public function testFactoryFailWhenConnectionNotSet()
    {
        $this->setExpectedException(InvalidConfigException::class, 'Database connection not found in doctrine configuration');

        $this->container->get('config')->willReturn([
            'doctrine' => [
                'entities_paths' => [
                    __DIR__ . '/../../../../src/Starcode/Staff/Entity',
                ],
            ],
        ]);

        $entityManagerFactory = new EntityManagerFactory();

        $entityManagerFactory($this->container->reveal(), EntityManager::class);
    }

    public function testFactorySuccessCreateEntityManager()
    {
        $this->container->get('config')->willReturn([
            'doctrine' => [
                'entities_paths' => [
                    __DIR__ . '/../../../../src/Starcode/Staff/Entity',
                ],
                'connection' => [
                    'driver' => 'pdo_sqlite',
                    'path' => __DIR__ . '/../../../../data/test-db.sqlite',
                ],
            ],
        ]);

        $entityManagerFactory = new EntityManagerFactory();

        /** @var EntityManager $entityManager */
        $entityManager = $entityManagerFactory($this->container->reveal(), EntityManager::class);

        $this->assertInstanceOf(EntityManager::class, $entityManager);
    }

    public function testServiceManagerReturnEntityManager()
    {
        /** @var ContainerInterface $container */
        $container = require(__DIR__ . '/../../../../config/container.php');

        $entityManager = $container->get(EntityManager::class);

        $this->assertInstanceOf(EntityManager::class, $entityManager);
    }
}
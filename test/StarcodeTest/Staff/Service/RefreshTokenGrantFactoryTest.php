<?php

namespace StarcodeTest\Staff\Service;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use Prophecy\Prophecy\ObjectProphecy;
use Starcode\Staff\Entity\RefreshToken;
use Starcode\Staff\Exception\InvalidConfigException;
use Starcode\Staff\Exception\InvalidRefreshTokenTTLException;
use Starcode\Staff\Repository\RefreshTokenRepository;
use Starcode\Staff\Service\RefreshTokenGrantFactory;

class RefreshTokenGrantFactoryTest extends \PHPUnit_Framework_TestCase
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

    public function testFactoryFailWhenAuthorizationConfigNotSet()
    {
        $this->setExpectedException(InvalidConfigException::class, 'Authorization config not set');

        $passwordGrantFactory = new RefreshTokenGrantFactory();

        $passwordGrantFactory($this->container->reveal(), RefreshTokenGrant::class);
    }

    public function testFactoryFailWhenRefreshTokenTTLInvalid()
    {
        $this->container->get('config')->willReturn([
            'authorization' => [
                'refresh_token_ttl' => 'BAD_FORMAT',
            ],
        ]);

        /** @var EntityManager|ObjectProphecy $entityManager */
        $entityManager = $this->prophesize(EntityManager::class);
        $entityManager->getRepository(RefreshToken::class)->willReturn($this->prophesize(RefreshTokenRepository::class)->reveal());

        $this->container->get(EntityManager::class)->willReturn($entityManager->reveal());

        $this->setExpectedException(InvalidRefreshTokenTTLException::class, sprintf(InvalidRefreshTokenTTLException::MESSAGE_PATTERN, 'BAD_FORMAT'));

        $passwordGrantFactory = new RefreshTokenGrantFactory();

        $passwordGrantFactory($this->container->reveal(), RefreshTokenGrant::class);
    }

    public function testFactorySuccessCreatePasswordGrant()
    {
        $this->container->get('config')->willReturn([
            'authorization' => [
                'refresh_token_ttl' => 'P1M',
            ],
        ]);

        $refreshTokenRepository = $this->prophesize(RefreshTokenRepository::class);

        /** @var EntityManager|ObjectProphecy $entityManager */
        $entityManager = $this->prophesize(EntityManager::class);
        $entityManager->getRepository(RefreshToken::class)->willReturn($refreshTokenRepository->reveal());

        $this->container->get(EntityManager::class)->willReturn($entityManager->reveal());

        $passwordGrantFactory = new RefreshTokenGrantFactory();

        /** @var RefreshTokenGrant $passwordGrant */
        $passwordGrant = $passwordGrantFactory($this->container->reveal(), RefreshTokenGrant::class);

        $this->assertInstanceOf(RefreshTokenGrant::class, $passwordGrant);
    }

    public function testServiceManagerReturnPasswordGrant()
    {
        /** @var ContainerInterface $container */
        $container = require(__DIR__ . '/../../../../config/container.php');

        $passwordGrant = $container->get(RefreshTokenGrant::class);

        $this->assertInstanceOf(RefreshTokenGrant::class, $passwordGrant);
    }
}
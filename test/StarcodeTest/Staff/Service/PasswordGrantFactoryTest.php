<?php

namespace StarcodeTest\Staff\Service;

use Doctrine\ORM\EntityManager;
use League\OAuth2\Server\Grant\PasswordGrant;
use Prophecy\Prophecy\ObjectProphecy;
use Starcode\Staff\Service\PasswordGrantFactory;
use Starcode\Staff\Entity\RefreshToken;
use Starcode\Staff\Entity\User;
use Starcode\Staff\Exception\InvalidConfigException;
use Starcode\Staff\Exception\InvalidRefreshTokenTTLException;
use Starcode\Staff\Repository\RefreshTokenRepository;
use Starcode\Staff\Repository\UserRepository;
use StarcodeTest\Staff\FactoryTestCase;

class PasswordGrantFactoryTest extends FactoryTestCase
{
    public function testFactoryFailWhenAuthorizationConfigNotSet()
    {
        $this->setExpectedException(InvalidConfigException::class, 'Authorization config not set');

        $passwordGrantFactory = new PasswordGrantFactory();

        $passwordGrantFactory($this->container->reveal(), PasswordGrant::class);
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
        $entityManager->getRepository(User::class)->willReturn($this->prophesize(UserRepository::class)->reveal());
        $entityManager->getRepository(RefreshToken::class)->willReturn($this->prophesize(RefreshTokenRepository::class)->reveal());

        $this->container->get(EntityManager::class)->willReturn($entityManager->reveal());

        $this->setExpectedException(InvalidRefreshTokenTTLException::class, sprintf(InvalidRefreshTokenTTLException::MESSAGE_PATTERN, 'BAD_FORMAT'));

        $passwordGrantFactory = new PasswordGrantFactory();

        $passwordGrantFactory($this->container->reveal(), PasswordGrant::class);
    }

    public function testFactoryySuccessCreatePasswordGrant()
    {
        $this->container->get('config')->willReturn([
            'authorization' => [
                'refresh_token_ttl' => 'P1M',
            ],
        ]);

        $userRepository = $this->prophesize(UserRepository::class);
        $refreshTokenRepository = $this->prophesize(RefreshTokenRepository::class);

        /** @var EntityManager|ObjectProphecy $entityManager */
        $entityManager = $this->prophesize(EntityManager::class);
        $entityManager->getRepository(User::class)->willReturn($userRepository->reveal());
        $entityManager->getRepository(RefreshToken::class)->willReturn($refreshTokenRepository->reveal());

        $this->container->get(EntityManager::class)->willReturn($entityManager->reveal());

        $passwordGrantFactory = new PasswordGrantFactory();

        /** @var PasswordGrant $passwordGrant */
        $passwordGrant = $passwordGrantFactory($this->container->reveal(), PasswordGrant::class);

        $this->assertInstanceOf(PasswordGrant::class, $passwordGrant);
    }

    public function testServiceManagerReturnPasswordGrant()
    {
        $passwordGrant = $this->getRealContainer()->get(PasswordGrant::class);

        $this->assertInstanceOf(PasswordGrant::class, $passwordGrant);
    }
}
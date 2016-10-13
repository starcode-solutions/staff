<?php

namespace StarcodeTest\Staff\Action\Auth;

use Doctrine\ORM\EntityManager;
use Prophecy\Prophecy\ObjectProphecy;
use Starcode\Staff\Action\Auth\InfoAction;
use Starcode\Staff\Action\Auth\InfoActionFactory;
use Starcode\Staff\Entity\User;
use Starcode\Staff\Repository\UserRepository;
use StarcodeTest\Staff\FactoryTestCase;

class InfoActionFactoryTest extends FactoryTestCase
{
    public function testFactorySuccessReturnTokenAction()
    {
        $userRepository = $this->prophesize(UserRepository::class);

        /** @var EntityManager|ObjectProphecy $entityManager */
        $entityManager = $this->prophesize(EntityManager::class);
        $entityManager->getRepository(User::class)->willReturn($userRepository->reveal());

        $this->container->get(EntityManager::class)->willReturn($entityManager->reveal());

        $infoActionFactory = new InfoActionFactory();

        $infoAction = $infoActionFactory($this->container->reveal(), InfoAction::class);

        $this->assertInstanceOf(InfoAction::class, $infoAction);
    }

    public function testServiceManagerReturnTokenAction()
    {
        $infoAction = $this->getRealContainer()->get(InfoAction::class);

        $this->assertInstanceOf(InfoAction::class, $infoAction);
    }
}
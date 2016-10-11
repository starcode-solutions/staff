<?php

namespace StarcodeTest\Staff\Action\Auth;

use Interop\Container\ContainerInterface;
use League\OAuth2\Server\AuthorizationServer;
use Prophecy\Prophecy\ObjectProphecy;
use Starcode\Staff\Action\Auth\TokenAction;
use Starcode\Staff\Action\Auth\TokenActionFactory;

class TokenActionFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    public function setUp()
    {
        parent::setUp();
        $this->container = $this->prophesize(ContainerInterface::class);
    }

    public function testFactorySuccessReturnTokenAction()
    {
        $authorizationServer = $this->prophesize(AuthorizationServer::class);

        $this->container->get(AuthorizationServer::class)->willReturn($authorizationServer->reveal());

        $tokenActionFactory = new TokenActionFactory();

        $tokenAction = $tokenActionFactory($this->container->reveal(), TokenAction::class);

        $this->assertInstanceOf(TokenAction::class, $tokenAction);
    }

    public function testServiceManagerReturnTokenAction()
    {
        /** @var ContainerInterface $container */
        $container = require(__DIR__ . '/../../../../../config/container.php');

        $tokenAction = $container->get(TokenAction::class);

        $this->assertInstanceOf(TokenAction::class, $tokenAction);
    }
}
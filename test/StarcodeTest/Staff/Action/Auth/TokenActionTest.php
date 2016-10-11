<?php

namespace StarcodeTest\Staff\Action\Auth;

use League\OAuth2\Server\AuthorizationServer;
use Prophecy\Prophecy\ObjectProphecy;

class TokenActionTest extends \PHPUnit_Framework_TestCase 
{
    /** @var AuthorizationServer|ObjectProphecy */
    private $authorizationServer;

    public function setUp()
    {
        parent::setUp();
        $this->authorizationServer = $this->prophesize(AuthorizationServer::class);
    }

    public function test()
    {
        
    }
}
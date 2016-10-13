<?php

namespace StarcodeTest\Staff;

use Interop\Container\ContainerInterface;
use Prophecy\Prophecy\ObjectProphecy;

class FactoryTestCase extends \PHPUnit_Framework_TestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    protected $container;

    public function setUp()
    {
        parent::setUp();
        $this->container = $this->prophesize(ContainerInterface::class);
    }

    /**
     * @return ContainerInterface
     */
    protected function getRealContainer()
    {
        return require(__DIR__ . '/../../../config/container.php');
    }
}
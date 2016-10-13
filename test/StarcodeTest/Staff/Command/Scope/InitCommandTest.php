<?php

namespace StarcodeTest\Staff\Command\Scope;

use Doctrine\ORM\EntityManager;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Starcode\Staff\Command\Scope\InitCommand;
use Starcode\Staff\Entity\Scope;
use Starcode\Staff\Repository\ScopeRepository;
use Starcode\Staff\Util\Console\ProgressBarBuilder;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Output\Output;

class InitCommandTest extends \PHPUnit_Framework_TestCase
{
    /** @var Input|ObjectProphecy */
    private $input;
    /** @var Output|ObjectProphecy */
    private $output;
    /** @var EntityManager|ObjectProphecy */
    private $entityManager;
    /** @var ProgressBarBuilder|ObjectProphecy */
    private $progressBarBuilder;
    /** @var ProgressBar|ObjectProphecy */
    private $progressBar;
    /** @var ScopeRepository|ObjectProphecy */
    private $scopeRepository;

    protected function setUp()
    {
        parent::setUp();

        $this->input = $this->prophesize(Input::class);
        $this->output = $this->prophesize(Output::class);
        $this->entityManager = $this->prophesize(EntityManager::class);
        $this->progressBarBuilder = $this->prophesize(ProgressBarBuilder::class);
        $this->progressBar = $this->prophesize(ProgressBar::class);
        $this->scopeRepository = $this->prophesize(ScopeRepository::class);

        $this->entityManager->getRepository(Scope::class)->willReturn($this->scopeRepository->reveal());

        $this->progressBarBuilder->build(Argument::type(Output::class), Argument::any())->willReturn($this->progressBar->reveal());
    }

    public function testExecuteCommandRunTruncateAllScopes()
    {
        $this->scopeRepository->truncate()->shouldBeCalled();

        $this->invokeExecuteMethod([]);
    }

    public function testScopeEntitiesPersistAndFlush()
    {
        $testCase = $this;

        $name = 'scope:a';
        $description = 'Description of a';

        $this->entityManager
            ->persist(Argument::type(Scope::class))
            ->will(function($args) use ($testCase, $name, $description) {
                /** @var Scope $scope */
                $scope = $args[0];

                $testCase->assertInstanceOf(Scope::class, $scope);
                $testCase->assertEquals($name, $scope->getName());
                $testCase->assertEquals($description, $scope->getDescription());
            });
        $this->entityManager->flush()->shouldBeCalled();

        $this->invokeExecuteMethod([$name => $description]);
    }

    public function testExecuteWithEmptyScopesConfig()
    {
        $this->invokeExecuteMethod([]);
    }

    private function invokeExecuteMethod(array $scopesConfig)
    {
        $generateCommand = new \ReflectionClass(InitCommand::class);

        $executeMethod = $generateCommand->getMethod('execute');
        $executeMethod->setAccessible(true);
        $executeMethod->invoke(
            new InitCommand($this->entityManager->reveal(), $scopesConfig, $this->progressBarBuilder->reveal()),
            $this->input->reveal(),
            $this->output->reveal()
        );
    }
}

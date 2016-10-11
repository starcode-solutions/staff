<?php

namespace StarcodeTest\Staff\Command\User;

use Doctrine\ORM\EntityManager;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Starcode\Staff\Command\User\GenerateCommand;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Output\Output;

class GenerateCommandTest extends \PHPUnit_Framework_TestCase 
{
    /** @var EntityManager|ObjectProphecy */
    protected $entityManager;
    /** @var Input|ObjectProphecy */
    protected $input;
    /** @var Output|ObjectProphecy */
    protected $output;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();
        $this->entityManager = $this->prophesize(EntityManager::class);
        $this->input = $this->prophesize(Input::class);
        $this->output = $this->prophesize(Output::class);
    }

    public function testRunCommandSuccessGenerateEntities()
    {
        $this->input->bind(Argument::any())->shouldBeCalled();
        $this->input->isInteractive()->willReturn(false);
        $this->input->hasArgument('command')->willReturn(false);
        $this->input->validate()->shouldBeCalled();

        $this->input->getOption(GenerateCommand::OPTION_COUNT)->willReturn(100);
        $this->input->getOption(GenerateCommand::OPTION_CLEAR)->willReturn(false);

        $this->output->writeln('Start generate users')->shouldBeCalled();

        $generateCommand = new GenerateCommand($this->entityManager->reveal());

        $generateCommand->run($this->input->reveal(), $this->output->reveal());
    }
}
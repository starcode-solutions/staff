<?php

namespace StarcodeTest\Staff\Command\Client;

use Doctrine\ORM\EntityManager;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Starcode\Staff\Command\Client\GenerateCommand;
use Starcode\Staff\Entity\Client;
use Starcode\Staff\Repository\ClientRepository;
use Starcode\Staff\Util\Console\ProgressBarBuilder;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Output\Output;

class GenerateCommandTest extends \PHPUnit_Framework_TestCase 
{
    /** @var EntityManager|ObjectProphecy */
    protected $entityManager;
    /** @var ProgressBarBuilder|ObjectProphecy */
    protected $progressBarBuilder;
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
        $this->progressBarBuilder = $this->prophesize(ProgressBarBuilder::class);
        $this->input = $this->prophesize(Input::class);
        $this->output = $this->prophesize(Output::class);

        $progress = $this->prophesize(ProgressBar::class);

        $this->progressBarBuilder->build($this->output->reveal(), Argument::any())->willReturn($progress->reveal());
    }

    public function testRunCommandSuccessGenerateEntities()
    {
        $count = 10;

        $this->input->getOption(GenerateCommand::OPTION_COUNT)->willReturn($count);
        $this->input->getOption(GenerateCommand::OPTION_CLEAR)->willReturn(false);

        for ($i = 0; $i < $count; $i++) {
            $this->entityManager->persist(Argument::any())->shouldBeCalled();
            $this->entityManager->flush()->shouldBeCalled();
        }

        $this->invokeExecuteMethod();
    }

    public function testRunCommandClearUserTable()
    {
        $this->input->getOption(GenerateCommand::OPTION_COUNT)->willReturn(0);
        $this->input->getOption(GenerateCommand::OPTION_CLEAR)->willReturn(true);

        $userRepository = $this->prophesize(ClientRepository::class);
        $userRepository->truncate()->shouldBeCalled();

        $this->entityManager->getRepository(Client::class)->willReturn($userRepository->reveal());

        $this->invokeExecuteMethod();
    }

    private function invokeExecuteMethod()
    {
        $generateCommand = new \ReflectionClass(GenerateCommand::class);

        $executeMethod = $generateCommand->getMethod('execute');
        $executeMethod->setAccessible(true);
        $executeMethod->invoke(
            new GenerateCommand($this->entityManager->reveal(), $this->progressBarBuilder->reveal()),
            $this->input->reveal(),
            $this->output->reveal()
        );
    }
}
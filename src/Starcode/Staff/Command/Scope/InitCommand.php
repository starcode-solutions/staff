<?php

namespace Starcode\Staff\Command\Scope;

use Doctrine\ORM\EntityManager;
use Starcode\Staff\Entity\Scope;
use Starcode\Staff\Repository\ScopeRepository;
use Starcode\Staff\Util\Console\ProgressBarBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitCommand extends Command
{
    /** @var EntityManager */
    private $entityManager;

    /** @var array */
    private $scopesConfig;

    /** @var ProgressBarBuilder */
    private $progressBarBuilder;

    /**
     * InitCommand constructor.
     * @param EntityManager $entityManager
     * @param array $scopesConfig
     * @param ProgressBarBuilder $progressBarBuilder
     */
    public function __construct(EntityManager $entityManager, array $scopesConfig, ProgressBarBuilder $progressBarBuilder)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->scopesConfig = $scopesConfig;
        $this->progressBarBuilder = $progressBarBuilder;
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('scope:init')
            ->setDescription('Initialize scope data');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var ScopeRepository $scopeRepository */
        $scopeRepository = $this->entityManager->getRepository(Scope::class);
        $scopeRepository->truncate();

        $output->writeln("Initialize scopes");

        $progressBar = $this->progressBarBuilder->build($output, count($this->scopesConfig));
        $progressBar->start();

        foreach ($this->scopesConfig as $name => $description) {
            $scope = new Scope();
            $scope->setName($name);
            $scope->setDescription($description);

            $this->entityManager->persist($scope);
            $this->entityManager->flush();

            $progressBar->advance();
        }

        $progressBar->finish();

        $output->writeln("\nScope initialized successful!");
    }
}
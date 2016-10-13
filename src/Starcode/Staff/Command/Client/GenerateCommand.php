<?php

namespace Starcode\Staff\Command\Client;

use Doctrine\ORM\EntityManager;
use Faker\Factory;
use Starcode\Staff\Entity\Client;
use Starcode\Staff\Util\Console\ProgressBarBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends Command
{
    const OPTION_COUNT = 'count';
    const OPTION_CLEAR = 'clear';

    /** @var EntityManager */
    private $entityManager;

    /** @var ProgressBarBuilder */
    private $progressBarBuilder;

    /**
     * GenerateCommand constructor.
     * @param EntityManager $entityManager
     * @param ProgressBarBuilder $progressBarBuilder
     */
    public function __construct(EntityManager $entityManager, ProgressBarBuilder $progressBarBuilder)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->progressBarBuilder = $progressBarBuilder;
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('client:generate')
            ->setDescription('Generate clients')
            ->addOption(
                self::OPTION_COUNT,
                null,
                InputOption::VALUE_OPTIONAL,
                'Count of generated Clients',
                100
            )
            ->addOption(
                self::OPTION_CLEAR,
                null,
                InputOption::VALUE_OPTIONAL,
                'Clear clients table before generate?',
                true
            );
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $count = $input->getOption(self::OPTION_COUNT);
        $clear = $input->getOption(self::OPTION_CLEAR);

        if ($clear) {
            $output->write('Clear clients... ');
            $this->entityManager->getRepository(Client::class)->truncate();
            $output->writeln('OK');
        }

        $faker = Factory::create();

        $output->writeln('Start generate clients');

        $progress = $this->progressBarBuilder->build($output, $count);
        $progress->start();

        $grantTypes = Client::GRANT_TYPES;

        for ($i = 0; $i < $count; $i++) {
            $client = new Client();
            $client->setIdentifier($faker->companyEmail);
            $client->setName($faker->company);
            $client->setSecret(md5($client->getIdentifier()));
            $client->setRedirectUri($faker->url);
            $client->setGrantTypes($grantTypes);

            $this->entityManager->persist($client);
            $this->entityManager->flush();

            $progress->advance();
        }

        $progress->finish();
    }
}
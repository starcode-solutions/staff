<?php

namespace Starcode\Staff\Command\User;

use Doctrine\ORM\EntityManager;
use Faker\Factory;
use Starcode\Staff\Entity\User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends Command
{
    const OPTION_COUNT = 'count';
    const OPTION_CLEAR = 'clear';

    /** @var EntityManager */
    private $entityManager;

    /**
     * GenerateCommand constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('user:generate')
            ->setDescription('Generate users')
            ->addOption(
                self::OPTION_COUNT,
                null,
                InputOption::VALUE_OPTIONAL,
                'Count of generated Users',
                100
            )
            ->addOption(
                self::OPTION_CLEAR,
                null,
                InputOption::VALUE_OPTIONAL,
                'Clear users table before generate?',
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
            $output->write('Clear users... ');
            $this->clearUsers();
            $output->writeln('OK');
        }

        $faker = Factory::create();

        $output->writeln('Start generate users');

        $progress = new ProgressBar($output, $count);
        $progress->start();

        for ($i = 0; $i < $count; $i++) {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setPassword(md5($user->getEmail()));
            $user->setForename($faker->firstName);
            $user->setSurname($faker->lastName);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $progress->advance();
        }

        $progress->finish();
    }

    /**
     * Clear users table
     */
    private function clearUsers()
    {
        $userMetadata = $this->entityManager->getClassMetadata(User::class);
        $connection = $this->entityManager->getConnection();
        $connection->query('TRUNCATE ' . $userMetadata->getTableName());
    }
}
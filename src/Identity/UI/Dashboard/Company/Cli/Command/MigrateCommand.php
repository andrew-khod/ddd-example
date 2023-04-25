<?php

declare(strict_types=1);

namespace App\Identity\UI\Dashboard\Company\Cli\Command;

use App\Shared\Infrastructure\Doctrine\CompanyMigrationInterface;
use App\Shared\Infrastructure\Doctrine\DefaultMigrationInterface;
use App\Shared\Infrastructure\Doctrine\Query\BaseQuery;
use Doctrine\Bundle\DoctrineBundle\Command\CreateDatabaseDoctrineCommand;
use Doctrine\DBAL\Exception\ConnectionException;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\ExistingConfiguration;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Metadata\MigrationPlan;
use Doctrine\Migrations\Tools\Console\Command\DoctrineCommand;
use Doctrine\Migrations\Tools\Console\Command\ExecuteCommand;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class MigrateCommand extends DoctrineCommand
{
    protected static $defaultName = 'app:migrate';

    protected const LATEST = 'latest';
    protected const CURRENT = 'current';

    private ManagerRegistry $managerRegistry;
    private DependencyFactory $dependencyFactory;

    public function __construct(ManagerRegistry $managerRegistry,
                                DependencyFactory $dependencyFactory,
                                ?string $name = null)
    {
        parent::__construct($dependencyFactory, $name);

        $this->managerRegistry = $managerRegistry;
        $this->dependencyFactory = $dependencyFactory;
    }

    protected function configure(): void
    {
        // TODO current implementation drops last migration for all DB's, not actual LAST RUN migrations!
        $this
//            ->setDescription('Tenant aware migrator')
//            ->addOption(
//                'up',
//                null,
//                InputOption::VALUE_NONE,
//                'Execute the migration up.'
//            )
            ->addOption(
                'down',
                null,
                InputOption::VALUE_NONE,
                'Execute the migration down.'
            )
        ;

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
//        $direction = '--up';
        $direction = $input->getOption('down') !== false
            ? '--down'
            : '--up';
//        $direction = $input->getOption('down') !== false
//            ? Direction::DOWN
//            : Direction::UP;

        array_map(function (EntityManagerInterface $manager) use ($direction, $input, $output, $io) {
            // TODO find the way to get a EM/connection name instead of using dbname
            $dbName = $manager->getConnection()->getDatabase();
            $isDefaultDb = BaseQuery::ALIAS === strtolower($dbName);

            $dependencyFactory = DependencyFactory::fromEntityManager(
                new ExistingConfiguration($this->dependencyFactory->getConfiguration()),
                new ExistingEntityManager($manager)
            );
            $aliasResolver = $dependencyFactory
                ->getVersionAliasResolver();

            $io->info('Checking migrations list');

            try {
                $latestVersion = $aliasResolver->resolveVersionAlias(self::LATEST);
            } catch (ConnectionException $exception) {
                if (1049 === $exception->getErrorCode()) {
                    $createDb = new CreateDatabaseDoctrineCommand($this->managerRegistry);
                    $createDb->run(new ArrayInput([
                        '--connection' => $dbName,
                    ]), $output);
                    $latestVersion = $aliasResolver->resolveVersionAlias(self::LATEST);
                }
            }

            $plan = $dependencyFactory
                ->getMigrationPlanCalculator()
                ->getPlanUntilVersion($latestVersion);

            $instance = $isDefaultDb
                ? DefaultMigrationInterface::class
                : CompanyMigrationInterface::class;

            $versions = array_map(
                fn (MigrationPlan $migrationPlan) => (string) $migrationPlan->getVersion(),
                array_filter(
                    $plan->getItems(),
                    fn (MigrationPlan $migrationPlan) =>
                        $migrationPlan->getMigration() instanceof $instance
                )
            );

            // TODO find the way to use MigrateCommand on specific migrations repository
//            $migrateCommand = new OriginalMigrateCommand($dependencyFactory);
            $migrateCommand = new ExecuteCommand($dependencyFactory);

            $io->info(sprintf('Running migration for %s', $dbName));

            $currentVersion = $aliasResolver->resolveVersionAlias(self::CURRENT);
            $isDown = $direction === '--down';
            $input = new ArrayInput([
                'versions' => $isDown ? [(string) $currentVersion] : $versions,
                $direction => true,
            ]);
            $input->setInteractive(false);

            // AliasResolver doesn't properly work when no migrations left for db
            if (!$isDown || (string) $currentVersion !== '0') {
                $migrateCommand->run($input, new NullOutput());
            }

            $io->success('');
        }, $this->managerRegistry->getManagers());

        return self::SUCCESS;
    }
}

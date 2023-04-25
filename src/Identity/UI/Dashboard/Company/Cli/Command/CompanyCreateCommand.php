<?php

declare(strict_types=1);

namespace App\Identity\UI\Dashboard\Company\Cli\Command;

use App\Identity\Application\Company\CompanyException;
use App\Identity\Application\Company\UseCase\CreateCompany\CreateCompanyCommand;
use App\Identity\Application\Company\UseCase\CreateCompany\CreateCompanyHandler;
use App\Identity\Domain\Company\Name;
use App\Shared\Application\HttpResponseCode;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class CompanyCreateCommand extends Command
{
    protected static $defaultName = 'app:company:create';

//    private Transaction $transaction;

    private CreateCompanyHandler $createCompanyHandler;

    private ManagerRegistry $managerRegistry;

//    private Configuration $configuration;
    private MigrateCommand $migrateCommand;

    public function __construct(CreateCompanyHandler $createCompanyHandler,
//                                Transaction $transaction,
                                ManagerRegistry $managerRegistry,
//                                Configuration $configuration,
                                MigrateCommand $migrateCommand,
                                string $name = null)
    {
        parent::__construct($name);

//        $this->transaction = $transaction;
        $this->createCompanyHandler = $createCompanyHandler;
        $this->managerRegistry = $managerRegistry;
//        $this->configuration = $configuration;
        $this->migrateCommand = $migrateCommand;
    }

    protected function configure(): void
    {
        parent::configure();

        $this
            ->setDescription('Create new Company')
            ->addArgument('name', InputArgument::REQUIRED, 'Name for the Company')
        ;
    }

    // TODO some hackish code below to make it finally work; not so easy to work with doctrine
    // TODO to create DB and orchestrate/mutate connections & migrations programmatically;
    // TODO the same is for transactions, tried to do using that first, but no luck;
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $name = $input->getArgument('name');
        $companyName = new Name($name);
        $alias = $companyName->alias();
        $command = new CreateCompanyCommand($name);

        $em = $this->managerRegistry->getManager($alias);

        $connection = $em->getConnection();
        $connection = EmptyDbNameConnection::fromConnection($connection);
        $em = new ExistingEntityManager($em);
        $manager = $connection->getSchemaManager();

        try {
//            $io->info('Creating database...');
//            $manager->createDatabase($alias);

            $io->info('Running migrations for the new Company\'s DB...');

            try {
//                $configuration = new ExistingConfiguration($this->configuration);
//                $dependencyFactory = DependencyFactory::fromEntityManager($configuration, $em);

                $this->migrateCommand->run(new ArrayInput([]), new NullOutput());

//                $plan = new MigrationPlan(
//                    new Version(CompanyMigration::class),
//                    new CompanyMigration(
//                        $dependencyFactory->getConnection(),
//                        $dependencyFactory->getLogger()
//                    ),
//                    Direction::UP);
//                $planList = new MigrationPlanList([$plan], Direction::UP);
//                $migratorConfigurationFactory = $dependencyFactory->getConsoleInputMigratorConfigurationFactory();
//                $migratorConfiguration        = $migratorConfigurationFactory->getMigratorConfiguration($input);
//                $dependencyFactory->getMetadataStorage()->ensureInitialized();
//                // TODO we can't simply inject all DbalMigrator dependencies, so creating it manually
//                $migrator = new DbalMigrator(
//                    $dependencyFactory->getConnection(),
//                    $dependencyFactory->getEventDispatcher(),
//                    new DbalExecutor(
//                        $dependencyFactory->getMetadataStorage(),
//                        $dependencyFactory->getEventDispatcher(),
//                        $dependencyFactory->getConnection(),
//                        $dependencyFactory->getSchemaDiffProvider(),
//                        $dependencyFactory->getLogger(),
//                        new InlineParameterFormatter(
//                            $dependencyFactory->getConnection()
//                        ),
//                        $dependencyFactory->getStopwatch()
//                    ),
//                    $dependencyFactory->getLogger(),
//                    $dependencyFactory->getStopwatch()
//                );
//                $migrator->migrate($planList, $migratorConfiguration);
//                $dependencyFactory->getMetadataStorage()->complete(
//                    new ExecutionResult(
//                        new Version(CompanyMigration::class)
//                    )
//                );

                //                $execute = new ExecuteCommand($dependencyFactory);
//                $executeInput = new ArrayInput([
//                    'versions' => [
//                        VersioCompanyDbMigration::class,
//                    ],
//                    '--up' => true,
//                ]);
//
//                $executeInput->setInteractive(false);
//                $execute->run($executeInput, new NullOutput());
            } catch (Exception $exception) {
                $manager->dropDatabase($alias);
                throw $exception;
            }

            $io->info('Creating company...');

            try {
                $company = $this->createCompanyHandler->handle($command);
                $io->success(sprintf('Company %s created! ID: %s', $name, $company->id()));
                $io->newLine();
            } catch (CompanyException $exception) {
                if ($exception->getCode() === HttpResponseCode::conflict()) {
                    $io->error('Already exist');
                }
//                $manager->dropDatabase($alias);
//                throw $exception;
            }

            return self::SUCCESS;
        } catch (Exception $exception) {
            $io->error($exception->getMessage());
            $io->newLine();

            return self::FAILURE;
        }

//        $transactions['default'] = clone $this->transaction;
//        $transactions[$name] = clone $this->transaction;
//
//        try {
//            $em = $this->managerRegistry->getManager($alias);
//
//            $transactions[$name]->setEntityManager($em);
//
//            $connection = $em->getConnection();
//            $connection = EmptyDbNameConnection::fromConnection($connection);
//            $em = new ExistingEntityManager($em);
//            $manager = $connection->getSchemaManager();
//
//            $transactions['default']->begin();
//            $transactions[$name]->begin();
//
//            try {
//                $transactions['default']->commit(function () use ($io, $command) {
//                    $io->info('Creating company...');
//                    $this->createCompanyHandler->handle($command);
//                });
//
//                $transactions[$name]->commit(function () use ($em, $manager, $alias, $io) {
//                    $io->info('Creating database...');
//                    $manager->createDatabase($alias);
//
//                    $io->info('Running migrations for the new Company\'s DB...');
//
//                    $configuration = new ExistingConfiguration($this->configuration);
//                    $dependencyFactory = DependencyFactory::fromEntityManager($configuration, $em);
//                    $execute = new ExecuteCommand($dependencyFactory);
//                    $executeInput = new ArrayInput([
//                        'versions' => [
//                            VersionCreateCompanyMigration::class,
//                        ],
//                        '--up' => true,
//                    ]);
//
//                    $executeInput->setInteractive(false);
//                    $execute->run($executeInput, new NullOutput());
//                });
//            } catch (Exception $exception) {
//                $transactions['default']->rollback();
//                $transactions[$name]->rollback();
//            }
//
//            $io->success(sprintf('Company %s created!', $name));
//
//            return Command::SUCCESS;
//        } catch (Exception $exception) {
//            $io->error($exception->getMessage());
//
//            return Command::FAILURE;
//        }
    }
}

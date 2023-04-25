<?php

declare(strict_types=1);

namespace App\Identity\UI\Dashboard\User;

use App\Identity\Application\Permission\Query\PermissionConfigurationQuery;
use App\Identity\Application\User\UseCase\CreateUser\CreateUserCommand;
use App\Identity\Application\User\UseCase\CreateUser\CreateUserHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class AdminCreateCommand extends Command
{
    protected static $defaultName = 'app:admin:create';

    private ContainerInterface $container;
    private PermissionConfigurationQuery $permissionConfigurationQuery;
    private CreateUserHandler $createUserHandler;

    public function __construct(string $name = null,
                                ContainerInterface $container,
                                PermissionConfigurationQuery $permissionConfigurationQuery,
                                CreateUserHandler $createUserHandler)
    {
        parent::__construct($name);

        $this->container = $container;
        $this->permissionConfigurationQuery = $permissionConfigurationQuery;
        $this->createUserHandler = $createUserHandler;
    }

    protected function configure(): void
    {
        parent::configure();

        $this
            ->setDescription('Create new Admin')
            ->addArgument('email', InputArgument::REQUIRED, 'Email of the admin')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
//        $createUserHandler = $this->container->get(CreateUserHandler::class);

        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $permissions = $this->permissionConfigurationQuery->query();

        $command = new CreateUserCommand(
            $email,
            $permissions->permissions()->toIDs(true),
            null,
            true
        );
        $this->createUserHandler->handle($command);

        $io->success('Admin created');

        return Command::SUCCESS;
    }
}

<?php

declare(strict_types=1);

namespace App\Identity\UI\Dashboard\Customer;

use App\Identity\Application\Customer\UseCase\CreateCustomer\CreateCustomerCommand;
use App\Identity\Application\Customer\UseCase\CreateCustomer\CreateCustomerHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class CustomerCreateCommand extends Command
{
    protected static $defaultName = 'app:customer:create';

    private ContainerInterface $container;

    public function __construct(string $name = null, ContainerInterface $container)
    {
        parent::__construct($name);

        $this->container = $container;
    }

    protected function configure(): void
    {
        parent::configure();

        $this
            ->setDescription('Create new Customer')
            ->addArgument('email', InputArgument::REQUIRED, 'Email of the customer')
            ->addArgument('password', InputArgument::REQUIRED, 'Password of the customer')
            ->addArgument('username', InputArgument::REQUIRED, 'Prefered username')
            ->addArgument('firstname', InputArgument::OPTIONAL)
            ->addArgument('lastname', InputArgument::OPTIONAL)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $createCustomerHandler = $this->container->get(CreateCustomerHandler::class);

        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $username = $input->getArgument('username');
        $firstname = $input->getArgument('firstname');
        $lastname = $input->getArgument('lastname');

        $command = new CreateCustomerCommand(
            $email,
            $username,
            $password,
            $firstname,
            $lastname
        );
        $createCustomerHandler->handle($command);

        $io->success('Customer created');

        return Command::SUCCESS;
    }
}

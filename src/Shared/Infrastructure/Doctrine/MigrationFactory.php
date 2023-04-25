<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\Migrations\AbstractMigration;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class MigrationFactory implements \Doctrine\Migrations\Version\MigrationFactory
{
    private $connection;

    private $logger;

    private ContainerBagInterface $container;

    public function __construct(Connection $connection,
                                LoggerInterface $logger,
                                ContainerBagInterface $container)
    {
        $this->connection = $connection;
        $this->logger = $logger;
        $this->container = $container;
    }

    public function createVersion(string $migrationClassName): AbstractMigration
    {
        $migration = new $migrationClassName(
            $this->connection,
            $this->logger
        );

        if ($migration instanceof CompanyMigrationInterface) {
            $migration->setContainer($this->container);
        }

        return $migration;
    }
}

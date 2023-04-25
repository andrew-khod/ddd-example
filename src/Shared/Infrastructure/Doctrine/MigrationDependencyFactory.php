<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine;

use Doctrine\Migrations\Configuration\EntityManager\EntityManagerLoader;
use Doctrine\Migrations\Configuration\Migration\ConfigurationLoader;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\FilesystemMigrationsRepository;
use Doctrine\Migrations\MigrationsRepository;
use Psr\Log\LoggerInterface;

final class MigrationDependencyFactory extends DependencyFactory
//final class MigrationDependencyFactory
{
//    private DependencyFactory $dependencyFactory;

    public function __construct()
    {
    }

//    public function __construct(DependencyFactory $dependencyFactory)
//    {
//
//        $this->dependencyFactory = $dependencyFactory;
//    }

    public function getMigrationRepository(): MigrationsRepository
    {
        return new FilesystemMigrationsRepository(
            $this->dependencyFactory->getConfiguration()->getMigrationClasses(),
            [],
            $this->dependencyFactory->getMigrationsFinder(),
            $this->dependencyFactory->getMigrationFactory()
        );
    }

//    public static function fromEntityManager(ConfigurationLoader $configurationLoader, EntityManagerLoader $emLoader, ?LoggerInterface $logger = null): DependencyFactory
    public static function fromEntityManager(ConfigurationLoader $configurationLoader, EntityManagerLoader $emLoader, ?LoggerInterface $logger = null): self
    {
        $dependencyFactory = new self(DependencyFactory::fromEntityManager($configurationLoader, $emLoader));

        return $dependencyFactory;
    }
}

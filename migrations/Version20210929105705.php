<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Shared\Infrastructure\Doctrine\CompanyMigrationInterface;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210929105705 extends AbstractMigration implements CompanyMigrationInterface
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE customer DROP INDEX UNIQ_81398E09F85E0677');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE customer ADD UNIQUE INDEX UNIQ_81398E09F85E0677 (username)');
    }
}

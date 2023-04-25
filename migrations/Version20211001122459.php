<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Shared\Infrastructure\Doctrine\CompanyMigrationInterface;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211001122459 extends AbstractMigration implements CompanyMigrationInterface
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE initiative ADD COLUMN location_name TEXT');
        // fixme can't set default for text type colummns
        //        $this->addSql('ALTER TABLE initiative ADD COLUMN location_name TEXT NOT NULL DEFAULT \'\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE customer DROP COLUMN location_name');
    }
}

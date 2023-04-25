<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Shared\Infrastructure\Doctrine\CompanyMigrationInterface;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220105130444 extends AbstractMigration implements CompanyMigrationInterface
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {

        $this->addSql('ALTER TABLE question ADD COLUMN orderIndex SMALLINT, ADD UNIQUE INDEX ORDER_INDEX (orderIndex)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE question DROP INDEX ORDER_INDEX, DROP COLUMN orderIndex');
    }
}

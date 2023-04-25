<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Shared\Infrastructure\Doctrine\CompanyMigrationInterface;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220111140358 extends AbstractMigration implements CompanyMigrationInterface
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE contact_translation ADD COLUMN id BINARY(16) NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY(id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE contact_translation DROP PRIMARY KEY, DROP COLUMN id, ADD PRIMARY KEY(contact_id, language_id)');
    }
}

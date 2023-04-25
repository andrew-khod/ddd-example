<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Shared\Infrastructure\Doctrine\CompanyMigrationInterface;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220111145312 extends AbstractMigration implements CompanyMigrationInterface
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE contact_translation DROP FOREIGN KEY FK_DAC5FAD1E7A1254A, ADD CONSTRAINT FK_CONTACT FOREIGN KEY (contact_id) REFERENCES contact (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE contact_translation DROP FOREIGN KEY FK_CONTACT, ADD CONSTRAINT FK_DAC5FAD1E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id)');
    }
}

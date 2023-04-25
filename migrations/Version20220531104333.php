<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Shared\Infrastructure\Doctrine\CompanyMigrationInterface;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220531104333 extends AbstractMigration implements CompanyMigrationInterface
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE customer ADD COLUMN active_language_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_CUSTOMER_ACTIVE_LANGUAGE FOREIGN KEY (active_language_id) REFERENCES language (id) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_CUSTOMER_ACTIVE_LANGUAGE, DROP COLUMN active_language_id');
    }
}

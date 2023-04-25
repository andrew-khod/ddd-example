<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Shared\Infrastructure\Doctrine\CompanyMigrationInterface;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220110144949 extends AbstractMigration implements CompanyMigrationInterface
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE contact (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', orderIndex SMALLINT NOT NULL, created DATETIME NOT NULL, deleted DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_4C62E63851660209 (orderIndex), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact_translation (contact_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', language_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', type VARCHAR(255) NOT NULL, value LONGTEXT NOT NULL, INDEX IDX_DAC5FAD1E7A1254A (contact_id), INDEX IDX_DAC5FAD182F1BAF4 (language_id), PRIMARY KEY(contact_id, language_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contact_translation ADD CONSTRAINT FK_DAC5FAD1E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id)');
        $this->addSql('ALTER TABLE contact_translation ADD CONSTRAINT FK_DAC5FAD182F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE contact_translation DROP FOREIGN KEY FK_DAC5FAD1E7A1254A');
        $this->addSql('ALTER TABLE contact_translation DROP FOREIGN KEY FK_DAC5FAD182F1BAF4');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE contact_translation');
    }
}

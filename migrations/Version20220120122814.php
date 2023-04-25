<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Shared\Infrastructure\Doctrine\CompanyMigrationInterface;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220120122814 extends AbstractMigration implements CompanyMigrationInterface
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE rule (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', language_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_46D8ACCC82F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rule ADD CONSTRAINT FK_46D8ACCC82F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE rule DROP FOREIGN KEY FK_46D8ACCC82F1BAF4');
        $this->addSql('DROP TABLE rule');
    }
}

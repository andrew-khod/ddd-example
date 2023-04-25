<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Shared\Infrastructure\Doctrine\CompanyMigrationInterface;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220125142223 extends AbstractMigration implements CompanyMigrationInterface
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE accessibility_policy (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', language_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_E27412E482F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE accessibility_policy ADD CONSTRAINT FK_E27412E482F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE accessibility_policy DROP FOREIGN KEY FK_E27412E482F1BAF4');
        $this->addSql('DROP TABLE accessibility_policy');
    }
}

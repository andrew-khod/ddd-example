<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Shared\Infrastructure\Doctrine\CompanyMigrationInterface;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211105120106 extends AbstractMigration implements CompanyMigrationInterface
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE language (id BINARY(16) NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_800230D35E237E05 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql("INSERT INTO language (id, name) VALUES (UUID_TO_BIN('726eb2d0-b61f-4ef1-b434-3c021c979b0c'), 'fi'), (UUID_TO_BIN('e5e52cca-a66d-4f9a-8ec1-7270cbaada0a'), 'en')");
        $this->addSql('ALTER TABLE category DROP COLUMN name');
        $this->addSql('CREATE TABLE category_translation (name VARCHAR(255) NOT NULL, category_id BINARY(16) NOT NULL, language_id BINARY(16) NOT NULL)');
        $this->addSql('ALTER TABLE category_translation ADD CONSTRAINT CATEGORY FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE, ADD CONSTRAINT LANGUAGE FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        //todo
        $this->addSql('DROP TABLE category_translation');
        $this->addSql('DROP TABLE language');
        $this->addSql('ALTER TABLE category ADD COLUMN name VARCHAR(255) NOT NULL');
    }

    // todo
    public function isTransactional(): bool
    {
        return true;
    }
}

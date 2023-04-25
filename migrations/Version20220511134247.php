<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Shared\Infrastructure\Doctrine\DefaultMigrationInterface;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220511134247 extends AbstractMigration implements DefaultMigrationInterface
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE available_language (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql("INSERT INTO available_language (id, name) VALUES (UUID_TO_BIN('2e38dfd5-e4e0-42c6-b63d-b837efc0aa16'), 'fi'), (UUID_TO_BIN('1d2e75e5-9ad1-45fe-b880-d4612cf501ff'), 'en'), (UUID_TO_BIN('97580abe-6781-4da8-bed7-c64f49cd0041'), 'sw')");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE available_language');
    }
}

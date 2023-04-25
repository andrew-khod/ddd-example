<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Shared\Infrastructure\Doctrine\CompanyMigrationInterface;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211228130719 extends AbstractMigration implements CompanyMigrationInterface
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE question (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', created DATETIME NOT NULL, deleted DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question_translation (question_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', language_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', title LONGTEXT NOT NULL, answer LONGTEXT NOT NULL, INDEX IDX_576D9AE21E27F6BF (question_id), INDEX IDX_576D9AE282F1BAF4 (language_id), PRIMARY KEY(question_id, language_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE question_translation ADD CONSTRAINT FK_576D9AE21E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE question_translation ADD CONSTRAINT FK_576D9AE282F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');

    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE question_translation DROP FOREIGN KEY FK_576D9AE282F1BAF4');
        $this->addSql('ALTER TABLE question_translation DROP FOREIGN KEY FK_576D9AE21E27F6BF');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE question_translation');
    }
}

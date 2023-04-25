<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Shared\Infrastructure\Doctrine\CompanyMigrationInterface;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211230143938 extends AbstractMigration implements CompanyMigrationInterface
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE question_translation DROP FOREIGN KEY FK_576D9AE21E27F6BF, ADD CONSTRAINT FK_QUESTION_ID FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE question_translation DROP FOREIGN KEY FK_QUESTION_ID, ADD CONSTRAINT FK_576D9AE21E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
    }
}

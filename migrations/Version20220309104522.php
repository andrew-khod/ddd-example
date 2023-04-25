<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Shared\Infrastructure\Doctrine\DefaultMigrationInterface;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220309104522 extends AbstractMigration implements DefaultMigrationInterface
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user_permission ADD COLUMN company_id BINARY(16) NULL');
        $this->addSql('UPDATE user_permission SET company_id=(SELECT company_id FROM user_company WHERE user_id=user_permission.user_id LIMIT 1)');
        $this->addSql('ALTER TABLE user_permission MODIFY COLUMN company_id BINARY(16) NOT NULL ');
        $this->addSql('ALTER TABLE user_permission ADD CONSTRAINT FK_USER_PERMISSION_COMPANY FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user_permission DROP COLUMN company_id');
    }
}

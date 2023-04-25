<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Shared\Infrastructure\Doctrine\CompanyMigrationInterface;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211215130906 extends AbstractMigration implements CompanyMigrationInterface
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DELETE FROM user_permission');
        $this->addSql('ALTER TABLE user_permission ADD COLUMN id BINARY(16) NOT NULL, ADD PRIMARY KEY(id)');

        $this->addSql('ALTER TABLE user ADD COLUMN username VARCHAR(255)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user_permission DROP PRIMARY KEY, DROP COLUMN id');
        $this->addSql('ALTER TABLE user DROP COLUMN username');
    }
}

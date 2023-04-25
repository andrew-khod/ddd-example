<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Shared\Infrastructure\Doctrine\DefaultMigrationInterface;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220126133039 extends AbstractMigration implements DefaultMigrationInterface
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('INSERT INTO permission (id, name) VALUES (UUID_TO_BIN(\'6c2a522e-7713-42a8-bee3-14282b0bca13\'), \'customization:*\'), (UUID_TO_BIN(\'f7430aa6-b01d-4b90-ae86-7c8dce6a56b0\'), \'customer:*\')');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM permission WHERE name = \'customization:*\' OR name = \'customer:*\'');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Shared\Infrastructure\Doctrine\DefaultMigrationInterface;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220316103129 extends AbstractMigration implements DefaultMigrationInterface
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE company ADD COLUMN alias VARCHAR(250), ADD COLUMN url VARCHAR(3000), ADD COLUMN color VARCHAR(6)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE company DROP COLUMN alias, DROP COLUMN url, DROP COLUMN color');
    }
}

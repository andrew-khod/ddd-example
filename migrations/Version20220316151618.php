<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Shared\Infrastructure\Doctrine\DefaultMigrationInterface;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220316151618 extends AbstractMigration implements DefaultMigrationInterface
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE company DROP INDEX UNIQ_800230D35E237E06, ADD UNIQUE INDEX UNIQ_COMPANY_ALIAS (alias)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE company DROP INDEX UNIQ_COMPANY_ALIAS, ADD UNIQUE INDEX UNIQ_800230D35E237E06 (name)');
    }
}

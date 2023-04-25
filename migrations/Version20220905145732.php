<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Shared\Infrastructure\Doctrine\CompanyMigrationInterface;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220905145732 extends AbstractMigration implements CompanyMigrationInterface
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
//        $this->addSql('CREATE TABLE following (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', customer_id BINARY(16) DEFAULT NULL, initiative_id BINARY(16) DEFAULT NULL, created DATETIME NOT NULL, INDEX IDX_71BF8DE39395C3F3 (customer_id), INDEX IDX_71BF8DE3AB7D9771 (initiative_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE following ADD COLUMN created DATETIME');
        $this->addSql('UPDATE following SET created = NOW()');
        $this->addSql('ALTER TABLE following MODIFY COLUMN created DATETIME NOT NULL');
//        $this->addSql('ALTER TABLE following ADD COLUMN id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', ADD COLUMN created DATETIME NOT NULL, ADD PRIMARY KEY(id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE following DROP COLUMN created');
//        $this->addSql('ALTER TABLE following DROP PRIMARY KEY, DROP COLUMN id, DROP COLUMN created');
    }
}

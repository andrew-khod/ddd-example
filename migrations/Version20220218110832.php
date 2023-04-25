<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Shared\Infrastructure\Doctrine\CompanyMigrationInterface;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220218110832 extends AbstractMigration implements CompanyMigrationInterface
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE following (initiative_id BINARY(16) NOT NULL, customer_id BINARY(16) NOT NULL, INDEX IDX_71BF8DE3AB7D9771 (initiative_id), INDEX IDX_71BF8DE39395C3F3 (customer_id), PRIMARY KEY(initiative_id, customer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE following ADD CONSTRAINT FK_71BF8DE3AB7D9771 FOREIGN KEY (initiative_id) REFERENCES initiative (id)');
        $this->addSql('ALTER TABLE following ADD CONSTRAINT FK_71BF8DE39395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE following DROP FOREIGN KEY FK_71BF8DE39395C3F3');
        $this->addSql('ALTER TABLE following DROP FOREIGN KEY FK_71BF8DE3AB7D9771');
        $this->addSql('DROP TABLE following');
    }
}

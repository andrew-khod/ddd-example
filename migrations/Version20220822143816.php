<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Shared\Infrastructure\Doctrine\CompanyMigrationInterface;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220822143816 extends AbstractMigration implements CompanyMigrationInterface
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE initiative_event (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', initiative_id BINARY(16) DEFAULT NULL, event JSON NOT NULL, INDEX IDX_672C4250AB7D9771 (initiative_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE initiative_event_read_status (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', event_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', customer_id BINARY(16) DEFAULT NULL, INDEX IDX_7B60B44271F7E88B (event_id), INDEX IDX_7B60B4429395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE initiative_event ADD CONSTRAINT FK_672C4250AB7D9771 FOREIGN KEY (initiative_id) REFERENCES initiative (id)');
        $this->addSql('ALTER TABLE initiative_event_read_status ADD CONSTRAINT FK_7B60B44271F7E88B FOREIGN KEY (event_id) REFERENCES initiative_event (id)');
        $this->addSql('ALTER TABLE initiative_event_read_status ADD CONSTRAINT FK_7B60B4429395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE initiative_event_read_status DROP FOREIGN KEY FK_7B60B4429395C3F3');
        $this->addSql('ALTER TABLE initiative_event DROP FOREIGN KEY FK_672C4250AB7D9771');
        $this->addSql('ALTER TABLE initiative_event_read_status DROP FOREIGN KEY FK_7B60B44271F7E88B');
        $this->addSql('DROP TABLE initiative_event');
        $this->addSql('DROP TABLE initiative_event_read_status');
    }
}

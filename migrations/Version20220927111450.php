<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Shared\Infrastructure\Doctrine\CompanyMigrationInterface;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220927111450 extends AbstractMigration implements CompanyMigrationInterface
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE initiative_event_read_status ADD UNIQUE INDEX INITIATIVE_EVENT_READ_STATUS_EVENT_CUSTOMER (event_id, customer_id)');
//        $this->addSql('ALTER TABLE initiative_event_read_status DROP INDEX IDX_7B60B4429395C3F3, DROP INDEX IDX_7B60B44271F7E88B, ADD UNIQUE INDEX INITIATIVE_EVENT_READ_STATUS_EVENT_CUSTOMER (event_id, customer_id)');
    }

    public function down(Schema $schema): void
    {
        //todo
//        $this->addSql('ALTER TABLE initiative_event_read_status DROP index');
    }
}

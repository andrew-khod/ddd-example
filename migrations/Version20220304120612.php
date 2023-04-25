<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Shared\Infrastructure\Doctrine\DefaultMigrationInterface;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220304120612 extends AbstractMigration implements DefaultMigrationInterface
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user_company DROP COLUMN is_active_company, ADD COLUMN id BINARY(16) NULL, DROP PRIMARY KEY, ADD UNIQUE INDEX UNIQ_USER_COMPANY (user_id, company_id)');
        $this->addSql('UPDATE user_company SET id=UUID_TO_BIN(UUID())');
        $this->addSql('ALTER TABLE user_company MODIFY COLUMN id BINARY(16) NOT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE user ADD COLUMN active_company_id BINARY(16) NULL');
//        $this->addSql('ALTER TABLE user ADD COLUMN active_company_id BINARY(16) NULL, ADD CONSTRAINT FK_USER_ACTIVE_COMPANY FOREIGN KEY (active_company_id) REFERENCES user_company (id)');
        $this->addSql('UPDATE user SET active_company_id=(SELECT id FROM user_company WHERE user_id=user.id LIMIT 1)');
        $this->addSql('ALTER TABLE user MODIFY COLUMN active_company_id BINARY(16) NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_USER_ACTIVE_COMPANY FOREIGN KEY (active_company_id) REFERENCES user_company (id)');
    }

    public function down(Schema $schema): void
    {
        //todo
        $this->addSql('ALTER TABLE user_company ADD COLUMN is_active_company TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_USER_ACTIVE_COMPANY, DROP COLUMN active_company_id');
    }
}

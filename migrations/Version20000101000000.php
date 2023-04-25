<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Shared\Infrastructure\Doctrine\DefaultMigrationInterface;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20000101000000 extends AbstractMigration implements DefaultMigrationInterface
{
    public function getDescription(): string
    {
        return 'Migration for the general default database';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE company (id BINARY(16) NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_800230D35E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE permission (id BINARY(16) NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_800230D35E237E07 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id BINARY(16) NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_800230D35E237E08 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role_permission (role_id BINARY(16) NOT NULL, permission_id BINARY(16) NOT NULL, PRIMARY KEY(role_id, permission_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refresh_tokens (id INT AUTO_INCREMENT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid DATETIME NOT NULL, UNIQUE INDEX UNIQ_9BACE7E1C74F2195 (refresh_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_company (user_id BINARY(16) NOT NULL, company_id BINARY(16) NOT NULL, email VARCHAR(180) NOT NULL, is_active_company TINYINT(1) DEFAULT \'0\' NOT NULL, PRIMARY KEY(user_id, company_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_password_recovery (user_id BINARY(16) NOT NULL, token VARCHAR(180) NOT NULL, PRIMARY KEY(user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE auth_vendor (user_id BINARY(16) NOT NULL, vendor_user_id VARCHAR(255) NOT NULL, vendor_type VARCHAR(255) NOT NULL, PRIMARY KEY(user_id, vendor_user_id, vendor_type)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE role_permission ADD CONSTRAINT FK_6F7DF886D60322AC FOREIGN KEY (role_id) REFERENCES Role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_permission ADD CONSTRAINT FK_6F7DF886FED90CCA FOREIGN KEY (permission_id) REFERENCES Permission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_company ADD CONSTRAINT FK_17B21745979B1AD6 FOREIGN KEY (company_id) REFERENCES Company (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user_company DROP FOREIGN KEY FK_17B21745979B1AD6');
        $this->addSql('ALTER TABLE role_permission DROP FOREIGN KEY FK_6F7DF886FED90CCA');
        $this->addSql('ALTER TABLE role_permission DROP FOREIGN KEY FK_6F7DF886D60322AC');

        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE permission');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE role_permission');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('DROP TABLE user_company');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('DROP TABLE auth_vendor');
        $this->addSql('DROP TABLE user_password_recovery');
    }
}

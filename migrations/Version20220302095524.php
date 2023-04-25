<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Shared\Infrastructure\Doctrine\DefaultMigrationInterface;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220302095524 extends AbstractMigration implements DefaultMigrationInterface
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE `user` (
          `id` binary(16) NOT NULL,
          `email` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
          `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
          `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `deleted` datetime DEFAULT NULL,
          `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
        $this->addSql('CREATE TABLE `user_permission` (
            `user_id` binary(16) NOT NULL,
            `permission_id` binary(16) NOT NULL,
            `id` binary(16) NOT NULL,
            PRIMARY KEY (`id`),
            KEY `FK_USER_PERMISSION` (`user_id`),
            CONSTRAINT FK_USER_PERMISSION_USER FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE,
            CONSTRAINT FK_USER_PERMISSION_PERMISSION FOREIGN KEY (permission_id) REFERENCES permission (id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');

//        $this->addSql('ALTER TABLE user_company ADD CONSTRAINT FK_USER_COMPANY_USER FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
//        $this->addSql('ALTER TABLE user_company DROP FOREIGN KEY FK_USER');
        $this->addSql('DROP TABLE user_permission');
        $this->addSql('DROP TABLE user');
    }
}

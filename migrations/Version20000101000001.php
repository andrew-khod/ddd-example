<?php

namespace DoctrineMigrations;

use App\Shared\Infrastructure\Doctrine\CompanyMigrationInterface;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

class Version20000101000001 extends AbstractMigration implements CompanyMigrationInterface
{
    public function getDescription(): string
    {
        return 'Migration for a company-related database';
    }

    public function up(Schema $schema): void
    {
//        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id BINARY(16) NOT NULL, email VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_permission (user_id BINARY(16) NOT NULL, permission_id BINARY(16) NOT NULL) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_role (user_id BINARY(16) NOT NULL, role_id BINARY(16) NOT NULL) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE customer (id BINARY(16) NOT NULL, email VARCHAR(180) NOT NULL, password_recovery_token VARCHAR(180) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, username VARCHAR(255) DEFAULT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, birthday DATETIME DEFAULT NULL, gender VARCHAR(1) DEFAULT NULL, postal VARCHAR(10) DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, discr VARCHAR(255) NOT NULL, activation_token VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_81398E09E7927C74 (email), UNIQUE INDEX UNIQ_81398E09F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id BINARY(16) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE initiative (id BINARY(16) NOT NULL, customer_id BINARY(16) NOT NULL, type VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, minimal_joined_people INT DEFAULT NULL, location POINT DEFAULT NULL COMMENT \'(DC2Type:spatial_point)\', location_radius_value NUMERIC(10, 2) DEFAULT NULL, location_radius_unit VARCHAR(255) DEFAULT NULL, date_start DATETIME NOT NULL, date_end DATETIME NOT NULL, created DATETIME NOT NULL, is_archived TINYINT(1) NOT NULL, updated DATETIME NOT NULL, INDEX IDX_AE48DD2E9395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
//        status VARCHAR(255) NOT NULL
        $this->addSql('CREATE TABLE initiative_category (initiative_id BINARY(16) NOT NULL, category_id BINARY(16) NOT NULL, PRIMARY KEY(initiative_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id BINARY(16) NOT NULL, initiative_id BINARY(16) NOT NULL, pathname VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participation (initiative_id BINARY(16) NOT NULL, customer_id BINARY(16) NOT NULL, INDEX IDX_AB55E24FAB7D9771 (initiative_id), INDEX IDX_AB55E24F9395C3F3 (customer_id), PRIMARY KEY(initiative_id, customer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id BINARY(16) NOT NULL, initiative_id BINARY(16) DEFAULT NULL, customer_id BINARY(16) DEFAULT NULL, comment VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_5BC96BF0AB7D9771 (initiative_id), INDEX IDX_5BC96BF09395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favourite (initiative_id BINARY(16) NOT NULL, customer_id BINARY(16) NOT NULL, INDEX IDX_62A2CA19AB7D9771 (initiative_id), INDEX IDX_62A2CA199395C3F3 (customer_id), PRIMARY KEY(initiative_id, customer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_5BC96BF0AB7D9771 FOREIGN KEY (initiative_id) REFERENCES Initiative (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_5BC96BF09395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_permission ADD CONSTRAINT FK_USER_PERMISSION FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_USER_ROLE FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE initiative ADD CONSTRAINT FK_AE48DD2E9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE initiative_category ADD CONSTRAINT FK_8DD15BC9AB7D9771 FOREIGN KEY (initiative_id) REFERENCES initiative (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE initiative_category ADD CONSTRAINT FK_8DD15BC912469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_4FC2B5BAB7D9771 FOREIGN KEY (initiative_id) REFERENCES initiative (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FAB7D9771 FOREIGN KEY (initiative_id) REFERENCES Initiative (id)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE favourite ADD CONSTRAINT FK_62A2CA19AB7D9771 FOREIGN KEY (initiative_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE favourite ADD CONSTRAINT FK_62A2CA199395C3F3 FOREIGN KEY (customer_id) REFERENCES initiative (id)');

    }

    public function down(Schema $schema): void
    {
//        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_USER_ROLE');
        $this->addSql('ALTER TABLE initiative_category DROP FOREIGN KEY FK_8DD15BC9AB7D9771');
        $this->addSql('ALTER TABLE initiative_category DROP FOREIGN KEY FK_8DD15BC912469DE2');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_4FC2B5BAB7D9771');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FAB7D9771');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F9395C3F3');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_5BC96BF0AB7D9771');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_5BC96BF09395C3F3');
        $this->addSql('ALTER TABLE favourite DROP FOREIGN KEY FK_62A2CA19AB7D9771');
        $this->addSql('ALTER TABLE favourite DROP FOREIGN KEY FK_62A2CA199395C3F3');

        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE participation');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_permission');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_role');
        $this->addSql('DROP TABLE initiative');
        $this->addSql('DROP TABLE initiative_category');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE favourite');
    }
}

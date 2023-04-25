<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Shared\Infrastructure\Doctrine\DefaultMigrationInterface;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220307134351 extends AbstractMigration implements DefaultMigrationInterface
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // fixme move back and fix oneToOne User->UserCompany, activeCompany relation
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_USER_ACTIVE_COMPANY');
        $this->addSql('UPDATE user SET active_company_id=(SELECT company_id FROM user_company WHERE user_id=user.id LIMIT 1)');
    }

    public function down(Schema $schema): void
    {
    }
}

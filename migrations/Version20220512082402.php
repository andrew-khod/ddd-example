<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Shared\Infrastructure\Doctrine\CompanyMigrationInterface;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220512082402 extends AbstractMigration implements CompanyMigrationInterface
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE accessibility_policy DROP FOREIGN KEY FK_E27412E482F1BAF4, ADD CONSTRAINT FK_LANGUAGE FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->addSql('ALTER TABLE contact_translation DROP FOREIGN KEY FK_DAC5FAD182F1BAF4, ADD CONSTRAINT FK_CONTACT_TRANSLATION_LANGUAGE FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->addSql('ALTER TABLE cookies_policy DROP FOREIGN KEY FK_AC4C7E9282F1BAF4, ADD CONSTRAINT FK_COOKIES_POLICY_LANGUAGE FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->addSql('ALTER TABLE privacy_policy DROP FOREIGN KEY FK_3EE6A81B82F1BAF4, ADD CONSTRAINT FK_PRIVACY_POLICY_LANGUAGE FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->addSql('ALTER TABLE question_translation DROP FOREIGN KEY FK_576D9AE282F1BAF4, ADD CONSTRAINT FK_QUESTION_TRANSLATION_LANGUAGE FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->addSql('ALTER TABLE rule DROP FOREIGN KEY FK_46D8ACCC82F1BAF4, ADD CONSTRAINT FK_RULE_LANGUAGE FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->addSql('ALTER TABLE terms_of_use DROP FOREIGN KEY FK_C2864F2A82F1BAF4, ADD CONSTRAINT FK_TERMS_OF_USE_LANGUAGE FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down(Schema $schema): void
    {
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Shared\Infrastructure\Doctrine\CompanyMigrationInterface;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221004135250 extends AbstractMigration implements CompanyMigrationInterface
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE questionnaire (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', initiative_id BINARY(16) NOT NULL, type VARCHAR(255) NOT NULL, question MEDIUMTEXT NOT NULL, INDEX IDX_7A64DAFAB7D9771 (initiative_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE questionnaire_answer (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', questionnaire_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', answerer_id BINARY(16) NOT NULL, option_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', freetext MEDIUMTEXT DEFAULT NULL, INDEX IDX_437B451CCE07E8FF (questionnaire_id), INDEX IDX_437B451CCCC8E8CE (answerer_id), INDEX IDX_437B451CA7C41D6F (option_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE questionnaire_option (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', questionnaire_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', `option` MEDIUMTEXT NOT NULL, INDEX IDX_C3200F89CE07E8FF (questionnaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE questionnaire ADD CONSTRAINT FK_7A64DAFAB7D9771 FOREIGN KEY (initiative_id) REFERENCES initiative (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE questionnaire_answer ADD CONSTRAINT FK_437B451CCE07E8FF FOREIGN KEY (questionnaire_id) REFERENCES questionnaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE questionnaire_answer ADD CONSTRAINT FK_437B451CCCC8E8CE FOREIGN KEY (answerer_id) REFERENCES customer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE questionnaire_answer ADD CONSTRAINT FK_437B451CA7C41D6F FOREIGN KEY (option_id) REFERENCES questionnaire_option (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE questionnaire_option ADD CONSTRAINT FK_C3200F89CE07E8FF FOREIGN KEY (questionnaire_id) REFERENCES questionnaire (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE questionnaire_answer DROP FOREIGN KEY FK_437B451CCCC8E8CE');
        $this->addSql('ALTER TABLE questionnaire DROP FOREIGN KEY FK_7A64DAFAB7D9771');
        $this->addSql('ALTER TABLE questionnaire_answer DROP FOREIGN KEY FK_437B451CCE07E8FF');
        $this->addSql('ALTER TABLE questionnaire_option DROP FOREIGN KEY FK_C3200F89CE07E8FF');
        $this->addSql('ALTER TABLE questionnaire_answer DROP FOREIGN KEY FK_437B451CA7C41D6F');
        $this->addSql('DROP TABLE questionnaire');
        $this->addSql('DROP TABLE questionnaire_answer');
        $this->addSql('DROP TABLE questionnaire_option');
    }
}

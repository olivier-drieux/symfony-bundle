<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250321224310 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, data JSON NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_mockup (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', project_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', domain_name VARCHAR(255) NOT NULL, theme VARCHAR(255) NOT NULL, is_mockup TINYINT(1) DEFAULT 1 NOT NULL, login VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, data JSON NOT NULL, image_zip_path VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_66323652166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', email VARCHAR(180) NOT NULL, username VARCHAR(255) NOT NULL, api_key VARCHAR(255) DEFAULT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project_mockup ADD CONSTRAINT FK_66323652166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE web_agency ADD extra_field VARCHAR(255) DEFAULT NULL, DROP version, CHANGE website website VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_mockup DROP FOREIGN KEY FK_66323652166D1F9C');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE project_mockup');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('ALTER TABLE web_agency ADD version INT DEFAULT 1 NOT NULL, DROP extra_field, CHANGE website website VARCHAR(255) DEFAULT NULL');
    }
}

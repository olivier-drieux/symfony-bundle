<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250321224859 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project ADD web_agency_id INT NOT NULL');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE13461AFE FOREIGN KEY (web_agency_id) REFERENCES web_agency (id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE13461AFE ON project (web_agency_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE13461AFE');
        $this->addSql('DROP INDEX IDX_2FB3D0EE13461AFE ON project');
        $this->addSql('ALTER TABLE project DROP web_agency_id');
    }
}

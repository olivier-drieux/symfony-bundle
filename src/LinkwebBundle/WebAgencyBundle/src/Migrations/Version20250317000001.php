<?php

namespace App\LinkwebBundle\WebAgencyBundle\src\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use App\LinkwebBundle\Constant\SharedTables;

final class Version20250317000001 extends AbstractMigration
{
    private string $webAgencyTable = SharedTables::WEB_AGENCY;

    public function getDescription(): string
    {
        return 'Create WebAgency table and insert initial data';
    }

    public function up(Schema $schema): void
    {
        // Creation of the table
        $this->addSql("
            CREATE TABLE $this->webAgencyTable (
                id INT AUTO_INCREMENT NOT NULL,
                name VARCHAR(255) NOT NULL,
                website VARCHAR(255) DEFAULT NULL,
                version INT DEFAULT 1 NOT NULL,
                PRIMARY KEY(id)
            )
        ");

        // Insert initial data
        $this->addSql("INSERT INTO $this->webAgencyTable (name, website) VALUES ('Linkweb', 'https://linkweb.fr')");
        $this->addSql("INSERT INTO $this->webAgencyTable (name, website) VALUES ('ReadyUp', 'https://readyup.fr')");
    }

    public function down(Schema $schema): void
    {
        // Deletion of the table
        $this->addSql("DROP TABLE $this->webAgencyTable");
    }
}
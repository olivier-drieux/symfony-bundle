<?php

declare(strict_types=1);

namespace Linkweb\SharedBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Create web_agency table
 */
final class Version20250323120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create web_agency table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE web_agency (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, website VARCHAR(255) DEFAULT NULL, version INT DEFAULT 1 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE web_agency');
    }
}

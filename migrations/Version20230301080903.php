<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230301080903 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE thematic (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(50) NOT NULL, page VARCHAR(50) NOT NULL, position VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL)');
        $this->addSql('CREATE TABLE thematic_image (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, thematic_id INTEGER NOT NULL, image_path VARCHAR(255) NOT NULL, title VARCHAR(50) DEFAULT NULL, description VARCHAR(150) DEFAULT NULL, position SMALLINT NOT NULL DEFAULT 1, CONSTRAINT FK_456ED5922395FCED FOREIGN KEY (thematic_id) REFERENCES thematic (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_456ED5922395FCED ON thematic_image (thematic_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE thematic');
        $this->addSql('DROP TABLE thematic_image');
    }
}

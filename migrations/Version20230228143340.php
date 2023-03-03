<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230228143340 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carousel_image ADD COLUMN position SMALLINT NOT NULL DEFAULT 0');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__carousel_image AS SELECT id, carousel_id, interval, image_path, title, description FROM carousel_image');
        $this->addSql('DROP TABLE carousel_image');
        $this->addSql('CREATE TABLE carousel_image (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, carousel_id INTEGER NOT NULL, interval SMALLINT NOT NULL, image_path VARCHAR(255) NOT NULL, title VARCHAR(50) DEFAULT NULL, description VARCHAR(150) DEFAULT NULL, CONSTRAINT FK_AABDD99C1CE5B98 FOREIGN KEY (carousel_id) REFERENCES carousel (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO carousel_image (id, carousel_id, interval, image_path, title, description) SELECT id, carousel_id, interval, image_path, title, description FROM __temp__carousel_image');
        $this->addSql('DROP TABLE __temp__carousel_image');
        $this->addSql('CREATE INDEX IDX_AABDD99C1CE5B98 ON carousel_image (carousel_id)');
    }
}

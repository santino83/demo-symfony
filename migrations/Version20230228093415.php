<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230228093415 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE carousel (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(50) NOT NULL, page VARCHAR(50) NOT NULL, position VARCHAR(50) NOT NULL, fade BOOLEAN NOT NULL, autoplay BOOLEAN NOT NULL, interval SMALLINT NOT NULL, indicators BOOLEAN NOT NULL, controls BOOLEAN NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL)');
        $this->addSql('CREATE TABLE carousel_image (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, carousel_id INTEGER NOT NULL, interval SMALLINT NOT NULL, image_path VARCHAR(255) NOT NULL, title VARCHAR(50) DEFAULT NULL, description VARCHAR(150) DEFAULT NULL, CONSTRAINT FK_AABDD99C1CE5B98 FOREIGN KEY (carousel_id) REFERENCES carousel (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_AABDD99C1CE5B98 ON carousel_image (carousel_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE carousel');
        $this->addSql('DROP TABLE carousel_image');
    }
}

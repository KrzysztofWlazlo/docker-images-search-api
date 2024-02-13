<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240213072116 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE docker_image (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE docker_tag (id INT AUTO_INCREMENT NOT NULL, tag_name VARCHAR(50) NOT NULL, status VARCHAR(50) NOT NULL, last_modified DATETIME NOT NULL, architecture VARCHAR(50) NOT NULL, os VARCHAR(50) NOT NULL, size DOUBLE PRECISION NOT NULL, image_id INT NOT NULL, INDEX IDX_DB04616D3DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE search_history (id INT AUTO_INCREMENT NOT NULL, image_name VARCHAR(255) NOT NULL, tag_name VARCHAR(255) DEFAULT NULL, searched_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE docker_tag ADD CONSTRAINT FK_DB04616D3DA5256D FOREIGN KEY (image_id) REFERENCES docker_image (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE docker_tag DROP FOREIGN KEY FK_DB04616D3DA5256D');
        $this->addSql('DROP TABLE docker_image');
        $this->addSql('DROP TABLE docker_tag');
        $this->addSql('DROP TABLE search_history');
    }
}

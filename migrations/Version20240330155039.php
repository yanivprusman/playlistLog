<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240330155039 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE video DROP INDEX UNIQ_7CC7DA2C6BBD148, ADD INDEX IDX_7CC7DA2C6BBD148 (playlist_id)');
        $this->addSql('ALTER TABLE video CHANGE playlist_id playlist_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2C6BBD148 FOREIGN KEY (playlist_id) REFERENCES playlist (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE video DROP INDEX IDX_7CC7DA2C6BBD148, ADD UNIQUE INDEX UNIQ_7CC7DA2C6BBD148 (playlist_id)');
        $this->addSql('ALTER TABLE video DROP FOREIGN KEY FK_7CC7DA2C6BBD148');
        $this->addSql('ALTER TABLE video CHANGE playlist_id playlist_id VARCHAR(255) NOT NULL');
    }
}

<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240330155646 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE video DROP FOREIGN KEY FK_7CC7DA2C6BBD148');
        $this->addSql('DROP INDEX IDX_7CC7DA2C6BBD148 ON video');
        $this->addSql('ALTER TABLE video CHANGE playlist_id playlist2_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2CBCD8F00E FOREIGN KEY (playlist2_id) REFERENCES playlist (id)');
        $this->addSql('CREATE INDEX IDX_7CC7DA2CBCD8F00E ON video (playlist2_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE video DROP FOREIGN KEY FK_7CC7DA2CBCD8F00E');
        $this->addSql('DROP INDEX IDX_7CC7DA2CBCD8F00E ON video');
        $this->addSql('ALTER TABLE video CHANGE playlist2_id playlist_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2C6BBD148 FOREIGN KEY (playlist_id) REFERENCES playlist (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_7CC7DA2C6BBD148 ON video (playlist_id)');
    }
}

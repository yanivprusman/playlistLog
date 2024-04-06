<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240401192718 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE video CHANGE seen seen TINYINT(1) DEFAULT NULL, CHANGE rewatch rewatch TINYINT(1) DEFAULT NULL, CHANGE paused_at paused_at VARCHAR(255) DEFAULT NULL, CHANGE length length VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE video CHANGE seen seen TINYINT(1) NOT NULL, CHANGE rewatch rewatch TINYINT(1) NOT NULL, CHANGE paused_at paused_at VARCHAR(255) NOT NULL, CHANGE length length VARCHAR(255) NOT NULL');
    }
}

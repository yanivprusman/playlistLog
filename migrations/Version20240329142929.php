<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240329142929 extends AbstractMigration
{
    private  $tables = [
        'playlist',
        'users',
        'video',
        // Add more table names as needed
    ];
    public function getDescription(): string
    {
        return 'delete all tables';
    }

    public function up(Schema $schema): void
    {
   
        foreach ($this->tables as $table) {
            $this->addSql(sprintf('DROP TABLE IF EXISTS %s;', $table));
        }   
    }

    public function down(Schema $schema): void
    {
        foreach ($this->tables as $table) {
            $this->addSql(sprintf('DROP TABLE IF EXISTS %s;', $table));
        }   
    }
}

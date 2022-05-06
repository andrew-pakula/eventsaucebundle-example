<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20220506183520 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql("
            CREATE TABLE foo_projection (
                id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid_binary)', 
                headers LONGTEXT NOT NULL, 
                value VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
        ");
    }
}

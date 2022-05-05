<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220505181051 extends AbstractMigration {

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE outbox (
          id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
          consumed TINYINT(1) DEFAULT 0 NOT NULL,
          payload VARCHAR(16001) NOT NULL,
          INDEX is_consumed (consumed, id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS `outbox`;');
    }
}
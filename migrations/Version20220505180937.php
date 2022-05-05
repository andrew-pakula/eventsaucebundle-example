<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220505180937 extends AbstractMigration {

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE bar_event_store (
          event_id BINARY(16) NOT NULL,
          aggregate_root_id BINARY(16) NOT NULL,
          version INT UNSIGNED DEFAULT NULL,
          payload VARCHAR(16001) NOT NULL,
          INDEX aggregate_root_id (aggregate_root_id),
          INDEX reconstitution (aggregate_root_id, version),
          PRIMARY KEY(event_id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE bar_outbox (
          id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
          consumed TINYINT(1) DEFAULT 0 NOT NULL,
          payload VARCHAR(16001) NOT NULL,
          INDEX is_consumed (consumed, id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE bar_snapshot (
          id INT UNSIGNED AUTO_INCREMENT NOT NULL,
          aggregate_root_id BINARY(16) NOT NULL,
          aggregate_root_version INT UNSIGNED NOT NULL,
          state VARCHAR(16001) NOT NULL,
          INDEX last_snapshot (
            aggregate_root_id, aggregate_root_version
          ),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS `bar_event_store`;');
        $this->addSql('DROP TABLE IF EXISTS `bar_outbox`;');
        $this->addSql('DROP TABLE IF EXISTS `bar_snapshot`;');
    }
}
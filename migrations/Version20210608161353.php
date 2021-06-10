<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210608161353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bus DROP CONSTRAINT fk_2f566b69a5bc2e0e');
        $this->addSql('DROP INDEX uniq_2f566b69a5bc2e0e');
        $this->addSql('ALTER TABLE bus DROP trip_id');
        $this->addSql('ALTER TABLE trip DROP CONSTRAINT fk_7656f53b2546731d');
        $this->addSql('DROP INDEX uniq_7656f53b2546731d');
        $this->addSql('ALTER TABLE trip DROP bus_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE trip ADD bus_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT fk_7656f53b2546731d FOREIGN KEY (bus_id) REFERENCES bus (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_7656f53b2546731d ON trip (bus_id)');
        $this->addSql('ALTER TABLE bus ADD trip_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bus ADD CONSTRAINT fk_2f566b69a5bc2e0e FOREIGN KEY (trip_id) REFERENCES trip (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_2f566b69a5bc2e0e ON bus (trip_id)');
    }
}

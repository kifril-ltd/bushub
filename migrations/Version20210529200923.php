<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210529200923 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE bus_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE route_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ticket_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE trip_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE bus (id INT NOT NULL, license_plate VARCHAR(20) NOT NULL, seats_number INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2F566B69F5AA79D0 ON bus (license_plate)');
        $this->addSql('CREATE TABLE route (id INT NOT NULL, creator_id INT NOT NULL, departure_point VARCHAR(255) NOT NULL, arrival_point VARCHAR(255) NOT NULL, length NUMERIC(10, 2) NOT NULL, cost_price NUMERIC(10, 2) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2C4207961220EA6 ON route (creator_id)');
        $this->addSql('CREATE TABLE ticket (id INT NOT NULL, passenger_id INT NOT NULL, seller_id INT DEFAULT NULL, trip_id INT NOT NULL, sale_datetime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, price NUMERIC(10, 2) NOT NULL, trip_date DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_97A0ADA34502E565 ON ticket (passenger_id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA38DE820D9 ON ticket (seller_id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA3A5BC2E0E ON ticket (trip_id)');
        $this->addSql('CREATE TABLE trip (id INT NOT NULL, route_id INT NOT NULL, bus_id INT NOT NULL, departure_time TIME(0) WITHOUT TIME ZONE NOT NULL, arrival_time TIME(0) WITHOUT TIME ZONE NOT NULL, regularity TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7656F53B34ECB4E6 ON trip (route_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7656F53B2546731D ON trip (bus_id)');
        $this->addSql('COMMENT ON COLUMN trip.regularity IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, patronymic VARCHAR(255) DEFAULT NULL, passport_number VARCHAR(20) NOT NULL, phone VARCHAR(20) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6494EF9AAC4 ON "user" (passport_number)');
        $this->addSql('ALTER TABLE route ADD CONSTRAINT FK_2C4207961220EA6 FOREIGN KEY (creator_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA34502E565 FOREIGN KEY (passenger_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA38DE820D9 FOREIGN KEY (seller_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3A5BC2E0E FOREIGN KEY (trip_id) REFERENCES trip (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53B34ECB4E6 FOREIGN KEY (route_id) REFERENCES route (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53B2546731D FOREIGN KEY (bus_id) REFERENCES bus (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE trip DROP CONSTRAINT FK_7656F53B2546731D');
        $this->addSql('ALTER TABLE trip DROP CONSTRAINT FK_7656F53B34ECB4E6');
        $this->addSql('ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA3A5BC2E0E');
        $this->addSql('ALTER TABLE route DROP CONSTRAINT FK_2C4207961220EA6');
        $this->addSql('ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA34502E565');
        $this->addSql('ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA38DE820D9');
        $this->addSql('DROP SEQUENCE bus_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE route_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ticket_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE trip_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP TABLE bus');
        $this->addSql('DROP TABLE route');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE trip');
        $this->addSql('DROP TABLE "user"');
    }
}

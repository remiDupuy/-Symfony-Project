<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180206100401 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE show_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE show (id INT NOT NULL, name VARCHAR(100) NOT NULL, author VARCHAR(100) NOT NULL, published_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, iso_country VARCHAR(2) NOT NULL, path_main_picture VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE categories_shows (show_id INT NOT NULL, category_id INT NOT NULL, PRIMARY KEY(show_id, category_id))');
        $this->addSql('CREATE INDEX IDX_6838E409D0C1FC64 ON categories_shows (show_id)');
        $this->addSql('CREATE INDEX IDX_6838E40912469DE2 ON categories_shows (category_id)');
        $this->addSql('CREATE TABLE category (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_64C19C15E237E06 ON category (name)');
        $this->addSql('ALTER TABLE categories_shows ADD CONSTRAINT FK_6838E409D0C1FC64 FOREIGN KEY (show_id) REFERENCES show (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE categories_shows ADD CONSTRAINT FK_6838E40912469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE categories_shows DROP CONSTRAINT FK_6838E409D0C1FC64');
        $this->addSql('ALTER TABLE categories_shows DROP CONSTRAINT FK_6838E40912469DE2');
        $this->addSql('DROP SEQUENCE show_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE category_id_seq CASCADE');
        $this->addSql('DROP TABLE show');
        $this->addSql('DROP TABLE categories_shows');
        $this->addSql('DROP TABLE category');
    }
}

<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180219152426 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE show ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE show DROP author');
        $this->addSql('ALTER TABLE show ADD CONSTRAINT FK_320ED901A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_320ED901A76ED395 ON show (user_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE show DROP CONSTRAINT FK_320ED901A76ED395');
        $this->addSql('DROP INDEX IDX_320ED901A76ED395');
        $this->addSql('ALTER TABLE show ADD author VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE show DROP user_id');
    }
}

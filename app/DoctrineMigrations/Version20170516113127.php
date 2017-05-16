<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170516113127 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE file ADD homework_id INT NOT NULL');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F3610B203DDE5 FOREIGN KEY (homework_id) REFERENCES homework (id)');
        $this->addSql('CREATE INDEX IDX_8C9F3610B203DDE5 ON file (homework_id)');
        $this->addSql('ALTER TABLE homework DROP attachment_name, DROP attachment_original_name');
        $this->addSql('ALTER TABLE module DROP attachment_name, DROP attachment_original_name');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F3610B203DDE5');
        $this->addSql('DROP INDEX IDX_8C9F3610B203DDE5 ON file');
        $this->addSql('ALTER TABLE file DROP homework_id');
        $this->addSql('ALTER TABLE homework ADD attachment_name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD attachment_original_name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE module ADD attachment_name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD attachment_original_name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}

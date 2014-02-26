<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20140222141528 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("ALTER TABLE companies ADD type_id INT DEFAULT NULL");
        $this->addSql("ALTER TABLE companies ADD CONSTRAINT FK_8244AA3AC54C8C93 FOREIGN KEY (type_id) REFERENCES company_types (id)");
        $this->addSql("CREATE INDEX IDX_8244AA3AC54C8C93 ON companies (type_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("ALTER TABLE companies DROP FOREIGN KEY FK_8244AA3AC54C8C93");
        $this->addSql("DROP INDEX IDX_8244AA3AC54C8C93 ON companies");
        $this->addSql("ALTER TABLE companies DROP type_id");
    }
}

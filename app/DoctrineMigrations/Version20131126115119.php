<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20131126115119 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("ALTER TABLE companies ADD registred_fully TINYINT(1) DEFAULT NULL, ADD registration_step VARCHAR(255) DEFAULT NULL, CHANGE name name VARCHAR(255) DEFAULT NULL, CHANGE address address VARCHAR(255) DEFAULT NULL, CHANGE createt_date createt_date DATE DEFAULT NULL, CHANGE visible visible TINYINT(1) DEFAULT NULL");
        $this->addSql("UPDATE `companies` SET `registred_fully` = TRUE");
        $this->addSql("ALTER TABLE company_gallery CHANGE visible visible TINYINT(1) DEFAULT '1'");
        $this->addSql("ALTER TABLE company_manager CHANGE phone phone VARCHAR(255) DEFAULT NULL");
    }

    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("ALTER TABLE companies DROP registred_fully, DROP registration_step, CHANGE name name VARCHAR(255) NOT NULL, CHANGE address address VARCHAR(255) NOT NULL, CHANGE createt_date createt_date DATE NOT NULL, CHANGE visible visible TINYINT(1) NOT NULL");
        $this->addSql("ALTER TABLE company_gallery CHANGE visible visible TINYINT(1) DEFAULT '1' NOT NULL");
        $this->addSql("ALTER TABLE company_manager CHANGE phone phone VARCHAR(255) NOT NULL");
    }
}
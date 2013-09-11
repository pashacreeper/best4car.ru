<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130910152533 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("DROP TABLE company_dictionary_company_type_children");
        $this->addSql("DROP TABLE company_dictionary_company_type_parrent");
    }

    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("CREATE TABLE company_dictionary_company_type_children (company_id INT NOT NULL, dictionary_id INT NOT NULL, INDEX IDX_D4EF96CF979B1AD6 (company_id), INDEX IDX_D4EF96CFAF5E5B3C (dictionary_id), PRIMARY KEY(company_id, dictionary_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE company_dictionary_company_type_parrent (company_id INT NOT NULL, dictionary_id INT NOT NULL, INDEX IDX_61B17979B1AD6 (company_id), INDEX IDX_61B17AF5E5B3C (dictionary_id), PRIMARY KEY(company_id, dictionary_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE company_dictionary_company_type_children ADD CONSTRAINT FK_D4EF96CF979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id)");
        $this->addSql("ALTER TABLE company_dictionary_company_type_children ADD CONSTRAINT FK_D4EF96CFAF5E5B3C FOREIGN KEY (dictionary_id) REFERENCES company_types (id)");
        $this->addSql("ALTER TABLE company_dictionary_company_type_parrent ADD CONSTRAINT FK_61B17979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id)");
        $this->addSql("ALTER TABLE company_dictionary_company_type_parrent ADD CONSTRAINT FK_61B17AF5E5B3C FOREIGN KEY (dictionary_id) REFERENCES company_types (id)");
    }
}
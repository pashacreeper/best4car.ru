<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130826191110 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("CREATE TABLE CompanyType (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, icon_name_map VARCHAR(255) DEFAULT NULL, icon_name_small VARCHAR(255) DEFAULT NULL, icon_name_large VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, short_name VARCHAR(15) DEFAULT NULL, name VARCHAR(255) NOT NULL, position INT DEFAULT NULL, INDEX IDX_E3E8EA52727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("INSERT INTO CompanyType (id, parent_id, icon_name_map, icon_name_small, icon_name_large, updated_at, short_name, name, position) SELECT id, parent_id, icon_name_map, icon_name_small, icon_name_large, updated_at, short_name, name, position FROM dictionaries WHERE discr = 'company_type'");
        $this->addSql("ALTER TABLE CompanyType ADD CONSTRAINT FK_E3E8EA52727ACA70 FOREIGN KEY (parent_id) REFERENCES CompanyType (id)");
        $this->addSql("ALTER TABLE company_dictionary_company_type_parrent DROP FOREIGN KEY FK_61B17AF5E5B3C");
        $this->addSql("ALTER TABLE company_dictionary_company_type_parrent ADD CONSTRAINT FK_61B17AF5E5B3C FOREIGN KEY (dictionary_id) REFERENCES CompanyType (id)");
        $this->addSql("ALTER TABLE company_dictionary_company_type_children DROP FOREIGN KEY FK_D4EF96CFAF5E5B3C");
        $this->addSql("ALTER TABLE company_dictionary_company_type_children ADD CONSTRAINT FK_D4EF96CFAF5E5B3C FOREIGN KEY (dictionary_id) REFERENCES CompanyType (id)");
        $this->addSql("ALTER TABLE companytype_autoservices DROP FOREIGN KEY FK_2D467E241E00F65");
        $this->addSql("ALTER TABLE companytype_autoservices ADD CONSTRAINT FK_2D467E241E00F65 FOREIGN KEY (companytype_id) REFERENCES CompanyType (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE company_type_auto_service DROP FOREIGN KEY FK_1006FC15E51E9644");
        $this->addSql("ALTER TABLE company_type_auto_service ADD CONSTRAINT FK_1006FC15E51E9644 FOREIGN KEY (company_type_id) REFERENCES CompanyType (id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("ALTER TABLE company_dictionary_company_type_parrent DROP FOREIGN KEY FK_61B17AF5E5B3C");
        $this->addSql("ALTER TABLE company_dictionary_company_type_children DROP FOREIGN KEY FK_D4EF96CFAF5E5B3C");
        $this->addSql("ALTER TABLE CompanyType DROP FOREIGN KEY FK_E3E8EA52727ACA70");
        $this->addSql("ALTER TABLE companytype_autoservices DROP FOREIGN KEY FK_2D467E241E00F65");
        $this->addSql("ALTER TABLE company_type_auto_service DROP FOREIGN KEY FK_1006FC15E51E9644");
        $this->addSql("DROP TABLE CompanyType");
        $this->addSql("ALTER TABLE company_dictionary_company_type_children DROP FOREIGN KEY FK_D4EF96CFAF5E5B3C");
        $this->addSql("ALTER TABLE company_dictionary_company_type_children ADD CONSTRAINT FK_D4EF96CFAF5E5B3C FOREIGN KEY (dictionary_id) REFERENCES dictionaries (id)");
        $this->addSql("ALTER TABLE company_dictionary_company_type_parrent DROP FOREIGN KEY FK_61B17AF5E5B3C");
        $this->addSql("ALTER TABLE company_dictionary_company_type_parrent ADD CONSTRAINT FK_61B17AF5E5B3C FOREIGN KEY (dictionary_id) REFERENCES dictionaries (id)");
        $this->addSql("ALTER TABLE company_type_auto_service DROP FOREIGN KEY FK_1006FC15E51E9644");
        $this->addSql("ALTER TABLE company_type_auto_service ADD CONSTRAINT FK_1006FC15E51E9644 FOREIGN KEY (company_type_id) REFERENCES dictionaries (id)");
        $this->addSql("ALTER TABLE companytype_autoservices DROP FOREIGN KEY FK_2D467E241E00F65");
        $this->addSql("ALTER TABLE companytype_autoservices ADD CONSTRAINT FK_2D467E241E00F65 FOREIGN KEY (companytype_id) REFERENCES dictionaries (id) ON DELETE CASCADE");
    }
}
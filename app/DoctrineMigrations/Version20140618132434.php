<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20140618132434 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("CREATE TABLE custom_modifications (id INT AUTO_INCREMENT NOT NULL, car_id INT DEFAULT NULL, engineType VARCHAR(255) DEFAULT NULL, engineModel VARCHAR(255) DEFAULT NULL, engineVolume INT DEFAULT NULL, enginePower INT DEFAULT NULL, fuelTypes LONGTEXT DEFAULT NULL COMMENT '(DC2Type:array)', wheelType VARCHAR(255) DEFAULT NULL, bodyType VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_F922562DC3C6F69F (car_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE custom_modifications ADD CONSTRAINT FK_F922562DC3C6F69F FOREIGN KEY (car_id) REFERENCES user_cars (id)");
        $this->addSql("ALTER TABLE user_cars ADD custom_modification_id INT DEFAULT NULL, DROP engineType, DROP engineModel, DROP engineVolume, DROP enginePower, DROP fuelTypes, DROP wheelType, DROP bodyType");
        $this->addSql("ALTER TABLE user_cars ADD CONSTRAINT FK_EF4651DD2C4C8FD8 FOREIGN KEY (custom_modification_id) REFERENCES custom_modifications (id)");
        $this->addSql("CREATE UNIQUE INDEX UNIQ_EF4651DD2C4C8FD8 ON user_cars (custom_modification_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("ALTER TABLE user_cars DROP FOREIGN KEY FK_EF4651DD2C4C8FD8");
        $this->addSql("DROP TABLE custom_modifications");
        $this->addSql("DROP INDEX UNIQ_EF4651DD2C4C8FD8 ON user_cars");
        $this->addSql("ALTER TABLE user_cars ADD engineType VARCHAR(255) DEFAULT NULL, ADD engineModel VARCHAR(255) DEFAULT NULL, ADD enginePower INT DEFAULT NULL, ADD fuelTypes LONGTEXT DEFAULT NULL COMMENT '(DC2Type:array)', ADD wheelType VARCHAR(255) DEFAULT NULL, ADD bodyType VARCHAR(255) DEFAULT NULL, CHANGE custom_modification_id engineVolume INT DEFAULT NULL");
    }
}

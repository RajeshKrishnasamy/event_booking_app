<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210203213252 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE app_user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_88BDF3E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employee_events (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, event_id_id INT DEFAULT NULL, entry VARCHAR(10) NOT NULL, INDEX IDX_7AB693DF9D86650F (user_id_id), INDEX IDX_7AB693DF3E5F2F7B (event_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, admin_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, description VARCHAR(255) NOT NULL, date DATETIME NOT NULL, start_time DATETIME NOT NULL, end_time DATETIME NOT NULL, seats INT NOT NULL, status INT NOT NULL, INDEX IDX_3BAE0AA7642B8210 (admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE employee_events ADD CONSTRAINT FK_7AB693DF9D86650F FOREIGN KEY (user_id_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE employee_events ADD CONSTRAINT FK_7AB693DF3E5F2F7B FOREIGN KEY (event_id_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7642B8210 FOREIGN KEY (admin_id) REFERENCES app_user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employee_events DROP FOREIGN KEY FK_7AB693DF9D86650F');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7642B8210');
        $this->addSql('ALTER TABLE employee_events DROP FOREIGN KEY FK_7AB693DF3E5F2F7B');
        $this->addSql('DROP TABLE app_user');
        $this->addSql('DROP TABLE employee_events');
        $this->addSql('DROP TABLE event');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250314101111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, price NUMERIC(10, 0) NOT NULL, duration INT NOT NULL, category VARCHAR(255) NOT NULL, max_participants INT NOT NULL, is_active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blog_post ADD blog_post_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE blog_post ADD CONSTRAINT FK_BA5AE01DA77FBEAF FOREIGN KEY (blog_post_id) REFERENCES blog_post (id)');
        $this->addSql('CREATE INDEX IDX_BA5AE01DA77FBEAF ON blog_post (blog_post_id)');
        $this->addSql('ALTER TABLE booking CHANGE user_id user_id INT NOT NULL, CHANGE schedule_id schedule_id INT NOT NULL');
        $this->addSql('ALTER TABLE schedule ADD service_id INT NOT NULL, ADD location_id INT NOT NULL, CHANGE start_time start_time TIME NOT NULL COMMENT \'(DC2Type:time_immutable)\', CHANGE end_time end_time TIME NOT NULL COMMENT \'(DC2Type:time_immutable)\'');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FBED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FB64D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('CREATE INDEX IDX_5A3811FBED5CA9E6 ON schedule (service_id)');
        $this->addSql('CREATE INDEX IDX_5A3811FB64D218E ON schedule (location_id)');
        $this->addSql('ALTER TABLE user ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD is_verified TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE schedule DROP FOREIGN KEY FK_5A3811FBED5CA9E6');
        $this->addSql('DROP TABLE service');
        $this->addSql('ALTER TABLE blog_post DROP FOREIGN KEY FK_BA5AE01DA77FBEAF');
        $this->addSql('DROP INDEX IDX_BA5AE01DA77FBEAF ON blog_post');
        $this->addSql('ALTER TABLE blog_post DROP blog_post_id');
        $this->addSql('ALTER TABLE booking CHANGE user_id user_id INT DEFAULT NULL, CHANGE schedule_id schedule_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE schedule DROP FOREIGN KEY FK_5A3811FB64D218E');
        $this->addSql('DROP INDEX IDX_5A3811FBED5CA9E6 ON schedule');
        $this->addSql('DROP INDEX IDX_5A3811FB64D218E ON schedule');
        $this->addSql('ALTER TABLE schedule DROP service_id, DROP location_id, CHANGE start_time start_time DATETIME NOT NULL, CHANGE end_time end_time DATETIME NOT NULL');
        $this->addSql('ALTER TABLE `user` DROP created_at, DROP is_verified');
    }
}

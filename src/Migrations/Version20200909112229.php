<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200909112229 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE v_admin (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', file_name VARCHAR(255) DEFAULT NULL, file_size INT DEFAULT NULL, file_mime_type VARCHAR(255) DEFAULT NULL, file_original_name VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, phone INT DEFAULT NULL, birth_day DATE DEFAULT NULL, created_at DATETIME DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, civilite VARCHAR(255) DEFAULT NULL, accept_condition TINYINT(1) DEFAULT NULL, username VARCHAR(255) DEFAULT NULL, usernameCanonical VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_A1207A26A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_A1207A26C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE v_user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', file_name VARCHAR(255) DEFAULT NULL, file_size INT DEFAULT NULL, file_mime_type VARCHAR(255) DEFAULT NULL, file_original_name VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, phone INT DEFAULT NULL, birth_day DATE DEFAULT NULL, created_at DATETIME DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, civilite VARCHAR(255) DEFAULT NULL, accept_condition TINYINT(1) DEFAULT NULL, username VARCHAR(255) DEFAULT NULL, usernameCanonical VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_7A2D1E30A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_7A2D1E30C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE v_review (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, note INT DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, enabled TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, position INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE v_settings (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) NOT NULL, fax VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, facebook_address VARCHAR(255) DEFAULT NULL, twitter_address VARCHAR(255) DEFAULT NULL, instagram_address VARCHAR(255) DEFAULT NULL, youtube_address VARCHAR(255) DEFAULT NULL, linkedin_address VARCHAR(255) DEFAULT NULL, active_voyance TINYINT(1) DEFAULT NULL, active_soins TINYINT(1) DEFAULT NULL, active_temoignages TINYINT(1) DEFAULT NULL, active_shop TINYINT(1) DEFAULT NULL, active_blog TINYINT(1) DEFAULT NULL, active_rencontre TINYINT(1) DEFAULT NULL, file_name VARCHAR(255) DEFAULT NULL, file_size INT DEFAULT NULL, file_mime_type VARCHAR(255) DEFAULT NULL, file_original_name VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE v_admin');
        $this->addSql('DROP TABLE v_user');
        $this->addSql('DROP TABLE v_review');
        $this->addSql('DROP TABLE v_settings');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240515063859 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (uuid UUID NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, text TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, user_uuid UUID NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_23A0E6677153098 ON article (code)');
        $this->addSql('CREATE TABLE category (uuid UUID NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_64C19C177153098 ON category (code)');
        $this->addSql('CREATE TABLE file (uuid UUID NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, size INT NOT NULL, product_uuid UUID NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE TABLE gallery (uuid UUID NOT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, size INT NOT NULL, product_uuid UUID NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE TABLE product (uuid UUID NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, preview_image VARCHAR(255) NOT NULL, description TEXT NOT NULL, price DOUBLE PRECISION NOT NULL, category_uuid UUID NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04AD77153098 ON product (code)');
        $this->addSql('CREATE TABLE user_favorite (user_uuid UUID NOT NULL, product_uuid UUID NOT NULL, PRIMARY KEY(user_uuid, product_uuid))');
        $this->addSql('CREATE INDEX IDX_88486AD9ABFE1C6F ON user_favorite (user_uuid)');
        $this->addSql('CREATE INDEX IDX_88486AD95C977207 ON user_favorite (product_uuid)');
        $this->addSql('ALTER TABLE user_favorite ADD CONSTRAINT FK_88486AD9ABFE1C6F FOREIGN KEY (user_uuid) REFERENCES "user" (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_favorite ADD CONSTRAINT FK_88486AD95C977207 FOREIGN KEY (product_uuid) REFERENCES product (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_favorite DROP CONSTRAINT FK_88486AD9ABFE1C6F');
        $this->addSql('ALTER TABLE user_favorite DROP CONSTRAINT FK_88486AD95C977207');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE gallery');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE user_favorite');
    }
}

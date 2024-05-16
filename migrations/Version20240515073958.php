<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240515073958 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article_product (product_uuid UUID NOT NULL, article_uuid UUID NOT NULL, PRIMARY KEY(product_uuid, article_uuid))');
        $this->addSql('CREATE INDEX IDX_3E98401A5C977207 ON article_product (product_uuid)');
        $this->addSql('CREATE INDEX IDX_3E98401A613DD7A7 ON article_product (article_uuid)');
        $this->addSql('ALTER TABLE article_product ADD CONSTRAINT FK_3E98401A5C977207 FOREIGN KEY (product_uuid) REFERENCES product (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE article_product ADD CONSTRAINT FK_3E98401A613DD7A7 FOREIGN KEY (article_uuid) REFERENCES article (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product ADD category_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE product DROP category_uuid');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D34A04AD12469DE2 ON product (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE article_product DROP CONSTRAINT FK_3E98401A5C977207');
        $this->addSql('ALTER TABLE article_product DROP CONSTRAINT FK_3E98401A613DD7A7');
        $this->addSql('DROP TABLE article_product');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT FK_D34A04AD12469DE2');
        $this->addSql('DROP INDEX IDX_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE product ADD category_uuid UUID NOT NULL');
        $this->addSql('ALTER TABLE product DROP category_id');
    }
}

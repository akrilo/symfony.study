<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240515082352 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ALTER user_uuid TYPE UUID');
        $this->addSql('ALTER TABLE article ALTER user_uuid DROP NOT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66ABFE1C6F FOREIGN KEY (user_uuid) REFERENCES "user" (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_23A0E66ABFE1C6F ON article (user_uuid)');
        $this->addSql('ALTER TABLE file ALTER product_uuid TYPE UUID');
        $this->addSql('ALTER TABLE file ALTER product_uuid DROP NOT NULL');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F36105C977207 FOREIGN KEY (product_uuid) REFERENCES product (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8C9F36105C977207 ON file (product_uuid)');
        $this->addSql('ALTER TABLE gallery ALTER product_uuid TYPE UUID');
        $this->addSql('ALTER TABLE gallery ALTER product_uuid DROP NOT NULL');
        $this->addSql('ALTER TABLE gallery ADD CONSTRAINT FK_472B783A5C977207 FOREIGN KEY (product_uuid) REFERENCES product (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_472B783A5C977207 ON gallery (product_uuid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE gallery DROP CONSTRAINT FK_472B783A5C977207');
        $this->addSql('DROP INDEX IDX_472B783A5C977207');
        $this->addSql('ALTER TABLE gallery ALTER product_uuid TYPE UUID');
        $this->addSql('ALTER TABLE gallery ALTER product_uuid SET NOT NULL');
        $this->addSql('ALTER TABLE article DROP CONSTRAINT FK_23A0E66ABFE1C6F');
        $this->addSql('DROP INDEX IDX_23A0E66ABFE1C6F');
        $this->addSql('ALTER TABLE article ALTER user_uuid TYPE UUID');
        $this->addSql('ALTER TABLE article ALTER user_uuid SET NOT NULL');
        $this->addSql('ALTER TABLE file DROP CONSTRAINT FK_8C9F36105C977207');
        $this->addSql('DROP INDEX IDX_8C9F36105C977207');
        $this->addSql('ALTER TABLE file ALTER product_uuid TYPE UUID');
        $this->addSql('ALTER TABLE file ALTER product_uuid SET NOT NULL');
    }
}

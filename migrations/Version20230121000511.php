<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230121000511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE banco ADD created_by_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE banco ADD CONSTRAINT FK_77DEE1D17D182D95 FOREIGN KEY (created_by_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_77DEE1D17D182D95 ON banco (created_by_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE banco DROP FOREIGN KEY FK_77DEE1D17D182D95');
        $this->addSql('DROP INDEX IDX_77DEE1D17D182D95 ON banco');
        $this->addSql('ALTER TABLE banco DROP created_by_user_id');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230121142910 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agencia ADD created_by_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE agencia ADD CONSTRAINT FK_EB6C2B997D182D95 FOREIGN KEY (created_by_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_EB6C2B997D182D95 ON agencia (created_by_user_id)');
        $this->addSql('ALTER TABLE conta ADD banco VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conta DROP banco');
        $this->addSql('ALTER TABLE agencia DROP FOREIGN KEY FK_EB6C2B997D182D95');
        $this->addSql('DROP INDEX IDX_EB6C2B997D182D95 ON agencia');
        $this->addSql('ALTER TABLE agencia DROP created_by_user_id');
    }
}

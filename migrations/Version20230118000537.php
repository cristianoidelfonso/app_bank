<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230118000537 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agencia ADD banco_id INT NOT NULL');
        $this->addSql('ALTER TABLE agencia ADD CONSTRAINT FK_EB6C2B99CC04A73E FOREIGN KEY (banco_id) REFERENCES banco (id)');
        $this->addSql('CREATE INDEX IDX_EB6C2B99CC04A73E ON agencia (banco_id)');
        $this->addSql('ALTER TABLE gerente ADD agencia_id INT NOT NULL');
        $this->addSql('ALTER TABLE gerente ADD CONSTRAINT FK_306C486DA6F796BE FOREIGN KEY (agencia_id) REFERENCES agencia (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_306C486DA6F796BE ON gerente (agencia_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agencia DROP FOREIGN KEY FK_EB6C2B99CC04A73E');
        $this->addSql('DROP INDEX IDX_EB6C2B99CC04A73E ON agencia');
        $this->addSql('ALTER TABLE agencia DROP banco_id');
        $this->addSql('ALTER TABLE gerente DROP FOREIGN KEY FK_306C486DA6F796BE');
        $this->addSql('DROP INDEX UNIQ_306C486DA6F796BE ON gerente');
        $this->addSql('ALTER TABLE gerente DROP agencia_id');
    }
}

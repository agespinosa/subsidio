<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210222150111 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE excel_ingreso ADD punta_caja_pago_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE excel_ingreso ADD CONSTRAINT FK_A7B08F73A5787AC8 FOREIGN KEY (punta_caja_pago_id) REFERENCES punta_caja_pago (id)');
        $this->addSql('CREATE INDEX IDX_A7B08F73A5787AC8 ON excel_ingreso (punta_caja_pago_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE excel_ingreso DROP FOREIGN KEY FK_A7B08F73A5787AC8');
        $this->addSql('DROP INDEX IDX_A7B08F73A5787AC8 ON excel_ingreso');
        $this->addSql('ALTER TABLE excel_ingreso DROP punta_caja_pago_id');
    }
}

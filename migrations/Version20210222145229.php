<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210222145229 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE excel_ingreso ADD localidad VARCHAR(255) DEFAULT NULL, ADD nodo VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE punta_caja_pago ADD file_excel_original_path VARCHAR(255) DEFAULT NULL, ADD file_excel_original_name VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE excel_ingreso DROP localidad, DROP nodo');
        $this->addSql('ALTER TABLE punta_caja_pago DROP file_excel_original_path, DROP file_excel_original_name');
    }
}

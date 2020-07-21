<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200721141302 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE subsidio_pago_proveedores ADD cuenta_bancaria_beneficiario INT DEFAULT NULL, CHANGE registro_id registro_id VARCHAR(2) DEFAULT NULL, CHANGE tipo_pago tipo_pago VARCHAR(3) DEFAULT NULL, CHANGE referencia_cliente referencia_cliente VARCHAR(16) DEFAULT NULL, CHANGE importe_apagar importe_apagar NUMERIC(15, 2) DEFAULT NULL, CHANGE moneda_pago moneda_pago VARCHAR(3) DEFAULT NULL, CHANGE fecha_ejecucion_pago fecha_ejecucion_pago DATE DEFAULT NULL, CHANGE numero_proveedor numero_proveedor VARCHAR(18) DEFAULT NULL, CHANGE nombre_beneficiario nombre_beneficiario VARCHAR(60) DEFAULT NULL, CHANGE cuit cuit VARCHAR(11) DEFAULT NULL, CHANGE domicilio domicilio VARCHAR(120) DEFAULT NULL, CHANGE moneda_cuenta moneda_cuenta VARCHAR(3) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE subsidio_pago_proveedores DROP cuenta_bancaria_beneficiario, CHANGE registro_id registro_id VARCHAR(2) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE tipo_pago tipo_pago VARCHAR(3) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE referencia_cliente referencia_cliente VARCHAR(5) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE importe_apagar importe_apagar NUMERIC(10, 2) NOT NULL, CHANGE moneda_pago moneda_pago VARCHAR(3) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE fecha_ejecucion_pago fecha_ejecucion_pago DATE NOT NULL, CHANGE numero_proveedor numero_proveedor VARCHAR(47) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE nombre_beneficiario nombre_beneficiario VARCHAR(65) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE cuit cuit VARCHAR(11) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE domicilio domicilio VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE moneda_cuenta moneda_cuenta VARCHAR(3) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}

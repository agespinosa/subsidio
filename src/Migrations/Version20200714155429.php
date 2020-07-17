<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200714155429 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE excel_ingreso (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(100) NOT NULL, apellido VARCHAR(100) NOT NULL, dni INT NOT NULL, cuit INT NOT NULL, regimen_iva VARCHAR(50) DEFAULT NULL, categoria_iva VARCHAR(2) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, cbu VARCHAR(255) DEFAULT NULL, tipo_cuenta VARCHAR(100) DEFAULT NULL, banco VARCHAR(100) DEFAULT NULL, monto NUMERIC(10, 2) NOT NULL, rubro VARCHAR(100) DEFAULT NULL, numero_cuenta_bancaria VARCHAR(255) DEFAULT NULL, estado VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE totales (id INT AUTO_INCREMENT NOT NULL, registro_id VARCHAR(2) NOT NULL, total_apagar NUMERIC(23, 2) NOT NULL, total_registros INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subsidio_pago_proveedores (id INT AUTO_INCREMENT NOT NULL, cabecera_id INT DEFAULT NULL, excel_ingreso_id INT DEFAULT NULL, totales_id INT DEFAULT NULL, registro_id VARCHAR(2) NOT NULL, tipo_pago VARCHAR(3) NOT NULL, referencia_cliente VARCHAR(5) NOT NULL, importe_apagar NUMERIC(10, 2) NOT NULL, moneda_pago VARCHAR(3) NOT NULL, fecha_ejecucion_pago DATE NOT NULL, numero_proveedor VARCHAR(47) DEFAULT NULL, nombre_beneficiario VARCHAR(65) NOT NULL, cuit VARCHAR(11) NOT NULL, domicilio VARCHAR(255) DEFAULT NULL, localidad VARCHAR(35) DEFAULT NULL, codigo_postal VARCHAR(15) DEFAULT NULL, medio_comunicacion VARCHAR(150) DEFAULT NULL, banco VARCHAR(5) DEFAULT NULL, sucursal VARCHAR(35) DEFAULT NULL, tipo_cuenta VARCHAR(2) DEFAULT NULL, moneda_cuenta VARCHAR(3) NOT NULL, numero_cuenta VARCHAR(22) DEFAULT NULL, forma_entrega_cheque VARCHAR(3) DEFAULT NULL, sucursal_prestador VARCHAR(8) DEFAULT NULL, moneda_cuenta_debito VARCHAR(3) DEFAULT NULL, cuenta_debito VARCHAR(17) DEFAULT NULL, motivo_pago VARCHAR(105) DEFAULT NULL, indicaciones_adicionales VARCHAR(80) DEFAULT NULL, requiere_recibo_impreso VARCHAR(1) DEFAULT NULL, con_recurso VARCHAR(1) DEFAULT NULL, numero_instrumento_pago VARCHAR(15) DEFAULT NULL, codigo_novedad_orden INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_2F82DCA9DE5DE1C (cabecera_id), INDEX IDX_2F82DCA9478C2496 (excel_ingreso_id), INDEX IDX_2F82DCA95447FF96 (totales_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cabecera (id INT AUTO_INCREMENT NOT NULL, registro_id VARCHAR(2) NOT NULL, fecha_creacion_archivo DATE NOT NULL, hora_creacion_archivo TIME NOT NULL, numero_archivo INT NOT NULL, numero_cliente INT NOT NULL, identificacion_archivo VARCHAR(26) NOT NULL, fecha_habil_procesamiento DATE NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE subsidio_pago_proveedores ADD CONSTRAINT FK_2F82DCA9DE5DE1C FOREIGN KEY (cabecera_id) REFERENCES cabecera (id)');
        $this->addSql('ALTER TABLE subsidio_pago_proveedores ADD CONSTRAINT FK_2F82DCA9478C2496 FOREIGN KEY (excel_ingreso_id) REFERENCES excel_ingreso (id)');
        $this->addSql('ALTER TABLE subsidio_pago_proveedores ADD CONSTRAINT FK_2F82DCA95447FF96 FOREIGN KEY (totales_id) REFERENCES totales (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE subsidio_pago_proveedores DROP FOREIGN KEY FK_2F82DCA9478C2496');
        $this->addSql('ALTER TABLE subsidio_pago_proveedores DROP FOREIGN KEY FK_2F82DCA95447FF96');
        $this->addSql('ALTER TABLE subsidio_pago_proveedores DROP FOREIGN KEY FK_2F82DCA9DE5DE1C');
        $this->addSql('DROP TABLE excel_ingreso');
        $this->addSql('DROP TABLE totales');
        $this->addSql('DROP TABLE subsidio_pago_proveedores');
        $this->addSql('DROP TABLE cabecera');
    }
}
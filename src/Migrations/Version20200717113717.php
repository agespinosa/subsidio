<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200717113717 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE establecimiento DROP FOREIGN KEY FK_94A4D17E53C8D32C');
        $this->addSql('DROP TABLE acta');
        $this->addSql('DROP TABLE establecimiento');
        $this->addSql('DROP TABLE propietario');
        $this->addSql('ALTER TABLE excel_ingreso ADD requisito_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE excel_ingreso ADD CONSTRAINT FK_A7B08F73FA50198E FOREIGN KEY (requisito_id) REFERENCES requisito (id)');
        $this->addSql('CREATE INDEX IDX_A7B08F73FA50198E ON excel_ingreso (requisito_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE acta (id INT AUTO_INCREMENT NOT NULL, sistematica TINYINT(1) NOT NULL, bovinos_vacunados_contra_fiebre_aftosa_vacas INT NOT NULL, vacuna_anti_aftosa_marca VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, vacuna_anti_aftosa_vencimiento DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE establecimiento (id INT AUTO_INCREMENT NOT NULL, propietario_id INT NOT NULL, nombre VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, cantidad_hectareas INT NOT NULL, is_deleted TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_94A4D17E53C8D32C (propietario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE propietario (id INT AUTO_INCREMENT NOT NULL, renspa VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, razon_social VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, domicilio VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, codigo_postal VARCHAR(10) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, telefono VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, cuit VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, slug VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE establecimiento ADD CONSTRAINT FK_94A4D17E53C8D32C FOREIGN KEY (propietario_id) REFERENCES propietario (id)');
        $this->addSql('ALTER TABLE excel_ingreso DROP FOREIGN KEY FK_A7B08F73FA50198E');
        $this->addSql('DROP INDEX IDX_A7B08F73FA50198E ON excel_ingreso');
        $this->addSql('ALTER TABLE excel_ingreso DROP requisito_id');
    }
}

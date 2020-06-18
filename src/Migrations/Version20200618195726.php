<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200618195726 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE acta (id INT AUTO_INCREMENT NOT NULL, sistematica TINYINT(1) NOT NULL, bovinos_vacunados_contra_fiebre_aftosa_vacas INT NOT NULL, vacuna_anti_aftosa_marca VARCHAR(255) DEFAULT NULL, vacuna_anti_aftosa_vencimiento DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE propietario (id INT AUTO_INCREMENT NOT NULL, renspa VARCHAR(50) NOT NULL, razon_social VARCHAR(255) NOT NULL, domicilio VARCHAR(255) DEFAULT NULL, codigo_postal VARCHAR(10) NOT NULL, telefono VARCHAR(50) DEFAULT NULL, cuit VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE regimen_tenencia (id INT AUTO_INCREMENT NOT NULL, tipo VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_DD5320B6702D1D47 (tipo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('UPDATE propietario SET created_at = NOW(), updated_at = NOW()');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE acta');
        $this->addSql('DROP TABLE propietario');
        $this->addSql('DROP TABLE regimen_tenencia');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200714130224 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', firstname VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE establecimiento (id INT AUTO_INCREMENT NOT NULL, propietario_id INT NOT NULL, nombre VARCHAR(255) NOT NULL, cantidad_hectareas INT NOT NULL, is_deleted TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_94A4D17E53C8D32C (propietario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE propietario (id INT AUTO_INCREMENT NOT NULL, renspa VARCHAR(50) NOT NULL, razon_social VARCHAR(255) NOT NULL, domicilio VARCHAR(255) DEFAULT NULL, codigo_postal VARCHAR(10) NOT NULL, telefono VARCHAR(50) DEFAULT NULL, cuit VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE acta (id INT AUTO_INCREMENT NOT NULL, sistematica TINYINT(1) NOT NULL, bovinos_vacunados_contra_fiebre_aftosa_vacas INT NOT NULL, vacuna_anti_aftosa_marca VARCHAR(255) DEFAULT NULL, vacuna_anti_aftosa_vencimiento DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE api_token (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, token VARCHAR(255) NOT NULL, expires_at DATETIME NOT NULL, INDEX IDX_7BA2F5EBA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE establecimiento ADD CONSTRAINT FK_94A4D17E53C8D32C FOREIGN KEY (propietario_id) REFERENCES propietario (id)');
        $this->addSql('ALTER TABLE api_token ADD CONSTRAINT FK_7BA2F5EBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE api_token DROP FOREIGN KEY FK_7BA2F5EBA76ED395');
        $this->addSql('ALTER TABLE establecimiento DROP FOREIGN KEY FK_94A4D17E53C8D32C');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE establecimiento');
        $this->addSql('DROP TABLE propietario');
        $this->addSql('DROP TABLE acta');
        $this->addSql('DROP TABLE api_token');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200717131524 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE subsidio_pago_proveedores DROP FOREIGN KEY FK_2F82DCA9478C2496');
        $this->addSql('DROP INDEX IDX_2F82DCA9478C2496 ON subsidio_pago_proveedores');
        $this->addSql('ALTER TABLE subsidio_pago_proveedores CHANGE excel_ingreso_id requsito_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE subsidio_pago_proveedores ADD CONSTRAINT FK_2F82DCA9FD03F4D4 FOREIGN KEY (requsito_id) REFERENCES requisito (id)');
        $this->addSql('CREATE INDEX IDX_2F82DCA9FD03F4D4 ON subsidio_pago_proveedores (requsito_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE subsidio_pago_proveedores DROP FOREIGN KEY FK_2F82DCA9FD03F4D4');
        $this->addSql('DROP INDEX IDX_2F82DCA9FD03F4D4 ON subsidio_pago_proveedores');
        $this->addSql('ALTER TABLE subsidio_pago_proveedores CHANGE requsito_id excel_ingreso_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE subsidio_pago_proveedores ADD CONSTRAINT FK_2F82DCA9478C2496 FOREIGN KEY (excel_ingreso_id) REFERENCES excel_ingreso (id)');
        $this->addSql('CREATE INDEX IDX_2F82DCA9478C2496 ON subsidio_pago_proveedores (excel_ingreso_id)');
    }
}

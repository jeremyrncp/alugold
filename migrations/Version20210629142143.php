<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210629142143 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE proposition (id INT AUTO_INCREMENT NOT NULL, vendor_id INT DEFAULT NULL, amount INT NOT NULL, shipping_fees INT NOT NULL, shipping_fees_discount INT DEFAULT NULL, vendor_cost INT NOT NULL, discount_rate INT NOT NULL, ref_customer VARCHAR(255) NOT NULL, INDEX IDX_C7CDC353F603EE73 (vendor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sale (id INT AUTO_INCREMENT NOT NULL, proposition_id INT NOT NULL, accepted_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', bonification_rate DOUBLE PRECISION NOT NULL, commission_rate DOUBLE PRECISION NOT NULL, goal INT NOT NULL, UNIQUE INDEX UNIQ_E54BC005DB96F9E (proposition_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE proposition ADD CONSTRAINT FK_C7CDC353F603EE73 FOREIGN KEY (vendor_id) REFERENCES vendor (id)');
        $this->addSql('ALTER TABLE sale ADD CONSTRAINT FK_E54BC005DB96F9E FOREIGN KEY (proposition_id) REFERENCES proposition (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sale DROP FOREIGN KEY FK_E54BC005DB96F9E');
        $this->addSql('DROP TABLE proposition');
        $this->addSql('DROP TABLE sale');
    }
}

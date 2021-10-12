<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210629122908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE discount (id INT AUTO_INCREMENT NOT NULL, minimalamount INT NOT NULL, maximumrate INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vendor (id INT AUTO_INCREMENT NOT NULL, discounts_id INT NOT NULL, email VARCHAR(200) NOT NULL, password VARCHAR(200) NOT NULL, name VARCHAR(200) NOT NULL, firstname VARCHAR(200) NOT NULL, department VARCHAR(2) NOT NULL, goal INT NOT NULL, commission INT NOT NULL, INDEX IDX_F52233F66A35CCB1 (discounts_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vendor ADD CONSTRAINT FK_F52233F66A35CCB1 FOREIGN KEY (discounts_id) REFERENCES discount (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vendor DROP FOREIGN KEY FK_F52233F66A35CCB1');
        $this->addSql('DROP TABLE discount');
        $this->addSql('DROP TABLE vendor');
    }
}

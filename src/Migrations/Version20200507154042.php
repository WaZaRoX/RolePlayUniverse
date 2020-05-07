<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200507154042 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE parent_enfant (personnage_source INT NOT NULL, personnage_target INT NOT NULL, INDEX IDX_7BA4CF64992CF5CD (personnage_source), INDEX IDX_7BA4CF6480C9A542 (personnage_target), PRIMARY KEY(personnage_source, personnage_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE parent_enfant ADD CONSTRAINT FK_7BA4CF64992CF5CD FOREIGN KEY (personnage_source) REFERENCES personnage (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE parent_enfant ADD CONSTRAINT FK_7BA4CF6480C9A542 FOREIGN KEY (personnage_target) REFERENCES personnage (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE community CHANGE statut_id statut_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE personnage CHANGE user_id user_id INT DEFAULT NULL, CHANGE nom nom VARCHAR(100) DEFAULT NULL, CHANGE prenom prenom VARCHAR(100) DEFAULT NULL, CHANGE date_naissance date_naissance DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE universe CHANGE short_resume short_resume VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE nom nom VARCHAR(80) DEFAULT NULL, CHANGE prenom prenom VARCHAR(80) DEFAULT NULL, CHANGE num_tel num_tel VARCHAR(20) DEFAULT NULL, CHANGE email email VARCHAR(200) DEFAULT NULL, CHANGE date_naissance date_naissance DATE DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE parent_enfant');
        $this->addSql('ALTER TABLE community CHANGE statut_id statut_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE personnage CHANGE user_id user_id INT DEFAULT NULL, CHANGE nom nom VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE prenom prenom VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE date_naissance date_naissance DATE DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE universe CHANGE short_resume short_resume VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE nom nom VARCHAR(80) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE prenom prenom VARCHAR(80) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE num_tel num_tel VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(200) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE date_naissance date_naissance DATE DEFAULT \'NULL\'');
    }
}

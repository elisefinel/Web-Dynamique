-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 30 mai 2024 à 10:57
-- Version du serveur : 8.0.31
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `medicare`
--


DROP TABLE IF EXISTS `service`;
DROP TABLE IF EXISTS `labo`;

CREATE TABLE IF NOT EXISTS `labo` (
  `Id_Labo` BIGINT NOT NULL AUTO_INCREMENT,
  `Nom` varchar(255) NOT NULL,
  `Salle` varchar(255) NOT NULL,
  `Adresse_Ligne_1` varchar(255) DEFAULT NULL,
  `Adresse_Ligne_2` varchar(255) DEFAULT NULL,
  `Ville` varchar(255) DEFAULT NULL,
  `Code_Postal` varchar(20) DEFAULT NULL,
  `Pays` varchar(100) DEFAULT NULL,
  `Num_Telephone` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`Id_Labo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `service` (
  `Id_Service` BIGINT NOT NULL AUTO_INCREMENT,
  `Id_Labo` BIGINT NOT NULL,
  `Salle` varchar(255) NOT NULL,
  `Nom_service` varchar(255) NOT NULL,
  `Tarif` DECIMAL(10, 2) NOT NULL,
  PRIMARY KEY (`Id_Service`),
  CONSTRAINT `fk_service_labo`
    FOREIGN KEY (`Id_Labo`) REFERENCES `labo`(`Id_Labo`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `message`;
DROP TABLE IF EXISTS `rdv`;
DROP TABLE IF EXISTS `medecin`;
DROP TABLE IF EXISTS `patient`;
DROP TABLE IF EXISTS `utilisateur`;

CREATE TABLE IF NOT EXISTS `utilisateur` (
  `Id_U` BIGINT NOT NULL AUTO_INCREMENT,
  `Nom` VARCHAR(255) NOT NULL,
  `Prenom` VARCHAR(255) NOT NULL,
  `Email` VARCHAR(255) NOT NULL,
  `Mdp` CHAR(60) NOT NULL,
  `Type` ENUM('A', 'P', 'M') NOT NULL,
  `Id_texto` VARCHAR(255) DEFAULT NULL,
  `Id_audio` VARCHAR(255) DEFAULT NULL,
  `Id_video` VARCHAR(255) DEFAULT NULL,
  `Date_creation` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id_U`),
  UNIQUE KEY `unique_email` (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS `medecin` (
  `Id_U` BIGINT NOT NULL,
  `Num_identification` varchar(255) NOT NULL,
  `Spe` ENUM('Medecin Generaliste', 'Addictologie', 'Andrologie', 'Cardiologie', 'Dermatologie', 'Gastro-Hepato-Enterologie', 'Gynecologie', 'I.S.T.', 'Osteopathie') NOT NULL,
  `Tarif` DECIMAL(10, 2) NOT NULL,
  `CV` BLOB NOT NULL,
  `Photo` BLOB NOT NULL,
  `Photo2` BLOB NOT NULL,
  `Video` BLOB NOT NULL,
  `Coordonnes` varchar(255) NOT NULL,
  `Repertoire` varchar(255) NOT NULL,
  PRIMARY KEY (`Id_U`),
  CONSTRAINT `fk_medecin_utilisateur`
    FOREIGN KEY (`Id_U`) REFERENCES `utilisateur`(`Id_U`)
    ON DELETE CASCADE,
  UNIQUE KEY `unique_num_id` (`Num_identification`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `patient` (
  `Id_U` BIGINT NOT NULL,
  `Num_vital` varchar(255) NOT NULL,
  `Dossier` varchar(255) NOT NULL,
  `Adresse_Ligne_1` varchar(255) DEFAULT NULL,
  `Adresse_Ligne_2` varchar(255) DEFAULT NULL,
  `Ville` varchar(255) DEFAULT NULL,
  `Code_Postal` varchar(20) DEFAULT NULL,
  `Pays` varchar(100) DEFAULT NULL,
  `Num_Telephone` varchar(20) DEFAULT NULL,
  `Type_Carte_Paiement` ENUM('Visa', 'MasterCard', 'American Express', 'PayPal') DEFAULT NULL,
  `Num_Carte` varchar(20) DEFAULT NULL,
  `Nom_Carte` varchar(255) DEFAULT NULL,
  `Date_Expiration_Carte` DATE DEFAULT NULL,
  `Code_Securite` CHAR(4) DEFAULT NULL,
  PRIMARY KEY (`Id_U`),
  CONSTRAINT `fk_patient_utilisateur`
    FOREIGN KEY (`Id_U`) REFERENCES `utilisateur`(`Id_U`)
    ON DELETE CASCADE,
  UNIQUE KEY `unique_num_vital` (`Num_vital`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `rdv` (
  `Id_rdv` BIGINT NOT NULL AUTO_INCREMENT,
  `Id_pat` BIGINT NOT NULL,
  `Id_med` BIGINT NOT NULL,
  `Date_Heure` DATETIME NOT NULL,
  `Statut` ENUM('annulé', 'valide') NOT NULL,
  `Tarif` DECIMAL(10, 2) NOT NULL,
  `Informations` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Id_rdv`),
  CONSTRAINT `fk_rdv_patient`
    FOREIGN KEY (`Id_pat`) REFERENCES `patient`(`Id_U`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_rdv_medecin`
    FOREIGN KEY (`Id_med`) REFERENCES `medecin`(`Id_U`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `message` (
  `Id_message` BIGINT NOT NULL AUTO_INCREMENT,
  `Id_expediteur` BIGINT NOT NULL,
  `Id_destinataire` BIGINT NOT NULL,
  `Contenu` varchar(255) NOT NULL,
  `Date_Heure` DATETIME NOT NULL,
  `Statut` ENUM('Lu', 'Pas lu') NOT NULL,
  PRIMARY KEY (`Id_message`),
  CONSTRAINT `fk_message_expediteur`
    FOREIGN KEY (`Id_expediteur`) REFERENCES `utilisateur`(`Id_U`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_message_destinataire`
    FOREIGN KEY (`Id_destinataire`) REFERENCES `utilisateur`(`Id_U`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


COMMIT;


INSERT INTO `utilisateur` (`Id_U`, `Nom`, `Prenom`, `Email`, `Mdp`, `Type`, `Id_texto`, `Id_audio`, `Id_video`, `Date_creation`) VALUES
(2, 'Admin', 'Admin', 'a@admin.com', '$2y$10$19SyBTIkNLt5Iv3RjLhWUOx/AfXzE1aQ/LTVYZDVve9.t174NvdZO', 'A', NULL, NULL, NULL, '2024-06-01 19:10:23');
COMMIT;



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

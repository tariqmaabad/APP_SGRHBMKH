-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : sam. 26 avr. 2025 à 13:53
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `sgrhbmkh_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories_etablissements`
--

CREATE TABLE `categories_etablissements` (
  `id` int(11) NOT NULL,
  `nom_categorie` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categories_etablissements`
--

INSERT INTO `categories_etablissements` (`id`, `nom_categorie`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Hôpital Provincial', 'Établissement hospitalier au niveau provincial', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(2, 'Centre de Santé Urbain', 'Centre de santé en milieu urbain', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(3, 'Centre de Santé Rural', 'Centre de santé en milieu rural', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(4, 'Dispensaire Rural', 'Petit établissement de santé en zone rurale', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(6, 'Hôpital Régional', 'Hôpital au niveau de le chef lieu de la région', '2025-04-26 11:19:17', '2025-04-26 11:19:17', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `corps`
--

CREATE TABLE `corps` (
  `id` int(11) NOT NULL,
  `nom_corps` varchar(100) NOT NULL,
  `type_corps` enum('MEDICAL','PARAMEDICAL','ADMINISTRATIF') NOT NULL DEFAULT 'ADMINISTRATIF',
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `corps`
--

INSERT INTO `corps` (`id`, `nom_corps`, `type_corps`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Médical', 'MEDICAL', 'Personnel médical', '2025-04-24 18:40:01', '2025-04-25 21:30:07', NULL),
(2, 'Paramédical', 'PARAMEDICAL', 'Personnel paramédical', '2025-04-24 18:40:01', '2025-04-25 21:30:15', NULL),
(3, 'Administratif', 'ADMINISTRATIF', 'Personnel administratif', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(4, 'Technique', 'ADMINISTRATIF', 'Personnel technique', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `corps_backup`
--

CREATE TABLE `corps_backup` (
  `id` int(11) NOT NULL DEFAULT 0,
  `nom_corps` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `formations_sanitaires`
--

CREATE TABLE `formations_sanitaires` (
  `id` int(11) NOT NULL,
  `nom_formation` varchar(255) NOT NULL,
  `type_formation` varchar(100) DEFAULT NULL,
  `province_id` int(11) DEFAULT NULL,
  `categorie_id` int(11) DEFAULT NULL,
  `milieu` enum('URBAIN','RURAL') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `formations_sanitaires`
--

INSERT INTO `formations_sanitaires` (`id`, `nom_formation`, `type_formation`, `province_id`, `categorie_id`, `milieu`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Hôpital Provincial d\'Azilal', 'HOPITAL', 1, 1, 'URBAIN', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(2, 'CSU Azilal', 'CENTRE_SANTE', 1, 2, 'URBAIN', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(3, 'CSR Demnat', 'CENTRE_SANTE', 1, 3, 'RURAL', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(4, 'DR Ait M\'hamed', 'DISPENSAIRE', 1, 4, 'RURAL', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(5, 'Hôpital Provincial de Beni Mellal', 'HOPITAL', 2, 1, 'URBAIN', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(6, 'CSU Atlas', 'CENTRE_SANTE', 2, 2, 'URBAIN', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(7, 'CSR Zaouit Cheikh', 'CENTRE_SANTE', 2, 3, 'RURAL', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(8, 'DR El Ksiba', 'DISPENSAIRE', 2, 4, 'RURAL', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(9, 'Hôpital Provincial de Khénifra', 'HOPITAL', 3, 1, 'URBAIN', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(10, 'CSU Al Amal', 'CENTRE_SANTE', 3, 2, 'URBAIN', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(11, 'CSR M\'rirt', 'CENTRE_SANTE', 3, 3, 'RURAL', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(12, 'DR Aguelmous', 'DISPENSAIRE', 3, 4, 'RURAL', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(13, 'Hôpital Provincial de Khouribga', 'HOPITAL', 4, 1, 'URBAIN', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(14, 'CSU Hassan II', 'CENTRE_SANTE', 4, 2, 'URBAIN', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(15, 'CSR Boujniba', 'CENTRE_SANTE', 4, 3, 'RURAL', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(16, 'DR Boulanouar', 'DISPENSAIRE', 4, 4, 'RURAL', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(17, 'Hôpital Provincial de Fquih Ben Salah', 'HOPITAL', 5, 1, 'URBAIN', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(18, 'CSU Al Massira', 'CENTRE_SANTE', 5, 2, 'URBAIN', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(19, 'CSR Souk Sebt', 'CENTRE_SANTE', 5, 3, 'RURAL', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(20, 'DR Had Bradia', 'DISPENSAIRE', 5, 4, 'RURAL', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(21, 'CENTRE HOSPITALIER RéGIONAL', 'Hôpital Régional', 2, 6, 'URBAIN', '2025-04-26 11:20:23', '2025-04-26 11:20:23', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `grades`
--

CREATE TABLE `grades` (
  `id` int(11) NOT NULL,
  `nom_grade` varchar(100) NOT NULL,
  `corps_id` int(11) DEFAULT NULL,
  `echelle` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `grades`
--

INSERT INTO `grades` (`id`, `nom_grade`, `corps_id`, `echelle`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Médecin 1er Grade', 1, 'Echelle 11', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(2, 'Médecin 2ème Grade', 1, 'Echelle 10', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(3, 'Infirmier 1er Grade', 2, 'Echelle 10', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(4, 'Infirmier 2ème Grade', 2, 'Echelle 9', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(5, 'Sage femme 1er Grade', 2, 'Echelle 10', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(6, 'Sage femme 2ème Grade', 2, 'Echelle 9', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(7, 'Administrateur 1er Grade', 3, 'Echelle 11', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(8, 'Administrateur 2ème Grade', 3, 'Echelle 10', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(9, 'Technicien 1er Grade', 4, 'Echelle 10', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(10, 'Technicien 2ème Grade', 4, 'Echelle 9', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `mouvements_personnel`
--

CREATE TABLE `mouvements_personnel` (
  `id` int(11) NOT NULL,
  `personnel_id` int(11) NOT NULL,
  `type_mouvement` enum('DECES','MUTATION','DEMISSION','FORMATION','SUSPENSION','MISE_A_DISPOSITION','MISE_EN_DISPONIBILITE','RETRAITE_AGE') NOT NULL,
  `date_mouvement` date NOT NULL,
  `formation_sanitaire_origine_id` int(11) DEFAULT NULL,
  `formation_sanitaire_destination_id` int(11) DEFAULT NULL,
  `commentaire` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `mouvements_personnel`
--

INSERT INTO `mouvements_personnel` (`id`, `personnel_id`, `type_mouvement`, `date_mouvement`, `formation_sanitaire_origine_id`, `formation_sanitaire_destination_id`, `commentaire`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 55, 'MISE_A_DISPOSITION', '2024-12-14', 15, 19, 'Mise à disposition temporaire', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(2, 6, 'MUTATION', '2021-09-28', 18, 8, 'Mutation dans l\'intérêt du service', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(3, 36, 'MISE_A_DISPOSITION', '2023-01-17', 17, 20, 'Mise à disposition temporaire', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(4, 65, 'MUTATION', '2022-02-05', 15, 10, 'Mutation dans l\'intérêt du service', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(5, 11, 'MUTATION', '2022-06-06', 17, 14, 'Mutation dans l\'intérêt du service', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(6, 8, 'FORMATION', '2024-02-03', 17, NULL, 'Formation continue pour amélioration des compétences', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(7, 57, 'FORMATION', '2025-11-10', 19, NULL, 'Formation continue pour amélioration des compétences', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(8, 74, 'MUTATION', '2023-11-11', 14, 13, 'Mutation dans l\'intérêt du service', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(9, 89, 'MISE_A_DISPOSITION', '2019-03-18', 12, 9, 'Mise à disposition temporaire', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(10, 50, 'FORMATION', '2024-05-27', 3, NULL, 'Formation continue pour amélioration des compétences', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(11, 83, 'MUTATION', '2019-07-23', 18, 8, 'Mutation dans l\'intérêt du service', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(12, 32, 'FORMATION', '2023-06-03', 1, NULL, 'Formation continue pour amélioration des compétences', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(13, 37, 'SUSPENSION', '2015-03-19', 19, NULL, 'Suspension temporaire', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(14, 54, 'MISE_A_DISPOSITION', '2015-03-17', 4, 5, 'Mise à disposition temporaire', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(15, 51, 'FORMATION', '2023-07-15', 11, NULL, 'Formation continue pour amélioration des compétences', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(16, 93, 'MISE_A_DISPOSITION', '2023-11-22', 16, 19, 'Mise à disposition temporaire', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(17, 49, 'FORMATION', '2019-05-08', 14, NULL, 'Formation continue pour amélioration des compétences', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(18, 96, 'FORMATION', '2017-08-25', 14, NULL, 'Formation continue pour amélioration des compétences', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(19, 84, 'FORMATION', '2013-11-17', 11, NULL, 'Formation continue pour amélioration des compétences', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(20, 77, 'FORMATION', '2023-02-23', 4, NULL, 'Formation continue pour amélioration des compétences', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(21, 78, 'FORMATION', '2017-10-12', 20, NULL, 'Formation continue pour amélioration des compétences', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(22, 95, 'MUTATION', '2025-03-02', 3, 10, 'Mutation dans l\'intérêt du service', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(23, 67, 'FORMATION', '2010-04-27', 19, NULL, 'Formation continue pour amélioration des compétences', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(24, 7, 'FORMATION', '2020-02-28', 1, NULL, 'Formation continue pour amélioration des compétences', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(25, 53, 'MISE_EN_DISPONIBILITE', '2018-06-26', 14, NULL, 'Mise en disponibilité pour raisons personnelles', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(26, 14, 'FORMATION', '2024-09-08', 6, NULL, 'Formation continue pour amélioration des compétences', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(27, 97, 'MISE_A_DISPOSITION', '2021-07-19', 2, 11, 'Mise à disposition temporaire', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(28, 29, 'SUSPENSION', '2024-07-19', 4, NULL, 'Suspension temporaire', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(29, 17, 'FORMATION', '2020-09-12', 2, NULL, 'Formation continue pour amélioration des compétences', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(30, 87, 'FORMATION', '2025-04-29', 10, NULL, 'Formation continue pour amélioration des compétences', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(31, 16, 'MUTATION', '2022-05-10', 18, 16, 'Mutation dans l\'intérêt du service', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(32, 66, 'FORMATION', '2014-04-28', 4, NULL, 'Formation continue pour amélioration des compétences', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(33, 64, 'MISE_EN_DISPONIBILITE', '2025-12-16', 4, NULL, 'Mise en disponibilité pour raisons personnelles', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(34, 100, 'FORMATION', '2023-05-04', 4, NULL, 'Formation continue pour amélioration des compétences', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(35, 21, 'MUTATION', '2025-05-28', 18, 10, 'Mutation dans l\'intérêt du service', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(36, 73, 'FORMATION', '2024-04-04', 16, NULL, 'Formation continue pour amélioration des compétences', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(37, 68, 'FORMATION', '2026-11-12', 5, NULL, 'Formation continue pour amélioration des compétences', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(38, 35, 'MISE_A_DISPOSITION', '2021-03-31', 10, 11, 'Mise à disposition temporaire', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(39, 52, 'FORMATION', '2023-09-14', 19, NULL, 'Formation continue pour amélioration des compétences', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(40, 46, 'FORMATION', '2017-04-25', 8, NULL, 'Formation continue pour amélioration des compétences', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(41, 88, 'FORMATION', '2019-01-20', 3, NULL, 'Formation continue pour amélioration des compétences', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(42, 13, 'MISE_A_DISPOSITION', '2016-11-23', 14, 20, 'Mise à disposition temporaire', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(43, 81, 'FORMATION', '2023-09-20', 20, NULL, 'Formation continue pour amélioration des compétences', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(44, 79, 'MUTATION', '2027-03-27', 17, 7, 'Mutation dans l\'intérêt du service', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(45, 75, 'FORMATION', '2020-06-17', 12, NULL, 'Formation continue pour amélioration des compétences', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(46, 43, 'FORMATION', '2025-08-20', 18, NULL, 'Formation continue pour amélioration des compétences', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(47, 58, 'MISE_EN_DISPONIBILITE', '2026-07-04', 19, NULL, 'Mise en disponibilité pour raisons personnelles', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(48, 90, 'MUTATION', '2022-05-16', 16, 20, 'Mutation dans l\'intérêt du service', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(49, 42, 'FORMATION', '2024-01-03', 3, NULL, 'Formation continue pour amélioration des compétences', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(50, 82, 'FORMATION', '2023-05-18', 13, NULL, 'Formation continue pour amélioration des compétences', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(51, 18, 'MUTATION', '2017-05-10', 8, 20, 'Mutation dans l\'intérêt du service', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(52, 1, 'FORMATION', '2027-03-25', 20, NULL, 'Formation continue pour amélioration des compétences', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(53, 85, 'MISE_A_DISPOSITION', '2019-03-11', 2, 8, 'Mise à disposition temporaire', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(54, 72, 'MUTATION', '2014-04-14', 18, 4, 'Mutation dans l\'intérêt du service', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(55, 39, 'MUTATION', '2025-04-02', 10, 9, 'Mutation dans l\'intérêt du service', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(56, 47, 'MUTATION', '2024-07-02', 1, 8, 'Mutation dans l\'intérêt du service', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(57, 30, 'FORMATION', '2015-10-05', 17, NULL, 'Formation continue pour amélioration des compétences', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(58, 34, 'FORMATION', '2025-12-11', 3, NULL, 'Formation continue pour amélioration des compétences', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(59, 80, 'FORMATION', '2019-04-04', 15, NULL, 'Formation continue pour amélioration des compétences', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(60, 48, 'MUTATION', '2014-02-18', 2, 11, 'Mutation dans l\'intérêt du service', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(64, 72, 'MUTATION', '2015-01-02', 4, 8, 'Mutation suite à une demande personnelle', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(65, 47, 'MUTATION', '2025-03-11', 8, 11, 'Mutation suite à une demande personnelle', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(66, 11, 'FORMATION', '2022-12-12', 14, NULL, 'Formation spécialisée', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(67, 89, 'MUTATION', '2019-05-05', 9, 15, 'Mutation suite à une demande personnelle', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(68, 97, 'FORMATION', '2021-12-14', 11, NULL, 'Formation spécialisée', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(69, 13, 'FORMATION', '2017-01-23', 20, NULL, 'Formation spécialisée', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(70, 74, 'FORMATION', '2024-06-22', 13, NULL, 'Formation spécialisée', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(71, 35, 'FORMATION', '2021-10-26', 11, NULL, 'Formation spécialisée', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(72, 65, 'FORMATION', '2022-02-12', 10, NULL, 'Formation spécialisée', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(73, 90, 'FORMATION', '2022-10-03', 20, NULL, 'Formation spécialisée', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(74, 36, 'MUTATION', '2023-11-27', 20, 8, 'Mutation suite à une demande personnelle', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(75, 18, 'MUTATION', '2018-01-18', 20, 10, 'Mutation suite à une demande personnelle', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(76, 21, 'FORMATION', '2026-04-15', 10, NULL, 'Formation spécialisée', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(77, 16, 'FORMATION', '2022-09-03', 16, NULL, 'Formation spécialisée', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(78, 93, 'FORMATION', '2024-10-30', 19, NULL, 'Formation spécialisée', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(79, 39, 'MUTATION', '2026-01-01', 9, 17, 'Mutation suite à une demande personnelle', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(80, 79, 'MUTATION', '2027-09-04', 7, 17, 'Mutation suite à une demande personnelle', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(81, 85, 'MISE_A_DISPOSITION', '2020-03-04', 8, 19, 'Nouvelle mise à disposition', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(82, 54, 'MUTATION', '2016-01-30', 5, 3, 'Mutation suite à une demande personnelle', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(83, 48, 'FORMATION', '2014-12-02', 11, NULL, 'Formation spécialisée', '2025-04-24 18:47:11', '2025-04-24 18:47:11', NULL),
(84, 101, 'MUTATION', '2025-04-25', 1, 7, 'MUTATION LOCAL', '2025-04-24 23:43:07', '2025-04-24 23:43:07', NULL),
(85, 13, 'MUTATION', '2025-04-26', 14, 21, '', '2025-04-26 11:25:32', '2025-04-26 11:25:32', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `personnel`
--

CREATE TABLE `personnel` (
  `id` int(11) NOT NULL,
  `ppr` varchar(10) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `cin` varchar(20) NOT NULL,
  `date_naissance` date NOT NULL,
  `sexe` enum('M','F') NOT NULL,
  `situation_familiale` enum('CELIBATAIRE','MARIE','DIVORCE','VEUF') NOT NULL,
  `corps_id` int(11) DEFAULT NULL,
  `grade_id` int(11) DEFAULT NULL,
  `specialite_id` int(11) DEFAULT NULL,
  `date_recrutement` date NOT NULL,
  `date_prise_service` date NOT NULL,
  `formation_sanitaire_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `personnel`
--

INSERT INTO `personnel` (`id`, `ppr`, `nom`, `prenom`, `cin`, `date_naissance`, `sexe`, `situation_familiale`, `corps_id`, `grade_id`, `specialite_id`, `date_recrutement`, `date_prise_service`, `formation_sanitaire_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'P000001', 'NOM_1', 'Prenom_1', 'CIN00001', '2001-04-24', 'F', 'DIVORCE', 1, 6, 6, '2019-04-24', '2024-04-24', 20, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(2, 'P000002', 'NOM_2', 'Prenom_2', 'CIN00002', '1991-04-24', 'F', 'MARIE', 1, 5, 5, '2018-04-24', '2018-05-21', 10, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(3, 'P000003', 'NOM_3', 'Prenom_3', 'CIN00003', '2001-04-24', 'M', 'CELIBATAIRE', 4, 6, 6, '2024-04-24', '2024-04-30', 11, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(4, 'P000004', 'NOM_4', 'Prenom_4', 'CIN00004', '2004-04-24', 'F', 'DIVORCE', 3, 4, 5, '2010-04-24', '2011-04-24', 15, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(5, 'P000005', 'NOM_5', 'Prenom_5', 'CIN00005', '2005-04-24', 'M', 'DIVORCE', 1, 5, 1, '2022-04-24', '2022-05-04', 10, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(6, 'P000006', 'NOM_6', 'Prenom_6', 'CIN00006', '1988-04-24', 'F', 'MARIE', 1, 5, 8, '2020-04-24', '2020-04-25', 18, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(7, 'P000007', 'NOM_7', 'Prenom_7', 'CIN00007', '1988-04-24', 'M', 'CELIBATAIRE', 1, 8, 6, '2018-04-24', '2018-04-28', 1, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(8, 'P000008', 'NOM_8', 'Prenom_8', 'CIN00008', '1997-04-24', 'F', 'DIVORCE', 4, 4, 7, '2022-04-24', '2022-05-12', 17, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(9, 'P000009', 'NOM_9', 'Prenom_9', 'CIN00009', '2004-04-24', 'F', 'CELIBATAIRE', 4, 9, 8, '2024-04-24', '2024-05-14', 5, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(10, 'P000010', 'NOM_10', 'Prenom_10', 'CIN00010', '1999-04-24', 'M', 'DIVORCE', 4, 7, 3, '2016-04-24', '2024-04-24', 8, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(11, 'P000011', 'NOM_11', 'Prenom_11', 'CIN00011', '1983-04-24', 'F', 'DIVORCE', 1, 9, 7, '2021-04-24', '2021-05-11', 17, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(12, 'P000012', 'NOM_12', 'Prenom_12', 'CIN00012', '1981-04-24', 'F', 'CELIBATAIRE', 4, 6, 7, '2017-04-24', '2017-05-20', 1, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(13, 'P000013', 'NOM_13', 'Prenom_13', 'CIN00013', '2000-04-24', 'F', 'CELIBATAIRE', 1, 2, 2, '2015-04-24', '2015-05-11', 14, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(14, 'P000014', 'NOM_14', 'Prenom_14', 'CIN00014', '2001-04-24', 'M', 'MARIE', 1, 2, 8, '2023-04-24', '2023-05-04', 6, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(15, 'P000015', 'NOM_15', 'Prenom_15', 'CIN00015', '1991-04-24', 'M', 'CELIBATAIRE', 1, 3, 7, '2018-04-24', '2018-05-22', 7, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(16, 'P000016', 'NOM_16', 'Prenom_16', 'CIN00016', '1994-04-24', 'M', 'CELIBATAIRE', 3, 6, 1, '2014-04-24', '2022-04-24', 18, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(17, 'P000017', 'NOM_17', 'Prenom_17', 'CIN00017', '1997-04-24', 'F', 'MARIE', 3, 1, 1, '2018-04-24', '2018-05-16', 2, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(18, 'P000018', 'NOM_18', 'Prenom_18', 'CIN00018', '1990-04-24', 'M', 'MARIE', 4, 9, 4, '2016-04-24', '2016-05-18', 8, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(19, 'P000019', 'NOM_19', 'Prenom_19', 'CIN00019', '2004-04-24', 'F', 'CELIBATAIRE', 3, 1, 5, '2013-04-24', '2013-05-21', 11, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(20, 'P000020', 'NOM_20', 'Prenom_20', 'CIN00020', '1990-04-24', 'F', 'CELIBATAIRE', 2, 9, 8, '2022-04-24', '2023-04-24', 1, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(21, 'P000021', 'NOM_21', 'Prenom_21', 'CIN00021', '1999-04-24', 'F', 'CELIBATAIRE', 3, 8, 1, '2023-04-24', '2023-04-25', 18, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(22, 'P000022', 'NOM_22', 'Prenom_22', 'CIN00022', '2002-04-24', 'F', 'VEUF', 3, 3, 5, '2024-04-24', '2024-05-12', 13, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(23, 'P000023', 'NOM_23', 'Prenom_23', 'CIN00023', '1984-04-24', 'F', 'MARIE', 2, 7, 2, '2011-04-24', '2022-04-24', 20, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(24, 'P000024', 'NOM_24', 'Prenom_24', 'CIN00024', '1995-04-24', 'F', 'DIVORCE', 2, 3, 3, '2015-04-24', '2018-04-24', 5, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(25, 'P000025', 'NOM_25', 'Prenom_25', 'CIN00025', '1989-04-24', 'M', 'CELIBATAIRE', 2, 1, 1, '2021-04-24', '2021-05-19', 17, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(26, 'P000026', 'NOM_26', 'Prenom_26', 'CIN00026', '1984-04-24', 'F', 'CELIBATAIRE', 2, 7, 5, '2010-04-24', '2021-04-24', 6, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(27, 'P000027', 'NOM_27', 'Prenom_27', 'CIN00027', '1994-04-24', 'F', 'VEUF', 2, 7, 1, '2013-04-24', '2017-04-24', 4, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(28, 'P000028', 'NOM_28', 'Prenom_28', 'CIN00028', '1982-04-24', 'M', 'CELIBATAIRE', 1, 7, 8, '2013-04-24', '2013-05-04', 15, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(29, 'P000029', 'NOM_29', 'Prenom_29', 'CIN00029', '1996-04-24', 'M', 'MARIE', 4, 2, 1, '2022-04-24', '2022-05-03', 4, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(30, 'P000030', 'NOM_30', 'Prenom_30', 'CIN00030', '1996-04-24', 'M', 'CELIBATAIRE', 2, 9, 6, '2013-04-24', '2013-05-08', 17, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(31, 'P000031', 'NOM_31', 'Prenom_31', 'CIN00031', '1985-04-24', 'M', 'CELIBATAIRE', 3, 8, 5, '2016-04-24', '2021-04-24', 8, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(32, 'P000032', 'NOM_32', 'Prenom_32', 'CIN00032', '1984-04-24', 'F', 'MARIE', 3, 2, 1, '2012-04-24', '2023-04-24', 1, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(33, 'P000033', 'NOM_33', 'Prenom_33', 'CIN00033', '1997-04-24', 'F', 'MARIE', 2, 4, 6, '2019-04-24', '2019-05-07', 8, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(34, 'P000034', 'NOM_34', 'Prenom_34', 'CIN00034', '1982-04-24', 'M', 'CELIBATAIRE', 2, 8, 5, '2013-04-24', '2023-04-24', 3, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(35, 'P000035', 'NOM_35', 'Prenom_35', 'CIN00035', '2000-04-24', 'M', 'MARIE', 2, 2, 3, '2020-04-24', '2020-05-19', 10, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(36, 'P000036', 'NOM_36', 'Prenom_36', 'CIN00036', '2005-04-24', 'F', 'CELIBATAIRE', 1, 7, 1, '2022-04-24', '2022-05-19', 17, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(37, 'P000037', 'NOM_37', 'Prenom_37', 'CIN00037', '1995-04-24', 'F', 'CELIBATAIRE', 4, 4, 8, '2014-04-24', '2014-05-15', 19, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(38, 'P000038', 'NOM_38', 'Prenom_38', 'CIN00038', '1990-04-24', 'F', 'MARIE', 1, 1, 5, '2013-04-24', '2023-04-24', 6, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(39, 'P000039', 'NOM_39', 'Prenom_39', 'CIN00039', '1983-04-24', 'F', 'DIVORCE', 1, 3, 3, '2024-04-24', '2024-04-26', 10, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(40, 'P000040', 'NOM_40', 'Prenom_40', 'CIN00040', '1985-04-24', 'M', 'DIVORCE', 3, 2, 5, '2021-04-24', '2021-04-30', 14, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(41, 'P000041', 'NOM_41', 'Prenom_41', 'CIN00041', '1992-04-24', 'F', 'MARIE', 2, 1, 1, '2021-04-24', '2021-05-19', 2, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(42, 'P000042', 'NOM_42', 'Prenom_42', 'CIN00042', '1996-04-24', 'M', 'CELIBATAIRE', 3, 2, 6, '2013-04-24', '2023-04-24', 3, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(43, 'P000043', 'NOM_43', 'Prenom_43', 'CIN00043', '2005-04-24', 'M', 'MARIE', 1, 10, 6, '2022-04-24', '2023-04-24', 18, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(44, 'P000044', 'NOM_44', 'Prenom_44', 'CIN00044', '1983-04-24', 'M', 'DIVORCE', 3, 6, 1, '2014-04-24', '2019-04-24', 14, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(45, 'P000045', 'NOM_45', 'Prenom_45', 'CIN00045', '1997-04-24', 'F', 'CELIBATAIRE', 2, 4, 6, '2024-04-24', '2024-05-11', 12, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(46, 'P000046', 'NOM_46', 'Prenom_46', 'CIN00046', '2001-04-24', 'F', 'MARIE', 1, 2, 4, '2015-04-24', '2015-05-06', 8, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(47, 'P000047', 'NOM_47', 'Prenom_47', 'CIN00047', '1997-04-24', 'F', 'CELIBATAIRE', 3, 9, 3, '2013-04-24', '2023-04-24', 1, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(48, 'P000048', 'NOM_48', 'Prenom_48', 'CIN00048', '2005-04-24', 'M', 'MARIE', 3, 10, 5, '2011-04-24', '2013-04-24', 2, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(49, 'P000049', 'NOM_49', 'Prenom_49', 'CIN00049', '1991-04-24', 'M', 'MARIE', 1, 3, 6, '2017-04-24', '2018-04-24', 14, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(50, 'P000050', 'NOM_50', 'Prenom_50', 'CIN00050', '1985-04-24', 'F', 'CELIBATAIRE', 3, 8, 7, '2011-04-24', '2022-04-24', 3, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(51, 'P000051', 'NOM_51', 'Prenom_51', 'CIN00051', '2004-04-24', 'M', 'MARIE', 2, 4, 7, '2019-04-24', '2023-04-24', 11, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(52, 'P000052', 'NOM_52', 'Prenom_52', 'CIN00052', '1993-04-24', 'F', 'MARIE', 1, 2, 4, '2014-04-24', '2021-04-24', 19, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(53, 'P000053', 'NOM_53', 'Prenom_53', 'CIN00053', '1981-04-24', 'F', 'CELIBATAIRE', 1, 4, 5, '2012-04-24', '2018-04-24', 14, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(54, 'P000054', 'NOM_54', 'Prenom_54', 'CIN00054', '1996-04-24', 'F', 'DIVORCE', 3, 10, 1, '2012-04-24', '2014-04-24', 4, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(55, 'P000055', 'NOM_55', 'Prenom_55', 'CIN00055', '2001-04-24', 'F', 'CELIBATAIRE', 4, 3, 6, '2018-04-24', '2022-04-24', 15, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(56, 'P000056', 'NOM_56', 'Prenom_56', 'CIN00056', '2003-04-24', 'M', 'CELIBATAIRE', 4, 7, 8, '2011-04-24', '2017-04-24', 16, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(57, 'P000057', 'NOM_57', 'Prenom_57', 'CIN00057', '2001-04-24', 'F', 'MARIE', 1, 8, 7, '2018-04-24', '2024-04-24', 19, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(58, 'P000058', 'NOM_58', 'Prenom_58', 'CIN00058', '1993-04-24', 'M', 'DIVORCE', 3, 2, 8, '2019-04-24', '2024-04-24', 19, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(59, 'P000059', 'NOM_59', 'Prenom_59', 'CIN00059', '1994-04-24', 'F', 'MARIE', 4, 7, 1, '2021-04-24', '2021-05-02', 20, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(60, 'P000060', 'NOM_60', 'Prenom_60', 'CIN00060', '1995-04-24', 'F', 'DIVORCE', 2, 10, 2, '2014-04-24', '2024-04-24', 5, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(61, 'P000061', 'NOM_61', 'Prenom_61', 'CIN00061', '2002-04-24', 'F', 'MARIE', 3, 8, 8, '2020-04-24', '2020-04-29', 18, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(62, 'P000062', 'NOM_62', 'Prenom_62', 'CIN00062', '2000-04-24', 'F', 'CELIBATAIRE', 1, 6, 3, '2014-04-24', '2014-04-28', 18, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(63, 'P000063', 'NOM_63', 'Prenom_63', 'CIN00063', '1994-04-24', 'M', 'DIVORCE', 1, 4, 3, '2019-04-24', '2019-04-26', 16, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(64, 'P000064', 'NOM_64', 'Prenom_64', 'CIN00064', '1984-04-24', 'F', 'MARIE', 4, 7, 5, '2024-04-24', '2024-05-23', 4, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(65, 'P000065', 'NOM_65', 'Prenom_65', 'CIN00065', '2002-04-24', 'M', 'MARIE', 4, 10, 3, '2020-04-24', '2020-05-15', 15, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(66, 'P000066', 'NOM_66', 'Prenom_66', 'CIN00066', '1989-04-24', 'F', 'DIVORCE', 3, 3, 5, '2011-04-24', '2011-05-14', 4, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(67, 'P000067', 'NOM_67', 'Prenom_67', 'CIN00067', '1989-04-24', 'F', 'CELIBATAIRE', 2, 10, 8, '2010-04-24', '2010-04-24', 19, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(68, 'P000068', 'NOM_68', 'Prenom_68', 'CIN00068', '1990-04-24', 'M', 'MARIE', 3, 10, 8, '2024-04-24', '2024-04-28', 5, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(69, 'P000069', 'NOM_69', 'Prenom_69', 'CIN00069', '1989-04-24', 'F', 'DIVORCE', 2, 2, 6, '2023-04-24', '2023-05-16', 14, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(70, 'P000070', 'NOM_70', 'Prenom_70', 'CIN00070', '1989-04-24', 'F', 'CELIBATAIRE', 3, 1, 8, '2023-04-24', '2023-05-02', 17, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(71, 'P000071', 'NOM_71', 'Prenom_71', 'CIN00071', '1989-04-24', 'M', 'DIVORCE', 3, 2, 6, '2023-04-24', '2023-04-28', 5, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(72, 'P000072', 'NOM_72', 'Prenom_72', 'CIN00072', '1996-04-24', 'M', 'CELIBATAIRE', 3, 3, 8, '2011-04-24', '2011-05-20', 18, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(73, 'P000073', 'NOM_73', 'Prenom_73', 'CIN00073', '2002-04-24', 'F', 'MARIE', 3, 8, 8, '2019-04-24', '2022-04-24', 16, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(74, 'P000074', 'NOM_74', 'Prenom_74', 'CIN00074', '2002-04-24', 'F', 'CELIBATAIRE', 3, 7, 6, '2018-04-24', '2021-04-24', 14, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(75, 'P000075', 'NOM_75', 'Prenom_75', 'CIN00075', '1998-04-24', 'M', 'CELIBATAIRE', 1, 2, 6, '2015-04-24', '2020-04-24', 12, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(76, 'P000076', 'NOM_76', 'Prenom_76', 'CIN00076', '1993-04-24', 'M', 'CELIBATAIRE', 1, 9, 6, '2012-04-24', '2012-05-22', 6, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(77, 'P000077', 'NOM_77', 'Prenom_77', 'CIN00077', '1999-04-24', 'F', 'MARIE', 2, 1, 7, '2011-04-24', '2022-04-24', 4, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(78, 'P000078', 'NOM_78', 'Prenom_78', 'CIN00078', '1995-04-24', 'F', 'CELIBATAIRE', 2, 2, 5, '2014-04-24', '2015-04-24', 20, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(79, 'P000079', 'NOM_79', 'Prenom_79', 'CIN00079', '2005-04-24', 'M', 'CELIBATAIRE', 1, 6, 6, '2012-04-24', '2024-04-24', 17, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(80, 'P000080', 'NOM_80', 'Prenom_80', 'CIN00080', '1991-04-24', 'M', 'MARIE', 4, 9, 6, '2014-04-24', '2016-04-24', 15, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(81, 'P000081', 'NOM_81', 'Prenom_81', 'CIN00081', '1987-04-24', 'F', 'MARIE', 3, 5, 1, '2022-04-24', '2022-04-27', 20, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(82, 'P000082', 'NOM_82', 'Prenom_82', 'CIN00082', '1997-04-24', 'M', 'MARIE', 1, 9, 6, '2023-04-24', '2023-05-14', 13, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(83, 'P000083', 'NOM_83', 'Prenom_83', 'CIN00083', '1998-04-24', 'M', 'MARIE', 1, 3, 6, '2014-04-24', '2019-04-24', 18, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(84, 'P000084', 'NOM_84', 'Prenom_84', 'CIN00084', '1990-04-24', 'M', 'MARIE', 2, 7, 2, '2011-04-24', '2012-04-24', 11, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(85, 'P000085', 'NOM_85', 'Prenom_85', 'CIN00085', '1990-04-24', 'M', 'CELIBATAIRE', 4, 6, 8, '2012-04-24', '2017-04-24', 2, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(86, 'P000086', 'NOM_86', 'Prenom_86', 'CIN00086', '1997-04-24', 'F', 'CELIBATAIRE', 2, 6, 6, '2015-04-24', '2020-04-24', 10, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(87, 'P000087', 'NOM_87', 'Prenom_87', 'CIN00087', '1985-04-24', 'F', 'CELIBATAIRE', 1, 8, 3, '2023-04-24', '2023-04-28', 10, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(88, 'P000088', 'NOM_88', 'Prenom_88', 'CIN00088', '1995-04-24', 'F', 'DIVORCE', 2, 9, 1, '2016-04-24', '2016-05-13', 3, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(89, 'P000089', 'NOM_89', 'Prenom_89', 'CIN00089', '1986-04-24', 'F', 'CELIBATAIRE', 2, 2, 1, '2013-04-24', '2016-04-24', 12, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(90, 'P000090', 'NOM_90', 'Prenom_90', 'CIN00090', '1992-04-24', 'F', 'MARIE', 2, 4, 7, '2021-04-24', '2021-05-19', 16, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(91, 'P000091', 'NOM_91', 'Prenom_91', 'CIN00091', '1996-04-24', 'F', 'MARIE', 4, 4, 6, '2022-04-24', '2022-05-03', 3, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(92, 'P000092', 'NOM_92', 'Prenom_92', 'CIN00092', '1982-04-24', 'M', 'CELIBATAIRE', 4, 9, 1, '2013-04-24', '2018-04-24', 20, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(93, 'P000093', 'NOM_93', 'Prenom_93', 'CIN00093', '1993-04-24', 'M', 'MARIE', 2, 5, 8, '2018-04-24', '2021-04-24', 16, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(94, 'P000094', 'NOM_94', 'Prenom_94', 'CIN00094', '1989-04-24', 'F', 'CELIBATAIRE', 2, 10, 2, '2020-04-24', '2020-05-22', 19, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(95, 'P000095', 'NOM_95', 'Prenom_95', 'CIN00095', '1984-04-24', 'M', 'CELIBATAIRE', 3, 9, 4, '2014-04-24', '2024-04-24', 3, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(96, 'P000096', 'NOM_96', 'Prenom_96', 'CIN00096', '1988-04-24', 'M', 'MARIE', 2, 8, 7, '2016-04-24', '2017-04-24', 14, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(97, 'P000097', 'NOM_97', 'Prenom_97', 'CIN00097', '2004-04-24', 'M', 'DIVORCE', 2, 6, 5, '2013-04-24', '2021-04-24', 2, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(98, 'P000098', 'NOM_98', 'Prenom_98', 'CIN00098', '1993-04-24', 'F', 'MARIE', 2, 6, 3, '2012-04-24', '2022-04-24', 11, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(99, 'P000099', 'NOM_99', 'Prenom_99', 'CIN00099', '1991-04-24', 'F', 'CELIBATAIRE', 2, 8, 5, '2014-04-24', '2015-04-24', 3, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(100, 'P000100', 'NOM_100', 'Prenom_100', 'CIN00100', '1985-04-24', 'M', 'DIVORCE', 2, 9, 8, '2021-04-24', '2021-05-19', 4, '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(101, '9999999', 'Alamiiiii', 'Zineb', 'P123456', '2000-11-11', 'F', 'CELIBATAIRE', 2, 3, 4, '2025-04-11', '2025-04-12', 1, '2025-04-24 20:55:31', '2025-04-24 21:02:57', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `provinces`
--

CREATE TABLE `provinces` (
  `id` int(11) NOT NULL,
  `nom_province` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `provinces`
--

INSERT INTO `provinces` (`id`, `nom_province`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'AZILAL', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(2, 'BENI MELLAL', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(3, 'KHENIFRA', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(4, 'KHOURIBGA', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(5, 'FQUIH BEN SALAH', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(6, 'OUARZAZTE', '2025-04-26 00:26:28', '2025-04-26 00:26:28', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `specialites`
--

CREATE TABLE `specialites` (
  `id` int(11) NOT NULL,
  `nom_specialite` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `specialites`
--

INSERT INTO `specialites` (`id`, `nom_specialite`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Médecine Générale', 'Médecine générale et familiale', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(2, 'Pédiatrie', 'Soins médicaux des enfants', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(3, 'Gynécologie', 'Santé des femmes et obstétrique', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(4, 'Cardiologie', 'Maladies cardiovasculaires', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(5, 'Chirurgie Générale', 'Interventions chirurgicales générales', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(6, 'Radiologie', 'Imagerie médicale', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(7, 'Infirmerie Générale', 'Soins infirmiers généraux', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL),
(8, 'Administration Médicale', 'Gestion administrative médicale', '2025-04-24 18:40:01', '2025-04-24 18:40:01', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `suivi_workflows`
--

CREATE TABLE `suivi_workflows` (
  `id` int(11) NOT NULL,
  `personnel_id` int(11) NOT NULL,
  `workflow_id` int(11) NOT NULL,
  `statut` enum('EN_ATTENTE','EN_COURS','TERMINE','ANNULE') NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime DEFAULT NULL,
  `commentaire` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `adresse` text DEFAULT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `avatar` varchar(255) DEFAULT NULL,
  `derniere_connexion` datetime DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL,
  `status` enum('active','inactive','blocked') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `nom`, `prenom`, `telephone`, `adresse`, `role`, `avatar`, `derniere_connexion`, `reset_token`, `reset_token_expiry`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'admin@example.com', '$2y$12$k0By1CzmT8eZm8InWSxhsuEG8FRuR2z2SSkrvGueUF/szeKGcxgvy', 'Administrator', '', NULL, NULL, 'admin', NULL, NULL, NULL, NULL, 'inactive', '2025-04-25 09:48:17', '2025-04-26 11:15:27', NULL),
(3, 'admin@test.com', '$2y$10$lW1VkL8s37tlQA.sfr.Mue.B0QXhHcecQyT4E3eRqQ6vcGtTXhena', 'MAABAD', 'TARIQ', '0600000000', 'Test Address', 'admin', NULL, '2025-04-26 12:51:50', NULL, NULL, 'active', '2025-04-26 10:39:16', '2025-04-26 11:51:50', NULL),
(4, 'kanza@test.com', '$2y$10$uTA5H9vFZ0wFlg8L1.P8oezylyAGSJ5qZK8pYyGw9deG7JPN5F2mW', 'ERRADY', 'KANZA', '0610101010', 'RABAT', 'admin', NULL, NULL, NULL, NULL, 'active', '2025-04-26 11:16:51', '2025-04-26 11:16:51', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user_sessions`
--

CREATE TABLE `user_sessions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `last_activity` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `workflows`
--

CREATE TABLE `workflows` (
  `id` int(11) NOT NULL,
  `type_workflow` enum('INTEGRATION','DEPART','MOBILITE') NOT NULL,
  `nom_etape` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `ordre` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categories_etablissements`
--
ALTER TABLE `categories_etablissements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_categories_etablissements_deleted_at` (`deleted_at`);

--
-- Index pour la table `corps`
--
ALTER TABLE `corps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_corps_deleted_at` (`deleted_at`);

--
-- Index pour la table `formations_sanitaires`
--
ALTER TABLE `formations_sanitaires`
  ADD PRIMARY KEY (`id`),
  ADD KEY `province_id` (`province_id`),
  ADD KEY `categorie_id` (`categorie_id`),
  ADD KEY `idx_formations_sanitaires_deleted_at` (`deleted_at`);

--
-- Index pour la table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `corps_id` (`corps_id`),
  ADD KEY `idx_grades_deleted_at` (`deleted_at`);

--
-- Index pour la table `mouvements_personnel`
--
ALTER TABLE `mouvements_personnel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `personnel_id` (`personnel_id`),
  ADD KEY `formation_sanitaire_origine_id` (`formation_sanitaire_origine_id`),
  ADD KEY `formation_sanitaire_destination_id` (`formation_sanitaire_destination_id`),
  ADD KEY `idx_mouvements_personnel_deleted_at` (`deleted_at`);

--
-- Index pour la table `personnel`
--
ALTER TABLE `personnel`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ppr` (`ppr`),
  ADD UNIQUE KEY `cin` (`cin`),
  ADD KEY `corps_id` (`corps_id`),
  ADD KEY `grade_id` (`grade_id`),
  ADD KEY `specialite_id` (`specialite_id`),
  ADD KEY `formation_sanitaire_id` (`formation_sanitaire_id`),
  ADD KEY `idx_personnel_deleted_at` (`deleted_at`);

--
-- Index pour la table `provinces`
--
ALTER TABLE `provinces`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_provinces_deleted_at` (`deleted_at`);

--
-- Index pour la table `specialites`
--
ALTER TABLE `specialites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_specialites_deleted_at` (`deleted_at`);

--
-- Index pour la table `suivi_workflows`
--
ALTER TABLE `suivi_workflows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `personnel_id` (`personnel_id`),
  ADD KEY `workflow_id` (`workflow_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_users_deleted_at` (`deleted_at`);

--
-- Index pour la table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_sessions_token` (`token`),
  ADD KEY `idx_user_sessions_user` (`user_id`);

--
-- Index pour la table `workflows`
--
ALTER TABLE `workflows`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categories_etablissements`
--
ALTER TABLE `categories_etablissements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `corps`
--
ALTER TABLE `corps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `formations_sanitaires`
--
ALTER TABLE `formations_sanitaires`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `grades`
--
ALTER TABLE `grades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `mouvements_personnel`
--
ALTER TABLE `mouvements_personnel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT pour la table `personnel`
--
ALTER TABLE `personnel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT pour la table `provinces`
--
ALTER TABLE `provinces`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `specialites`
--
ALTER TABLE `specialites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `suivi_workflows`
--
ALTER TABLE `suivi_workflows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `user_sessions`
--
ALTER TABLE `user_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `workflows`
--
ALTER TABLE `workflows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `formations_sanitaires`
--
ALTER TABLE `formations_sanitaires`
  ADD CONSTRAINT `formations_sanitaires_ibfk_1` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`),
  ADD CONSTRAINT `formations_sanitaires_ibfk_2` FOREIGN KEY (`categorie_id`) REFERENCES `categories_etablissements` (`id`);

--
-- Contraintes pour la table `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`corps_id`) REFERENCES `corps` (`id`);

--
-- Contraintes pour la table `mouvements_personnel`
--
ALTER TABLE `mouvements_personnel`
  ADD CONSTRAINT `mouvements_personnel_ibfk_1` FOREIGN KEY (`personnel_id`) REFERENCES `personnel` (`id`),
  ADD CONSTRAINT `mouvements_personnel_ibfk_2` FOREIGN KEY (`formation_sanitaire_origine_id`) REFERENCES `formations_sanitaires` (`id`),
  ADD CONSTRAINT `mouvements_personnel_ibfk_3` FOREIGN KEY (`formation_sanitaire_destination_id`) REFERENCES `formations_sanitaires` (`id`);

--
-- Contraintes pour la table `personnel`
--
ALTER TABLE `personnel`
  ADD CONSTRAINT `personnel_ibfk_1` FOREIGN KEY (`corps_id`) REFERENCES `corps` (`id`),
  ADD CONSTRAINT `personnel_ibfk_2` FOREIGN KEY (`grade_id`) REFERENCES `grades` (`id`),
  ADD CONSTRAINT `personnel_ibfk_3` FOREIGN KEY (`specialite_id`) REFERENCES `specialites` (`id`),
  ADD CONSTRAINT `personnel_ibfk_4` FOREIGN KEY (`formation_sanitaire_id`) REFERENCES `formations_sanitaires` (`id`);

--
-- Contraintes pour la table `suivi_workflows`
--
ALTER TABLE `suivi_workflows`
  ADD CONSTRAINT `suivi_workflows_ibfk_1` FOREIGN KEY (`personnel_id`) REFERENCES `personnel` (`id`),
  ADD CONSTRAINT `suivi_workflows_ibfk_2` FOREIGN KEY (`workflow_id`) REFERENCES `workflows` (`id`);

--
-- Contraintes pour la table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD CONSTRAINT `user_sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  lun. 25 mai 2020 à 11:19
-- Version du serveur :  5.7.19
-- Version de PHP :  7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `attitudeefficace`
--

-- --------------------------------------------------------

--
-- Structure de la table `administrateurs`
--

DROP TABLE IF EXISTS `administrateurs`;
CREATE TABLE IF NOT EXISTS `administrateurs` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8_bin NOT NULL,
  `login` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `slug` varchar(1) COLLATE utf8_bin DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `categorie` varchar(255) COLLATE utf8_bin DEFAULT 'utilisateur',
  `statut` varchar(255) COLLATE utf8_bin DEFAULT 'activé',
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_modification` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UN_admin_code` (`code`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `administrateurs`
--

INSERT INTO `administrateurs` (`id`, `code`, `login`, `slug`, `password`, `email`, `categorie`, `statut`, `date_creation`, `date_modification`) VALUES
(1, 'u1s73YMd1rToMd', 'joel', NULL, '$2y$10$NlCt2e.XtG8DmZFYiSB0suGNVX6G0ZeNLny6mdLAriyTUnxXgMQge', 'tanohbassapatrick@gmail.com', 'administrateur', 'activé', '2019-10-01 08:22:06', NULL),
(7, 'ITyPZnLwd', 'benoit', NULL, '$2y$10$XxpT8MxPW4VUFFtNuWWwS.diIqFvN1bnUUx9Sw4/J3CwNXvvrJA22', 'abc@gmail.com', 'utilisateur', 'activé', '2020-04-14 11:04:26', '2020-04-14 11:04:26');

-- --------------------------------------------------------

--
-- Structure de la table `clients_miniservices`
--

DROP TABLE IF EXISTS `clients_miniservices`;
CREATE TABLE IF NOT EXISTS `clients_miniservices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `first_names` varchar(255) COLLATE utf8_bin NOT NULL,
  `adresse_email` varchar(255) COLLATE utf8_bin NOT NULL,
  `contact` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UN_ADRESSE_EMAIL` (`adresse_email`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `clients_miniservices`
--

INSERT INTO `clients_miniservices` (`id`, `name`, `first_names`, `adresse_email`, `contact`) VALUES
(1, 'tanoh', 'bassa patrick joel', 'tanohbassapatrick@gmail.com', '+22549324696');

-- --------------------------------------------------------

--
-- Structure de la table `commands`
--

DROP TABLE IF EXISTS `commands`;
CREATE TABLE IF NOT EXISTS `commands` (
  `id` int(15) NOT NULL,
  `id_service` int(15) DEFAULT NULL,
  `id_client` int(15) DEFAULT NULL,
  `description` text COLLATE utf8_bin,
  `date_commande` datetime DEFAULT NULL,
  `geree` char(5) COLLATE utf8_bin DEFAULT 'non',
  PRIMARY KEY (`id`),
  KEY `fk_client_id` (`id_client`),
  KEY `fk_miniservices_id` (`id_service`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `item_childs`
--

DROP TABLE IF EXISTS `item_childs`;
CREATE TABLE IF NOT EXISTS `item_childs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `categorie` varchar(255) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `slug` varchar(300) DEFAULT NULL,
  `article_content` text,
  `author` varchar(255) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `pages` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT '0',
  `rang` int(11) DEFAULT '0',
  `edition_home` varchar(255) DEFAULT NULL,
  `annee_parution` char(4) DEFAULT NULL,
  `date_creation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modification` datetime DEFAULT NULL,
  `date_post` datetime DEFAULT NULL,
  `video_link` varchar(255) DEFAULT NULL,
  `views` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UN_CODE` (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `item_parents`
--

DROP TABLE IF EXISTS `item_parents`;
CREATE TABLE IF NOT EXISTS `item_parents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `categorie` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `slug` varchar(300) DEFAULT NULL,
  `price` int(11) DEFAULT '0',
  `rang` int(11) DEFAULT '0',
  `date_creation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modification` datetime DEFAULT NULL,
  `date_post` datetime DEFAULT NULL,
  `views` int(11) DEFAULT '0',
  `video_link` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=83 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `item_parents_suivies`
--

DROP TABLE IF EXISTS `item_parents_suivies`;
CREATE TABLE IF NOT EXISTS `item_parents_suivies` (
  `id` int(11) NOT NULL,
  `item_parent_id` int(11) NOT NULL,
  `learner_id` int(11) NOT NULL,
  `date_debut` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_fin` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_id_categorie` (`item_parent_id`),
  KEY `FK_id_abonne` (`learner_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `item_parents_suivies`
--

INSERT INTO `item_parents_suivies` (`id`, `item_parent_id`, `learner_id`, `date_debut`, `date_fin`) VALUES
(1, 51, 1, '2020-04-24 15:16:47', NULL),
(2, 52, 1, '2020-04-24 15:18:35', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `newsletters`
--

DROP TABLE IF EXISTS `newsletters`;
CREATE TABLE IF NOT EXISTS `newsletters` (
  `id` int(11) NOT NULL,
  `adresse_email` text NOT NULL,
  `date_abonnement` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `newsletters`
--

INSERT INTO `newsletters` (`id`, `adresse_email`, `date_abonnement`) VALUES
(1, 'tanohbassapatrick@gmail.com', '2020-04-24 14:51:52'),
(2, 'joel.developpeur@gmail.com', '2020-04-24 14:52:15');

-- --------------------------------------------------------

--
-- Structure de la table `options`
--

DROP TABLE IF EXISTS `options`;
CREATE TABLE IF NOT EXISTS `options` (
  `intitule` text,
  `valeur` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `parent_and_child`
--

DROP TABLE IF EXISTS `parent_and_child`;
CREATE TABLE IF NOT EXISTS `parent_and_child` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `child_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`) USING BTREE,
  KEY `child_id` (`child_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `suscribers`
--

DROP TABLE IF EXISTS `suscribers`;
CREATE TABLE IF NOT EXISTS `suscribers` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `first_names` varchar(255) COLLATE utf8_bin NOT NULL,
  `password` varchar(255) COLLATE utf8_bin NOT NULL,
  `contact` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `adresse_email` varchar(255) COLLATE utf8_bin NOT NULL,
  `date_abonnement` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UN_MAIL` (`adresse_email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `suscribers`
--

INSERT INTO `suscribers` (`id`, `name`, `first_names`, `password`, `contact`, `adresse_email`, `date_abonnement`) VALUES
(1, 'tanoh', 'bassa patrick joel', '$2y$10$NlCt2e.XtG8DmZFYiSB0suGNVX6G0ZeNLny6mdLAriyTUnxXgMQge', '+22549324696', 'tanohbassapatrick@gmail.com', '2020-04-24 15:13:48');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `item_childs`
--
ALTER TABLE `item_childs` ADD FULLTEXT KEY `RECH_CONTENT` (`article_content`);
ALTER TABLE `item_childs` ADD FULLTEXT KEY `title` (`title`);

--
-- Index pour la table `item_parents`
--
ALTER TABLE `item_parents` ADD FULLTEXT KEY `title` (`title`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

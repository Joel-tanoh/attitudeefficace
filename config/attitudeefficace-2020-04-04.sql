-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  ven. 03 avr. 2020 à 13:29
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
-- Structure de la table `abonnes`
--

DROP TABLE IF EXISTS `abonnes`;
CREATE TABLE IF NOT EXISTS `abonnes` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `first_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `password` varchar(255) COLLATE utf8_bin NOT NULL,
  `contact` int(15) DEFAULT NULL,
  `adresse_email` varchar(255) COLLATE utf8_bin NOT NULL,
  `date_abonnement` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UN_MAIL` (`adresse_email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `administrateurs`
--

DROP TABLE IF EXISTS `administrateurs`;
CREATE TABLE IF NOT EXISTS `administrateurs` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8_bin NOT NULL,
  `login` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_bin DEFAULT 'utilisateur',
  `statut` varchar(255) COLLATE utf8_bin DEFAULT 'activé',
  `date_creation` datetime DEFAULT NULL,
  `date_modification` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UN_admin_code` (`code`) USING BTREE,
  UNIQUE KEY `UN_admin_login` (`login`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `administrateurs`
--

INSERT INTO `administrateurs` (`id`, `code`, `login`, `slug`, `password`, `email`, `type`, `statut`, `date_creation`, `date_modification`) VALUES
(1, 'u1s73YMd1rToMd', 'joel', NULL, '$2y$10$NlCt2e.XtG8DmZFYiSB0suGNVX6G0ZeNLny6mdLAriyTUnxXgMQge', 'tanohbassapatrick@gmail.com', 'administrateur', 'activé', '2019-10-01 08:22:06', NULL),
(15, 'OWMtxB', 'benoit', 'benoit', '$2y$10$.aXNhvidFGwPIvtG2TdG2.PC3qmWL26eKV3bYC2d3MuhoQu/49A9W', NULL, 'utilisateur', 'activé', '2020-03-10 13:18:12', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `slug` varchar(300) DEFAULT NULL,
  `prix` int(11) DEFAULT NULL,
  `rang` int(11) DEFAULT NULL,
  `date_creation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modification` datetime DEFAULT NULL,
  `date_post` datetime DEFAULT NULL,
  `video_link` varchar(255) DEFAULT NULL,
  `image_name` varchar(255) DEFAULT 'defaut.png',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `code`, `type`, `title`, `description`, `slug`, `prix`, `rang`, `date_creation`, `date_modification`, `date_post`, `video_link`, `image_name`) VALUES
(31, 'cjCoH', 'formations', 'test', 'test', 'formations-test-31', 15000, 1, '2020-03-26 15:27:51', '2020-03-26 15:27:51', NULL, NULL, 'formations-test-31'),
(32, 'rqSXOE', 'themes', 'développement personnel', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorem quis atque ad saepe, non totam mollitia laudantium? Dicta similique, sed velit asperiores.', 'themes-developpement-personnel-32', NULL, NULL, '2020-03-26 15:37:50', '2020-03-26 15:37:50', NULL, NULL, 'defaut.png'),
(30, 'iOf_k', 'formations', 'penser comme un leader', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Magni saepe quas, ducimus harum neque, molestiae, itaque voluptatum ullam ea at et reiciendis. Asperiores, ducimus molestias?', 'formations-penser-comme-un-leader-30', 15000, 1, '2020-03-26 15:23:42', '2020-03-26 15:23:42', NULL, 'R9gACncMkoo', 'formations-penser-comme-un-leader-30');

-- --------------------------------------------------------

--
-- Structure de la table `categories_elements`
--

DROP TABLE IF EXISTS `categories_elements`;
CREATE TABLE IF NOT EXISTS `categories_elements` (
  `id_creation` int(11) NOT NULL AUTO_INCREMENT,
  `id_categorie` int(11) NOT NULL,
  `id_element` int(11) NOT NULL,
  PRIMARY KEY (`id_creation`),
  KEY `id_categorie` (`id_categorie`),
  KEY `id_element` (`id_element`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `categories_suivies`
--

DROP TABLE IF EXISTS `categories_suivies`;
CREATE TABLE IF NOT EXISTS `categories_suivies` (
  `id` int(11) NOT NULL,
  `id_categorie` int(11) NOT NULL,
  `id_abonne` int(11) NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_id_categorie` (`id_categorie`),
  KEY `FK_id_abonne` (`id_abonne`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `first_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `adresse_email` varchar(255) COLLATE utf8_bin NOT NULL,
  `contact` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UN_ADRESSE_EMAIL` (`adresse_email`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `commands`
--

DROP TABLE IF EXISTS `commands`;
CREATE TABLE IF NOT EXISTS `commands` (
  `id` int(15) NOT NULL,
  `miniservice_id` int(15) DEFAULT NULL,
  `client_id` int(15) DEFAULT NULL,
  `description` text COLLATE utf8_bin,
  `date_commande` datetime DEFAULT NULL,
  `geree` char(5) COLLATE utf8_bin DEFAULT 'non',
  PRIMARY KEY (`id`),
  KEY `fk_client_id` (`client_id`),
  KEY `fk_miniservices_id` (`miniservice_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `elements`
--

DROP TABLE IF EXISTS `elements`;
CREATE TABLE IF NOT EXISTS `elements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `categorie_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `slug` varchar(300) DEFAULT NULL,
  `content` text,
  `auteur` varchar(255) DEFAULT NULL,
  `fournisseur` varchar(255) DEFAULT NULL,
  `nombre_page` int(11) DEFAULT NULL,
  `prix` int(11) DEFAULT NULL,
  `maison_edition` varchar(255) DEFAULT NULL,
  `annee_parution` char(4) DEFAULT NULL,
  `date_creation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modification` datetime DEFAULT NULL,
  `date_post` datetime DEFAULT NULL,
  `video_link` varchar(255) DEFAULT NULL,
  `nbr_vues` int(11) DEFAULT '0',
  `image_name` varchar(255) DEFAULT 'defaut.png',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UN_CODE` (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `elements`
--

INSERT INTO `elements` (`id`, `code`, `type`, `categorie_id`, `title`, `description`, `slug`, `content`, `auteur`, `fournisseur`, `nombre_page`, `prix`, `maison_edition`, `annee_parution`, `date_creation`, `date_modification`, `date_post`, `video_link`, `nbr_vues`, `image_name`) VALUES
(1, 'AbXyg', 'articles', 9, 'Essai', 'Description de essai', 'articles-essai-1', 'Contenu de essai', NULL, NULL, NULL, NULL, NULL, NULL, '2020-03-16 21:00:00', NULL, NULL, NULL, 0, 'defaut.png'),
(2, 'Avgde', 'articles', 9, 'Article 2', 'Description de article 2', 'article-2', 'Contenu de article 2', NULL, NULL, NULL, NULL, NULL, NULL, '2020-03-16 21:00:00', NULL, NULL, NULL, 0, 'defaut.png'),
(3, 'AscEh', 'videos', 1, 'une belle vidéo', 'Description d\'une belle vidéo', 'une-belle-video-3', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-03-27 23:29:51', NULL, NULL, NULL, 0, 'defaut.png');

-- --------------------------------------------------------

--
-- Structure de la table `email`
--

DROP TABLE IF EXISTS `email`;
CREATE TABLE IF NOT EXISTS `email` (
  `id` int(11) NOT NULL,
  `content` text NOT NULL,
  `date_creation` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `fournisseurs_services`
--

DROP TABLE IF EXISTS `fournisseurs_services`;
CREATE TABLE IF NOT EXISTS `fournisseurs_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `first_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `adresse_email` varchar(255) COLLATE utf8_bin NOT NULL,
  `contact` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `adresse_email` (`adresse_email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `fournisseurs_services`
--

INSERT INTO `fournisseurs_services` (`id`, `name`, `first_name`, `adresse_email`, `contact`) VALUES
(1, 'Tanoh', 'Joel', 'abc@abc.abc', '+22549324696');

-- --------------------------------------------------------

--
-- Structure de la table `newsletters`
--

DROP TABLE IF EXISTS `newsletters`;
CREATE TABLE IF NOT EXISTS `newsletters` (
  `id` int(11) NOT NULL,
  `id_mail` int(11) NOT NULL,
  `adresse_email` varchar(255) COLLATE utf8_bin NOT NULL,
  `date_envoi` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_ID_MAIL` (`id_mail`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `services_providers`
--

DROP TABLE IF EXISTS `services_providers`;
CREATE TABLE IF NOT EXISTS `services_providers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `first_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `adresse_email` varchar(255) COLLATE utf8_bin NOT NULL,
  `numero_telephone_1` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `numero_telephone_2` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `adresse_email` (`adresse_email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `services_providers`
--

INSERT INTO `services_providers` (`id`, `name`, `first_name`, `adresse_email`, `numero_telephone_1`, `numero_telephone_2`) VALUES
(1, 'Tanoh', 'Joel', 'abc@abc.abc', '+22549324696', NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `elements`
--
ALTER TABLE `elements` ADD FULLTEXT KEY `RECH_CONTENT` (`content`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

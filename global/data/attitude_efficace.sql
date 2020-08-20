-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  jeu. 20 août 2020 à 20:47
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
-- Base de données :  `attitude_efficace`
--

-- --------------------------------------------------------

--
-- Structure de la table `administrators`
--

DROP TABLE IF EXISTS `administrators`;
CREATE TABLE IF NOT EXISTS `administrators` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8_bin NOT NULL,
  `login` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `email_address` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `role` int(11) NOT NULL DEFAULT '2',
  `birth_day` date DEFAULT NULL,
  `state` varchar(255) COLLATE utf8_bin DEFAULT 'activé',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UN_admin_code` (`code`) USING BTREE,
  KEY `email_address` (`email_address`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `administrators`
--

INSERT INTO `administrators` (`id`, `code`, `login`, `password`, `email_address`, `role`, `birth_day`, `state`, `created_at`, `updated_at`) VALUES
(1, 'u1s73YMd1rToMd', 'joel', '$2y$10$NlCt2e.XtG8DmZFYiSB0suGNVX6G0ZeNLny6mdLAriyTUnxXgMQge', 'joel.developpeur@gmail.com', 3, NULL, 'activé', '2019-10-01 08:22:06', NULL),
(7, 'ITyPZnLwd', 'benoit', '$2y$10$XxpT8MxPW4VUFFtNuWWwS.diIqFvN1bnUUx9Sw4/J3CwNXvvrJA22', 'benoitkoua2015@gmail.com', 2, NULL, 'activé', '2020-04-14 11:04:26', '2020-04-14 11:04:26');

-- --------------------------------------------------------

--
-- Structure de la table `baskets`
--

DROP TABLE IF EXISTS `baskets`;
CREATE TABLE IF NOT EXISTS `baskets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(50) COLLATE utf8_bin NOT NULL,
  `email_address` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `products_codes` text COLLATE utf8_bin,
  `statut` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_session_id` (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `slug` varchar(255) COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `title`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'formations', 'formations', NULL, '2020-08-05 20:48:10', NULL),
(2, 'thèmes', 'themes', NULL, '2020-08-05 20:48:21', NULL),
(3, 'étapes', 'etapes', NULL, '2020-08-05 20:48:56', NULL),
(4, 'articles', 'articles', NULL, '2020-08-05 20:49:04', NULL),
(5, 'vidéos', 'videos', NULL, '2020-08-05 20:49:15', NULL),
(6, 'livres', 'livres', NULL, '2020-08-05 20:49:37', NULL),
(7, 'ebooks', 'ebooks', NULL, '2020-08-05 20:49:45', NULL),
(8, 'mini services', 'mini-services', NULL, '2020-08-05 21:21:10', NULL),
(9, 'motivation plus', 'motivation-plus', NULL, '2020-08-05 21:22:50', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `items_child`
--

DROP TABLE IF EXISTS `items_child`;
CREATE TABLE IF NOT EXISTS `items_child` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `categorie` varchar(255) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `slug` varchar(300) DEFAULT NULL,
  `article_content` longtext,
  `author` varchar(255) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `pages` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT '0',
  `rank` int(11) DEFAULT '0',
  `edition_home` varchar(255) DEFAULT NULL,
  `parution_year` char(5) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `posted_at` datetime DEFAULT NULL,
  `youtube_video_link` varchar(255) DEFAULT NULL,
  `views` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UN_CODE` (`code`),
  KEY `fk_parent_id` (`parent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=110 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `items_child`
--

INSERT INTO `items_child` (`id`, `code`, `categorie`, `parent_id`, `title`, `description`, `slug`, `article_content`, `author`, `provider`, `pages`, `price`, `rank`, `edition_home`, `parution_year`, `created_at`, `updated_at`, `posted_at`, `youtube_video_link`, `views`) VALUES
(107, '2G_ylpTWSk', 'videos', 0, 'Lorem toto', 'Une belle description', 'lorem-toto-107', NULL, NULL, NULL, NULL, 0, 1, NULL, NULL, '2020-07-26 21:25:21', '2020-08-18 05:39:57', NULL, 'R9gACncMkoo', 0),
(108, 'ScBJUp3cxG', 'livres', 145, 'Lorem idor', 'Une belle description', 'lorem-idor-108', '', NULL, NULL, NULL, 0, 1, NULL, NULL, '2020-07-26 21:32:23', '2020-07-26 21:32:24', NULL, '', 0),
(105, 'OxKgbS', 'articles', 0, 'Lorem ipsum', 'Lorem ipsum dolor sit amet consectetur a. Reiciendis tempore quae odio perferendis pariatur. Placeat aliquid sapiente consequuntur ullam alias vitae rem iure aperiam dolor dolorum culpa sit eius quas fugit blanditiis nisi nemo dolores repellat pariatur, maiores corrupti ipsa commodi enim.ipisicing elit. Eveniet voluptatem labore cupiditate molestiae porro velit inventore totam eos?', 'lorem-ipsum-105', 'Vide<br>', NULL, NULL, NULL, 0, 1, NULL, NULL, '2020-07-21 22:03:47', '2020-08-20 15:11:54', NULL, '', 0),
(109, 'sexjYacqto', 'articles', 143, 'Papa mange su riz', 'Du bon riz je vous dit...', 'papa-mange-su-riz-109', '&lt;br&gt;', NULL, NULL, NULL, 0, 10, NULL, NULL, '2020-08-13 19:40:33', '2020-08-13 19:40:33', NULL, '', 0);

-- --------------------------------------------------------

--
-- Structure de la table `items_parent`
--

DROP TABLE IF EXISTS `items_parent`;
CREATE TABLE IF NOT EXISTS `items_parent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `categorie` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `slug` varchar(300) DEFAULT NULL,
  `price` int(11) DEFAULT '0',
  `rank` int(11) DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `posted_at` datetime DEFAULT NULL,
  `views` int(11) DEFAULT '0',
  `youtube_video_link` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `items_parent` (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=146 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `items_parent`
--

INSERT INTO `items_parent` (`id`, `code`, `categorie`, `title`, `description`, `slug`, `price`, `rank`, `created_at`, `updated_at`, `posted_at`, `views`, `youtube_video_link`) VALUES
(143, 'PGJThbPBiqw', 'formations', 'Lorem ipsum', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'lorem-ipsum-143', 0, 1, '2020-07-13 05:56:05', '2020-07-27 12:02:08', NULL, 0, ''),
(140, 'XG1Cny', 'themes', 'Développement personnel', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Eveniet voluptatem labore cupiditate molestiae porro velit inventore totam eos? Reiciendis tempore quae odio perferendis pariatur.', 'developpement-personnel-140', 0, 1, '2020-07-09 15:35:19', '2020-07-10 09:08:29', '2020-07-10 09:08:29', 0, 'R9gACncMkoo');

-- --------------------------------------------------------

--
-- Structure de la table `miniservices_customers`
--

DROP TABLE IF EXISTS `miniservices_customers`;
CREATE TABLE IF NOT EXISTS `miniservices_customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `last_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `first_names` varchar(255) COLLATE utf8_bin NOT NULL,
  `email_address` varchar(255) COLLATE utf8_bin NOT NULL,
  `contact_1` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `contact_2` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UN_ADRESSE_EMAIL` (`email_address`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `miniservices_customers`
--

INSERT INTO `miniservices_customers` (`id`, `last_name`, `first_names`, `email_address`, `contact_1`, `contact_2`) VALUES
(1, 'tanoh', 'bassa patrick joel', 'tanohbassapatrick@gmail.com', '+22549324696', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `miniservices_orders`
--

DROP TABLE IF EXISTS `miniservices_orders`;
CREATE TABLE IF NOT EXISTS `miniservices_orders` (
  `id` int(15) NOT NULL,
  `code` int(11) NOT NULL,
  `miniservice_id` int(15) DEFAULT NULL,
  `customer_id` int(15) DEFAULT NULL,
  `description` text COLLATE utf8_bin,
  `ordered_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `state` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_id_miniservice` (`miniservice_id`) USING BTREE,
  KEY `fk_id_customer` (`customer_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `newsletters`
--

DROP TABLE IF EXISTS `newsletters`;
CREATE TABLE IF NOT EXISTS `newsletters` (
  `id` int(11) NOT NULL,
  `email_address` text NOT NULL,
  `subscription_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `newsletters`
--

INSERT INTO `newsletters` (`id`, `email_address`, `subscription_date`) VALUES
(1, 'tanohbassapatrick@gmail.com', '2020-04-24 14:51:52'),
(2, 'joel.developpeur@gmail.com', '2020-04-24 14:52:15');

-- --------------------------------------------------------

--
-- Structure de la table `options`
--

DROP TABLE IF EXISTS `options`;
CREATE TABLE IF NOT EXISTS `options` (
  `name` text NOT NULL,
  `content` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
CREATE TABLE IF NOT EXISTS `subscriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8_bin NOT NULL,
  `suscriber_email_address` int(11) NOT NULL,
  `item_code` int(11) NOT NULL,
  `subscription_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_suscriber_email_address` (`suscriber_email_address`) USING BTREE,
  KEY `FK_item_code` (`item_code`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `suscribers`
--

DROP TABLE IF EXISTS `suscribers`;
CREATE TABLE IF NOT EXISTS `suscribers` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8_bin NOT NULL,
  `last_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `first_names` varchar(255) COLLATE utf8_bin NOT NULL,
  `password` varchar(255) COLLATE utf8_bin NOT NULL,
  `role` int(11) NOT NULL DEFAULT '1',
  `state` varchar(255) COLLATE utf8_bin DEFAULT 'activé',
  `contact_1` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `contact_2` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `email_address` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UN_MAIL` (`email_address`),
  UNIQUE KEY `suscrierbers` (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `visitors`
--

DROP TABLE IF EXISTS `visitors`;
CREATE TABLE IF NOT EXISTS `visitors` (
  `session_id` varchar(50) COLLATE utf8_bin NOT NULL,
  `date_visit` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_action_date` datetime DEFAULT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `visitors`
--

INSERT INTO `visitors` (`session_id`, `date_visit`, `last_action_date`) VALUES
('4SGK5IspuVEX9BztdoBKwkd5jw', '2020-08-06 22:37:23', NULL),
('p0R5T_2DTlI9gUOQg', '2020-08-06 22:53:31', NULL),
('zEpoLs9jBChy', '2020-08-07 05:28:16', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `visits`
--

DROP TABLE IF EXISTS `visits`;
CREATE TABLE IF NOT EXISTS `visits` (
  `date` date NOT NULL,
  `number` int(11) DEFAULT '1',
  PRIMARY KEY (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `visits`
--

INSERT INTO `visits` (`date`, `number`) VALUES
('2020-08-06', 1),
('2020-08-07', 1),
('2020-08-13', 1),
('2020-08-16', 1),
('2020-08-19', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `items_child`
--
ALTER TABLE `items_child` ADD FULLTEXT KEY `RECH_CONTENT` (`article_content`);
ALTER TABLE `items_child` ADD FULLTEXT KEY `RECH_TITLE` (`title`);
ALTER TABLE `items_child` ADD FULLTEXT KEY `RCH_SLUG` (`slug`);

--
-- Index pour la table `items_parent`
--
ALTER TABLE `items_parent` ADD FULLTEXT KEY `RCH_TITLE` (`title`);
ALTER TABLE `items_parent` ADD FULLTEXT KEY `RCH_SLUG` (`slug`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

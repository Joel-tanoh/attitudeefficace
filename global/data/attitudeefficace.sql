-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  jeu. 11 juin 2020 à 15:54
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
  `article_content` text,
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
  UNIQUE KEY `UN_CODE` (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=66 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `items_child`
--

INSERT INTO `items_child` (`id`, `code`, `categorie`, `parent_id`, `title`, `description`, `slug`, `article_content`, `author`, `provider`, `pages`, `price`, `rank`, `edition_home`, `parution_year`, `created_at`, `updated_at`, `posted_at`, `youtube_video_link`, `views`) VALUES
(63, '0JzU35gZr', 'articles', 85, 'Lorem ipsum', 'Une belle description', 'lorem-ipsum-63', '&lt;p&gt;&lt;b&gt;Texte en gras&lt;/b&gt;&lt;/p&gt;&lt;p&gt;&lt;u&gt;Texte souligné&lt;/u&gt;&lt;b&gt;&lt;br&gt;&lt;/b&gt;&lt;br&gt;&lt;/p&gt;', NULL, NULL, NULL, 0, 2, NULL, NULL, '2020-06-05 22:02:46', '2020-06-05 22:02:46', NULL, NULL, 0),
(58, 'rg8Jy2j', 'livres', 0, 'Lorem ipsum', 'Belle description', 'lorem-ipsum-58', NULL, NULL, NULL, NULL, 0, 1, NULL, NULL, '2020-06-04 13:06:20', '2020-06-04 13:06:21', NULL, NULL, 0),
(59, 'j2QjWia', 'articles', 84, 'Avoir une vision', 'Une belle description', 'avoir-une-vision-59', '<p>Un beau texte pour cet article.</p>', NULL, NULL, NULL, 0, 1, NULL, NULL, '2020-06-05 00:44:42', '2020-06-05 13:12:59', NULL, 'R9gACncMkoo', 0),
(62, 'ypxPfUtq', 'ebooks', 83, 'Lorem toto', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Eveniet voluptatem labore cupiditate molestiae porro velit inventore totam eos? Reiciendis tempore quae odio perferendis pariatur.', 'lorem-toto-62', NULL, NULL, NULL, NULL, 0, 1, NULL, NULL, '2020-06-05 10:45:35', '2020-06-06 00:21:08', NULL, '', 0);

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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=92 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `items_parent`
--

INSERT INTO `items_parent` (`id`, `code`, `categorie`, `title`, `description`, `slug`, `price`, `rank`, `created_at`, `updated_at`, `posted_at`, `views`, `youtube_video_link`) VALUES
(84, 'LvDkJDkqb', 'themes', 'Développement personnel', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Totam fugit deserunt laboriosam ut. Excepturi dolores nam unde possimus? Minus, a voluptates? Perferendis at consectetur cupiditate!', 'developpement-personnel-84', 0, 1, '2020-05-25 19:37:01', '2020-05-25 19:37:01', NULL, 0, NULL),
(85, 'zel7_FIv', 'themes', 'Leadership', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Totam fugit deserunt laboriosam ut. Excepturi dolores nam unde possimus? Minus, a voluptates? Perferendis at consectetur cupiditate!', 'leadership-85', 0, 2, '2020-05-25 19:38:08', '2020-05-25 19:38:08', NULL, 0, NULL),
(86, 'vcdCSG3', 'themes', 'Séduction', 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quasi laborum tenetur fugit eveniet exercitationem! Vitae blanditiis veniam laborum corporis delectus.', 'seduction-86', 0, 3, '2020-05-25 19:59:15', '2020-05-25 19:59:15', NULL, 0, NULL),
(87, 'ZK8DgL1t', 'themes', 'Art oratoire', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Necessitatibus cumque sed ex dolores quos aperiam reprehenderit labore cum, possimus aliquam?', 'art-oratoire-87', 0, 4, '2020-05-25 20:28:40', '2020-05-25 20:28:40', NULL, 0, NULL),
(88, '_kP1ccBZT', 'etapes', 'Lorem ipsum', 'Une belle description', 'lorem-ipsum-88', 0, 1, '2020-05-26 12:12:35', '2020-06-02 18:40:50', NULL, 0, '');

-- --------------------------------------------------------

--
-- Structure de la table `items_parent_suscribed`
--

DROP TABLE IF EXISTS `items_parent_suscribed`;
CREATE TABLE IF NOT EXISTS `items_parent_suscribed` (
  `id` int(11) NOT NULL,
  `item_parent_id` int(11) NOT NULL,
  `suscriber_id` int(11) NOT NULL,
  `date_begin` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_end` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_id_categorie` (`item_parent_id`),
  KEY `FK_suscriber_id` (`suscriber_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `items_parent_suscribed`
--

INSERT INTO `items_parent_suscribed` (`id`, `item_parent_id`, `suscriber_id`, `date_begin`, `date_end`) VALUES
(1, 51, 1, '2020-04-24 15:16:47', NULL),
(2, 52, 1, '2020-04-24 15:18:35', NULL);

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
  `state` varchar(255) COLLATE utf8_bin DEFAULT 'NEW',
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
  `suscriber_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `subscription_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_suscriber_id` (`suscriber_id`),
  KEY `FK_item_id` (`item_id`)
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
  `state` int(11) NOT NULL DEFAULT '1',
  `contact_1` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `contact_2` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `email_address` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UN_MAIL` (`email_address`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `suscribers`
--

INSERT INTO `suscribers` (`id`, `code`, `last_name`, `first_names`, `password`, `role`, `state`, `contact_1`, `contact_2`, `email_address`) VALUES
(1, 'XdvCjK202', 'tanoh', 'bassa patrick joel', '$2y$10$NlCt2e.XtG8DmZFYiSB0suGNVX6G0ZeNLny6mdLAriyTUnxXgMQge', 1, 1, '+22549324696', NULL, 'tanohbassapatrick@gmail.com');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
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
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `code`, `login`, `password`, `email_address`, `role`, `birth_day`, `state`, `created_at`, `updated_at`) VALUES
(1, 'u1s73YMd1rToMd', 'joel', '$2y$10$NlCt2e.XtG8DmZFYiSB0suGNVX6G0ZeNLny6mdLAriyTUnxXgMQge', 'joel.developpeur@gmail.com', 3, NULL, 'activé', '2019-10-01 08:22:06', NULL),
(7, 'ITyPZnLwd', 'benoit', '$2y$10$XxpT8MxPW4VUFFtNuWWwS.diIqFvN1bnUUx9Sw4/J3CwNXvvrJA22', 'benoitkoua2015@gmail.com', 2, NULL, 'activé', '2020-04-14 11:04:26', '2020-04-14 11:04:26');

-- --------------------------------------------------------

--
-- Structure de la table `visit_counter`
--

DROP TABLE IF EXISTS `visit_counter`;
CREATE TABLE IF NOT EXISTS `visit_counter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `year` varchar(4) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `month` varchar(2) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `day` varchar(2) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `number` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `visit_counter`
--

INSERT INTO `visit_counter` (`id`, `year`, `month`, `day`, `number`) VALUES
(3, '2020', '05', '26', 2),
(4, '2020', '05', '27', 20),
(5, '2020', '06', '01', 2),
(6, '2020', '06', '04', 1),
(7, '2020', '06', '05', 7),
(8, '2020', '06', '08', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `items_child`
--
ALTER TABLE `items_child` ADD FULLTEXT KEY `RECH_CONTENT` (`article_content`);
ALTER TABLE `items_child` ADD FULLTEXT KEY `title` (`title`);

--
-- Index pour la table `items_parent`
--
ALTER TABLE `items_parent` ADD FULLTEXT KEY `title` (`title`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  ven. 05 juin 2020 à 10:38
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
-- Structure de la table `commands_miniservices`
--

DROP TABLE IF EXISTS `commands_miniservices`;
CREATE TABLE IF NOT EXISTS `commands_miniservices` (
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
-- Structure de la table `compteur_visites`
--

DROP TABLE IF EXISTS `compteur_visites`;
CREATE TABLE IF NOT EXISTS `compteur_visites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `year` varchar(4) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `month` varchar(2) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `day` varchar(2) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `nombre_visite` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `compteur_visites`
--

INSERT INTO `compteur_visites` (`id`, `year`, `month`, `day`, `nombre_visite`) VALUES
(3, '2020', '05', '26', 2),
(4, '2020', '05', '27', 20),
(5, '2020', '06', '01', 2),
(6, '2020', '06', '04', 1),
(7, '2020', '06', '05', 7);

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
) ENGINE=MyISAM AUTO_INCREMENT=62 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `item_childs`
--

INSERT INTO `item_childs` (`id`, `code`, `categorie`, `parent_id`, `title`, `description`, `slug`, `article_content`, `author`, `provider`, `pages`, `price`, `rang`, `edition_home`, `annee_parution`, `date_creation`, `date_modification`, `date_post`, `video_link`, `views`) VALUES
(60, '7SQnfYXJT', 'videos', -1, 'Lorem ipsum', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Eveniet voluptatem labore cupiditate molestiae porro velit inventore totam eos? Reiciendis tempore quae odio perferendis pariatur. Placeat aliquid sapiente consequuntur ullam alias vitae rem iure aperiam dolor dolorum culpa sit eius quas fugit blanditiis nisi nemo dolores repellat pariatur, maiores corrupti ipsa commodi enim.', 'lorem-ipsum-60', NULL, NULL, NULL, NULL, 0, 3, NULL, NULL, '2020-06-05 09:35:35', '2020-06-05 09:35:35', NULL, 'R9gACncMkoo', 0),
(55, 'eQZGGlB', 'videos', -1, 'Lorem', 'Une belle description', 'lorem-55', NULL, NULL, NULL, NULL, 0, 1, NULL, NULL, '2020-05-26 11:50:45', '2020-05-26 11:50:45', NULL, 'R9gACncMkoo', 0),
(56, 'VRsXAOM', 'videos', -1, 'Lorem', 'Une belle description', 'lorem-56', NULL, NULL, NULL, NULL, 0, 2, NULL, NULL, '2020-05-26 11:52:13', '2020-06-05 09:02:10', NULL, '', 0),
(57, '_vk40r', 'minis-services', NULL, 'Développement web', 'Une belle description', 'developpement-web-57', NULL, NULL, NULL, NULL, 0, 1, NULL, NULL, '2020-05-26 12:25:52', '2020-05-26 12:25:52', NULL, NULL, 0),
(58, 'rg8Jy2j', 'livres', 0, 'Lorem ipsum', 'Belle description', 'lorem-ipsum-58', NULL, NULL, NULL, NULL, 0, 1, NULL, NULL, '2020-06-04 13:06:20', '2020-06-04 13:06:21', NULL, NULL, 0),
(59, 'j2QjWia', 'articles', 84, 'Lorem ipsum', 'Une belle description', 'lorem-ipsum-59', 'Un beau texte pour cet article.&lt;br&gt;', NULL, NULL, NULL, 0, 1, NULL, NULL, '2020-06-05 00:44:42', '2020-06-05 00:44:43', NULL, NULL, 0),
(61, '6VSCP3KLE', 'articles', 84, 'Lorem ipsum chipe', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Eveniet voluptatem labore cupiditate molestiae porro velit inventore totam eos? Reiciendis tempore quae odio perferendis pariatur.', 'lorem-ipsum-chipe-61', '&lt;h1 align=&quot;center&quot;&gt;&lt;u&gt;&lt;span style=&quot;font-family: &amp;quot;Impact&amp;quot;;&quot;&gt;Black is Pround&lt;/span&gt;&lt;/u&gt;&lt;/h1&gt;&lt;h3 align=&quot;center&quot;&gt;Yes I think, Black is Pround&lt;br&gt;&lt;/h3&gt;&lt;p&gt;Lorem ipsum dolor sit amet consectetur adipisicing elit. Eveniet voluptatem labore cupiditate molestiae porro velit inventore totam eos? Reiciendis tempore quae odio perferendis pariatur. Placeat aliquid sapiente consequuntur ullam alias vitae rem iure aperiam dolor dolorum culpa sit eius quas fugit blanditiis nisi nemo dolores repellat pariatur, maiores corrupti ipsa commodi enim. Aut eligendi perspiciatis tempore ut similique? Tenetur, eum dignissimos. Aperiam possimus quidem officia vitae similique quisquam in voluptas! Possimus quasi velit est dolore voluptatem doloribus ratione dicta non eligendi sequi! Ipsam ipsum minus esse laudantium ex dolorem, animi magnam nulla repellendus beatae aperiam harum voluptatem. Perspiciatis quia provident a nesciunt dicta, obcaecati ipsam, laboriosam perferendis quae mollitia ratione officia natus omnis facere atque expedita fuga esse. Nobis ex culpa nihil fugiat autem alias temporibus, unde beatae aperiam odit laudantium quae deserunt doloremque sint iure ad placeat voluptas eos magni. Tenetur vitae molestias quidem asperiores esse deleniti rem, at quisquam debitis eos, beatae atque repudiandae facere.&lt;/p&gt;&lt;p&gt;Lorem ipsum dolor sit amet consectetur adipisicing elit. Eveniet \r\nvoluptatem labore cupiditate molestiae porro velit inventore totam eos? \r\nReiciendis tempore quae odio perferendis pariatur. Placeat aliquid \r\nsapiente consequuntur ullam alias vitae rem iure aperiam dolor dolorum \r\nculpa sit eius quas fugit blanditiis nisi nemo dolores repellat \r\npariatur, maiores corrupti ipsa commodi enim. Aut eligendi perspiciatis \r\ntempore ut similique? Tenetur, eum dignissimos. Aperiam possimus quidem \r\nofficia vitae similique quisquam in voluptas! Possimus quasi velit est \r\ndolore voluptatem doloribus ratione dicta non eligendi sequi! Ipsam \r\nipsum minus esse laudantium ex dolorem, animi magnam nulla repellendus \r\nbeatae aperiam harum voluptatem. Perspiciatis quia provident a nesciunt \r\ndicta, obcaecati ipsam, laboriosam perferendis quae mollitia ratione \r\nofficia natus omnis facere atque expedita fuga esse. Nobis ex culpa \r\nnihil fugiat autem alias temporibus, unde beatae aperiam odit laudantium\r\n quae deserunt doloremque sint iure ad placeat voluptas eos magni. \r\nTenetur vitae molestias quidem asperiores esse deleniti rem, at quisquam\r\n debitis eos, beatae atque repudiandae facere.&lt;/p&gt;&lt;p&gt;Lorem ipsum dolor sit amet consectetur adipisicing elit. Eveniet \r\nvoluptatem labore cupiditate molestiae porro velit inventore totam eos? \r\nReiciendis tempore quae odio perferendis pariatur. Placeat aliquid \r\nsapiente consequuntur ullam alias vitae rem iure aperiam dolor dolorum \r\nculpa sit eius quas fugit blanditiis nisi nemo dolores repellat \r\npariatur, maiores corrupti ipsa commodi enim. Aut eligendi perspiciatis \r\ntempore ut similique? Tenetur, eum dignissimos. Aperiam possimus quidem \r\nofficia vitae similique quisquam in voluptas! Possimus quasi velit est \r\ndolore voluptatem doloribus ratione dicta non eligendi sequi! Ipsam \r\nipsum minus esse laudantium ex dolorem, animi magnam nulla repellendus \r\nbeatae aperiam harum voluptatem. Perspiciatis quia provident a nesciunt \r\ndicta, obcaecati ipsam, laboriosam perferendis quae mollitia ratione \r\nofficia natus omnis facere atque expedita fuga esse. Nobis ex culpa \r\nnihil fugiat autem alias temporibus, unde beatae aperiam odit laudantium\r\n quae deserunt doloremque sint iure ad placeat voluptas eos magni. \r\nTenetur vitae molestias quidem asperiores esse deleniti rem, at quisquam\r\n debitis eos, beatae atque repudiandae facere.&lt;/p&gt;', NULL, NULL, NULL, 0, 2, NULL, NULL, '2020-06-05 09:41:18', '2020-06-05 09:41:18', NULL, NULL, 0);

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
) ENGINE=MyISAM AUTO_INCREMENT=92 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `item_parents`
--

INSERT INTO `item_parents` (`id`, `code`, `categorie`, `title`, `description`, `slug`, `price`, `rang`, `date_creation`, `date_modification`, `date_post`, `views`, `video_link`) VALUES
(83, 'IMpFxrEJI', 'formations', 'Penser comme un gagnant', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Totam fugit deserunt laboriosam ut. Excepturi dolores nam unde possimus? Minus, a voluptates? Perferendis at consectetur cupiditate!', 'penser-comme-un-gagnant-83', 2500, 1, '2020-05-25 19:34:15', '2020-05-26 14:24:48', NULL, 0, ''),
(84, 'LvDkJDkqb', 'themes', 'Développement personnel', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Totam fugit deserunt laboriosam ut. Excepturi dolores nam unde possimus? Minus, a voluptates? Perferendis at consectetur cupiditate!', 'developpement-personnel-84', 0, 1, '2020-05-25 19:37:01', '2020-05-25 19:37:01', NULL, 0, NULL),
(85, 'zel7_FIv', 'themes', 'Leadership', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Totam fugit deserunt laboriosam ut. Excepturi dolores nam unde possimus? Minus, a voluptates? Perferendis at consectetur cupiditate!', 'leadership-85', 0, 2, '2020-05-25 19:38:08', '2020-05-25 19:38:08', NULL, 0, NULL),
(86, 'vcdCSG3', 'themes', 'Séduction', 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quasi laborum tenetur fugit eveniet exercitationem! Vitae blanditiis veniam laborum corporis delectus.', 'seduction-86', 0, 3, '2020-05-25 19:59:15', '2020-05-25 19:59:15', NULL, 0, NULL),
(87, 'ZK8DgL1t', 'themes', 'Art oratoire', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Necessitatibus cumque sed ex dolores quos aperiam reprehenderit labore cum, possimus aliquam?', 'art-oratoire-87', 0, 4, '2020-05-25 20:28:40', '2020-05-25 20:28:40', NULL, 0, NULL),
(88, '_kP1ccBZT', 'etapes', 'Lorem ipsum', 'Une belle description', 'lorem-ipsum-88', 0, 1, '2020-05-26 12:12:35', '2020-06-02 18:40:50', NULL, 0, ''),
(89, 'xfQsI7Be', 'formations', 'Lola', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Eveniet voluptatem labore cupiditate molestiae porro velit inventore totam eos? Reiciendis tempore quae odio perferendis pariatur. Placeat aliquid sapiente consequuntur ullam alias vitae rem iure aperiam dolor dolorum culpa sit eius quas fugit blanditiis nisi nemo dolores repellat pariatur, maiores corrupti ipsa commodi enim.', 'lola-89', 1200, 2, '2020-06-05 09:31:22', '2020-06-05 09:31:22', NULL, 0, 'R9gACncMkoo'),
(91, 'Vdg1Zy', 'etapes', 'Brassi', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Eveniet voluptatem labore cupiditate molestiae porro velit inventore totam eos? Reiciendis tempore quae odio perferendis pariatur. Placeat aliquid sapiente consequuntur ullam alias vitae rem iure aperiam dolor dolorum culpa sit eius quas fugit blanditiis nisi nemo dolores repellat pariatur, maiores corrupti ipsa commodi enim.', 'brassi-91', 0, 2, '2020-06-05 09:32:38', '2020-06-05 09:32:38', NULL, 0, NULL);

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

-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mer. 08 avr. 2020 à 15:51
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
  `slug` varchar(1) COLLATE utf8_bin DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_bin DEFAULT 'utilisateur',
  `statut` varchar(255) COLLATE utf8_bin DEFAULT 'activé',
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_modification` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UN_admin_code` (`code`) USING BTREE,
  UNIQUE KEY `UN_admin_login` (`login`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `administrateurs`
--

INSERT INTO `administrateurs` (`id`, `code`, `login`, `slug`, `password`, `email`, `type`, `statut`, `date_creation`, `date_modification`) VALUES
(1, 'u1s73YMd1rToMd', 'joel', NULL, '$2y$10$NlCt2e.XtG8DmZFYiSB0suGNVX6G0ZeNLny6mdLAriyTUnxXgMQge', 'tanohbassapatrick@gmail.com', 'administrateur', 'activé', '2019-10-01 08:22:06', NULL),
(4, 'f1pgt', 'benoit', NULL, '$2y$10$fcx1mm08vYUWGaF8nYe1qunPdEr2ZzpOHdVLWLxm.aTERsqjLJRdm', 'tanohbassapatrick@gmail.com', 'utilisateur', 'activé', '2020-04-06 13:18:44', '2020-04-06 13:18:44');

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
  `price` int(11) DEFAULT NULL,
  `rang` int(11) DEFAULT NULL,
  `date_creation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modification` datetime DEFAULT NULL,
  `date_post` datetime DEFAULT NULL,
  `view` int(11) DEFAULT '0',
  `video_link` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `code`, `type`, `title`, `description`, `slug`, `price`, `rang`, `date_creation`, `date_modification`, `date_post`, `view`, `video_link`) VALUES
(9, 'q5Hh_', 'formations', 'Lorem, ipsum.', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Iure, eaque earum repellendus eum incidunt excepturi!', 'lorem-ipsum-9', 1500, NULL, '2020-04-06 16:27:51', '2020-04-06 16:27:52', NULL, 0, 'R9gACncMkoo'),
(10, 'k0BOB', 'themes', 'Lorem Ipsum', 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Eveniet officiis quia quasi!', 'lorem-ipsum-10', NULL, NULL, '2020-04-06 16:31:25', '2020-04-06 16:31:27', NULL, 0, 'R9gACncMkoo');

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
  `author` varchar(255) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `pages` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `edition_home` varchar(255) DEFAULT NULL,
  `annee_parution` char(4) DEFAULT NULL,
  `date_creation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modification` datetime DEFAULT NULL,
  `date_post` datetime DEFAULT NULL,
  `video_link` varchar(255) DEFAULT NULL,
  `views` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UN_CODE` (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `elements`
--

INSERT INTO `elements` (`id`, `code`, `type`, `categorie_id`, `title`, `description`, `slug`, `content`, `author`, `provider`, `pages`, `price`, `edition_home`, `annee_parution`, `date_creation`, `date_modification`, `date_post`, `video_link`, `views`) VALUES
(9, 's661uB', 'articles', NULL, 'Lorem', 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Eveniet officiis quia quasi!', 'lorem-9', 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Autem aperiam minima iste natus, id voluptatibus sunt iure suscipit, dolores fugit laudantium recusandae? Quos repudiandae a animi cum quasi vero dicta velit minima quaerat nesciunt itaque placeat, obcaecati impedit at deserunt alias. Minima nostrum voluptates magni esse rem ullam sapiente ad aperiam, odit cupiditate illum labore? Distinctio neque eligendi, eveniet dolore est expedita odit dignissimos fuga atque maxime nulla obcaecati sapiente provident itaque. Ad aliquam assumenda debitis qui ab repellendus, impedit, autem iste dolor exercitationem quia explicabo tempore quaerat! Vero, eaque qui culpa quibusdam illo earum perferendis dicta cum hic nam asperiores quo aspernatur, eos quae. Esse sint culpa exercitationem incidunt saepe iure quas pariatur perspiciatis deserunt aspernatur illo sit laudantium ab recusandae debitis quasi, tempore cumque? Eveniet earum autem, nisi nulla asperiores nesciunt placeat, odio consectetur ab maxime tempore! Fugit beatae aliquid, quaerat dolorum, temporibus consequuntur error sed tempore ut ad minus, iure similique. Vitae officiis officia excepturi ea voluptas ducimus dignissimos quo, animi maxime! Aliquid ea sint nostrum atque incidunt ipsa voluptatum quidem, porro minima. Pariatur totam eligendi animi odio tempore necessitatibus nemo officiis ut perspiciatis voluptatum? Esse necessitatibus excepturi at non, officiis corporis nam hic, provident natus nisi officia, asperiores illum aperiam ipsum! Eum sint nostrum sapiente minus? Nesciunt dolor amet, numquam saepe voluptatum quas qui fuga consequuntur? Nulla laborum fugit sequi in voluptatem. Velit vel quam eaque sint recusandae laboriosam aspernatur tempora rerum. Vel ea deleniti numquam at accusantium? Dolore eius laboriosam aliquid nihil soluta ducimus dolores similique eaque? Minima animi quisquam, distinctio earum obcaecati voluptate! Consequatur iure aspernatur enim ullam fugit eaque accusamus facere alias? Eos dolore odit nostrum placeat. Magni ullam dicta nesciunt, doloribus cupiditate quos in ad ratione recusandae? Delectus consectetur sint, optio facilis tempore molestiae, accusantium ratione totam, doloremque quod ab accusamus odio iure similique libero explicabo mollitia commodi! Vitae delectus provident explicabo ut quis, quos quasi? Ab et ut velit reprehenderit eos quibusdam temporibus sapiente libero neque nisi eius praesentium officia eum, nobis modi ducimus molestias quae ipsam, illum sint voluptatibus dolorum qui. Molestias excepturi dolorum earum nostrum a sint consequatur cumque dicta doloremque corporis voluptatibus explicabo autem voluptatem necessitatibus maxime consectetur officia tenetur sit veritatis impedit voluptatum quasi, est maiores officiis. Id a non, ullam nobis totam accusantium sunt quos doloremque consequuntur. Inventore, odio deserunt error eaque, quo dolorum aut dolorem culpa alias quod numquam optio accusamus omnis, maiores at magni ex esse illo expedita reiciendis quos libero? Suscipit nostrum commodi, itaque eaque nesciunt iste dolores qui. Nemo fuga ratione qui natus molestiae! Cum ea nam eum, reiciendis voluptatem vel voluptates amet laborum facere ex? Alias nulla officiis voluptatibus voluptas ea vero aliquid tempore neque animi quae quam fugit soluta necessitatibus quasi quas debitis doloremque odio harum natus, laudantium nostrum voluptatem delectus beatae in! Quas pariatur possimus alias natus sapiente ullam, et ea ab voluptate unde quis fuga excepturi, suscipit, nostrum vel voluptatem vitae accusantium laudantium error dolor reiciendis quia est officiis. Dicta ullam incidunt deserunt, eveniet dignissimos ad. Laborum reprehenderit harum in quidem, maiores eos eius dolores hic mollitia asperiores beatae molestias voluptatem facilis nesciunt nihil quia dolore officia. Doloribus quae, aut adipisci dolore quis fugit delectus. Consectetur, eveniet esse perspiciatis nam nostrum possimus doloremque saepe laudantium distinctio adipisci quos. Eveniet officiis laboriosam, qui deserunt distinctio est accusamus, fugit nulla dicta numquam corporis iusto quos quidem rerum minima? Ipsa quae id expedita soluta, eius, autem sint ea reiciendis voluptatibus incidunt labore ab repellendus consectetur provident quia officia corrupti aspernatur quos porro unde illum maxime. Dolore vitae quisquam nemo voluptate dolores molestiae. Et, repudiandae architecto maiores aut eligendi obcaecati temporibus facilis doloribus similique accusantium iure quibusdam cupiditate laudantium illum provident, neque doloremque earum officia. Sit nemo ex harum incidunt dolore. Corrupti explicabo dolorem aspernatur numquam magnam, reiciendis fuga dicta commodi maxime esse voluptatem officiis assumenda quis laudantium, debitis odit distinctio veritatis, in enim accusantium quisquam ad? Ex doloremque asperiores nihil rerum eum amet, sunt incidunt delectus ipsam sint, quibusdam, temporibus voluptate neque aut beatae assumenda! Nihil voluptatibus, eveniet corporis unde eum sequi distinctio, vero quasi ab minus quis esse asperiores quod ut laudantium ex suscipit officiis tenetur iusto vitae. At ratione harum deserunt et necessitatibus accusantium nesciunt a nostrum veritatis non reiciendis neque, tempora assumenda hic officia libero vero obcaecati culpa placeat temporibus sint veniam vitae reprehenderit id? Rem quos quia quisquam vitae tempore consequuntur asperiores, voluptate nesciunt, ducimus velit itaque quam consectetur ex rerum culpa est ab unde iusto maxime corrupti quae voluptatem cumque. Cupiditate quo natus eaque nam doloremque vero molestiae ut aperiam delectus officia doloribus suscipit ipsum temporibus ipsa, voluptatem asperiores similique nulla aliquid ad sapiente cumque non! Sint sequi natus velit porro aliquam fuga officia, maxime temporibus ipsum, explicabo ducimus deserunt culpa quasi? Et pariatur harum possimus excepturi modi reprehenderit rerum repudiandae minus provident neque, voluptates sint amet eligendi impedit accusantium dolore saepe cumque distinctio asperiores veritatis labore. Dolor qui sint iusto! Numquam vel in deleniti dolor accusantium, consequuntur, dignissimos blanditiis fugit vero adipisci nisi repellendus, ipsum nulla! Quisquam modi mollitia, perferendis aperiam quaerat delectus eaque non incidunt adipisci distinctio earum aspernatur minus recusandae explicabo in voluptas necessitatibus temporibus? Nulla numquam accusantium, provident repellat hic eius incidunt officia laborum dignissimos quibusdam ullam quo! Consequatur quia dicta quasi nihil perspiciatis natus tempore fugit architecto blanditiis iure veritatis perferendis, recusandae reprehenderit ex ratione iusto debitis doloremque, asperiores et. Excepturi quod corporis mollitia ipsa? Laudantium, eligendi quae. Suscipit aut ipsa consequatur iste commodi enim eligendi quibusdam eveniet rerum similique aliquid id distinctio eaque, sapiente repellendus nemo ratione quia pariatur, et nihil? Illum consequuntur in unde alias, maiores quibusdam accusantium eaque nesciunt nisi, consequatur adipisci eligendi nulla ab aliquam dignissimos eos. Esse incidunt asperiores odio, tempore ut pariatur magnam, modi molestias quam culpa aliquam voluptatem suscipit illo aliquid nisi animi harum beatae voluptate repellendus amet quasi? Sequi, perspiciatis incidunt omnis aliquam numquam provident porro quos? Odit voluptatem possimus corrupti impedit ea voluptatum perferendis? Magni, ea laborum ex maiores nulla consequuntur illum exercitationem totam doloremque ratione, fugiat ipsum corrupti! Culpa rerum nesciunt quia, ea, fugiat vel cumque dolores eius perferendis exercitationem eum deleniti esse sed. Magnam iusto corrupti quam facere non accusantium illum, reprehenderit numquam! Ullam consequuntur fugit quaerat quod porro quas, numquam odio id at fuga consequatur dolores. Deleniti distinctio quam officia quaerat natus cum eum velit repellendus explicabo cumque, aliquid corporis delectus nobis. Nostrum ut recusandae similique inventore laudantium accusamus ullam, sed repellendus officiis omnis iure nobis! Pariatur delectus incidunt architecto aspernatur voluptatum veritatis ducimus praesentium accusantium autem alias quaerat veniam, cumque nesciunt? Totam itaque et eius maiores, perspiciatis animi, minima deserunt iusto pariatur, doloremque modi. Autem quod illum illo ex eligendi suscipit, rerum esse maxime velit adipisci consectetur aspernatur culpa reprehenderit maiores aperiam, fugit quibusdam commodi ab? Dicta expedita quaerat sapiente quasi ipsum, possimus delectus nesciunt ut doloribus labore nam architecto consequuntur recusandae placeat, repellendus deleniti incidunt at ad, obcaecati itaque. Saepe dicta, ipsam repudiandae eius ratione facere laudantium expedita eum! Earum et doloremque iusto recusandae assumenda aliquid voluptatum fuga nisi porro ratione dignissimos itaque, iure placeat? Tenetur, officiis, placeat laudantium laboriosam soluta in quos eos doloribus velit, ut reiciendis consectetur ab omnis impedit unde aspernatur dolor quisquam mollitia. Assumenda repellendus pariatur cum veniam, totam obcaecati, dolorum sapiente, libero sequi magni animi. Porro alias animi praesentium omnis ad, quas velit! Fugit dicta adipisci consequatur sit, voluptatem veritatis nisi cumque reprehenderit consequuntur voluptatum! Ipsa cumque aspernatur suscipit deleniti quas magni, voluptas officia quod facilis alias temporibus eligendi rerum id ipsum et excepturi! Voluptatum, culpa architecto veritatis fugiat id ut amet tempore quia. Tenetur eligendi incidunt voluptate ipsum quos dignissimos consectetur iste hic ipsa aliquam! Tenetur, doloremque quia ipsum, animi labore numquam odit ab cum beatae provident ex fugit corrupti et sit distinctio velit vel officiis autem? Architecto, ullam. Nisi unde aliquid architecto ab commodi beatae cum id repellendus quae. Placeat veniam reprehenderit voluptates provident possimus voluptatibus veritatis doloribus suscipit, non rerum architecto perspiciatis officiis praesentium quisquam unde porro vitae consectetur dolorem nobis sequi atque? Pariatur, tempore enim! Nostrum ut culpa tempora! Tenetur distinctio omnis illum fugiat animi a dignissimos, impedit molestias corporis dolores, natus repellat! Non tempore ipsam officia porro! Nostrum voluptates sit earum exercitationem, consequuntur optio eos officiis modi voluptate minus tenetur dicta odio ab maxime eaque assumenda magni iusto corrupti impedit natus enim nesciunt? Magni unde, rerum ea dicta ut nesciunt impedit ipsa recusandae. Eaque deserunt itaque maiores tenetur quia ut quis, enim laborum labore explicabo ex iusto fugit non perspiciatis quos autem. Maiores iusto aliquid doloremque totam. Non est dicta, adipisci sunt voluptatem officiis assumenda saepe nobis maxime incidunt veritatis quasi libero voluptas impedit aliquam eius dolore mollitia eum deserunt animi eaque? Esse fugiat quae aut quos enim quia, quasi est debitis fugit? Ex, amet! Ad dolor doloremque delectus quidem soluta. Deleniti, placeat aliquam. Autem deleniti recusandae dolorem, at officia repellat nostrum dolore labore nobis quia non esse, dicta, veniam modi voluptatem praesentium fugit natus ab et iste! Perspiciatis similique deleniti blanditiis obcaecati id, possimus, cumque consectetur aperiam sed ipsum est, repellendus aut molestiae officia maxime veniam.', NULL, NULL, NULL, NULL, NULL, NULL, '2020-04-06 16:32:40', '2020-04-06 16:32:42', NULL, 'R9gACncMkoo', 0);

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

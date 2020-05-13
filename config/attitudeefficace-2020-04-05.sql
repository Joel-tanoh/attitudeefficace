-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  Dim 05 avr. 2020 à 22:51
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
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_modification` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UN_admin_code` (`code`) USING BTREE,
  UNIQUE KEY `UN_admin_login` (`login`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `administrateurs`
--

INSERT INTO `administrateurs` (`id`, `code`, `login`, `slug`, `password`, `email`, `type`, `statut`, `date_creation`, `date_modification`) VALUES
(1, 'u1s73YMd1rToMd', 'joel', NULL, '$2y$10$NlCt2e.XtG8DmZFYiSB0suGNVX6G0ZeNLny6mdLAriyTUnxXgMQge', 'tanohbassapatrick@gmail.com', 'administrateur', 'activé', '2019-10-01 08:22:06', NULL),
(15, 'OWMtxB', 'benoit', 'benoit', '$2y$10$.aXNhvidFGwPIvtG2TdG2.PC3qmWL26eKV3bYC2d3MuhoQu/49A9W', NULL, 'utilisateur', 'activé', '2020-03-10 13:18:12', NULL),
(25, '9p4YBt', 'béné', NULL, '$2y$10$k81EObr/aakwt5CtMN9eLOuEOWsfE8pHnDcwbqAb.fQimtethYZNy', 'tanohbassapatrick@gmail.com', 'utilisateur', 'activé', '2020-04-05 18:20:00', '2020-04-05 18:20:00');

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
  `image_name` varchar(255) DEFAULT 'defaut.png',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `code`, `type`, `title`, `description`, `slug`, `price`, `rang`, `date_creation`, `date_modification`, `date_post`, `view`, `video_link`, `image_name`) VALUES
(1, 'EL86H', 'formations', 'Lorem', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nulla recusandae, aut fugiat tempore molestiae necessitatibus eos laboriosam maxime blanditiis illo sapiente. Quisquam harum architecto reiciendis.', 'lorem-1', 1500, NULL, '2020-04-05 22:35:01', '2020-04-05 22:35:02', NULL, 0, 'R9gACncMkoo', 'defaut.png'),
(2, '6MOAEL', 'themes', 'Lorem, ipsum.', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Reprehenderit magnam et rem amet at id accusamus, ab ipsa dicta ex, modi quidem hic accusantium soluta, expedita nesciunt.', 'lorem-ipsum-2', NULL, NULL, '2020-04-05 22:36:42', '2020-04-05 22:36:42', NULL, 0, 'R9gACncMkoo', 'defaut.png'),
(3, 'GTbaU', 'etapes', 'Lorem stum', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Reprehenderit magnam et rem amet at id accusamus, ab ipsa dicta ex, modi quidem hic accusantium soluta, expedita nesciunt.', 'lorem-stum-3', NULL, 1, '2020-04-05 22:37:33', '2020-04-05 22:37:33', NULL, 0, 'R9gACncMkoo', 'defaut.png');

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
  `image_name` varchar(255) DEFAULT 'defaut.png',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UN_CODE` (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `elements`
--

INSERT INTO `elements` (`id`, `code`, `type`, `categorie_id`, `title`, `description`, `slug`, `content`, `author`, `provider`, `pages`, `price`, `edition_home`, `annee_parution`, `date_creation`, `date_modification`, `date_post`, `video_link`, `views`, `image_name`) VALUES
(1, 'Y0X5J', 'articles', NULL, 'Lorem dolor', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Reprehenderit magnam et rem amet at id accusamus, ab ipsa dicta ex, modi quidem hic accusantium soluta, expedita nesciunt.', 'lorem-dolor-1', 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Officia dolorem harum blanditiis impedit eum rerum veritatis perferendis hic fugiat mollitia, odit omnis, modi atque unde nobis? Officiis magnam, nam atque voluptatem saepe minima excepturi reiciendis accusamus voluptates quisquam. Dolorum dolores nemo laborum animi, nulla a cupiditate iure ducimus voluptatem. Iste facilis vitae dolore quidem et illo nesciunt doloribus, repellat optio sint, quo dolores tempore. Ipsum, veniam! Quia optio error in nam consectetur, vero voluptates rerum neque asperiores dolorum explicabo id voluptatum autem beatae commodi quam sunt, ea atque. Ab porro dolorum iusto repellendus, natus optio, sit rem, pariatur ratione quod aut consequatur repellat assumenda! Necessitatibus, beatae impedit perspiciatis qui incidunt quam, est debitis distinctio at placeat eligendi commodi excepturi aperiam laborum porro sequi tempora amet ad aut. Fugiat rem inventore tempore odit numquam repellat consequuntur, hic reprehenderit necessitatibus! Nam officia dolore adipisci praesentium nemo voluptates quaerat quia deleniti sit animi incidunt veniam nihil cumque, perspiciatis error voluptatem aperiam facilis voluptatum alias placeat accusamus? Minima provident cupiditate deleniti omnis expedita esse, eaque odio tempore labore eius eos debitis rerum sequi quasi quisquam nobis mollitia quaerat distinctio dolore. Quod blanditiis, reiciendis, unde iste labore minus atque tempora illo quas possimus facilis recusandae, velit nesciunt porro sed eos. Necessitatibus possimus beatae maiores debitis. Architecto praesentium deserunt in cum quasi eaque dolorem provident saepe cupiditate facilis, aut, iste aperiam laboriosam officiis a ipsum sit eum eius? Eius officiis esse, vel consequuntur, vero laborum inventore expedita doloremque autem voluptates maxime! Esse facere praesentium voluptatum saepe qui facilis vitae voluptas cumque incidunt? Quibusdam quod vero sunt dignissimos voluptate nesciunt quaerat totam nulla ipsum incidunt sit id nisi tempora voluptatibus, perspiciatis pariatur? Eaque facilis est nobis molestiae, et quasi laborum vel? Quisquam numquam incidunt voluptatum reprehenderit rerum pariatur impedit blanditiis voluptates possimus maiores officia neque consequatur quia, ullam dolorem laborum provident enim at consequuntur? Exercitationem at et tenetur ratione, obcaecati perferendis delectus odit tempora consequuntur cupiditate fuga reprehenderit nulla, quaerat aliquid ad sit asperiores. Molestias nisi, a error beatae nulla odit quae expedita libero sit ut voluptates voluptatum architecto tempora cupiditate. Recusandae ut ipsa dolor consectetur. Quam, ex, totam temporibus, eveniet animi nemo voluptas facilis dolore voluptate optio doloribus nam architecto harum. Minus quibusdam autem doloribus architecto commodi saepe. Corrupti earum eius fugit, exercitationem cum, voluptate dolore impedit iste explicabo doloremque provident illo architecto enim maiores facilis placeat, nostrum consectetur sint blanditiis perspiciatis. Possimus quam ipsum illo qui quia id commodi molestiae esse nobis, assumenda nihil quaerat aperiam cupiditate, voluptates earum omnis similique quibusdam delectus. Ullam provident ipsum ratione obcaecati ipsam temporibus, a iusto nihil reprehenderit eligendi numquam cum voluptatibus quos delectus corporis est eos recusandae unde expedita sunt! Nihil quo asperiores reprehenderit, alias eligendi porro earum vitae facilis culpa recusandae aliquam corrupti consectetur reiciendis officia iusto. Voluptas consectetur culpa sunt possimus adipisci consequatur minus, deserunt sint cum impedit quasi molestias esse eveniet rem quibusdam natus commodi ullam nisi laudantium, vitae earum, sequi libero error tempore? Laudantium sed alias repudiandae, deleniti voluptatem rerum necessitatibus, perferendis aliquam asperiores numquam odio quam cumque omnis praesentium impedit voluptates ipsam libero cum. Eveniet neque autem repudiandae enim delectus molestiae est optio, aperiam qui placeat voluptatibus fugiat modi at, laudantium iure eaque omnis. Itaque placeat voluptatibus nihil totam reprehenderit quibusdam dolor delectus vero magni? Suscipit inventore aliquid voluptas dolor accusantium assumenda, architecto quasi dolorem ducimus voluptatum quibusdam vitae reprehenderit provident atque placeat deleniti aspernatur totam aliquam pariatur itaque a dignissimos nemo cupiditate consequuntur. Corporis necessitatibus placeat eaque ullam maiores ad numquam, voluptates quisquam obcaecati optio consequatur quod quidem cum reprehenderit sint eum tempore hic fugiat provident officiis. Architecto eligendi dicta pariatur eveniet perspiciatis. Cumque deserunt eaque repudiandae sint optio facere amet numquam, necessitatibus eligendi id debitis reprehenderit. Voluptate pariatur cum obcaecati inventore maxime quos ipsam, est dolorum esse porro! Neque totam dolorem quaerat aliquam ipsum, fuga ab? Tempora, nostrum quod minus, temporibus vitae sunt velit ad veritatis sed cum perspiciatis ab rem autem esse, placeat excepturi dolorum consequatur nihil odio iusto itaque accusamus. Sed, praesentium deserunt? Molestias nulla porro rem corporis quod culpa voluptas consectetur eos, non reiciendis illo ipsa, voluptates magnam eum perferendis dignissimos! Nobis quas doloribus nam veniam nemo non magni saepe quis deleniti ipsum ea quibusdam mollitia qui aut, soluta sunt unde voluptate at repellat. Eveniet iusto placeat quaerat quis alias rem necessitatibus velit dolore illo, soluta, officia in minima, ullam deleniti vero ducimus nisi. Facilis perferendis earum minus numquam aperiam voluptas, ea molestiae cum accusamus nesciunt pariatur blanditiis culpa quam omnis laudantium possimus? Nesciunt adipisci obcaecati veritatis laboriosam odio labore quas doloribus, voluptas architecto laborum. Molestias vel dolor nihil saepe consequuntur ipsam? Nobis, fugit. Praesentium quia obcaecati quod rem soluta amet? Officia accusantium aperiam ut. Temporibus, nostrum rem accusamus, provident, quidem numquam accusantium delectus voluptates in dolor consequatur obcaecati vero asperiores eius repellendus unde quod iusto. Aut nisi adipisci laborum officia aliquid natus hic consectetur blanditiis, nulla sint deleniti, veniam temporibus? Ut pariatur nihil ad odio in accusantium velit, dolore doloribus dolorem quos placeat animi officiis provident officia, quaerat fugiat obcaecati eveniet accusamus magnam nemo earum asperiores saepe ex! Natus non a earum quia repellat voluptatibus ipsa ex id minima saepe! Ratione expedita iste, debitis dicta ullam tempora deleniti libero earum pariatur rem fugiat veritatis rerum architecto, quos, eum assumenda voluptas. Nemo dolores molestiae sunt molestias incidunt voluptates soluta quod totam est hic rerum tempore debitis eveniet maiores quasi corrupti placeat, pariatur dolorem et, unde commodi sed suscipit repellat? Officia voluptatibus mollitia laborum, temporibus sed quae voluptatum minima rerum consectetur veniam! Praesentium vel saepe eos sit odio nesciunt, exercitationem iure quisquam architecto, optio eaque. Fuga, similique. Cupiditate quos suscipit voluptas exercitationem dicta, tenetur esse. Corrupti asperiores, incidunt molestiae, recusandae maxime omnis quidem explicabo, ipsam cupiditate placeat ad eveniet? Beatae reprehenderit fugiat reiciendis vero veniam? Blanditiis sed reiciendis maxime culpa quo cum. Id aperiam assumenda quae vel. Asperiores dicta voluptate voluptas aut, optio nulla facilis temporibus amet iusto possimus culpa impedit inventore rerum nesciunt odit aperiam commodi enim eum distinctio? Maxime doloribus alias beatae magnam perspiciatis fugit soluta explicabo architecto deleniti, numquam illo labore, cupiditate ut exercitationem molestias dicta mollitia illum expedita eum quae non amet. Incidunt eius temporibus sed? Dolorum, obcaecati maiores hic molestias cupiditate sint reiciendis blanditiis deserunt laudantium impedit aut quam minima quod eaque! Dolorum ducimus, corporis voluptatum, dicta facilis ea quae similique quibusdam nam maxime eaque repellat magni at officiis cupiditate ad id consequatur nulla ullam exercitationem culpa reprehenderit maiores. Sit, officia! Quam id, molestias iusto sed necessitatibus dolores exercitationem adipisci dignissimos, dolore natus quaerat quibusdam totam error, minus tenetur libero at cum. Doloremque voluptas perferendis quasi sint vitae illum animi modi quo sapiente! Natus, quo. Ad id debitis cupiditate eaque sapiente suscipit iste omnis saepe nam unde, numquam eius minima odio obcaecati perferendis quos consequatur minus culpa dignissimos quae aliquid? Delectus a reiciendis voluptate libero placeat quidem! Eveniet eius adipisci harum nihil fugiat quis voluptatem. Excepturi autem vel magnam repellendus? Architecto quaerat est praesentium corporis exercitationem deserunt cum vel ut odio maiores hic sint, vitae recusandae, perferendis amet iure dolores laboriosam tempore cupiditate. Similique laboriosam aperiam excepturi, aspernatur sed cumque impedit vitae alias ad nemo, beatae ut doloremque, blanditiis iste reprehenderit totam magni esse veniam corporis quos laudantium? Illum voluptas modi ipsum fugit, nesciunt omnis, voluptate vitae dolore laborum explicabo, blanditiis adipisci soluta. Nam, aliquid ratione amet quidem, eligendi, dolorem nemo quos facere cumque consectetur id necessitatibus nihil quod! Totam, minima voluptatem. Culpa tenetur quaerat sequi sit sed tempora eos delectus provident inventore dolorem! Voluptatibus distinctio blanditiis ab eveniet recusandae magnam fuga error laboriosam aliquam! In, aperiam doloribus quos deserunt blanditiis ipsam deleniti explicabo vero commodi corporis cum quas? Dolores minus numquam dolorum earum rerum blanditiis sapiente voluptas molestiae tenetur officiis doloribus ad accusantium exercitationem labore excepturi asperiores quod odit, non modi iure vitae. Quibusdam earum molestiae autem consequuntur, amet dignissimos, nisi beatae, harum temporibus enim hic quae praesentium et consectetur totam in excepturi ex. Amet necessitatibus quae vero saepe cupiditate ipsam veritatis, praesentium incidunt tempore mollitia dignissimos tenetur ullam? Quas ab neque dolor, iusto perferendis quibusdam rerum esse qui quos eum, quis fugiat tempora accusamus similique perspiciatis, minus mollitia necessitatibus! Eos tenetur omnis quasi sequi officia suscipit non veniam quaerat, optio illo itaque odio voluptatem tempora adipisci vero dolor nobis libero temporibus facilis odit, maiores assumenda aliquam est dolorem. Neque, cupiditate. Blanditiis ratione in omnis vel rem soluta ipsum? Veritatis eos reiciendis nisi dicta dolore perspiciatis eum soluta distinctio nihil nemo asperiores quam fuga voluptate quasi sequi rerum non iure doloremque facilis, in possimus quos. Dolorum, ad libero suscipit accusantium iure distinctio minima provident sit corrupti quis labore, soluta quam minus quod sint. Rem distinctio vel tempore aliquam aperiam vitae pariatur quos qui voluptatem debitis doloribus ratione quia earum similique delectus ex est ducimus quod quasi cupiditate, nulla saepe. Laborum, inventore? Eveniet, cumque voluptatum esse animi consectetur magnam eos sapiente tempore corporis! Eum sequi minima iste consectetur architecto perferendis doloribus? Culpa dolorem, magnam iure fugit nisi doloribus veritatis. Ea quidem earum numquam aliquam ad sed deleniti quaerat quos impedit corrupti perferendis laborum rerum delectus sint odit facilis consectetur, accusamus ipsum quisquam necessitatibus quas quis praesentium sunt consequuntur. Quo ad consequuntur maiores dolor fugiat. Sint perspiciatis necessitatibus in aliquid debitis, aperiam labore? Laborum perferendis ad labore nesciunt iste accusantium totam neque vero aperiam numquam consectetur dolor, officiis perspiciatis commodi provident repellat laudantium modi quo, ex fuga illo tempora in? Nostrum officiis dolor a facere dolorum. Adipisci numquam, voluptate libero fugit aliquam mollitia cum deserunt. Earum enim doloremque, nobis eligendi natus impedit repudiandae ipsa recusandae iure ducimus laborum sint quia dolorem dolores ullam incidunt beatae exercitationem! Distinctio accusamus laborum delectus repellendus eius deserunt quaerat expedita doloremque, velit alias ea, facilis fugit enim libero ducimus ipsam beatae vero quisquam minus! Dolores, sapiente aliquam. Voluptatibus reiciendis animi tempore! Ab, dolor? Deserunt, libero eveniet. Aliquam aspernatur placeat similique quisquam eius culpa cumque reiciendis aliquid delectus tempore facilis consectetur voluptatibus fugiat cum, reprehenderit perferendis, nulla praesentium libero quasi unde voluptas rem? Ullam, corporis repellat eaque maxime modi iste ut distinctio recusandae inventore, sequi aut praesentium architecto minima. Eos modi deserunt, eligendi, quos laborum excepturi pariatur sunt, inventore harum eum officia illo aut quam deleniti obcaecati iure corporis totam vero magnam! Nobis totam eos voluptatibus deserunt quas culpa magnam consectetur in rem ipsum, tempora autem deleniti dolores tempore iure ullam sunt facere sint laudantium expedita. Voluptatibus accusamus temporibus officiis sint eligendi! Commodi cum voluptas atque perspiciatis facere animi provident, dolorum vitae culpa veritatis impedit amet quos, et, blanditiis sequi mollitia molestias aperiam repellendus. Sed quisquam atque itaque sapiente alias. Aperiam, consectetur perspiciatis aliquam saepe molestiae modi aliquid natus tempore itaque distinctio officiis, nam provident blanditiis consequuntur eligendi quasi repellendus alias facere sequi explicabo eos. Illum voluptate distinctio hic sunt qui perferendis alias, similique iusto sequi. Sapiente impedit numquam reprehenderit nulla voluptatibus quia alias ipsam consequuntur similique quibusdam vero harum esse animi repudiandae rerum eligendi sint repellendus nisi, facere quam ea quisquam adipisci, cupiditate aut. Delectus labore soluta porro ex iure quaerat eos, unde quas aspernatur cupiditate, exercitationem tempore quos! Officiis nobis odit tenetur aperiam voluptatibus maxime magni commodi illum magnam quidem atque iure voluptatum, eum impedit, cupiditate quibusdam. Itaque repellendus similique mollitia adipisci sed aspernatur consequatur delectus ipsum fugiat a dicta ab sit, eos saepe, perferendis quaerat voluptatum provident veritatis. Ab placeat dolore quidem ipsum, reiciendis dolorem tempore, atque dolorum sequi possimus maiores. Enim necessitatibus ipsum ut, mollitia explicabo repellendus cum reprehenderit eligendi consectetur beatae exercitationem at atque soluta totam iure aliquid, pariatur numquam! Provident, qui, illum deserunt accusantium expedita ut ullam eos totam itaque rem adipisci ad ducimus, exercitationem quibusdam tenetur maiores cupiditate minima quia commodi. Commodi, nobis. Qui suscipit soluta fuga inventore laborum dolor, optio, quidem neque iste id veniam dolore dolorem, veritatis incidunt. Ipsam fuga vitae odit itaque numquam quis corrupti nobis eum dolores ducimus, fugiat blanditiis modi magni tempore totam in odio mollitia quia maiores alias hic obcaecati. Autem assumenda rerum nesciunt animi, sequi officiis ab facilis eius quae iure optio iste repudiandae consequatur quasi saepe sit velit fugit minima cumque corporis quo unde, facere consequuntur harum. Magni architecto dolor reiciendis saepe eligendi sed praesentium fuga corrupti modi cum, doloremque autem unde ex laudantium a? Eum, ad provident doloremque eos placeat quae illum assumenda obcaecati iure, exercitationem consequuntur reprehenderit perferendis nulla consequatur sint dolorum vitae ratione esse? Ipsum tempore at, officia doloribus ad cum quam aspernatur sapiente dolores inventore quod vel rem omnis consequatur voluptatem et in temporibus laboriosam nesciunt quasi! Perspiciatis minus repellat, recusandae ullam tempora maxime amet non! Sunt temporibus aperiam qui ea perferendis architecto quo culpa amet, cupiditate magni accusamus dolorum quia! Praesentium explicabo, repellat ut cumque nulla repellendus eius et, labore quo ex expedita blanditiis impedit doloribus a at mollitia obcaecati beatae aspernatur voluptates vero accusamus inventore. Earum rem quas ducimus magni! Enim repudiandae necessitatibus dolorem? A deserunt aliquid doloremque, accusantium quisquam eligendi, illum et nesciunt reprehenderit aut repellat modi fuga itaque reiciendis! Magnam natus temporibus aperiam dicta. Maiores dolorem omnis enim dolorum quidem, iste esse laborum soluta dicta aliquid voluptas quo quos consequuntur debitis commodi sapiente maxime doloribus qui ullam culpa suscipit facere nisi tempora magni. Deleniti, a similique atque sit aspernatur in? Consequatur nulla magnam animi et enim non aspernatur eveniet voluptatem molestias ex sint excepturi nobis debitis fugiat labore officia dolorem commodi aliquam, corrupti similique dolorum, sed mollitia quas veritatis. Voluptatum adipisci cum ipsa excepturi modi. Aliquam, delectus! Consequuntur possimus ullam vitae ab accusantium sit impedit consectetur autem quo a reprehenderit ut animi aspernatur facere veritatis hic, velit quidem explicabo excepturi, tempore reiciendis expedita cupiditate magnam nihil? Fuga cum magnam assumenda quas officia minus repellendus error at, numquam illum obcaecati facere nemo a ratione provident! Aliquid, officia odio obcaecati provident ipsa saepe, inventore illum totam ea, enim maxime facere voluptates sapiente vero eligendi dolorum. Aut ratione, esse sed deserunt accusamus deleniti tempora quos. Nesciunt neque recusandae alias porro velit veritatis, eius quos architecto fuga obcaecati doloremque asperiores cumque impedit totam aperiam iste. Aperiam quos repudiandae nihil velit, dolore nulla! Quam quisquam ducimus dolores, in exercitationem iusto nesciunt architecto expedita, vel earum commodi possimus soluta eos laudantium! Enim sequi consequatur ipsam labore reiciendis rerum magnam sunt iusto, blanditiis, ad maxime ducimus, aut soluta. Maiores fuga perferendis deserunt repudiandae sint voluptatum, mollitia nisi perspiciatis architecto repellat consequuntur rem alias laudantium necessitatibus! Magnam consequuntur blanditiis dolorum dolorem excepturi voluptatem vero quas quae culpa corporis saepe sapiente animi qui, adipisci, laborum, dolore laboriosam. Odit veniam placeat, modi nesciunt reiciendis mollitia nihil aliquid ab cum eum exercitationem recusandae! Ipsa quibusdam nam totam amet, natus error distinctio reprehenderit! Fugit cupiditate quaerat, commodi distinctio dolores velit veritatis ipsum architecto dolorem. Cum culpa nulla tempore ex corrupti odio earum impedit deserunt, omnis tenetur at repellat obcaecati harum, blanditiis molestiae maiores. Doloribus eos corrupti ipsa labore quis sit? Veniam enim dignissimos maiores nesciunt repellendus assumenda quidem mollitia? Commodi maiores repellendus laudantium obcaecati fugit in corrupti voluptatum sint veniam voluptatibus odio porro accusamus sit laborum, inventore dolores illo dicta deserunt nostrum, facere blanditiis! Adipisci quas nemo voluptatibus nostrum dolorum, et porro optio. Quos nesciunt ab qui eum obcaecati esse.\r\n', NULL, NULL, NULL, NULL, NULL, NULL, '2020-04-05 22:42:20', '2020-04-05 22:42:20', NULL, 'R9gACncMkoo', 0, 'defaut.png'),
(2, 'dtVYE', 'videos', NULL, 'Lorem stum', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. In nostrum, eaque nam commodi magni temporibus saepe repellat est molestiae deleniti aut dolores voluptate placeat iusto id ex.', 'lorem-stum-2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-04-05 22:43:25', '2020-04-05 22:43:25', NULL, 'R9gACncMkoo', 0, 'defaut.png'),
(3, 'Oz1pga', 'livres', NULL, 'Lorem doum', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. In nostrum, eaque nam commodi magni temporibus saepe repellat est molestiae deleniti aut dolores voluptate placeat iusto id ex.', 'lorem-doum-3', NULL, NULL, NULL, NULL, 1700, NULL, NULL, '2020-04-05 22:44:17', '2020-04-05 22:44:17', NULL, 'R9gACncMkoo', 0, 'defaut.png'),
(4, 'hlfeR6', 'ebooks', NULL, 'Lorem tum I', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. In nostrum, eaque nam commodi magni temporibus saepe repellat est molestiae deleniti aut dolores voluptate placeat iusto id ex.', 'lorem-tum-i-4', NULL, NULL, NULL, NULL, 1850, NULL, NULL, '2020-04-05 22:47:37', '2020-04-05 22:47:37', NULL, 'R9gACncMkoo', 0, 'defaut.png');

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

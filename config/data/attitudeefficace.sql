-- MySQL dump 10.13  Distrib 5.7.19, for Win64 (x86_64)
--
-- Host: localhost    Database: attitude_efficace
-- ------------------------------------------------------
-- Server version	5.7.19

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `administrators`
--

DROP TABLE IF EXISTS `administrators`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `administrators` (
  `code` varchar(255) COLLATE utf8_bin NOT NULL,
  `login` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `email_address` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `role` int(11) NOT NULL DEFAULT '2',
  `birth_day` date DEFAULT NULL,
  `state` varchar(255) COLLATE utf8_bin DEFAULT 'activé',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  UNIQUE KEY `UN_admin_code` (`code`) USING BTREE,
  KEY `email_address` (`email_address`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `administrators`
--

LOCK TABLES `administrators` WRITE;
/*!40000 ALTER TABLE `administrators` DISABLE KEYS */;
INSERT INTO `administrators` VALUES ('u1s73YMd1rToMd','joel','$2y$10$NlCt2e.XtG8DmZFYiSB0suGNVX6G0ZeNLny6mdLAriyTUnxXgMQge','joel.developpeur@gmail.com',3,NULL,'activé','2019-10-01 08:22:06',NULL),('ITyPZnLwd','benoit','$2y$10$XxpT8MxPW4VUFFtNuWWwS.diIqFvN1bnUUx9Sw4/J3CwNXvvrJA22','benoitkoua2015@gmail.com',2,NULL,'activé','2020-04-14 11:04:26','2020-04-14 11:04:26');
/*!40000 ALTER TABLE `administrators` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `baskets`
--

DROP TABLE IF EXISTS `baskets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `baskets` (
  `session_id` varchar(255) COLLATE utf8_bin NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `state` int(1) NOT NULL DEFAULT '0',
  KEY `fk_session_id` (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `baskets`
--

LOCK TABLES `baskets` WRITE;
/*!40000 ALTER TABLE `baskets` DISABLE KEYS */;
INSERT INTO `baskets` VALUES ('ij9Rzi7UoOVKM91uEWvq3','2020-09-15 09:35:12',NULL,0),('q8Hv1IeIPZhs52RRl6Qk2sYrPU9','2020-09-15 12:24:06',NULL,0),('MAqlcMicz','2020-09-16 08:43:00',NULL,0);
/*!40000 ALTER TABLE `baskets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `baskets_content`
--

DROP TABLE IF EXISTS `baskets_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `baskets_content` (
  `basket_session_id` varchar(255) COLLATE utf8_bin NOT NULL,
  `miniservice_code` varchar(255) COLLATE utf8_bin NOT NULL,
  `added_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `fk_basket_id` (`basket_session_id`),
  KEY `fk_miniservice_id` (`miniservice_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `baskets_content`
--

LOCK TABLES `baskets_content` WRITE;
/*!40000 ALTER TABLE `baskets_content` DISABLE KEYS */;
/*!40000 ALTER TABLE `baskets_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `slug` varchar(255) COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES ('formations','formations',NULL,'2020-08-05 20:48:10',NULL),('thèmes','themes',NULL,'2020-08-05 20:48:21',NULL),('étapes','etapes',NULL,'2020-08-05 20:48:56',NULL),('articles','articles',NULL,'2020-08-05 20:49:04',NULL),('vidéos','videos',NULL,'2020-08-05 20:49:15',NULL),('livres','livres',NULL,'2020-08-05 20:49:37',NULL),('ebooks','ebooks',NULL,'2020-08-05 20:49:45',NULL),('mini services','mini-services',NULL,'2020-08-05 21:21:10',NULL),('motivation plus','motivation-plus',NULL,'2020-08-05 21:22:50',NULL);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customers` (
  `code` varchar(255) COLLATE utf8_bin NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `first_names` varchar(255) COLLATE utf8_bin NOT NULL,
  `email_address` varchar(255) COLLATE utf8_bin NOT NULL,
  `contact_1` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `contact_2` varchar(50) COLLATE utf8_bin DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES ('','tanoh','bassa patrick joel','tanohbassapatrick@gmail.com','+22549324696',NULL);
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items_child`
--

DROP TABLE IF EXISTS `items_child`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items_child` (
  `code` varchar(255) NOT NULL,
  `categorie` varchar(255) NOT NULL,
  `parent_code` varchar(255) DEFAULT NULL,
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
  UNIQUE KEY `UN_CODE` (`code`),
  KEY `fk_parent_id` (`parent_code`),
  FULLTEXT KEY `RECH_CONTENT` (`article_content`),
  FULLTEXT KEY `RECH_TITLE` (`title`),
  FULLTEXT KEY `RCH_SLUG` (`slug`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items_child`
--

LOCK TABLES `items_child` WRITE;
/*!40000 ALTER TABLE `items_child` DISABLE KEYS */;
INSERT INTO `items_child` VALUES ('Ar7FEVLjWKP','ebooks','0','Test','test','test-Ar7FEVLjWKP',NULL,NULL,NULL,NULL,0,1,NULL,NULL,'2020-09-09 05:14:58','2020-09-15 12:21:44',NULL,'',0);
/*!40000 ALTER TABLE `items_child` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items_parent`
--

DROP TABLE IF EXISTS `items_parent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items_parent` (
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
  UNIQUE KEY `items_parent` (`code`),
  FULLTEXT KEY `RCH_TITLE` (`title`),
  FULLTEXT KEY `RCH_SLUG` (`slug`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items_parent`
--

LOCK TABLES `items_parent` WRITE;
/*!40000 ALTER TABLE `items_parent` DISABLE KEYS */;
/*!40000 ALTER TABLE `items_parent` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `newsletters`
--

DROP TABLE IF EXISTS `newsletters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `newsletters` (
  `email_address` text NOT NULL,
  `subscription_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `newsletters`
--

LOCK TABLES `newsletters` WRITE;
/*!40000 ALTER TABLE `newsletters` DISABLE KEYS */;
INSERT INTO `newsletters` VALUES ('tanohbassapatrick@gmail.com','2020-04-24 14:51:52'),('joel.developpeur@gmail.com','2020-04-24 14:52:15');
/*!40000 ALTER TABLE `newsletters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `code` varchar(255) COLLATE utf8_bin NOT NULL,
  `customer_id` int(11) NOT NULL,
  `miniservice_id` int(15) DEFAULT NULL,
  `description` text COLLATE utf8_bin,
  `ordered_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `state` int(11) NOT NULL DEFAULT '1',
  UNIQUE KEY `miniservices_orders` (`code`),
  KEY `fk_id_miniservice` (`miniservice_id`) USING BTREE,
  KEY `fk_id_customer` (`customer_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscriptions` (
  `code` varchar(255) COLLATE utf8_bin NOT NULL,
  `suscriber_email_address` int(11) NOT NULL,
  `item_code` int(11) NOT NULL,
  `subscribed_at` datetime DEFAULT CURRENT_TIMESTAMP,
  KEY `FK_suscriber_email_address` (`suscriber_email_address`) USING BTREE,
  KEY `FK_item_code` (`item_code`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscriptions`
--

LOCK TABLES `subscriptions` WRITE;
/*!40000 ALTER TABLE `subscriptions` DISABLE KEYS */;
/*!40000 ALTER TABLE `subscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suscribers`
--

DROP TABLE IF EXISTS `suscribers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `suscribers` (
  `code` varchar(255) COLLATE utf8_bin NOT NULL,
  `last_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `first_names` varchar(255) COLLATE utf8_bin NOT NULL,
  `password` varchar(255) COLLATE utf8_bin NOT NULL,
  `state` varchar(255) COLLATE utf8_bin DEFAULT 'activé',
  `contact_1` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `contact_2` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `email_address` varchar(255) COLLATE utf8_bin NOT NULL,
  UNIQUE KEY `UN_MAIL` (`email_address`),
  UNIQUE KEY `suscrierbers` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suscribers`
--

LOCK TABLES `suscribers` WRITE;
/*!40000 ALTER TABLE `suscribers` DISABLE KEYS */;
INSERT INTO `suscribers` VALUES ('ADVCUxzE0','KOUTOUAN','fabrice','12345678','activé','02030405',NULL,'koutouan@gmail.com'),('AqdscEfsghZ','ALBAN','george','87654321','activé','45201232','45213245','joachimz@gmail.com');
/*!40000 ALTER TABLE `suscribers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `visitors`
--

DROP TABLE IF EXISTS `visitors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `visitors` (
  `session_id` varchar(255) COLLATE utf8_bin NOT NULL,
  `date_visit` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_action_timestamp` bigint(20) DEFAULT NULL,
  KEY `session_id` (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `visitors`
--

LOCK TABLES `visitors` WRITE;
/*!40000 ALTER TABLE `visitors` DISABLE KEYS */;
INSERT INTO `visitors` VALUES ('ij9Rzi7UoOVKM91uEWvq3','2020-09-15 09:35:12',1600162761),('F4vDvJIeWjh7Lkfk8','2020-09-15 12:13:09',1600171989),('DpeC7E_9HqEVyXs','2020-09-15 12:21:17',1600172477),('q8Hv1IeIPZhs52RRl6Qk2sYrPU9','2020-09-15 12:24:06',1600172646),('MAqlcMicz','2020-09-16 08:42:59',1600248077);
/*!40000 ALTER TABLE `visitors` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-09-16  9:41:56

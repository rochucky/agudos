-- MySQL dump 10.16  Distrib 10.1.31-MariaDB, for Win32 (AMD64)
--
-- Host: localhost    Database: agudos
-- ------------------------------------------------------
-- Server version	10.1.31-MariaDB

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
-- Table structure for table `balance`
--

DROP TABLE IF EXISTS `balance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `balance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `value` decimal(15,2) NOT NULL,
  `type` varchar(45) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uSER_MONTH_YEAR_TYPE` (`user_id`,`type`),
  CONSTRAINT `fk_balance_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `balance`
--

LOCK TABLES `balance` WRITE;
/*!40000 ALTER TABLE `balance` DISABLE KEYS */;
INSERT INTO `balance` VALUES (1,83,600.00,'1','2018-11-28 00:40:03',1,NULL,NULL,NULL,NULL),(2,83,1000.00,'2','2018-11-28 00:42:39',1,NULL,NULL,NULL,NULL),(3,89,200.00,'1','2018-11-28 13:20:54',1,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `balance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `establishments`
--

DROP TABLE IF EXISTS `establishments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `establishments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `cnpj` varchar(45) NOT NULL,
  `password` varchar(150) NOT NULL,
  `description` varchar(150) NOT NULL,
  `is_active` tinyint(4) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `code` int(11) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cnpj` (`cnpj`),
  UNIQUE KEY `code_UNIQUE` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `establishments`
--

LOCK TABLES `establishments` WRITE;
/*!40000 ALTER TABLE `establishments` DISABLE KEYS */;
INSERT INTO `establishments` VALUES (8,'Casa das AlianÃ§as','78744244000176','$2y$12$tYNzbWiPsoX2D/TPqv14DuExNh5phNi3We9kihqegorrQVpCMGkI.','Casa das AlianÃ§as',1,NULL,NULL,'2018-12-03 19:32:33',1,NULL,NULL,1,'teste@gmail.com','Av. InconfidÃªncia Mineira, 35'),(15,'Coxinha da VovÃ³','56531196000103','$2y$12$tYNzbWiPsoX2D/TPqv14DuExNh5phNi3We9kihqegorrQVpCMGkI.','Coxinha da VovÃ³',1,NULL,NULL,'2018-12-03 21:38:28',90,NULL,NULL,3,'est2@gmail.com','Rua JoÃ£o Jacinto Silva, 94'),(16,'Galeria do Rock','99848236000140','$2y$12$tYNzbWiPsoX2D/TPqv14DuKVxMl2t82JrmITJnMv7lXyM3telwMcG','Galeria do Rock',1,'2018-12-01 04:20:46',1,'2018-12-03 19:32:37',1,NULL,NULL,2,'galeriadorock@gmail.com','PraÃ§a JoÃ£o Mendes, 32');
/*!40000 ALTER TABLE `establishments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `establishment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `value` decimal(15,2) NOT NULL,
  `date` datetime NOT NULL,
  `comments` varchar(100) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `code` varchar(20) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
INSERT INTO `transactions` VALUES (1,15,83,22.37,'2018-12-01 15:07:44','Ã€ Vista',2,'2018120115074415','2018-12-01 15:07:44',15,'2018-12-03 22:02:58',NULL,NULL,NULL),(2,15,83,33.10,'2018-12-01 15:10:00','Ã€ Vista',2,'2018120115100015','2018-12-01 15:10:00',15,'2018-12-03 21:59:01',NULL,NULL,NULL),(3,8,83,266.67,'2018-12-01 00:00:00','1/3',1,'201812011541518','2018-12-01 15:41:51',8,'2018-12-03 20:33:51',90,NULL,NULL),(4,8,83,266.67,'2019-01-01 00:00:00','2/3',1,'201812011541518','2018-12-01 15:41:51',8,'2018-12-03 20:33:51',90,NULL,NULL),(5,8,83,266.67,'2019-02-01 00:00:00','3/3',1,'201812011541518','2018-12-01 15:41:51',8,'2018-12-03 20:33:51',90,NULL,NULL);
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_types`
--

DROP TABLE IF EXISTS `user_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_types`
--

LOCK TABLES `user_types` WRITE;
/*!40000 ALTER TABLE `user_types` DISABLE KEYS */;
INSERT INTO `user_types` VALUES (2,'admin','2018-05-15 02:00:10',1,'2018-05-15 02:00:10',1,NULL,NULL),(3,'dev','2018-05-15 02:00:10',1,'2018-05-15 02:00:10',1,NULL,NULL),(5,'funcionario','2018-10-16 00:28:14',1,'2018-10-16 00:28:14',1,NULL,NULL);
/*!40000 ALTER TABLE `user_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `cpf` varchar(11) DEFAULT NULL,
  `code` varchar(20) NOT NULL,
  `token` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `code_UNIQUE` (`code`),
  KEY `user_type_id` (`user_type_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`user_type_id`) REFERENCES `user_types` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Desenvolvedor do Sistema','alves.rodrigo31@outlook.com','dev','$2y$12$tYNzbWiPsoX2D/TPqv14DuVgct/cGfbeOKqqPzxXw/WTgLjs4I8eW',3,'2018-05-07 13:48:42',1,'2018-12-03 16:18:12',1,NULL,NULL,1,NULL,'1',NULL),(83,'Rodrigo Alves','alves.rodrigo31@outlook.com','35843016889','$2y$12$tYNzbWiPsoX2D/TPqv14DuExNh5phNi3We9kihqegorrQVpCMGkI.',5,NULL,NULL,'2018-11-29 02:44:27',1,NULL,NULL,1,'35843016889','2',NULL),(85,'FuncionÃ¡rio Teste','funcionario@teste.com.br','65397334022','$2y$12$tYNzbWiPsoX2D/TPqv14DuExNh5phNi3We9kihqegorrQVpCMGkI.',5,NULL,NULL,'2018-11-29 00:13:11',1,NULL,NULL,1,'65397334022','3',NULL),(86,'Luciana Digna das Neves','ld@gmail.com','36959735874','$2y$12$tYNzbWiPsoX2D/TPqv14DuExNh5phNi3We9kihqegorrQVpCMGkI.',5,NULL,NULL,'2018-12-03 19:25:25',1,NULL,NULL,0,'36959735874','4',NULL),(88,'JoÃ£o da Silva','js@gmail.com','16899463008','$2y$12$tYNzbWiPsoX2D/TPqv14DuZy4/6AkmfkhsJttNIOR6yOCzYiFsZoW',5,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'16899463008','5',NULL),(89,'Raul Alves','ra@gmail.com','63140455097','$2y$12$tYNzbWiPsoX2D/TPqv14DuQBoqgirf4nPC1/l58B4ewGkWghy3Gqm',5,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'63140455097','6',NULL),(90,'Administrador do Sistema',NULL,'admin','$2y$12$tYNzbWiPsoX2D/TPqv14Duuu88pcX6t5ub2aXeEupAptCxx6UlMXy',2,'2018-12-03 16:19:01',1,'2018-12-03 16:19:36',1,NULL,NULL,1,NULL,'10',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-12-03 20:24:14

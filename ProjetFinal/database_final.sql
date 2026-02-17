-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: bngrc_final_s3
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `achats`
--

DROP TABLE IF EXISTS `achats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `achats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_produit` int(11) DEFAULT NULL,
  `id_ville` int(11) DEFAULT NULL,
  `quantite` int(11) DEFAULT NULL,
  `montant_total` decimal(10,2) DEFAULT NULL,
  `date_achat` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `achats`
--

LOCK TABLES `achats` WRITE;
/*!40000 ALTER TABLE `achats` DISABLE KEYS */;
INSERT INTO `achats` VALUES (1,3,3,40,1540000.00,'2026-02-17'),(2,3,3,2,77000.00,'2026-02-17'),(3,3,3,1,38500.00,'2026-02-17'),(4,1,2,20,55000.00,'2026-02-17');
/*!40000 ALTER TABLE `achats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `besoins`
--

DROP TABLE IF EXISTS `besoins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `besoins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  `id_produit` int(11) DEFAULT NULL,
  `id_ville` int(11) DEFAULT NULL,
  `id_region` int(11) DEFAULT NULL,
  `quantite` int(11) NOT NULL,
  `quantite_restante` int(11) NOT NULL DEFAULT 0,
  `etat` varchar(50) DEFAULT 'En attente',
  `date_besoin` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `besoins`
--

LOCK TABLES `besoins` WRITE;
/*!40000 ALTER TABLE `besoins` DISABLE KEYS */;
INSERT INTO `besoins` VALUES (10,'Riz',1,4,2,100,0,'Satisfait','2026-02-17'),(11,'Besoin Argent Ville1',2,1,NULL,200000,0,'Satisfait','2026-01-15'),(12,'Besoin Argent Ville2',2,2,NULL,150000,0,'Satisfait','2026-01-20'),(13,'Besoin Argent Ville3',2,3,NULL,100000,0,'Satisfait','2026-01-25'),(14,'Besoin Riz Ville1',1,1,NULL,80,0,'Satisfait','2026-01-10'),(15,'Besoin Riz Ville2',1,2,NULL,120,40,'Partiel','2026-01-12'),(16,'Besoin Tôle Ville2',3,2,NULL,50,50,'En attente','2026-02-01'),(17,'Besoin Huile Ville1',4,1,NULL,30,0,'Satisfait','2026-02-05'),(18,'Urgence Argent',5,1,NULL,200000,0,'Satisfait','2026-01-15'),(19,'Besoin Argent',5,2,NULL,150000,0,'Satisfait','2026-01-20');
/*!40000 ALTER TABLE `besoins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categorie_produits`
--

DROP TABLE IF EXISTS `categorie_produits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categorie_produits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(40) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorie_produits`
--

LOCK TABLES `categorie_produits` WRITE;
/*!40000 ALTER TABLE `categorie_produits` DISABLE KEYS */;
INSERT INTO `categorie_produits` VALUES (1,'Naturel','Produits de consommation alimentaire'),(2,'Matériel','Matériaux pour réparation'),(3,'Financier','Aide en argent');
/*!40000 ALTER TABLE `categorie_produits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conf_frais_achat`
--

DROP TABLE IF EXISTS `conf_frais_achat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conf_frais_achat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `taux_pourcentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `date_config` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conf_frais_achat`
--

LOCK TABLES `conf_frais_achat` WRITE;
/*!40000 ALTER TABLE `conf_frais_achat` DISABLE KEYS */;
INSERT INTO `conf_frais_achat` VALUES (1,0.00,'2026-02-16'),(2,10.00,'2026-02-16');
/*!40000 ALTER TABLE `conf_frais_achat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dispatch`
--

DROP TABLE IF EXISTS `dispatch`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dispatch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_don` int(11) NOT NULL,
  `id_besoin` int(11) NOT NULL,
  `quantite_attribuee` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=486 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dispatch`
--

LOCK TABLES `dispatch` WRITE;
/*!40000 ALTER TABLE `dispatch` DISABLE KEYS */;
INSERT INTO `dispatch` VALUES (476,34,19,150000),(477,34,18,200000),(478,33,17,30),(479,29,13,100000),(480,29,12,150000),(481,29,11,200000),(482,31,14,80),(483,31,10,100),(484,31,15,20),(485,28,15,40);
/*!40000 ALTER TABLE `dispatch` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dons`
--

DROP TABLE IF EXISTS `dons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  `id_produit` int(11) DEFAULT NULL,
  `id_ville` int(11) DEFAULT NULL,
  `id_region` int(11) DEFAULT NULL,
  `quantite` int(11) NOT NULL,
  `date_don` date DEFAULT NULL,
  `donneur` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dons`
--

LOCK TABLES `dons` WRITE;
/*!40000 ALTER TABLE `dons` DISABLE KEYS */;
INSERT INTO `dons` VALUES (28,'Riz',1,4,NULL,40,'2026-02-17','Moi'),(29,'Don Argent 1',2,1,NULL,500000,'2026-02-01','Donateur A'),(30,'Don Argent 2',2,2,NULL,300000,'2026-02-05','Donateur B'),(31,'Don Riz',1,1,NULL,200,'2026-02-10','Donateur C'),(32,'Don Tôle',3,2,NULL,100,'2026-02-12','Donateur D'),(33,'Don Huile',4,NULL,NULL,50,'2026-02-15','Donateur E'),(34,'Don Argent A',5,1,NULL,500000,'2026-02-01','Donateur X'),(35,'Don Argent B',5,2,NULL,300000,'2026-02-05','Donateur Y');
/*!40000 ALTER TABLE `dons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produits`
--

DROP TABLE IF EXISTS `produits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `produits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(40) NOT NULL,
  `id_categorie` int(11) DEFAULT NULL,
  `prix_unitaire` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produits`
--

LOCK TABLES `produits` WRITE;
/*!40000 ALTER TABLE `produits` DISABLE KEYS */;
INSERT INTO `produits` VALUES (1,'Riz',1,2500.00),(2,'Huile',1,6000.00),(3,'Tôle',2,35000.00),(4,'Clous (kg)',2,8000.00),(5,'Argent',3,1.00);
/*!40000 ALTER TABLE `produits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `regions`
--

DROP TABLE IF EXISTS `regions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `regions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `regions`
--

LOCK TABLES `regions` WRITE;
/*!40000 ALTER TABLE `regions` DISABLE KEYS */;
INSERT INTO `regions` VALUES (1,'Atsinanana'),(2,'Analamanga');
/*!40000 ALTER TABLE `regions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `type_user`
--

DROP TABLE IF EXISTS `type_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `type_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_user` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `type_user`
--

LOCK TABLES `type_user` WRITE;
/*!40000 ALTER TABLE `type_user` DISABLE KEYS */;
INSERT INTO `type_user` VALUES (1,'admin'),(2,'user');
/*!40000 ALTER TABLE `type_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `login` varchar(100) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `id_type_user` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`),
  KEY `id_type_user` (`id_type_user`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_type_user`) REFERENCES `type_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrateur','admin','admin123',1),(2,'Jean Rakoto','jean','jean123',2),(3,'Marie Rasoa','marie','marie123',2),(4,'Paul Andry','paul','paul123',2);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `villes`
--

DROP TABLE IF EXISTS `villes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `villes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(40) NOT NULL,
  `id_region` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `villes`
--

LOCK TABLES `villes` WRITE;
/*!40000 ALTER TABLE `villes` DISABLE KEYS */;
INSERT INTO `villes` VALUES (1,'Toamasina',1),(2,'Brickaville',1),(3,'Antananarivo',2),(4,'Ambohidratrimo',2),(5,'Ankazobe',2),(6,'Ambatofotsy',2);
/*!40000 ALTER TABLE `villes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-17 13:18:09

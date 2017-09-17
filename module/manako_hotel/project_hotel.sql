-- MySQL dump 10.13  Distrib 5.6.24, for linux-glibc2.5 (x86_64)
--
-- Host: localhost    Database: gt_manako32s
-- ------------------------------------------------------
-- Server version	5.6.26

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
-- Table structure for table `project_hotel`
--
USE magang_gtproject_testing;

DROP TABLE IF EXISTS `project_hotel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_hotel` (
  `hotelId` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hotelNama` varchar(200) NOT NULL,
  `hotelAlamat` varchar(255) NOT NULL,
  `hotelKodeProv` bigint(20) NOT NULL,
  `hotelPhone` varchar(255) NOT NULL,
  `hotelHarga` varchar(100) NOT NULL,
  `hotelFasilitas` varchar(255) NOT NULL,
  `hotelKeterangan` varchar(255) NOT NULL,
  PRIMARY KEY (`hotelId`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_hotel`
--

LOCK TABLES `project_hotel` WRITE;
/*!40000 ALTER TABLE `project_hotel` DISABLE KEYS */;
INSERT INTO `project_hotel` VALUES (1,'Hotelku 1','sleman',340,'08181818181','200000','ac, tv, kamar mandi','hotelku bla bla bla'),(2,'Hotelku 2','yogyakarta',340,'123123123','123000000','ac, tv','hotelku 2 bla bla bla'),(4,'hotel 6','aceh',110,'123123','20000','aklshd','lkakndlwaw'),(9,'ini hotel','sumbar',130,'1233123','12312313','ini fasilitas','ini keterangan'),(17,'asdasd','dasd',100,'123','3123','asdasd','asdasd'),(20,'qwe','qwe',110,'123123','200000','asdasd','asdasd'),(21,'ad','asdasd',170,'081881','2000000','asadasd','asdasd');
/*!40000 ALTER TABLE `project_hotel` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-11-23 10:35:54

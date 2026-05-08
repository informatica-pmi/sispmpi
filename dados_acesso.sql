-- MySQL dump 10.13  Distrib 8.0.37, for Linux (x86_64)
--
-- Host: localhost    Database: sispmpi
-- ------------------------------------------------------
-- Server version	8.0.37

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (83,'admin','xxxxxx','admin','$2y$10$QDI3wK4ofixgk4/3XOOeGuAEDHiBUW9KyWSJP10xSfOs9n10l8Iva','Administrador','admin@email.com','(11) 1111-1111',17,NULL,1,NULL,'82M64C2e1IjZsD_nCIObWDLiSdiWspCb_1647974070',NULL,'2020-04-27 14:15:50'),(907,'Dinah','1111','dinah.braga','$2y$10$oqaqV4PSl0i0diHFMpCT9OZ4creLBQ96VE6FgxND6ZryMw49zxSCm','Controladora Interna','dinah.braga@pmi.mg.gov.br','(11) 1111-1111',17,NULL,1,NULL,NULL,83,'2026-05-05 19:06:55');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `auth_assignment`
--

LOCK TABLES `auth_assignment` WRITE;
/*!40000 ALTER TABLE `auth_assignment` DISABLE KEYS */;
INSERT INTO `auth_assignment` VALUES ('Administrador','83',1,NULL),('Administrador','907',1,1778008015),('Alta Administração','83',0,1778168411),('Alta Administração','907',0,1778008015),('Auditor','83',0,1778168411),('Auditor','907',0,1778008015),('Executor','83',0,1778168411),('Executor','907',0,1778008015),('Grupo de Trabalho','83',0,1778168411),('Grupo de Trabalho','907',0,1778008015),('Monitoramento','83',0,1778168411),('Monitoramento','907',0,1778008015),('Observador','83',0,1777560354),('Observador','907',0,1778008015);
/*!40000 ALTER TABLE `auth_assignment` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-08 17:17:47

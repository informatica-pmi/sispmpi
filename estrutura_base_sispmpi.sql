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
-- Table structure for table `acao`
--

DROP TABLE IF EXISTS `acao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `acao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `eixo_id` int NOT NULL,
  `subeixo_id` int DEFAULT NULL,
  `numero` int NOT NULL,
  `titulo` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `descricao` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `unidade_executora` int NOT NULL,
  `objetivo` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `beneficio_instituicao` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `classificacao` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `previsao_inicio` date DEFAULT NULL,
  `previsao_conclusao` date DEFAULT NULL,
  `orcamento_previsto` decimal(20,2) DEFAULT NULL,
  `status` tinyint NOT NULL,
  `acao_referencia_id` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx-acao-eixo_id` (`eixo_id`),
  KEY `idx-acao-subeixo_id` (`subeixo_id`),
  KEY `idx-acao-unidade_executura` (`unidade_executora`),
  KEY `idx-acao-acao_referencia_id` (`acao_referencia_id`),
  CONSTRAINT `fk-acao-acao_referencia_id` FOREIGN KEY (`acao_referencia_id`) REFERENCES `acao` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk-acao-eixo_id` FOREIGN KEY (`eixo_id`) REFERENCES `eixo` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-acao-subeixo_id` FOREIGN KEY (`subeixo_id`) REFERENCES `subeixo` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-acao-unidade_executura` FOREIGN KEY (`unidade_executora`) REFERENCES `unidade_administrativa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1384 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `acao_avaliacao_recomendacao`
--

DROP TABLE IF EXISTS `acao_avaliacao_recomendacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `acao_avaliacao_recomendacao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `recomendacao` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `resposta` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `acao_id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `usuario_resposta_id` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx-acao_avaliacao_recomendacao-acao_id` (`acao_id`),
  KEY `idx-acao_avaliacao_recomendacao-usuario_id` (`usuario_id`),
  KEY `idx-acao_avaliacao_recomendacao-usuario_resposta_id` (`usuario_resposta_id`),
  CONSTRAINT `fk-acao_avaliacao_recomendacao-acao_id` FOREIGN KEY (`acao_id`) REFERENCES `acao` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-acao_avaliacao_recomendacao-usuario_id` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-acao_avaliacao_recomendacao-usuario_resposta_id` FOREIGN KEY (`usuario_resposta_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `acao_execucao`
--

DROP TABLE IF EXISTS `acao_execucao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `acao_execucao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `data_inicio` date DEFAULT NULL,
  `data_conclusao` date DEFAULT NULL,
  `orcamento_executado` decimal(20,2) DEFAULT NULL,
  `observacoes` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `evidencias_sugeridas` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `evidencia_link` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `processo_sei` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `acao_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx-acao_execucao-acao_id` (`acao_id`),
  CONSTRAINT `fk-acao_execucao-acao_id` FOREIGN KEY (`acao_id`) REFERENCES `acao` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=489 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `acao_execucao_arquivo`
--

DROP TABLE IF EXISTS `acao_execucao_arquivo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `acao_execucao_arquivo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `acao_execucao_id` int NOT NULL,
  `arquivo_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx-acao_execucao_arquivo-acao_execucao_id` (`acao_execucao_id`),
  KEY `idx-acao_execucao_arquivo-arquivo_id` (`acao_execucao_id`),
  KEY `fk-acao_execucao_arquivo-arquivo_id` (`arquivo_id`),
  CONSTRAINT `fk-acao_execucao_arquivo-acao_execucao_id` FOREIGN KEY (`acao_execucao_id`) REFERENCES `acao_execucao` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-acao_execucao_arquivo-arquivo_id` FOREIGN KEY (`arquivo_id`) REFERENCES `arquivo` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=158 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `acao_execucao_fator_limitante`
--

DROP TABLE IF EXISTS `acao_execucao_fator_limitante`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `acao_execucao_fator_limitante` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fator_limitante_id` int NOT NULL,
  `acao_execucao_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx-acao_execucao_fator_limitante-fator_limitante_id` (`fator_limitante_id`),
  KEY `idx-acao_execucao_fator_limitante-acao_execucao_id` (`acao_execucao_id`),
  CONSTRAINT `fk-acao_execucao_fator_limitante-acao_execucao_id` FOREIGN KEY (`acao_execucao_id`) REFERENCES `acao_execucao` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-acao_execucao_fator_limitante-fator_limitante_id` FOREIGN KEY (`fator_limitante_id`) REFERENCES `fator_limitante` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1033 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `acao_monitoramento`
--

DROP TABLE IF EXISTS `acao_monitoramento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `acao_monitoramento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `risco_n_implementacao` tinyint NOT NULL,
  `acao_id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx-acao_monitoramento-acao_id` (`acao_id`),
  KEY `idx-acao_monitoramento-usuario_id` (`usuario_id`),
  CONSTRAINT `fk-acao_monitoramento-acao_id` FOREIGN KEY (`acao_id`) REFERENCES `acao` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-acao_monitoramento-usuario_id` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=196 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `acao_monitoramento_recomendacao`
--

DROP TABLE IF EXISTS `acao_monitoramento_recomendacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `acao_monitoramento_recomendacao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `recomendacao` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `resposta` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `acao_monitoramento_id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `usuario_resposta_id` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx-acao_monitoramento_recomendacao-acao_monitoramento_id` (`acao_monitoramento_id`),
  KEY `idx-acao_monitoramento_recomendacao-usuario_id` (`usuario_id`),
  KEY `idx-acao_monitoramento_recomendacao-usuario_resposta_id` (`usuario_resposta_id`),
  CONSTRAINT `fk-acao_monitoramento_recomendacao-acao_monitoramento_id` FOREIGN KEY (`acao_monitoramento_id`) REFERENCES `acao_monitoramento` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-acao_monitoramento_recomendacao-usuario_id` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`),
  CONSTRAINT `fk-acao_monitoramento_recomendacao-usuario_resposta_id` FOREIGN KEY (`usuario_resposta_id`) REFERENCES `usuario` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=217 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `acao_servidor`
--

DROP TABLE IF EXISTS `acao_servidor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `acao_servidor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `acao_id` int NOT NULL,
  `servidor_id` int NOT NULL,
  `tipo` tinyint NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx-acao_servidor-acao_id` (`acao_id`),
  KEY `idx-acao_servidor-servidor_id` (`servidor_id`),
  CONSTRAINT `fk-acao_servidor-acao_id` FOREIGN KEY (`acao_id`) REFERENCES `acao` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-acao_servidor-servidor_id` FOREIGN KEY (`servidor_id`) REFERENCES `servidor` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1127 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `acao_tipo`
--

DROP TABLE IF EXISTS `acao_tipo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `acao_tipo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `acao_id` int NOT NULL,
  `tipo_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx-acao_tipo-tipo_id` (`tipo_id`),
  KEY `idx-acao_tipo-acao_id` (`acao_id`),
  CONSTRAINT `fk-acao_tipo-acao_id` FOREIGN KEY (`acao_id`) REFERENCES `acao` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-acao_tipo-tipo_id` FOREIGN KEY (`tipo_id`) REFERENCES `tipo` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1245 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `acao_unidade_apoio`
--

DROP TABLE IF EXISTS `acao_unidade_apoio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `acao_unidade_apoio` (
  `id` int NOT NULL AUTO_INCREMENT,
  `acao_id` int NOT NULL,
  `unidade_administrativa_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx-acao_unidade_apoio-acao_id` (`acao_id`),
  KEY `idx-acao_unidade_apoio-unidade_administrativa_id` (`unidade_administrativa_id`),
  CONSTRAINT `fk-acao_unidade_apoio-acao_id` FOREIGN KEY (`acao_id`) REFERENCES `acao` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-acao_unidade_apoio-unidade_administrativa_id` FOREIGN KEY (`unidade_administrativa_id`) REFERENCES `unidade_administrativa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6505 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `arquivo`
--

DROP TABLE IF EXISTS `arquivo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `arquivo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome_original` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `nome_servidor` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `tamanho` decimal(10,2) NOT NULL,
  `extensao` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `path` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`)
) ENGINE=InnoDB AUTO_INCREMENT=506 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `auth_assignment`
--

DROP TABLE IF EXISTS `auth_assignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `user_id` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `active` int NOT NULL DEFAULT '1',
  `created_at` int DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `idx-auth_assignment-user_id` (`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `auth_item`
--

DROP TABLE IF EXISTS `auth_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_item` (
  `name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `type` smallint NOT NULL,
  `description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `rule_name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `data` blob,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `auth_item_child`
--

DROP TABLE IF EXISTS `auth_item_child`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `child` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `auth_rule`
--

DROP TABLE IF EXISTS `auth_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_rule` (
  `name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `data` blob,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `diagnostico`
--

DROP TABLE IF EXISTS `diagnostico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `diagnostico` (
  `id` int NOT NULL AUTO_INCREMENT,
  `plano_integridade_id` int NOT NULL,
  `page_key` tinyint NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx-diagnostico-plano_integridade_id` (`plano_integridade_id`),
  CONSTRAINT `fk-diagnostico-plano_integridade_id` FOREIGN KEY (`plano_integridade_id`) REFERENCES `plano_integridade` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `diagnostico_ciencia`
--

DROP TABLE IF EXISTS `diagnostico_ciencia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `diagnostico_ciencia` (
  `id` int NOT NULL AUTO_INCREMENT,
  `diagnostico_id` int NOT NULL,
  `sugestoes` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `validado` tinyint NOT NULL,
  `usuario_id` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx-diagnostico_ciencia-diagnostico_id` (`diagnostico_id`),
  KEY `idx-diagnostico_ciencia-usuario_id` (`usuario_id`),
  CONSTRAINT `fk-diagnostico_ciencia-diagnostico_id` FOREIGN KEY (`diagnostico_id`) REFERENCES `diagnostico` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-diagnostico_ciencia-usuario_id` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `diagnostico_eixo_tematico`
--

DROP TABLE IF EXISTS `diagnostico_eixo_tematico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `diagnostico_eixo_tematico` (
  `id` int NOT NULL AUTO_INCREMENT,
  `eixo_tematico_id` int NOT NULL,
  `diagnostico_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx-diagnostico_eixo_tematico-eixo_tematico_id` (`eixo_tematico_id`),
  KEY `idx-diagnostico_eixo_tematico-diagnostico_id` (`diagnostico_id`),
  CONSTRAINT `fk-diagnostico_eixo_tematico-diagnostico_id` FOREIGN KEY (`diagnostico_id`) REFERENCES `diagnostico` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-diagnostico_eixo_tematico-eixo_tematico_id` FOREIGN KEY (`eixo_tematico_id`) REFERENCES `eixo_tematico` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `diagnostico_info_estrategica`
--

DROP TABLE IF EXISTS `diagnostico_info_estrategica`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `diagnostico_info_estrategica` (
  `id` int NOT NULL AUTO_INCREMENT,
  `diagnostico_id` int NOT NULL,
  `missao` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `visao` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `valores` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `estrutura_organica` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `competencias` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `atribuicoes` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk-diagnostico_info_estrategica-diagnostico_id` (`diagnostico_id`),
  CONSTRAINT `fk-diagnostico_info_estrategica-diagnostico_id` FOREIGN KEY (`diagnostico_id`) REFERENCES `diagnostico` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `diagnostico_instrumento`
--

DROP TABLE IF EXISTS `diagnostico_instrumento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `diagnostico_instrumento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `instrumento_id` int NOT NULL,
  `diagnostico_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx-diagnostico_instrumento-instrumento_id` (`instrumento_id`),
  KEY `idx-diagnostico_instrumento-diagnostico_id` (`diagnostico_id`),
  CONSTRAINT `fk-diagnostico_instrumento-diagnostico_id` FOREIGN KEY (`diagnostico_id`) REFERENCES `diagnostico` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-diagnostico_instrumento-instrumento_id` FOREIGN KEY (`instrumento_id`) REFERENCES `instrumento` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=433 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `diagnostico_resultado`
--

DROP TABLE IF EXISTS `diagnostico_resultado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `diagnostico_resultado` (
  `id` int NOT NULL AUTO_INCREMENT,
  `diagnostico_id` int NOT NULL,
  `descricao` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `objetivos_trabalhados` text COLLATE utf8mb3_unicode_ci,
  `objetivos_estrategicos` text COLLATE utf8mb3_unicode_ci,
  `estrutura_governanca` text COLLATE utf8mb3_unicode_ci,
  `periodicidade_monitoramentos` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `periodicidade_avaliacoes` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `periodicidade_atualizacoes` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `aspectos_comunicacao` text COLLATE utf8mb3_unicode_ci,
  `aspectos_capacitacao` text COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx-diagnostico_resultado-diagnostico_id` (`diagnostico_id`),
  CONSTRAINT `fk-diagnostico_resultado-diagnostico_id` FOREIGN KEY (`diagnostico_id`) REFERENCES `diagnostico` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `eixo`
--

DROP TABLE IF EXISTS `eixo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `eixo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `plano_integridade_id` int NOT NULL,
  `titulo` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `descricao` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx-eixo-plano_integridade_id` (`plano_integridade_id`),
  CONSTRAINT `fk-eixo-plano_integridade_id` FOREIGN KEY (`plano_integridade_id`) REFERENCES `plano_integridade` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=355 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `eixo_tematico`
--

DROP TABLE IF EXISTS `eixo_tematico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `eixo_tematico` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `orgao_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx-eixo_tematico-orgao_id` (`orgao_id`),
  CONSTRAINT `fk-eixo_tematico-orgao_id` FOREIGN KEY (`orgao_id`) REFERENCES `orgao` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fator_limitante`
--

DROP TABLE IF EXISTS `fator_limitante`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fator_limitante` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `orgao_id` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx-fator_limitante-orgao_id` (`orgao_id`),
  CONSTRAINT `fk-fator_limitante-orgao_id` FOREIGN KEY (`orgao_id`) REFERENCES `orgao` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `grupo`
--

DROP TABLE IF EXISTS `grupo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grupo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `plano_integridade_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx-grupo-plano_integridade_id` (`plano_integridade_id`),
  CONSTRAINT `fk-grupo-plano_integridade_id` FOREIGN KEY (`plano_integridade_id`) REFERENCES `plano_integridade` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=140 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `grupo_instituido`
--

DROP TABLE IF EXISTS `grupo_instituido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grupo_instituido` (
  `id` int NOT NULL AUTO_INCREMENT,
  `grupo_id` int NOT NULL,
  `formalmente` tinyint DEFAULT NULL,
  `nome_numero` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `data_publicacao` date DEFAULT NULL,
  `dias_conclusao` int DEFAULT NULL,
  `data_prevista_conclusao` date DEFAULT NULL,
  `link` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `data_inicio` date DEFAULT NULL,
  `order` int NOT NULL,
  `tipo` tinyint NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx-grupo_instituido-grupo_id` (`grupo_id`),
  CONSTRAINT `fk-grupo_instituido-grupo_id` FOREIGN KEY (`grupo_id`) REFERENCES `grupo` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=194 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `grupo_servidor`
--

DROP TABLE IF EXISTS `grupo_servidor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grupo_servidor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `grupo_id` int NOT NULL,
  `servidor_id` int NOT NULL,
  `status` tinyint NOT NULL,
  `coordenador` tinyint NOT NULL,
  `order` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx-grupo_servidor-servidor_id` (`servidor_id`),
  KEY `idx-grupo_servidor-grupo_id` (`grupo_id`),
  CONSTRAINT `fk-grupo_servidor-grupo_id` FOREIGN KEY (`grupo_id`) REFERENCES `grupo` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-grupo_servidor-servidor_id` FOREIGN KEY (`servidor_id`) REFERENCES `servidor` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=926 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `historico`
--

DROP TABLE IF EXISTS `historico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `historico` (
  `id` int NOT NULL AUTO_INCREMENT,
  `action` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `model` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `id_registro` int NOT NULL,
  `campo` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `antigo_valor` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `novo_valor` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `justificativa` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `multiple` tinyint DEFAULT NULL,
  `usuario_id` int NOT NULL,
  `usuario_perfil` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx-historico-usuario_id` (`usuario_id`),
  CONSTRAINT `fk-historico-usuario_id` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11809 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `informacao_estado`
--

DROP TABLE IF EXISTS `informacao_estado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `informacao_estado` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ano` int NOT NULL,
  `orcamento` decimal(20,2) NOT NULL,
  `quantitativo_pessoal` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `instrumento`
--

DROP TABLE IF EXISTS `instrumento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `instrumento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `orgao_id` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx-instrumento-orgao_id` (`orgao_id`),
  CONSTRAINT `fk-instrumento-orgao_id` FOREIGN KEY (`orgao_id`) REFERENCES `orgao` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=214 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `migration`
--

DROP TABLE IF EXISTS `migration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migration` (
  `version` varchar(180) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `apply_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `orgao`
--

DROP TABLE IF EXISTS `orgao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orgao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `sigla` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `tipo` tinyint NOT NULL,
  `status` tinyint NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `orgao_contabil`
--

DROP TABLE IF EXISTS `orgao_contabil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orgao_contabil` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ano` int NOT NULL,
  `orcamento` decimal(20,2) NOT NULL,
  `quantitativo_pessoal` int NOT NULL,
  `orgao_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx-orgao_contabil-orgao_id` (`orgao_id`),
  CONSTRAINT `fk-orgao_contabil-orgao_id` FOREIGN KEY (`orgao_id`) REFERENCES `orgao` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `plano_integridade`
--

DROP TABLE IF EXISTS `plano_integridade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `plano_integridade` (
  `id` int NOT NULL AUTO_INCREMENT,
  `edicao` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `versao` float NOT NULL DEFAULT '1',
  `status` tinyint NOT NULL,
  `orgao_id` int NOT NULL,
  `plano_integridade_referencia_id` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx-plano_integridade-orgao_id` (`orgao_id`),
  KEY `idx-plano_integridade-plano_integridade_referencia_id` (`plano_integridade_referencia_id`),
  CONSTRAINT `fk-plano_integridade-orgao_id` FOREIGN KEY (`orgao_id`) REFERENCES `orgao` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-plano_integridade-plano_integridade_referencia_id` FOREIGN KEY (`plano_integridade_referencia_id`) REFERENCES `plano_integridade` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=121 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `plano_integridade_novo`
--

DROP TABLE IF EXISTS `plano_integridade_novo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `plano_integridade_novo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `plano_integridade_id` int NOT NULL,
  `tipo` tinyint NOT NULL,
  `usuario_solicitante_id` int NOT NULL,
  `autorizado` tinyint DEFAULT NULL,
  `usuario_autorizador_id` int DEFAULT NULL,
  `justificativa` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx-plano_integridade_novo-plano_integridade_id` (`plano_integridade_id`),
  KEY `idx-plano_integridade_novo-usuario_solicitante_id` (`usuario_solicitante_id`),
  KEY `idx-plano_integridade_novo-usuario_autorizador_id` (`usuario_autorizador_id`),
  CONSTRAINT `fk-plano_integridade_novo-plano_integridade_id` FOREIGN KEY (`plano_integridade_id`) REFERENCES `plano_integridade` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-plano_integridade_novo-usuario_autorizador_id` FOREIGN KEY (`usuario_autorizador_id`) REFERENCES `usuario` (`id`),
  CONSTRAINT `fk-plano_integridade_novo-usuario_solicitante_id` FOREIGN KEY (`usuario_solicitante_id`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `plano_integridade_reabertura`
--

DROP TABLE IF EXISTS `plano_integridade_reabertura`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `plano_integridade_reabertura` (
  `id` int NOT NULL AUTO_INCREMENT,
  `plano_integridade_id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx-plano_integridade_reabertura-plano_integridade_id` (`plano_integridade_id`),
  KEY `idx-plano_integridade_reabertura-usuario_id` (`usuario_id`),
  CONSTRAINT `fk-plano_integridade_reabertura-plano_integridade_id` FOREIGN KEY (`plano_integridade_id`) REFERENCES `plano_integridade` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-plano_integridade_reabertura-usuario_id` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `plano_integridade_recomendacao`
--

DROP TABLE IF EXISTS `plano_integridade_recomendacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `plano_integridade_recomendacao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `recomendacao` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `resposta` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `plano_integridade_id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `usuario_resposta_id` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx-plano_integridade_recomendacao-plano_integridade_id` (`plano_integridade_id`),
  KEY `idx-plano_integridade_recomendacao-usuario_id` (`usuario_id`),
  KEY `idx-plano_integridade_recomendacao-usuario_resposta_id` (`usuario_resposta_id`),
  CONSTRAINT `fk-plano_integridade_recomendacao-plano_integridade_id` FOREIGN KEY (`plano_integridade_id`) REFERENCES `plano_integridade` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-plano_integridade_recomendacao-usuario_id` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-plano_integridade_recomendacao-usuario_resposta_id` FOREIGN KEY (`usuario_resposta_id`) REFERENCES `usuario` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `promover_integridade`
--

DROP TABLE IF EXISTS `promover_integridade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `promover_integridade` (
  `id` int NOT NULL AUTO_INCREMENT,
  `data` date NOT NULL,
  `acao_desenvolvida` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `horas_trabalho` smallint NOT NULL,
  `plano_integridade_id` int NOT NULL,
  `orgao_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx-promover_integridade-plano_integridade_id` (`plano_integridade_id`),
  KEY `idx-promover_integridade-orgao_id` (`orgao_id`),
  CONSTRAINT `fk-promover_integridade-orgao_id` FOREIGN KEY (`orgao_id`) REFERENCES `orgao` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-promover_integridade-plano_integridade_id` FOREIGN KEY (`plano_integridade_id`) REFERENCES `plano_integridade` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `publicacao`
--

DROP TABLE IF EXISTS `publicacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `publicacao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `plano_integridade_id` int NOT NULL,
  `evento` tinyint NOT NULL,
  `data_evento` date DEFAULT NULL,
  `justificativa_evento` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `disponibilizado` tinyint NOT NULL,
  `endereco_disponibilizado` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `justificativa_disponibilizado` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `plano_comunicacao` tinyint DEFAULT NULL,
  `plano_comunicacao_arquivo` int DEFAULT NULL,
  `plano_treinamento` tinyint DEFAULT NULL,
  `plano_treinamento_arquivo` int DEFAULT NULL,
  `data_publicacao` date NOT NULL,
  `nome_numero` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `link` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `plano_acao_arquivo` int DEFAULT NULL,
  `plano_integridade_arquivo` int NOT NULL,
  `ciente_conteudo` tinyint(1) NOT NULL,
  `ciente_conclusao` tinyint(1) NOT NULL,
  `usuario_id` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx-publicacao-plano_integridade_id` (`plano_integridade_id`),
  KEY `idx-publicacao-plano_acao_arquivo` (`plano_acao_arquivo`),
  KEY `idx-publicacao-plano_integridade_arquivo` (`plano_integridade_arquivo`),
  KEY `idx-publicacao-plano_comunicacao_arquivo` (`plano_comunicacao_arquivo`),
  KEY `idx-publicacao-plano_treinamento_arquivo` (`plano_treinamento_arquivo`),
  KEY `idx-publicacao-usuario_id` (`usuario_id`),
  CONSTRAINT `fk-publicacao-plano_acao_arquivo` FOREIGN KEY (`plano_acao_arquivo`) REFERENCES `arquivo` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-publicacao-plano_comunicacao_arquivo` FOREIGN KEY (`plano_comunicacao_arquivo`) REFERENCES `arquivo` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-publicacao-plano_integridade_arquivo` FOREIGN KEY (`plano_integridade_arquivo`) REFERENCES `arquivo` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-publicacao-plano_integridade_id` FOREIGN KEY (`plano_integridade_id`) REFERENCES `plano_integridade` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-publicacao-plano_treinamento_arquivo` FOREIGN KEY (`plano_treinamento_arquivo`) REFERENCES `arquivo` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-publicacao-usuario_id` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `queue`
--

DROP TABLE IF EXISTS `queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `queue` (
  `id` int NOT NULL AUTO_INCREMENT,
  `channel` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `job` longblob NOT NULL,
  `pushed_at` int NOT NULL,
  `ttr` int NOT NULL,
  `delay` int NOT NULL DEFAULT '0',
  `priority` int unsigned NOT NULL DEFAULT '1024',
  `reserved_at` int DEFAULT NULL,
  `attempt` int DEFAULT NULL,
  `done_at` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `channel` (`channel`),
  KEY `reserved_at` (`reserved_at`),
  KEY `priority` (`priority`)
) ENGINE=InnoDB AUTO_INCREMENT=5952 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reuniao`
--

DROP TABLE IF EXISTS `reuniao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reuniao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `plano_integridade_id` int NOT NULL,
  `data` date NOT NULL,
  `nome` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `pauta` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `registro` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `usuario_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx-reuniao-plano_integridade_id` (`plano_integridade_id`),
  KEY `idx-reuniao-usuario_id` (`usuario_id`),
  CONSTRAINT `fk-reuniao-plano_integridade_id` FOREIGN KEY (`plano_integridade_id`) REFERENCES `plano_integridade` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-reuniao-usuario_id` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reuniao_servidor`
--

DROP TABLE IF EXISTS `reuniao_servidor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reuniao_servidor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `reuniao_id` int NOT NULL,
  `servidor_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx-reuniao_servidor-reuniao_id` (`reuniao_id`),
  KEY `idx-reuniao_servidor-servidor_id` (`servidor_id`),
  CONSTRAINT `fk-reuniao_servidor-reuniao_id` FOREIGN KEY (`reuniao_id`) REFERENCES `reuniao` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-reuniao_servidor-servidor_id` FOREIGN KEY (`servidor_id`) REFERENCES `servidor` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=109 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `servidor`
--

DROP TABLE IF EXISTS `servidor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `servidor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `masp_matricula` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `unidade_administrativa_id` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx-servidor-unidade_administrativa_id` (`unidade_administrativa_id`),
  CONSTRAINT `fk-servidor-unidade_administrativa_id` FOREIGN KEY (`unidade_administrativa_id`) REFERENCES `unidade_administrativa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1859 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stakeholder`
--

DROP TABLE IF EXISTS `stakeholder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stakeholder` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `orgao_id` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx-stakeholder-orgao_id` (`orgao_id`),
  CONSTRAINT `fk-stakeholder-orgao_id` FOREIGN KEY (`orgao_id`) REFERENCES `orgao` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `subeixo`
--

DROP TABLE IF EXISTS `subeixo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subeixo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `eixo_id` int NOT NULL,
  `titulo` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `descricao` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx-subeixo-eixo_id` (`eixo_id`),
  CONSTRAINT `fk-subeixo-eixo_id` FOREIGN KEY (`eixo_id`) REFERENCES `eixo` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=419 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tipo`
--

DROP TABLE IF EXISTS `tipo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `orgao_id` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx-tipo-orgao_id` (`orgao_id`),
  CONSTRAINT `fk-tipo-orgao_id` FOREIGN KEY (`orgao_id`) REFERENCES `orgao` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `unidade_administrativa`
--

DROP TABLE IF EXISTS `unidade_administrativa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `unidade_administrativa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `orgao_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx-unidade_administrativa-orgao_id` (`orgao_id`),
  CONSTRAINT `fk-unidade_administrativa-orgao_id` FOREIGN KEY (`orgao_id`) REFERENCES `orgao` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1545 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `masp` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `login` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `senha` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `cargo` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `telefone` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `orgao_id` int NOT NULL,
  `unidade_administrativa_id` int DEFAULT NULL,
  `status` tinyint NOT NULL,
  `auth_key` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `password_reset_token` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `cadastrado_por` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx-usuario-cadastrado_por` (`cadastrado_por`),
  KEY `idx-usuario-orgao_id` (`orgao_id`),
  KEY `idx-usuario-unidade_administrativa_id` (`unidade_administrativa_id`),
  CONSTRAINT `fk-usuario-cadastrado_por` FOREIGN KEY (`cadastrado_por`) REFERENCES `usuario` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-usuario-orgao_id` FOREIGN KEY (`orgao_id`) REFERENCES `orgao` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-usuario-unidade_administrativa_id` FOREIGN KEY (`unidade_administrativa_id`) REFERENCES `unidade_administrativa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=908 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary view structure for view `v_indicadores`
--

DROP TABLE IF EXISTS `v_indicadores`;
/*!50001 DROP VIEW IF EXISTS `v_indicadores`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `v_indicadores` AS SELECT 
 1 AS `orgao_nome`,
 1 AS `orgao_tipo`,
 1 AS `orgao_pessoal`,
 1 AS `orgao_orcamento`,
 1 AS `orgao_orcamento_ano`,
 1 AS `plano_integridade_id`,
 1 AS `plano_integridade_status`,
 1 AS `plano_integridade_data_inicio`,
 1 AS `plano_integridade_data_conclusao`,
 1 AS `plano_integridade_edicao`,
 1 AS `plano_integridade_versao`,
 1 AS `eixos`,
 1 AS `acoes`,
 1 AS `acoes_atrasadas`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `validacao`
--

DROP TABLE IF EXISTS `validacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `validacao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `plano_integridade_id` int NOT NULL,
  `data_inicio` date NOT NULL,
  `info_complementar` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `data_conclusao` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx-validacao-plano_integridade_id` (`plano_integridade_id`),
  CONSTRAINT `fk-validacao-plano_integridade_id` FOREIGN KEY (`plano_integridade_id`) REFERENCES `plano_integridade` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `validacao_stakeholder`
--

DROP TABLE IF EXISTS `validacao_stakeholder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `validacao_stakeholder` (
  `id` int NOT NULL AUTO_INCREMENT,
  `validacao_id` int NOT NULL,
  `stakeholder_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx-validacao_stakeholder-stakeholder_id` (`stakeholder_id`),
  KEY `idx-validacao_stakeholder-validacao_id` (`validacao_id`),
  CONSTRAINT `fk-validacao_stakeholder-stakeholder_id` FOREIGN KEY (`stakeholder_id`) REFERENCES `stakeholder` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-validacao_stakeholder-validacao_id` FOREIGN KEY (`validacao_id`) REFERENCES `validacao` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=234 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Final view structure for view `v_indicadores`
--

/*!50001 DROP VIEW IF EXISTS `v_indicadores`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb3 */;
/*!50001 SET character_set_results     = utf8mb3 */;
/*!50001 SET collation_connection      = utf8mb3_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`sispmpi`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `v_indicadores` AS select `o`.`nome` AS `orgao_nome`,`o`.`tipo` AS `orgao_tipo`,`oc`.`quantitativo_pessoal` AS `orgao_pessoal`,`oc`.`orcamento` AS `orgao_orcamento`,`oc`.`ano` AS `orgao_orcamento_ano`,`pi`.`id` AS `plano_integridade_id`,`pi`.`status` AS `plano_integridade_status`,if(`gi`.`formalmente`,`gi`.`data_publicacao`,`gi`.`data_inicio`) AS `plano_integridade_data_inicio`,`p`.`data_publicacao` AS `plano_integridade_data_conclusao`,`pi`.`edicao` AS `plano_integridade_edicao`,`pi`.`versao` AS `plano_integridade_versao`,count(distinct `e`.`id`) AS `eixos`,count(`a`.`id`) AS `acoes`,(select count(0) from ((`acao` `_a` left join `eixo` `_e` on((`_e`.`id` = `_a`.`eixo_id`))) left join `plano_integridade` `_pi` on((`_pi`.`id` = `_e`.`plano_integridade_id`))) where ((`_e`.`plano_integridade_id` = `pi`.`id`) and (((`_a`.`previsao_inicio` < now()) and (`_a`.`status` = 1)) or ((`_a`.`previsao_conclusao` < now()) and (`_a`.`status` not in (3,4)))))) AS `acoes_atrasadas` from (((((((`orgao` `o` left join `plano_integridade` `pi` on((`pi`.`orgao_id` = `o`.`id`))) left join `grupo` `g` on((`g`.`plano_integridade_id` = `pi`.`id`))) left join `grupo_instituido` `gi` on(((`gi`.`grupo_id` = `g`.`id`) and (`gi`.`order` = 0)))) left join `eixo` `e` on((`e`.`plano_integridade_id` = `pi`.`id`))) left join `publicacao` `p` on((`p`.`plano_integridade_id` = `pi`.`id`))) left join `orgao_contabil` `oc` on(((`oc`.`orgao_id` = `o`.`id`) and (`oc`.`ano` = '2026')))) left join `acao` `a` on((`a`.`eixo_id` = `e`.`id`))) where ((`pi`.`id` in (select max(`plano_integridade`.`id`) from `plano_integridade` group by `plano_integridade`.`orgao_id`) or (`pi`.`id` is null)) and (`o`.`status` = 1)) group by `o`.`id` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-08 14:33:26

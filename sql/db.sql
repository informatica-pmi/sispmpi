/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=1383 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acao`
--

LOCK TABLES `acao` WRITE;
/*!40000 ALTER TABLE `acao` DISABLE KEYS */;
/*!40000 ALTER TABLE `acao` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `acao_avaliacao_recomendacao`
--

LOCK TABLES `acao_avaliacao_recomendacao` WRITE;
/*!40000 ALTER TABLE `acao_avaliacao_recomendacao` DISABLE KEYS */;
/*!40000 ALTER TABLE `acao_avaliacao_recomendacao` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `acao_execucao`
--

LOCK TABLES `acao_execucao` WRITE;
/*!40000 ALTER TABLE `acao_execucao` DISABLE KEYS */;
/*!40000 ALTER TABLE `acao_execucao` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `acao_execucao_arquivo`
--

LOCK TABLES `acao_execucao_arquivo` WRITE;
/*!40000 ALTER TABLE `acao_execucao_arquivo` DISABLE KEYS */;
/*!40000 ALTER TABLE `acao_execucao_arquivo` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `acao_execucao_fator_limitante`
--

LOCK TABLES `acao_execucao_fator_limitante` WRITE;
/*!40000 ALTER TABLE `acao_execucao_fator_limitante` DISABLE KEYS */;
/*!40000 ALTER TABLE `acao_execucao_fator_limitante` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `acao_monitoramento`
--

LOCK TABLES `acao_monitoramento` WRITE;
/*!40000 ALTER TABLE `acao_monitoramento` DISABLE KEYS */;
/*!40000 ALTER TABLE `acao_monitoramento` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `acao_monitoramento_recomendacao`
--

LOCK TABLES `acao_monitoramento_recomendacao` WRITE;
/*!40000 ALTER TABLE `acao_monitoramento_recomendacao` DISABLE KEYS */;
/*!40000 ALTER TABLE `acao_monitoramento_recomendacao` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `acao_servidor`
--

LOCK TABLES `acao_servidor` WRITE;
/*!40000 ALTER TABLE `acao_servidor` DISABLE KEYS */;
/*!40000 ALTER TABLE `acao_servidor` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `acao_tipo`
--

LOCK TABLES `acao_tipo` WRITE;
/*!40000 ALTER TABLE `acao_tipo` DISABLE KEYS */;
/*!40000 ALTER TABLE `acao_tipo` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=6504 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acao_unidade_apoio`
--

LOCK TABLES `acao_unidade_apoio` WRITE;
/*!40000 ALTER TABLE `acao_unidade_apoio` DISABLE KEYS */;
/*!40000 ALTER TABLE `acao_unidade_apoio` ENABLE KEYS */;
UNLOCK TABLES;

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=505 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `arquivo`
--

LOCK TABLES `arquivo` WRITE;
/*!40000 ALTER TABLE `arquivo` DISABLE KEYS */;
/*!40000 ALTER TABLE `arquivo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_assignment`
--

DROP TABLE IF EXISTS `auth_assignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `user_id` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` int DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `idx-auth_assignment-user_id` (`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_assignment`
--

LOCK TABLES `auth_assignment` WRITE;
/*!40000 ALTER TABLE `auth_assignment` DISABLE KEYS */;
INSERT INTO `auth_assignment` VALUES ('Administrador','83',NULL);
/*!40000 ALTER TABLE `auth_assignment` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `auth_item`
--

LOCK TABLES `auth_item` WRITE;
/*!40000 ALTER TABLE `auth_item` DISABLE KEYS */;
INSERT INTO `auth_item` VALUES ('/rbac/*',2,NULL,NULL,NULL,1600864778,1600864778),('acao-apagar',2,'Apagar ação.','OrgaoWithStatusRule',NULL,1589304166,1594224081),('acao-editar',2,'Editar ação.','OrgaoWithStatusRule',NULL,1589304177,1594224091),('Administrador',1,'Perfil destinado a CGE.',NULL,NULL,1588164385,1602265958),('Alta Administração',1,'Perfil destinado a Alta Administração.',NULL,NULL,1588259701,1588259701),('Auditor',1,'Perfil destinado aos Auditores.',NULL,NULL,1588164399,1588164448),('diagnostico-cadastrar',2,'Cadastrar diagnóstico.','OrgaoWithStatusRule',NULL,1590411151,1594224125),('diagnostico-cadastrar-ciencia',2,'Cadastrar ou editar os dados na seção diagnostico/ciência.',NULL,NULL,1589198284,1589198326),('diagnostico-cadastrar-informacoes-estrategicas',2,'Cadastrar ou editar os dados na seção diagnostico/informações estratégicas.',NULL,NULL,1589198186,1589198314),('diagnostico-cadastrar-instrumentos',2,'Cadastrar ou editar os dados na seção diagnostico/instrumentos.',NULL,NULL,1589198229,1589198336),('diagnostico-cadastrar-resultados',2,'Cadastrar ou editar os dados na seção diagnostico/resultados do diagnostico.',NULL,NULL,1589198258,1589198344),('diagnostico-editar',2,'Editar diagnóstico.','OrgaoWithStatusRule',NULL,1590411167,1594224131),('eixo-apagar',2,'Apagar eixo.','OrgaoWithStatusRule',NULL,1589226403,1594224004),('eixo-editar',2,'Editar eixo.','OrgaoWithStatusRule',NULL,1589224799,1594224016),('Executor',1,'Perfil destinado ao Executor',NULL,NULL,1588259663,1593692226),('fator-limitante-apagar',2,'Apagar fator limitante.',NULL,NULL,1599486257,1599486257),('fator-limitante-cadastrar',2,'Cadastrar fator limitante.',NULL,NULL,1599486271,1599486271),('fator-limitante-editar',2,'Editar fator limitante.',NULL,NULL,1599486248,1599486248),('fator-limitante-listar',2,'Listar fatores limitantes.',NULL,NULL,1599486236,1599486236),('Grupo de Trabalho',1,'Perfil destinado ao Grupo de Trabalho.',NULL,NULL,1588256897,1588260496),('grupo-cadastrar',2,'Cadastrar grupo.','OrgaoWithStatusRule',NULL,1590410530,1594224100),('grupo-editar',2,'Editar grupo.','OrgaoWithStatusRule',NULL,1590410670,1594224106),('informacao-estado-apagar',2,'Apagar informação do estado.',NULL,NULL,1588005795,1588008401),('informacao-estado-cadastrar',2,'Cadastrar informação do estado.',NULL,NULL,1588005770,1588008412),('informacao-estado-editar',2,'Editar informação do estado.',NULL,NULL,1588005787,1588008424),('informacao-estado-listar',2,'Listar informações do estado.',NULL,NULL,1588005700,1588008435),('informacao-estado-visualizar',2,'Visualizar informação do estado.',NULL,NULL,1588005805,1588008444),('instrumento-apagar',2,'Apagar instrumento.',NULL,NULL,1588793066,1588793066),('instrumento-cadastrar',2,'Cadastrar instrumento.',NULL,NULL,1588793008,1588793008),('instrumento-editar',2,'Editar instrumento.',NULL,NULL,1588793037,1588793037),('instrumento-listar',2,'Listar instrumentos.',NULL,NULL,1588793020,1588793020),('instrumento-visualizar',2,'Visualizar instrumento.',NULL,NULL,1588793054,1588793054),('menu-administracao',2,'Acesso ao menu \"Administração\".',NULL,NULL,1588005507,1588005779),('menu-usuarios',2,'Acesso ao menu \"Administração/Usuários\".',NULL,NULL,1588255793,1588255800),('modulo-auditor',2,'Acesso ao módulo \"Auditor\", onde se encontra a listagem dos planos de integridade.',NULL,NULL,1643729051,1643729051),('modulo-avaliacao',2,'Acesso ao módulo \"Avaliação das ações do plano de integridade\"',NULL,NULL,1590408502,1590408502),('modulo-elaboracao',2,'Acesso ao módulo \"Elaboração do plano de integridade\"',NULL,NULL,1590408443,1590408443),('modulo-execucao',2,'Acesso ao módulo \"Execução das ações do plano de integridade\"',NULL,NULL,1590408465,1600126972),('modulo-monitoramento',2,'Acesso ao módulo \"Monitoramento das ações do plano de integridade\"',NULL,NULL,1590408484,1590408484),('Monitoramento',1,'Perfil destinado ao Monitoramento.',NULL,NULL,1588259683,1593692210),('Observador',1,'Perfil destinado ao Observador.',NULL,NULL,1628718005,1628718005),('observar-monitoramento',2,'Observar o modulo de monitoramento.','OrgaoRule',NULL,1628717887,1628717887),('orgao-apagar',2,'Apagar órgão.',NULL,NULL,1588015627,1588015627),('orgao-cadastrar',2,'Cadastrar órgão.',NULL,NULL,1588015604,1588015604),('orgao-editar',2,'Editar órgão.',NULL,NULL,1588015616,1588015616),('orgao-listar',2,'Listar órgãos.',NULL,NULL,1588015593,1588015593),('orgao-visualizar',2,'Visualizar órgão.',NULL,NULL,1588015646,1588015646),('plano-acao-elaborar',2,'Gerar plano de ação parcial.','OrgaoRule',NULL,1590413789,1590499263),('plano-integridade-elaborar',2,'Gerar plano de integridade parcial.','OrgaoRule',NULL,1590414056,1590499275),('plano-integridade-reabertura',2,'Reabrir módulo 01 do plano de integridade selecionado.',NULL,NULL,1650388643,1650388643),('preencher-auditor',2,'Preencher o modulo auditor.','OrgaoRule',NULL,1643729087,1643729087),('preencher-avaliacao',2,'Preencher o modulo de avaliação.','OrgaoRule',NULL,1643729150,1643729150),('preencher-elaboracao',2,'Preencher o modulo de elaboração.','OrgaoWithStatusRule',NULL,1646245928,1646245928),('preencher-execucao',2,'Preencher o modulo de execução.','OrgaoRule',NULL,1600127052,1600127052),('preencher-monitoramento',2,'Preencher o modulo de monitoramento.','OrgaoRule',NULL,1628717862,1628717862),('publicacao-cadastrar',2,'Cadastrar publicação e comunicação.','OrgaoWithStatusRule',NULL,1590412759,1594224193),('publicacao-editar',2,'Editar publicação e comunicação.','OrgaoWithStatusRule',NULL,1590412787,1594224190),('redacao-cadastrar',2,'Cadastrar ou editar redação.','OrgaoWithStatusRule',NULL,1590411402,1594224172),('stakeholder-apagar',2,'Apagar stakeholder.',NULL,NULL,1589304712,1589304712),('stakeholder-cadastrar',2,'Cadastrar stakeholder.',NULL,NULL,1589304720,1589304720),('stakeholder-editar',2,'Editar stakeholder.',NULL,NULL,1589304702,1589304702),('stakeholder-listar',2,'Listar stakeholders.',NULL,NULL,1589304687,1589304746),('subeixo-apagar',2,'Apagar subeixo.','OrgaoWithStatusRule',NULL,1589227419,1594224025),('subeixo-editar',2,'Editar subeixo.','OrgaoWithStatusRule',NULL,1589227390,1594224039),('TI',1,'Perfil destinado a TI.',NULL,NULL,1588005521,1588164457),('unidade-administrativa-apagar-orgao',2,'Apagar unidade administrativa somente do seu órgão.','OrgaoRule',NULL,1588163488,1594224062),('unidade-administrativa-cadastrar',2,'Cadastrar unidade administrativa.',NULL,NULL,1588163498,1588163498),('unidade-administrativa-editar-orgao',2,'Editar unidade administrativa somente do seu órgão.','OrgaoRule',NULL,1588163510,1588168549),('unidade-administrativa-listar',2,'Listar unidades administrativas.',NULL,NULL,1588163477,1588163477),('usuario-apagar',2,'Apagar usuário.',NULL,NULL,1588181544,1588181544),('usuario-apagar-orgao',2,'Apagar usuário vinculado ao órgão do usuário logado.','UserAuditorRule',NULL,1588256329,1588256631),('usuario-cadastrar-auditor',2,'Cadastrar usuário com perfil Auditor.',NULL,NULL,1588255937,1588255937),('usuario-cadastrar-cge',2,'Cadastrar usuário com perfil CGE.',NULL,NULL,1588255922,1588255922),('usuario-cadastrar-outros',2,'Cadastrar usuário com a opção de selecionar o perfil [Grupo de de trabalho, Unidade Executora, Comitê de Monitoramento, Alta Administração]',NULL,NULL,1588181534,1588258381),('usuario-editar',2,'Editar usuário.',NULL,NULL,1588181360,1588181360),('usuario-editar-orgao',2,'Editar usuário vinculado ao órgão do usuário logado.','UserAuditorRule',NULL,1588256239,1588256643),('usuario-editar-seu-perfil',2,'Editar o seu próprio perfil.','UserRule',NULL,1588271106,1588271166),('usuario-listar',2,'Listar usuários.',NULL,NULL,1588181344,1588181347),('usuario-visualizar',2,'Visualizar usuário.',NULL,NULL,1588181517,1588181517),('usuario-visualizar-orgao',2,'Visualizar usuário vinculado ao órgão do usuário logado.','UserAuditorRule',NULL,1588256279,1588256654),('usuario-visualizar-seu-perfil',2,'Visualizar o seu próprio perfil.','UserRule',NULL,1588271128,1588271133),('validacao-cadastrar',2,'Cadastrar validação.','OrgaoWithStatusRule',NULL,1590412189,1594224165),('validacao-editar',2,'Editar validação.','OrgaoWithStatusRule',NULL,1590412200,1594224160);
/*!40000 ALTER TABLE `auth_item` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `auth_item_child`
--

LOCK TABLES `auth_item_child` WRITE;
/*!40000 ALTER TABLE `auth_item_child` DISABLE KEYS */;
INSERT INTO `auth_item_child` VALUES ('Administrador','/rbac/*'),('TI','acao-apagar'),('TI','acao-editar'),('TI','diagnostico-cadastrar'),('Administrador','diagnostico-cadastrar-ciencia'),('Alta Administração','diagnostico-cadastrar-ciencia'),('TI','diagnostico-cadastrar-ciencia'),('TI','diagnostico-cadastrar-informacoes-estrategicas'),('TI','diagnostico-cadastrar-instrumentos'),('TI','diagnostico-cadastrar-resultados'),('TI','diagnostico-editar'),('TI','eixo-apagar'),('TI','eixo-editar'),('Administrador','fator-limitante-apagar'),('Administrador','fator-limitante-cadastrar'),('Administrador','fator-limitante-editar'),('Administrador','fator-limitante-listar'),('Administrador','informacao-estado-apagar'),('TI','informacao-estado-apagar'),('Administrador','informacao-estado-cadastrar'),('TI','informacao-estado-cadastrar'),('Administrador','informacao-estado-editar'),('TI','informacao-estado-editar'),('Administrador','informacao-estado-listar'),('TI','informacao-estado-listar'),('Administrador','informacao-estado-visualizar'),('TI','informacao-estado-visualizar'),('Administrador','instrumento-apagar'),('TI','instrumento-apagar'),('Administrador','instrumento-cadastrar'),('TI','instrumento-cadastrar'),('Administrador','instrumento-editar'),('TI','instrumento-editar'),('Administrador','instrumento-listar'),('TI','instrumento-listar'),('Administrador','instrumento-visualizar'),('TI','instrumento-visualizar'),('Administrador','menu-administracao'),('Auditor','menu-administracao'),('TI','menu-administracao'),('Administrador','menu-usuarios'),('Auditor','menu-usuarios'),('TI','menu-usuarios'),('Auditor','modulo-auditor'),('Auditor','modulo-avaliacao'),('Administrador','modulo-elaboracao'),('Alta Administração','modulo-elaboracao'),('Auditor','modulo-elaboracao'),('Executor','modulo-elaboracao'),('Grupo de Trabalho','modulo-elaboracao'),('Monitoramento','modulo-elaboracao'),('TI','modulo-elaboracao'),('Executor','modulo-execucao'),('Monitoramento','modulo-monitoramento'),('Observador','modulo-monitoramento'),('Observador','observar-monitoramento'),('Administrador','orgao-apagar'),('TI','orgao-apagar'),('Administrador','orgao-cadastrar'),('TI','orgao-cadastrar'),('Administrador','orgao-editar'),('TI','orgao-editar'),('Administrador','orgao-listar'),('TI','orgao-listar'),('Administrador','orgao-visualizar'),('TI','orgao-visualizar'),('Administrador','plano-acao-elaborar'),('Alta Administração','plano-acao-elaborar'),('Auditor','plano-acao-elaborar'),('Executor','plano-acao-elaborar'),('Grupo de Trabalho','plano-acao-elaborar'),('Monitoramento','plano-acao-elaborar'),('Administrador','plano-integridade-elaborar'),('Alta Administração','plano-integridade-elaborar'),('Auditor','plano-integridade-elaborar'),('Executor','plano-integridade-elaborar'),('Grupo de Trabalho','plano-integridade-elaborar'),('Monitoramento','plano-integridade-elaborar'),('Administrador','plano-integridade-reabertura'),('Auditor','preencher-auditor'),('Auditor','preencher-avaliacao'),('Administrador','preencher-elaboracao'),('Alta Administração','preencher-elaboracao'),('Auditor','preencher-elaboracao'),('Grupo de Trabalho','preencher-elaboracao'),('Executor','preencher-execucao'),('Monitoramento','preencher-monitoramento'),('observar-monitoramento','preencher-monitoramento'),('TI','redacao-cadastrar'),('Administrador','stakeholder-apagar'),('TI','stakeholder-apagar'),('Administrador','stakeholder-cadastrar'),('TI','stakeholder-cadastrar'),('Administrador','stakeholder-editar'),('TI','stakeholder-editar'),('Administrador','stakeholder-listar'),('TI','stakeholder-listar'),('TI','subeixo-apagar'),('TI','subeixo-editar'),('Auditor','unidade-administrativa-apagar-orgao'),('Auditor','unidade-administrativa-cadastrar'),('Auditor','unidade-administrativa-editar-orgao'),('Administrador','unidade-administrativa-listar'),('Auditor','unidade-administrativa-listar'),('TI','unidade-administrativa-listar'),('TI','usuario-apagar'),('usuario-apagar-orgao','usuario-apagar'),('Administrador','usuario-cadastrar-auditor'),('TI','usuario-cadastrar-auditor'),('Administrador','usuario-cadastrar-cge'),('TI','usuario-cadastrar-cge'),('Administrador','usuario-cadastrar-outros'),('Auditor','usuario-cadastrar-outros'),('Administrador','usuario-editar'),('TI','usuario-editar'),('usuario-editar-orgao','usuario-editar'),('usuario-editar-seu-perfil','usuario-editar'),('Auditor','usuario-editar-orgao'),('Alta Administração','usuario-editar-seu-perfil'),('Auditor','usuario-editar-seu-perfil'),('Executor','usuario-editar-seu-perfil'),('Grupo de Trabalho','usuario-editar-seu-perfil'),('Monitoramento','usuario-editar-seu-perfil'),('Observador','usuario-editar-seu-perfil'),('Administrador','usuario-listar'),('Auditor','usuario-listar'),('TI','usuario-listar'),('Administrador','usuario-visualizar'),('TI','usuario-visualizar'),('usuario-visualizar-orgao','usuario-visualizar'),('usuario-visualizar-seu-perfil','usuario-visualizar'),('Auditor','usuario-visualizar-orgao'),('Alta Administração','usuario-visualizar-seu-perfil'),('Auditor','usuario-visualizar-seu-perfil'),('Executor','usuario-visualizar-seu-perfil'),('Grupo de Trabalho','usuario-visualizar-seu-perfil'),('Monitoramento','usuario-visualizar-seu-perfil'),('Observador','usuario-visualizar-seu-perfil'),('TI','validacao-cadastrar'),('TI','validacao-editar');
/*!40000 ALTER TABLE `auth_item_child` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `auth_rule`
--

LOCK TABLES `auth_rule` WRITE;
/*!40000 ALTER TABLE `auth_rule` DISABLE KEYS */;
INSERT INTO `auth_rule` VALUES ('OrgaoRule',_binary 'O:18:\"app\\rbac\\OrgaoRule\":3:{s:4:\"name\";s:9:\"OrgaoRule\";s:9:\"createdAt\";i:1588168528;s:9:\"updatedAt\";i:1588168528;}',1588168528,1588168528),('OrgaoWithStatusRule',_binary 'O:28:\"app\\rbac\\OrgaoWithStatusRule\":3:{s:4:\"name\";s:19:\"OrgaoWithStatusRule\";s:9:\"createdAt\";i:1594223980;s:9:\"updatedAt\";i:1594223980;}',1594223980,1594223980),('UserAuditorRule',_binary 'O:24:\"app\\rbac\\UserAuditorRule\":3:{s:4:\"name\";s:15:\"UserAuditorRule\";s:9:\"createdAt\";i:1588256612;s:9:\"updatedAt\";i:1588256612;}',1588256612,1588256612),('UserRule',_binary 'O:17:\"app\\rbac\\UserRule\":3:{s:4:\"name\";s:8:\"UserRule\";s:9:\"createdAt\";i:1588271080;s:9:\"updatedAt\";i:1588271080;}',1588271080,1588271080);
/*!40000 ALTER TABLE `auth_rule` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=123 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `diagnostico`
--

LOCK TABLES `diagnostico` WRITE;
/*!40000 ALTER TABLE `diagnostico` DISABLE KEYS */;
/*!40000 ALTER TABLE `diagnostico` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `diagnostico_ciencia`
--

LOCK TABLES `diagnostico_ciencia` WRITE;
/*!40000 ALTER TABLE `diagnostico_ciencia` DISABLE KEYS */;
/*!40000 ALTER TABLE `diagnostico_ciencia` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `diagnostico_info_estrategica`
--

LOCK TABLES `diagnostico_info_estrategica` WRITE;
/*!40000 ALTER TABLE `diagnostico_info_estrategica` DISABLE KEYS */;
/*!40000 ALTER TABLE `diagnostico_info_estrategica` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=432 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `diagnostico_instrumento`
--

LOCK TABLES `diagnostico_instrumento` WRITE;
/*!40000 ALTER TABLE `diagnostico_instrumento` DISABLE KEYS */;
/*!40000 ALTER TABLE `diagnostico_instrumento` ENABLE KEYS */;
UNLOCK TABLES;

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
  `objetivos_trabalhados` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `objetivos_estrategicos` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx-diagnostico_resultado-diagnostico_id` (`diagnostico_id`),
  CONSTRAINT `fk-diagnostico_resultado-diagnostico_id` FOREIGN KEY (`diagnostico_id`) REFERENCES `diagnostico` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `diagnostico_resultado`
--

LOCK TABLES `diagnostico_resultado` WRITE;
/*!40000 ALTER TABLE `diagnostico_resultado` DISABLE KEYS */;
/*!40000 ALTER TABLE `diagnostico_resultado` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=354 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eixo`
--

LOCK TABLES `eixo` WRITE;
/*!40000 ALTER TABLE `eixo` DISABLE KEYS */;
/*!40000 ALTER TABLE `eixo` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `fator_limitante`
--

LOCK TABLES `fator_limitante` WRITE;
/*!40000 ALTER TABLE `fator_limitante` DISABLE KEYS */;
INSERT INTO `fator_limitante` VALUES (11,'Fator Limitante Teste',NULL,'2020-11-06 13:43:06');
/*!40000 ALTER TABLE `fator_limitante` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=139 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grupo`
--

LOCK TABLES `grupo` WRITE;
/*!40000 ALTER TABLE `grupo` DISABLE KEYS */;
/*!40000 ALTER TABLE `grupo` ENABLE KEYS */;
UNLOCK TABLES;

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
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx-grupo_instituido-grupo_id` (`grupo_id`),
  CONSTRAINT `fk-grupo_instituido-grupo_id` FOREIGN KEY (`grupo_id`) REFERENCES `grupo` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=193 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grupo_instituido`
--

LOCK TABLES `grupo_instituido` WRITE;
/*!40000 ALTER TABLE `grupo_instituido` DISABLE KEYS */;
/*!40000 ALTER TABLE `grupo_instituido` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=924 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grupo_servidor`
--

LOCK TABLES `grupo_servidor` WRITE;
/*!40000 ALTER TABLE `grupo_servidor` DISABLE KEYS */;
/*!40000 ALTER TABLE `grupo_servidor` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `historico`
--

LOCK TABLES `historico` WRITE;
/*!40000 ALTER TABLE `historico` DISABLE KEYS */;
/*!40000 ALTER TABLE `historico` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `informacao_estado`
--

LOCK TABLES `informacao_estado` WRITE;
/*!40000 ALTER TABLE `informacao_estado` DISABLE KEYS */;
INSERT INTO `informacao_estado` VALUES (11,2020,103500000000.00,355975);
/*!40000 ALTER TABLE `informacao_estado` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `instrumento`
--

LOCK TABLES `instrumento` WRITE;
/*!40000 ALTER TABLE `instrumento` DISABLE KEYS */;
INSERT INTO `instrumento` VALUES (159,'Instrumento Teste',NULL,'2020-11-05 20:26:21');
/*!40000 ALTER TABLE `instrumento` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `migration`
--

LOCK TABLES `migration` WRITE;
/*!40000 ALTER TABLE `migration` DISABLE KEYS */;
INSERT INTO `migration` VALUES ('m000000_000000_base',1604689281),('m140506_102106_rbac_init',1612120620),('m170907_052038_rbac_add_index_on_auth_assignment_user_id',1612120620),('m180523_151638_rbac_updates_indexes_without_prefix',1612120620),('m200409_110543_rbac_update_mssql_trigger',1612120620),('m210519_140732_create_acao_monitoramento_table',1628717689),('m210519_141714_create_acao_monitoramento_recomendacao_table',1628717691),('m210527_142911_create_reuniao_table',1628717695),('m210527_165251_alter_servidor_table',1628717697),('m210527_170903_create_reuniao_servidor_table',1628717700),('m210622_170837_add_usuario_perfil_column_to_historico_table',1628717705),('m210623_200317_add_usuario_id_to_diagnostico_ciencia_table',1628717707),('m210623_213122_add_usuario_resposta_id_column_to_acao_monitoramento_recomendacao_table',1628717708),('m210903_162442_create_acao_execucao_arquivo_table',1643729294),('m210903_183219_insert_rows_to_acao_execucao_arquivo_table',1643729294),('m210908_170536_drop_evidencia_arquivo_column_from_acao_execucao_table',1643729294),('m210909_171140_create_plano_integridade_recomendacao_table',1643729294),('m211026_122152_create_acao_avaliacao_recomendacao_table',1643729294),('m211130_125631_create_promocao_integridade_table',1643729295),('m211202_130153_create_plano_integridade_novo_table',1643729295),('m211202_132807_add_versao_column_plano_integridade_referencia_id_column_to_plano_integridade_table',1643729295),('m211207_122352_add_acao_referencia_id_column_to_acao_table',1643729295),('m211210_161444_add_orgao_id_column_to_promover_integridade_table',1643729295),('m220106_170446_alter_acao_desenvolvida_column_from_promover_integridade_table',1643729295),('m220419_132304_create_plano_integridade_reabertura_table',1650389393),('yii\\queue\\db\\migrations\\M161119140200Queue',1628717683),('yii\\queue\\db\\migrations\\M170307170300Later',1628717684),('yii\\queue\\db\\migrations\\M170509001400Retry',1628717686),('yii\\queue\\db\\migrations\\M170601155600Priority',1628717687);
/*!40000 ALTER TABLE `migration` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `orgao`
--

LOCK TABLES `orgao` WRITE;
/*!40000 ALTER TABLE `orgao` DISABLE KEYS */;
INSERT INTO `orgao` VALUES (17,'Órgão Teste','OT',1,1,'2020-04-27 14:15:50');
/*!40000 ALTER TABLE `orgao` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `orgao_contabil`
--

LOCK TABLES `orgao_contabil` WRITE;
/*!40000 ALTER TABLE `orgao_contabil` DISABLE KEYS */;
INSERT INTO `orgao_contabil` VALUES (54,2020,1.00,1,17,'2020-11-06 20:12:59');
/*!40000 ALTER TABLE `orgao_contabil` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plano_integridade`
--

LOCK TABLES `plano_integridade` WRITE;
/*!40000 ALTER TABLE `plano_integridade` DISABLE KEYS */;
/*!40000 ALTER TABLE `plano_integridade` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `plano_integridade_novo`
--

LOCK TABLES `plano_integridade_novo` WRITE;
/*!40000 ALTER TABLE `plano_integridade_novo` DISABLE KEYS */;
/*!40000 ALTER TABLE `plano_integridade_novo` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `plano_integridade_reabertura`
--

LOCK TABLES `plano_integridade_reabertura` WRITE;
/*!40000 ALTER TABLE `plano_integridade_reabertura` DISABLE KEYS */;
/*!40000 ALTER TABLE `plano_integridade_reabertura` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `plano_integridade_recomendacao`
--

LOCK TABLES `plano_integridade_recomendacao` WRITE;
/*!40000 ALTER TABLE `plano_integridade_recomendacao` DISABLE KEYS */;
/*!40000 ALTER TABLE `plano_integridade_recomendacao` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `promover_integridade`
--

LOCK TABLES `promover_integridade` WRITE;
/*!40000 ALTER TABLE `promover_integridade` DISABLE KEYS */;
/*!40000 ALTER TABLE `promover_integridade` ENABLE KEYS */;
UNLOCK TABLES;

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
  `plano_comunicacao` tinyint NOT NULL,
  `plano_comunicacao_arquivo` int DEFAULT NULL,
  `plano_treinamento` tinyint NOT NULL,
  `plano_treinamento_arquivo` int DEFAULT NULL,
  `data_publicacao` date NOT NULL,
  `nome_numero` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `link` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `plano_acao_arquivo` int NOT NULL,
  `plano_integridade_arquivo` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx-publicacao-plano_integridade_id` (`plano_integridade_id`),
  KEY `idx-publicacao-plano_acao_arquivo` (`plano_acao_arquivo`),
  KEY `idx-publicacao-plano_integridade_arquivo` (`plano_integridade_arquivo`),
  KEY `idx-publicacao-plano_comunicacao_arquivo` (`plano_comunicacao_arquivo`),
  KEY `idx-publicacao-plano_treinamento_arquivo` (`plano_treinamento_arquivo`),
  CONSTRAINT `fk-publicacao-plano_acao_arquivo` FOREIGN KEY (`plano_acao_arquivo`) REFERENCES `arquivo` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-publicacao-plano_comunicacao_arquivo` FOREIGN KEY (`plano_comunicacao_arquivo`) REFERENCES `arquivo` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-publicacao-plano_integridade_arquivo` FOREIGN KEY (`plano_integridade_arquivo`) REFERENCES `arquivo` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-publicacao-plano_integridade_id` FOREIGN KEY (`plano_integridade_id`) REFERENCES `plano_integridade` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-publicacao-plano_treinamento_arquivo` FOREIGN KEY (`plano_treinamento_arquivo`) REFERENCES `arquivo` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publicacao`
--

LOCK TABLES `publicacao` WRITE;
/*!40000 ALTER TABLE `publicacao` DISABLE KEYS */;
/*!40000 ALTER TABLE `publicacao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `queue`
--

DROP TABLE IF EXISTS `queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `queue` (
  `id` int NOT NULL AUTO_INCREMENT,
  `channel` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `job` blob NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=5948 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `queue`
--

LOCK TABLES `queue` WRITE;
/*!40000 ALTER TABLE `queue` DISABLE KEYS */;
/*!40000 ALTER TABLE `queue` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `reuniao`
--

LOCK TABLES `reuniao` WRITE;
/*!40000 ALTER TABLE `reuniao` DISABLE KEYS */;
/*!40000 ALTER TABLE `reuniao` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `reuniao_servidor`
--

LOCK TABLES `reuniao_servidor` WRITE;
/*!40000 ALTER TABLE `reuniao_servidor` DISABLE KEYS */;
/*!40000 ALTER TABLE `reuniao_servidor` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=1857 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servidor`
--

LOCK TABLES `servidor` WRITE;
/*!40000 ALTER TABLE `servidor` DISABLE KEYS */;
/*!40000 ALTER TABLE `servidor` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `stakeholder`
--

LOCK TABLES `stakeholder` WRITE;
/*!40000 ALTER TABLE `stakeholder` DISABLE KEYS */;
INSERT INTO `stakeholder` VALUES (60,'Stakeholder Teste',NULL,'2020-11-06 13:49:00');
/*!40000 ALTER TABLE `stakeholder` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=418 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subeixo`
--

LOCK TABLES `subeixo` WRITE;
/*!40000 ALTER TABLE `subeixo` DISABLE KEYS */;
/*!40000 ALTER TABLE `subeixo` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `tipo`
--

LOCK TABLES `tipo` WRITE;
/*!40000 ALTER TABLE `tipo` DISABLE KEYS */;
INSERT INTO `tipo` VALUES (1,'Processo',NULL,'2020-08-28 12:18:03'),(2,'Normativo',NULL,'2020-08-28 12:18:03'),(3,'Sensibilização',NULL,'2020-08-28 12:18:03'),(4,'Formação',NULL,'2020-08-28 12:18:03');
/*!40000 ALTER TABLE `tipo` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `unidade_administrativa`
--

LOCK TABLES `unidade_administrativa` WRITE;
/*!40000 ALTER TABLE `unidade_administrativa` DISABLE KEYS */;
INSERT INTO `unidade_administrativa` VALUES (1544,'Unidade Administrativa Teste',17,'2020-11-09 16:51:57');
/*!40000 ALTER TABLE `unidade_administrativa` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=907 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (83,'admin','xxxxxx','admin','$2y$10$xP73Xv4Wc63RqILfuoKNkuLvw.5729zFvPtsY63rJiU3Y3wy0a.lG','Administrador','admin@email.com','(11) 1111-1111',17,NULL,1,NULL,'82M64C2e1IjZsD_nCIObWDLiSdiWspCb_1647974070',NULL,'2020-04-27 14:15:50');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `validacao`
--

LOCK TABLES `validacao` WRITE;
/*!40000 ALTER TABLE `validacao` DISABLE KEYS */;
/*!40000 ALTER TABLE `validacao` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=233 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `validacao_stakeholder`
--

LOCK TABLES `validacao_stakeholder` WRITE;
/*!40000 ALTER TABLE `validacao_stakeholder` DISABLE KEYS */;
/*!40000 ALTER TABLE `validacao_stakeholder` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-01-18 11:18:22

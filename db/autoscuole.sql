SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for argomenti
-- ----------------------------
DROP TABLE IF EXISTS `argomenti`;
CREATE TABLE `argomenti` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descrizione` varchar(255) DEFAULT NULL,
  `categorie` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of argomenti
-- ----------------------------
INSERT INTO `argomenti` VALUES ('1', 'Elementi di diritto pubblico, amministrativo e dell\'Unione europea (Stato, costituzione, fonti del diritto, organi legislativi, Governo, enti locali, organi comunitari, fonti del diritto dell\'Unione europea)', 'a:1:{i:0;s:1:\"2\";}');
INSERT INTO `argomenti` VALUES ('2', 'Elementi di diritto penale (reato, dolo, colpa, reati contro la pubblica amministrazione)', 'a:1:{i:0;s:1:\"2\";}');
INSERT INTO `argomenti` VALUES ('3', 'Procedure legali in caso di incidente e assicurazione; illecito amministrativo', 'a:1:{i:0;s:1:\"2\";}');
INSERT INTO `argomenti` VALUES ('4', 'Definizioni, costruzione e manutenzione delle strade, organizzazione della circolazione stradale e segnaletica stradale. Analisi degli incidenti stradali. Utenti vulnerabili', 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"4\";}');
INSERT INTO `argomenti` VALUES ('5', 'Definizione dei veicoli, elementi strutturali dei veicoli e loro funzionamento', 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"3\";}');
INSERT INTO `argomenti` VALUES ('6', 'Disposizioni amministrative in materia di circolazione dei veicoli (destinazione ed uso dei veicoli, documenti di circolazione e di immatricolazione)', 'a:1:{i:0;s:1:\"2\";}');
INSERT INTO `argomenti` VALUES ('7', 'Autotrasporto di persone e di cose - Elementi sull\'uso del cronotachigrafo e sul rallentatore di velocitÃ ', 'a:1:{i:0;s:1:\"2\";}');
INSERT INTO `argomenti` VALUES ('8', 'Trasporto delle merci pericolose', 'a:1:{i:0;s:1:\"2\";}');
INSERT INTO `argomenti` VALUES ('9', 'Conducenti e titoli abilitativi alla guida', 'a:1:{i:0;s:1:\"2\";}');
INSERT INTO `argomenti` VALUES ('10', 'Norme di comportamento sulle strade', 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"4\";}');
INSERT INTO `argomenti` VALUES ('11', 'Illeciti amministrativi previsti dal codice della strada e relative sanzioni', 'a:1:{i:0;s:1:\"2\";}');
INSERT INTO `argomenti` VALUES ('12', 'Elementi di pedagogia e di tecnica delle comunicazioni', 'a:1:{i:0;s:1:\"2\";}');
INSERT INTO `argomenti` VALUES ('13', 'Metodiche di insegnamento per allievi con disturbi specifici dell’apprendimento (tale materia non costituisce oggetto del programma di esame)', null);
INSERT INTO `argomenti` VALUES ('14', 'Stato psicofisico dei conducenti, tempo di reazione, alcool, ecc.', 'a:1:{i:0;s:1:\"2\";}');
INSERT INTO `argomenti` VALUES ('15', 'Elementi di primo soccorso', 'a:1:{i:0;s:1:\"2\";}');
INSERT INTO `argomenti` VALUES ('16', 'Elementi di fisica', 'a:1:{i:0;s:1:\"3\";}');
INSERT INTO `argomenti` VALUES ('17', 'Autoscuole: normativa, ruolo, inquadramento insegnante', 'a:1:{i:0;s:1:\"2\";}');

-- ----------------------------
-- Table structure for domande
-- ----------------------------
DROP TABLE IF EXISTS `domande`;
CREATE TABLE `domande` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_argomento` int(11) NOT NULL,
  `domanda` longtext DEFAULT NULL,
  `attivo` int(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Table structure for profili
-- ----------------------------
DROP TABLE IF EXISTS `profili`;
CREATE TABLE `profili` (
  `id` int(11) NOT NULL,
  `descrizione` varchar(255) NOT NULL,
  `note` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of profili
-- ----------------------------
INSERT INTO `profili` VALUES ('1', 'Sola lettura', null);
INSERT INTO `profili` VALUES ('2', 'Operatore', null);
INSERT INTO `profili` VALUES ('3', 'Amministratore', null);

-- ----------------------------
-- Table structure for questionari
-- ----------------------------
DROP TABLE IF EXISTS `questionari`;
CREATE TABLE `questionari` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_esame` date NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Table structure for schede
-- ----------------------------
DROP TABLE IF EXISTS `schede`;
CREATE TABLE `schede` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_questionario` int(11) NOT NULL,
  `codice` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Table structure for schede_domande
-- ----------------------------
DROP TABLE IF EXISTS `schede_domande`;
CREATE TABLE `schede_domande` (
  `id_scheda` int(11) NOT NULL,
  `id_domanda` int(11) NOT NULL,
  `ordine` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_scheda`,`id_domanda`)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Table structure for struttura
-- ----------------------------
DROP TABLE IF EXISTS `struttura`;
CREATE TABLE `struttura` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codice` int(11) NOT NULL,
  `descrizione` varchar(255) NOT NULL,
  `dal` date DEFAULT NULL,
  `al` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Table structure for tipo_categoria
-- ----------------------------
DROP TABLE IF EXISTS `tipo_categoria`;
CREATE TABLE `tipo_categoria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descrizione` varchar(255) NOT NULL,
  `note` text DEFAULT NULL,
  `attivo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of tipo_categoria
-- ----------------------------
INSERT INTO `tipo_categoria` VALUES ('1', 'Simulazione lezione di teoria', null, '1');
INSERT INTO `tipo_categoria` VALUES ('2', 'I Domanda orale', null, '1');
INSERT INTO `tipo_categoria` VALUES ('3', 'II Domanda orale', null, '1');
INSERT INTO `tipo_categoria` VALUES ('4', 'III Domanda orale', null, '1');

-- ----------------------------
-- Table structure for utenti
-- ----------------------------
DROP TABLE IF EXISTS `utenti`;
CREATE TABLE `utenti` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(32) DEFAULT NULL,
  `cognome` varchar(255) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `area` varchar(3) DEFAULT NULL,
  `settore` varchar(5) DEFAULT NULL,
  `uo` varchar(7) NOT NULL,
  `codice` varchar(255) NOT NULL,
  `incarico` varchar(255) NOT NULL,
  `profilo` tinyint(4) NOT NULL DEFAULT 0,
  `attivo` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `utente` (`id`) USING BTREE
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- View structure for struttura_attuale
-- ----------------------------
DROP VIEW IF EXISTS `struttura_attuale`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `struttura_attuale` AS select `s`.`codice` AS `codice`,`s`.`descrizione` AS `descrizione` from `struttura` `s` where `s`.`id` = (select max(`s1`.`id`) from `struttura` `s1` where `s1`.`codice` = `s`.`codice`) ;

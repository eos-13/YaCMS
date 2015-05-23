-- MySQL dump 10.13  Distrib 5.6.19, for osx10.7 (x86_64)
--
-- Host: localhost    Database: install
-- ------------------------------------------------------
-- Server version    5.6.19

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
-- Table structure for table `api`
--

DROP TABLE IF EXISTS `api`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `api` (
  `token` varchar(150) DEFAULT NULL,
  `login` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `api`
--

LOCK TABLES `api` WRITE;
/*!40000 ALTER TABLE `api` DISABLE KEYS */;
/*!40000 ALTER TABLE `api` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) DEFAULT NULL,
  `cached_data` longtext,
  `page_refid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `page_refid` (`page_refid`),
  CONSTRAINT `cache_ibfk_1` FOREIGN KEY (`page_refid`) REFERENCES `page` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commentaire`
--

DROP TABLE IF EXISTS `commentaire`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commentaire` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `page_refid` int(11) DEFAULT NULL,
  `content` text,
  `author` varchar(150) DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `title` varchar(150) DEFAULT NULL,
  `date_creation` timestamp NULL DEFAULT NULL,
  `valid` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `author_id` (`author_id`),
  CONSTRAINT `commentaire_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commentaire`
--

LOCK TABLES `commentaire` WRITE;
/*!40000 ALTER TABLE `commentaire` DISABLE KEYS */;
/*!40000 ALTER TABLE `commentaire` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conf`
--

DROP TABLE IF EXISTS `conf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conf` (
  `key` varchar(50) DEFAULT NULL,
  `value` longtext,
  `type` varchar(20) DEFAULT NULL,
  `mandatory` tinyint(4) DEFAULT '0',
  `description` text,
  UNIQUE KEY `key_UNIQUE` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conf`
--

LOCK TABLES `conf` WRITE;
/*!40000 ALTER TABLE `conf` DISABLE KEYS */;
INSERT INTO `conf` VALUES ('meta','confmeta','text',1,'Liste des metas du site (Commun à toutes les pages)'),('liste_limit','3','text',1,'Limite par défaut pour les LR'),('site_js','[\"jquery.js\",\"jquery-ui.js\"]','textarea',1,'Javascript devant être chargés pour toutes les pages\n<br/><u>Attention </u> doit être un array json valid\n<br/><u>ex:[\'js1.js\',\'js2.js\']'),('site_css','[]','textarea',1,'Fichiers CSS devant être chargés pour toutes les pages\n<br/><u>Attention </u> doit être un array json valid\n<br/><u>ex:[\'css1.css\',\'css2.css\']'),('sitemap_add_restricted_page','off','bool',1,'Ajoute les pages dont la publication est restreinte dans le sitemap.xml'),('sphinx_host','localhost','text',1,'Sphinx Host'),('sphinx_port','9306','text',1,'Sphinx Port'),('sphinx_index','demo_idx','text',1,'Sphinx index'),('sphinx_index_type','rt','text',1,'Sphinx index type\n<br/>2 valeurs possibles: \n<br/><u>rt</u> RealTime index\n<br/><u>bd</u> MySQL Full Text search'),('debug_on_error','on','bool',1,'Affiche le debug sur la page d\'erreur'),('timezone','Europe/Paris','text',1,'TimeZone pour le site \n<br/><u>ex:</u>America/New_York ou Europe/Paris'),('cookie_path','/','text',1,'Chemin du cookie d\'authentification\n<u>Attn</u> / => Tout le domaine'),('cookie_runtime','1209600','text',1,'Durée de validité d\'un cookie (en seconde)'),('dot_path','/usr/local/bin/','text',1,'Chemin vers le binaire dot'),('mysqldump_path','/usr/bin/','text',1,'Chemin vers le binaire mysqldump (le / à la fin est nécessaire)'),('moderation_type','on','bool',1,'Modération des commentaires\n<br/>\n<u>on</u>: A prioris\n<u>off</u>: A posterioris'),('anonymous_can_post_comment','off','bool',1,'Les utilisateurs anonymes peuvent-ils poster des commentaires?'),('reCaptcha_key','6Lcp4QITAAAAAEAVBLRfvBiMskpUCGEPX6YOT7_A','textarea',1,'Clef de l\'API reCaptcha pour activer les captcha pour les commentaires'),('reCaptcha_key_private','6Lcp4QITAAAAABYrcuePSZPejaJet-p00b-VxWjt','textarea',1,'Clef de l\'API reCaptcha pour la validation des saisies'),('use_tinymce_for_comment','on','bool',1,'Utilisation de tinyMCE pour les commentaires des pages'),('comment_max_length','1000','text',1,'Longueur maximum d\'un commentaire'),('akismet_api_key','aa5da984e6ec','text',1,'Clef pour l\'API akismet'),('version','0.0.1','text',1,'Version de l\'application'),('user_agent','iZend2/0.0.1','text',1,'UserAgent de l\'application'),('use_growl_for_message','on','bool',1,'Utilisation de growl pour l\'affichage des messages globaux'),('userdesc_max_length','10000','text',1,'Longueur maximum d\'une description utilisateur'),('logo','/img/logo/sitelogo.png','text',1,'Le logo du site'),('default_avatar','/img/avatars/avatar-default.png','text',1,'Avatar par defaut'),('max_avatar_size','2097152','text',1,'Poid maximum d\'un avatar (en octet)\n<u>ex</u>: 2097152 (2Mo)'),('user_delete_delete_comment','off','bool',1,'Est-ce que l\'on supprime les commentaires d\'un utilisateur si on efface son compte?'),('syslog_prefix','izend2','text',1,'Préfix pour les logs syslog'),('user_id_from_email','1','text',1,'Id SQL de l\'utilisateur qui envoie des mails pour le site'),('site_name','My CMS','text',1,'Nom du site'),('use_css_cache','off','bool',1,'Utilisation du cache CSS'),('use_js_cache','off','bool',1,'Utilisation du cache JS'),('mantis_url','http://127.0.0.1/mantis','text',1,'URL de mantis pour les internal_message de type technique'),('use_mantis','off','bool',1,'Utilisation de mantis pour les internal message technique'),('mantis_login','mantis','text',1,'Login mantis'),('mantis_pass','mantis','text',1,'Pass mantis'),('mantis_project','1','text',1,'ID du projet mantis'),('mantis_category','General','text',1,'Categorie pour un nouveau mantis'),('user_id_from_internal_msg','0','text',1,'ID SQL de l\'utilisateur qui repond aux messages internes\nSi 0 => utilisateur courant'),('flicker_account','131016933@N03','text',1,'Id du compte flickr'),('instagram_account','jmlf_06','text',1,'Id du compte instagram'),('prettyHtml','off','bool',1,'Reformatage du HTML de sortie'),('default_lang','fr_FR','text',1,'Langue par defaut'),('available_lang','fr_FR','text',1,'Liste des langues disponibles'),('imap_server','imap.zoho.com','text',1,'Imap serveur to get message from'),('imap_user','test_iz2_acc@zoho.com','text',1,'Imap login'),('imap_pass','cca_2zi_tset','text',1,'Imap pass'),('imap_encryption','ssl','text',1,'Imap encryption type\n<u>ex:</u>\n&nbsp;&nbsp;&nbsp; : pas d\'encryption\nssl : encryption ssl\ntls : encryption tls'),('use_imapservice','off','bool',1,'Utilisation de l\'IMAP'),('external_message_model','11','text',1,'Id du mail model pour la réponse aux messages externes'),('user_id_from_external_msg','0','text',1,'ID SQL de l\'utilisateur qui repond aux messages externes\nSi 0 => utilisateur courant'),('logo_xlsx','/img/logo/sitelogo_xlsx.png','text',1,'Le logo pour les exports excel'),('use_sphinx','off','bool',1,'Utilisation du moteur de recherche'),('favicon_rel','shortcut icon','text',1,'Paramètre rel pour les favicons <br/><u>ex: </u> shortcut icon ... icon'),('favicon','favicon.ico','text',1,'Nom de la favicon'),('favicon_type','image/ico','text',1,'Type mime pour la favicon <br/><u>ex: </u> image/x-icon ... image/png'),('code_google_analytics','','text',1,'Code google analytics'),('custom_section_css_class','','text',1,'Classes css supplémentaires pour l\'affichage des sections dans tinymce'),('custom_main_css_class','','text',1,'Classes css supplémentaires pour l\'affichage du contenu principal des pages dans tinymce'),('tinymce_custom_style','','text',1,'Style suplémantaire pour tinyMCE\n<u>Doit être un json valide ! </u>\n<u>ex:</u>\n<pre>\n{title: \'Custom Header\', items: [\n            {title: \'Header 1\', format: \'h1\'},\n            {title: \'Header 2\', format: \'h2\'},\n            {title: \'Header 3\', format: \'h3\'},\n            {title: \'Header 4\', format: \'h4\'},\n            {title: \'Header 5\', format: \'h5\'},\n            {title: \'Header 6\', format: \'h6\'}\n            ]\n        }\n</pre>'),('api_key','8b26a50c-d8b5-11e4-8b9b-a2189292','text',1,'Clef pour l\'API');
INSERT INTO `conf` (`key`, `value`, `type`, `mandatory`, `description`) VALUES ('use_submenu', 'on', 'bool', 1, 'Utilisation des sous-menu');
/*!40000 ALTER TABLE `conf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cron`
--

DROP TABLE IF EXISTS `cron`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cron` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) DEFAULT NULL,
  `description` longtext,
  `tms` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `object` varchar(30) DEFAULT NULL,
  `active` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cron`
--

LOCK TABLES `cron` WRITE;
/*!40000 ALTER TABLE `cron` DISABLE KEYS */;
INSERT INTO `cron` VALUES (1,'test','test','2015-03-16 22:18:58','test_cron',1),(2,'TestScript - Demo','test','2015-03-16 22:20:49','test_cron',0);
/*!40000 ALTER TABLE `cron` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cron_schedule`
--

DROP TABLE IF EXISTS `cron_schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cron_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lastRun` timestamp NULL DEFAULT NULL,
  `nextRun` timestamp NULL DEFAULT NULL,
  `has_run` tinyint(1) DEFAULT '0',
  `cron_refid` int(11) DEFAULT NULL,
  `last_result` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `cron_refid` (`cron_refid`),
  CONSTRAINT `cron_schedule_ibfk_1` FOREIGN KEY (`cron_refid`) REFERENCES `cron` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cron_schedule`
--

LOCK TABLES `cron_schedule` WRITE;
/*!40000 ALTER TABLE `cron_schedule` DISABLE KEYS */;
INSERT INTO `cron_schedule` VALUES (1,'2015-03-16 22:33:02','2015-03-16 23:00:00',3,2,1);
/*!40000 ALTER TABLE `cron_schedule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `data_file`
--

DROP TABLE IF EXISTS `data_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `data_file` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `file_path` varchar(1000) DEFAULT NULL,
  `data_name` varchar(45) DEFAULT NULL,
  `data_value` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `data_file`
--

LOCK TABLES `data_file` WRITE;
/*!40000 ALTER TABLE `data_file` DISABLE KEYS */;
/*!40000 ALTER TABLE `data_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `display_name` varchar(75) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (1,'SEND_MAIL_TMPTOKEN','Perte de mot de passe',1),(2,'SEND_MAIL_ACTIVATION','Activation compte',1);
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `external_msg`
--

DROP TABLE IF EXISTS `external_msg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `external_msg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_mail` varchar(150) DEFAULT NULL,
  `external_msg` longtext,
  `title` varchar(45) DEFAULT NULL,
  `date_create` timestamp NULL DEFAULT NULL,
  `parents_refid` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `parents_refid` (`parents_refid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `external_msg`
--

LOCK TABLES `external_msg` WRITE;
/*!40000 ALTER TABLE `external_msg` DISABLE KEYS */;
/*!40000 ALTER TABLE `external_msg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `forms`
--

DROP TABLE IF EXISTS `forms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` longtext,
  `page_refid` int(11) DEFAULT NULL,
  `title` varchar(145) DEFAULT NULL,
  `jsonData` longtext,
  `lang` varchar(10) DEFAULT NULL,
  `is_publish` int(11) DEFAULT '0',
  `type_connector` varchar(45) DEFAULT NULL,
  `connector_option` text,
  PRIMARY KEY (`id`),
  KEY `page_refid` (`page_refid`),
  CONSTRAINT `forms_ibfk_1` FOREIGN KEY (`page_refid`) REFERENCES `page` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forms`
--

LOCK TABLES `forms` WRITE;
/*!40000 ALTER TABLE `forms` DISABLE KEYS */;
/*!40000 ALTER TABLE `forms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `forms_result`
--

DROP TABLE IF EXISTS `forms_result`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forms_result` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `result` longtext,
  `user_refid` int(11) DEFAULT NULL,
  `tms` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `forms_refid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `forms_refid` (`forms_refid`),
  CONSTRAINT `forms_result_ibfk_1` FOREIGN KEY (`forms_refid`) REFERENCES `forms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forms_result`
--

LOCK TABLES `forms_result` WRITE;
/*!40000 ALTER TABLE `forms_result` DISABLE KEYS */;
/*!40000 ALTER TABLE `forms_result` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `group`
--

DROP TABLE IF EXISTS `group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `description` longtext,
  `avatar_path` varchar(200) DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1',
  `md5` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `g_md5_2` (`md5`),
  UNIQUE KEY `g_email_2` (`email`),
  KEY `g_email` (`email`),
  KEY `g_md5` (`md5`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group`
--

LOCK TABLES `group` WRITE;
/*!40000 ALTER TABLE `group` DISABLE KEYS */;
INSERT INTO `group` VALUES (1,'admin','admin@mysite.com','Le groupe des administrateurs du site',NULL,1,'88f70b37fbbda745419eae28834eb3e1');
/*!40000 ALTER TABLE `group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `group_publication`
--

DROP TABLE IF EXISTS `group_publication`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `group_publication` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `description` longtext,
  `avatar_path` varchar(200) DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1',
  `md5` varchar(150) DEFAULT NULL,
  `is_public` tinyint(4) DEFAULT '0',
  `is_admin` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `gp_md5_2` (`md5`),
  UNIQUE KEY `gp_email_2` (`email`),
  KEY `gp_email` (`email`),
  KEY `gp_md5` (`md5`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group_publication`
--

LOCK TABLES `group_publication` WRITE;
/*!40000 ALTER TABLE `group_publication` DISABLE KEYS */;
INSERT INTO `group_publication` VALUES (1,'public','','Pages publiques',NULL,1,'d46449bd424d422a8373f095d7b1bb34',1,0),(2,'interne','interne@test.com','Page interne',NULL,1,'e1a979735adde29676ca178005b4694a',0,0),(3,'admin','test@test.com','Groupe des administrateur de publications',NULL,1,'c46449bd424d422a8373f095d7b1ba98',0,0);
/*!40000 ALTER TABLE `group_publication` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `group_publication_page`
--

DROP TABLE IF EXISTS `group_publication_page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `group_publication_page` (
  `page_refid` int(11) NOT NULL DEFAULT '0',
  `group_publication_refid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`page_refid`,`group_publication_refid`),
  KEY `group_publication_refid` (`group_publication_refid`),
  KEY `page_refid` (`page_refid`),
  CONSTRAINT `group_publication_page_ibfk_1` FOREIGN KEY (`page_refid`) REFERENCES `page` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `group_publication_page_ibfk_2` FOREIGN KEY (`group_publication_refid`) REFERENCES `group_publication` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group_publication_page`
--

LOCK TABLES `group_publication_page` WRITE;
/*!40000 ALTER TABLE `group_publication_page` DISABLE KEYS */;
/*!40000 ALTER TABLE `group_publication_page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `group_publication_user`
--

DROP TABLE IF EXISTS `group_publication_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `group_publication_user` (
  `user_refid` int(11) NOT NULL DEFAULT '0',
  `group_publication_refid` int(11) NOT NULL DEFAULT '0',
  `can_approuve` int(11) DEFAULT '0',
  PRIMARY KEY (`user_refid`,`group_publication_refid`),
  KEY `group_publication_refid` (`group_publication_refid`),
  CONSTRAINT `group_publication_user_ibfk_1` FOREIGN KEY (`user_refid`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `group_publication_user_ibfk_2` FOREIGN KEY (`group_publication_refid`) REFERENCES `group_publication` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group_publication_user`
--

LOCK TABLES `group_publication_user` WRITE;
/*!40000 ALTER TABLE `group_publication_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `group_publication_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `group_rights`
--

DROP TABLE IF EXISTS `group_rights`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `group_rights` (
  `group_refid` int(11) NOT NULL,
  `rights_def_refid` int(11) NOT NULL,
  PRIMARY KEY (`group_refid`,`rights_def_refid`),
  KEY `rights_ref_refid` (`rights_def_refid`),
  CONSTRAINT `group_rights_ibfk_1` FOREIGN KEY (`group_refid`) REFERENCES `group` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `group_rights_ibfk_2` FOREIGN KEY (`rights_def_refid`) REFERENCES `rights_def` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group_rights`
--

LOCK TABLES `group_rights` WRITE;
/*!40000 ALTER TABLE `group_rights` DISABLE KEYS */;
/*!40000 ALTER TABLE `group_rights` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `group_user`
--

DROP TABLE IF EXISTS `group_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `group_user` (
  `user_refid` int(11) NOT NULL DEFAULT '0',
  `group_refid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_refid`,`group_refid`),
  KEY `group_refid` (`group_refid`),
  CONSTRAINT `group_user_ibfk_1` FOREIGN KEY (`user_refid`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `group_user_ibfk_2` FOREIGN KEY (`group_refid`) REFERENCES `group` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group_user`
--

LOCK TABLES `group_user` WRITE;
/*!40000 ALTER TABLE `group_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `group_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `historic`
--

DROP TABLE IF EXISTS `historic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `historic` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `page_refid` int(11) DEFAULT NULL,
  `content` longtext,
  `date_historic` timestamp NULL DEFAULT NULL,
  `author_refid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historic`
--

LOCK TABLES `historic` WRITE;
/*!40000 ALTER TABLE `historic` DISABLE KEYS */;
/*!40000 ALTER TABLE `historic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `html_save`
--

DROP TABLE IF EXISTS `html_save`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `html_save` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `html` longtext,
  `page_refid` int(11) DEFAULT NULL,
  `date_save` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `save_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `save_by` (`save_by`),
  KEY `page_refid` (`page_refid`),
  CONSTRAINT `html_save_ibfk_1` FOREIGN KEY (`save_by`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `html_save_ibfk_2` FOREIGN KEY (`page_refid`) REFERENCES `page` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `html_save`
--

LOCK TABLES `html_save` WRITE;
/*!40000 ALTER TABLE `html_save` DISABLE KEYS */;
/*!40000 ALTER TABLE `html_save` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `internal_msg`
--

DROP TABLE IF EXISTS `internal_msg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `internal_msg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_refid` int(11) DEFAULT NULL,
  `internal_msg_type_refid` int(11) DEFAULT NULL,
  `internal_msg` longtext,
  `title` varchar(45) DEFAULT NULL,
  `date_create` timestamp NULL DEFAULT NULL,
  `parents_refid` int(11) DEFAULT NULL,
  `internal_msg_status_refid` int(11) DEFAULT NULL,
  `user_deleted` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_refid` (`user_refid`),
  KEY `internal_msg_type_refid` (`internal_msg_type_refid`),
  KEY `user_refid_2` (`user_refid`),
  KEY `internal_msg_type_refid_2` (`internal_msg_type_refid`),
  KEY `parents_refid` (`parents_refid`),
  KEY `internal_msg_status_refid` (`internal_msg_status_refid`),
  CONSTRAINT `internal_msg_ibfk_1` FOREIGN KEY (`user_refid`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `internal_msg_ibfk_2` FOREIGN KEY (`parents_refid`) REFERENCES `internal_msg` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `internal_msg_ibfk_3` FOREIGN KEY (`internal_msg_status_refid`) REFERENCES `internal_msg_status` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `internal_msg_ibfk_4` FOREIGN KEY (`internal_msg_type_refid`) REFERENCES `internal_msg_type` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `internal_msg`
--

LOCK TABLES `internal_msg` WRITE;
/*!40000 ALTER TABLE `internal_msg` DISABLE KEYS */;
/*!40000 ALTER TABLE `internal_msg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `internal_msg_status`
--

DROP TABLE IF EXISTS `internal_msg_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `internal_msg_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `internal_msg_status`
--

LOCK TABLES `internal_msg_status` WRITE;
/*!40000 ALTER TABLE `internal_msg_status` DISABLE KEYS */;
INSERT INTO `internal_msg_status` VALUES (1,'Non lu'),(2,'Lu'),(3,'Cloturé');
/*!40000 ALTER TABLE `internal_msg_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `internal_msg_type`
--

DROP TABLE IF EXISTS `internal_msg_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `internal_msg_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `visible` tinyint(4) DEFAULT '1',
  `mantis` int(11) DEFAULT '0',
  `defaut` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `internal_msg_type`
--

LOCK TABLES `internal_msg_type` WRITE;
/*!40000 ALTER TABLE `internal_msg_type` DISABLE KEYS */;
INSERT INTO `internal_msg_type` VALUES (1,'Commercial',1,0,0),(2,'Technique',1,1,0),(3,'Autre',1,0,1),(4,'Refus validation',0,0,0),(5,'mails',1,0,0);
/*!40000 ALTER TABLE `internal_msg_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_modif_page`
--

DROP TABLE IF EXISTS `log_modif_page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_modif_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_refid` int(11) DEFAULT NULL,
  `user_refid` int(11) DEFAULT NULL,
  `date_modif` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `type_modif` varchar(150) DEFAULT NULL,
  `old_value` longtext,
  `new_value` longtext,
  PRIMARY KEY (`id`),
  KEY `page_refid` (`page_refid`),
  KEY `user_refid` (`user_refid`),
  CONSTRAINT `log_modif_page_ibfk_1` FOREIGN KEY (`page_refid`) REFERENCES `page` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `log_modif_page_ibfk_2` FOREIGN KEY (`user_refid`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_modif_page`
--

LOCK TABLES `log_modif_page` WRITE;
/*!40000 ALTER TABLE `log_modif_page` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_modif_page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mail_model`
--

DROP TABLE IF EXISTS `mail_model`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mail_model` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) DEFAULT NULL,
  `content` longtext,
  `active` tinyint(4) DEFAULT '1',
  `events_refid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `events_refid` (`events_refid`),
  CONSTRAINT `mail_model_ibfk_1` FOREIGN KEY (`events_refid`) REFERENCES `events` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mail_model`
--

LOCK TABLES `mail_model` WRITE;
/*!40000 ALTER TABLE `mail_model` DISABLE KEYS */;
INSERT INTO `mail_model` VALUES (1,'Activation de votre compte','Bonjour {{data.firstname}},\n\n\nAfin d\'activer votre compte, cliquer sur le lien ci-dessous:\n<a href=\"{{conf.main_document_root}}/activation?action=activate&token={{data.token_activation}}\">Activer mon compte</a>\n\nMerci ! \n\nLes administrateur du site  <a href=\"{{conf.main_url_root}}\">{{conf.site_name}}</a>',1,2),(2,'Changement de votre mot de passe','Bonjour {{data.firstname}},\n\n\nVous avez décidez de changer votre mot de passe.\nMerci de cliquer sur le lien ci dessous pour procéder au changement:\n<a href=\"{{conf.main_document_root}}/lost_pass?token={{data.tmp_token}}\">Changer mon mot de passe</a>\n\nMerci ! \n\nLes administrateur du site  <a href=\"{{conf.main_url_root}}\">{{conf.site_name}}</a>',1,1),(3,'External Message','<html>\n<h1>Bonjour, </h1>\n\n{{data|safe}}\n\n<h3>Merci</h3>\n\n</html>',1,NULL);
/*!40000 ALTER TABLE `mail_model` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_hierarchy`
--

DROP TABLE IF EXISTS `menu_hierarchy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_hierarchy` (
  `page_refid` int(11) NOT NULL,
  `level` int(11) DEFAULT NULL,
  `parent_page_refid` int(11) DEFAULT NULL,
  `top` float DEFAULT NULL,
  `left` float DEFAULT NULL,
  `endpoint_pos` varchar(15) DEFAULT '',
  PRIMARY KEY (`page_refid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_hierarchy`
--

LOCK TABLES `menu_hierarchy` WRITE;
/*!40000 ALTER TABLE `menu_hierarchy` DISABLE KEYS */;
/*!40000 ALTER TABLE `menu_hierarchy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `message_publication`
--

DROP TABLE IF EXISTS `message_publication`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message_publication` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) DEFAULT NULL,
  `content` longtext,
  `js` longtext,
  `js_code` longtext,
  `css` longtext,
  `css_code` longtext,
  `date_derniere_maj` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `group_publication_refid` int(11) DEFAULT NULL,
  `lang` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `group_publication_refid` (`group_publication_refid`),
  CONSTRAINT `message_publication_ibfk_1` FOREIGN KEY (`group_publication_refid`) REFERENCES `group_publication` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `message_publication`
--

LOCK TABLES `message_publication` WRITE;
/*!40000 ALTER TABLE `message_publication` DISABLE KEYS */;
/*!40000 ALTER TABLE `message_publication` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model`
--

DROP TABLE IF EXISTS `model`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) DEFAULT NULL,
  `path` varchar(150) DEFAULT NULL,
  `content` longtext,
  `extra_params` longtext,
  `plugins` longtext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model`
--

LOCK TABLES `model` WRITE;
/*!40000 ALTER TABLE `model` DISABLE KEYS */;
INSERT INTO `model` VALUES (1,'standard','standard','',NULL,NULL),(2,'standardBDD','','{% if ! search_mode %}\r\n<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\r\n<html lang=\'{{lang_short}}\'>\r\n    <head>\r\n        <meta http-equiv=\"Content-Type\" content=\"text/html;charset=UTF-8\"/>\r\n        <meta charset=\'UTF-8\'/>\r\n        <title>{{title}}</title>\r\n        <meta name=\"description\" content=\"{{meta}}\"/>\r\n        <meta name=\"keywords\" content=\"{{meta}}\"/>\r\n        <link rel=\"{{conf.favicon_rel}}\" type=\"{{conf.favicon_type}}\" href=\"{{conf.main_base_path}}/{{conf.favicon}}\" />\r\n        <!--[if lt IE 9]>\r\n            <script src=\"//html5shim.googlecode.com/svn/trunk/html5.js\"></script>\r\n        <![endif]-->\r\n        {% for j in js %}\r\n            <script type=\"text/javascript\" src=\"{{j}}\" ></script>\r\n        {% endfor %}\r\n        {% for j in js_external %}\r\n            <script type=\"text/javascript\" src=\"{{j}}\" async defer></script>\r\n        {% endfor %}\r\n        {% for j in js_code %}\r\n            <script type=\"text/javascript\">{{j|safe}}</script>\r\n        {% endfor %}\r\n        {% for c in css %}\r\n            <link media=\"screen\"  rel=\"stylesheet\" type=\"text/css\" href=\"{{c}}\"/>\r\n        {% endfor %}\r\n        {% for c in css_code %}\r\n            <style>{{c|safe}}</style>\r\n        {% endfor %}\r\n        {% if bandeau_growl_bool %}\r\n        {% if bandeau_growl %}\r\n        <script>\r\n        jQuery(document).ready(function(){\r\n            jQuery.growl({\r\n                title: \"{{_(\"Message\")}}\",\r\n                message: \"{{bandeau_growl | safe}}\",\r\n                static: true,\r\n                location: \'tr\',\r\n            });\r\n        });\r\n        </script>\r\n        {% endif %}\r\n        {% endif %}\r\n        {% if bandeau_growl_publication_bool %}\r\n        <script>\r\n        {%for i in bandeau_growl_publication%}\r\n        jQuery(document).ready(function(){\r\n            jQuery.growl({\r\n                title: \"{{_(\"Message\")}}\",\r\n                message: \"{{i.msg | safe}}\",\r\n                static: true,\r\n                location: \'tr\',\r\n            });\r\n        });\r\n        {% endfor %}\r\n        </script>\r\n        {% endif %}\r\n        {%if plugins %}\r\n        <script type=\"text/javascript\">\r\n        {% block plugin_js_code %}\r\n        {{plugin_js_code | safe}}\r\n        {% endblock %}\r\n        </script>\r\n        {% endif %}\r\n         {% if conf.code_google_analytics %}\r\n        <script>\r\n        (function(i,s,o,g,r,a,m){i[\'GoogleAnalyticsObject\']=r;i[r]=i[r]||function(){\r\n        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),\r\n        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)\r\n        })(window,document,\'script\',\'//www.google-analytics.com/analytics.js\',\'ga\');\r\n\r\n        ga(\'create\', \'{{code_google_analytics}}\', \'auto\');\r\n        ga(\'send\', \'pageview\');\r\n        </script>\r\n        {% endif %}\r\n    </head>\r\n    <body>\r\n        <header>\r\n            {% block header %}\r\n            {{header | safe}}\r\n            <form method=\"post\" action=\"lr\">\r\n            <input id=\"search\" class=\"ui-corner-all\" name=\"s\" placeholder=\"{{_(\"Recherche\")}}\"></input>\r\n            </form>\r\n            {{menu | safe}}\r\n            {% endblock %}\r\n            {% block user_connected %}\r\n            <div class=\"user_connected\">\r\n            {% if cur_user.id > 0 %}\r\n            {{_(\"Logged as:\")}} {{ cur_user.login}}\r\n            {% endif %}\r\n            </div>\r\n            {% endblock %}\r\n            {% block header_management %}\r\n            {% if cur_user.id > 0 %}\r\n            {{header_management | safe}}\r\n            {% endif %}\r\n            {% endblock %}\r\n            {% block lang_switch %}\r\n            {{ lang_switch | safe }}\r\n            {% endblock %}\r\n            <div id=\'bandeau\' class=\"bandeau\">{{bandeau | safe}}</div>\r\n            {%for i in bandeau_publication%}\r\n            <div id=\"bandeau_publication_{{i.id}}\" class=\'bandeau_publication\'>{{i.msg | safe}}</div>\r\n            {%endfor%}\r\n            <div id=\'cookie\' class=\"cookie\">{{cookie_msg | safe}}</div>\r\n            {% if breadcrumbs %}\r\n            <div class=\'breadcrumbs flat\'>\r\n            {% for i in breadcrumbs %}\r\n            {{i|safe}}\r\n            {% endfor %}\r\n            </div>\r\n            <br/>\r\n            {% endif %}\r\n\r\n        </header>\r\n        {%block col_gauche %}\r\n        <aside class=\'col_gauche\'>\r\n        {{col_gauche|safe}}\r\n        </aside>\r\n        {%endblock%}\r\n        {%block col_droite %}\r\n        <aside class=\'col_droite\'>\r\n        {{col_droite|safe}}\r\n        </aside>\r\n        {% endblock %}\r\n{% endif %}\r\n\r\n        <div class=\'wrapper\'>\r\n            <main>\r\n                <article id=\'main\' class=\'main\'>\r\n                    {% block main %}\r\n                    {{main|safe}}\r\n                    {% endblock %}\r\n                </article>\r\n                {% block form %}\r\n                <article id=\'form\' class=\'form\'>\r\n                {{form|safe}}\r\n                </article>\r\n                {% endblock form %}\r\n            </main>\r\n            {% block section %}\r\n            {% for i in section %}\r\n            <section>\r\n                <div>{{i.content|safe}}</div>\r\n            </section>\r\n            {% endfor %}\r\n            {% endblock %}\r\n\r\n            {{social|safe}}\r\n            <section id=\'subpage\' class=\'subpage\'>\r\n                {% block subpage %}\r\n                {% endblock %}\r\n            </section>\r\n{% if ! search_mode %}\r\n            {% block commentaires %}\r\n            {% if comment_bool %}\r\n             {{ commenthtml | safe }}\r\n             {% endif %}\r\n            {% endblock %}\r\n        </div>\r\n        <footer>\r\n            {%block footer %}\r\n            {{footer | safe}}\r\n            {% endblock %}\r\n        </footer>\r\n\r\n            {% if debug_bool %}\r\n            <section id=\'debug\'>\r\n            {%block debug %}\r\n            {{debug_app|safe}}\r\n            {% endblock %}\r\n            </section>\r\n            {% endif %}\r\n\r\n    </body>\r\n</html>\r\n{% endif %}',NULL,NULL);
/*!40000 ALTER TABLE `model` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page`
--

DROP TABLE IF EXISTS `page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` tinytext,
  `content` longtext,
  `model_refid` int(11) DEFAULT NULL,
  `in_menu` tinyint(4) NOT NULL DEFAULT '1',
  `url` varchar(250) DEFAULT '',
  `parent_refid` int(11) unsigned DEFAULT NULL,
  `meta` text,
  `js` longtext,
  `js_external` longtext,
  `js_code` longtext,
  `css` longtext,
  `css_code` longtext,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `date_creation` timestamp NULL DEFAULT NULL,
  `date_derniere_maj` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `last_modif_by` int(11) DEFAULT NULL,
  `changeFreq_refid` int(11) unsigned DEFAULT NULL,
  `priority` float DEFAULT '0.5',
  `exclude_sitemap` tinyint(4) NOT NULL DEFAULT '0',
  `exclude_search` tinyint(4) NOT NULL DEFAULT '0',
  `keyword` longtext,
  `generated_formated_content` longtext,
  `page_on_disk` tinyint(4) DEFAULT '0',
  `permit_comment` tinyint(4) DEFAULT '0',
  `author_refid` int(11) DEFAULT NULL,
  `extra_params` longtext,
  `is_locked_for_edition` tinyint(4) DEFAULT '0',
  `social_media` text,
  `plugins` longtext,
  `use_cache` tinyint(4) DEFAULT '1',
  `publication_status_refid` int(11) DEFAULT '1',
  `is_a_draft_for` int(11) DEFAULT NULL,
  `lang` varchar(10) DEFAULT NULL,
  `form_refid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url_2` (`url`,`is_a_draft_for`),
  KEY `model_refid` (`model_refid`),
  KEY `changeFreq_refid` (`changeFreq_refid`),
  KEY `author_refid` (`author_refid`),
  KEY `publication_status_refid` (`publication_status_refid`),
  KEY `url` (`url`),
  KEY `form_refid` (`form_refid`),
  CONSTRAINT `page_ibfk_5` FOREIGN KEY (`form_refid`) REFERENCES `forms` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `page_ibfk_1` FOREIGN KEY (`model_refid`) REFERENCES `model` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `page_ibfk_2` FOREIGN KEY (`changeFreq_refid`) REFERENCES `sitemapFreq` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `page_ibfk_3` FOREIGN KEY (`author_refid`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `page_ibfk_4` FOREIGN KEY (`publication_status_refid`) REFERENCES `publication_status` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page`
--

LOCK TABLES `page` WRITE;
/*!40000 ALTER TABLE `page` DISABLE KEYS */;
INSERT INTO `page` VALUES (1,'home','',1,1,'home',NULL,'','','','','','',1,'2015-02-22 23:19:28','2015-04-07 23:20:21',1,1,0.5,0,0,'','',1,NULL,1,NULL,0,NULL,'',1,2,NULL,NULL,NULL);
/*!40000 ALTER TABLE `page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_parts`
--

DROP TABLE IF EXISTS `page_parts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_parts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) DEFAULT NULL,
  `content` longtext,
  `js` longtext,
  `js_code` longtext,
  `css` longtext,
  `css_code` longtext,
  `date_derniere_maj` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `lang` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_parts`
--

LOCK TABLES `page_parts` WRITE;
/*!40000 ALTER TABLE `page_parts` DISABLE KEYS */;
INSERT INTO `page_parts` VALUES (1,'header','',NULL,'','','','2015-04-07 23:20:53',NULL),(2,'footer','',NULL,'','','','2015-04-07 23:20:56',NULL),(3,'cookie_message','<p>En continuant la navigation sur ce site, vous acceptez les cookies</p>\r\n<p><button id=\"cookieOK\">OK</button></p>',NULL,'jQuery(document).ready(function(){\r\n    jQuery(\'#cookieOK\').click(function(e){\r\n        document.cookie=\"accept=oui\";\r\n        jQuery(\'#cookie\').remove();\r\n    });\r\n});',NULL,NULL,'2015-03-31 23:35:03',NULL),(4,'bandeau_message','',NULL,NULL,NULL,NULL,'2015-03-31 23:35:05',NULL),(5,'col_gauche',NULL,NULL,'',NULL,'','2015-04-03 01:41:47',NULL),(6,'col_droite',NULL,NULL,'',NULL,'','2015-04-03 01:42:01',NULL);
/*!40000 ALTER TABLE `page_parts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_revision`
--

DROP TABLE IF EXISTS `page_revision`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_revision` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_refid` int(11) DEFAULT NULL,
  `title` tinytext,
  `content` longtext,
  `model_refid` int(11) DEFAULT NULL,
  `in_menu` tinyint(4) NOT NULL DEFAULT '1',
  `url` varchar(250) DEFAULT '',
  `parent_refid` int(11) unsigned DEFAULT NULL,
  `meta` text,
  `js` longtext,
  `js_external` longtext,
  `js_code` longtext,
  `css` longtext,
  `css_code` longtext,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `date_creation` timestamp NULL DEFAULT NULL,
  `date_derniere_maj` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `last_modif_by` int(11) DEFAULT NULL,
  `changeFreq_refid` int(11) unsigned DEFAULT NULL,
  `priority` float DEFAULT NULL,
  `exclude_sitemap` tinyint(4) NOT NULL DEFAULT '0',
  `exclude_search` tinyint(4) NOT NULL DEFAULT '0',
  `keyword` longtext,
  `generated_formated_content` longtext,
  `page_on_disk` tinyint(4) DEFAULT '0',
  `permit_comment` tinyint(4) DEFAULT '0',
  `author_refid` int(11) DEFAULT NULL,
  `extra_params` longtext,
  `is_locked_for_edition` tinyint(4) DEFAULT '0',
  `social_media` text,
  `plugins` longtext,
  `use_cache` tinyint(4) DEFAULT '1',
  `publication_status_refid` int(11) DEFAULT '1',
  `is_a_draft_for` int(11) DEFAULT NULL,
  `lang` varchar(10) DEFAULT NULL,
  `form_refid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url_3` (`url`,`lang`,`is_a_draft_for`),
  KEY `model_refid` (`model_refid`),
  KEY `changeFreq_refid` (`changeFreq_refid`),
  KEY `author_refid` (`author_refid`),
  KEY `publication_status_refid` (`publication_status_refid`),
  KEY `url` (`url`),
  KEY `url_2` (`url`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_revision`
--

LOCK TABLES `page_revision` WRITE;
/*!40000 ALTER TABLE `page_revision` DISABLE KEYS */;
/*!40000 ALTER TABLE `page_revision` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `publication_status`
--

DROP TABLE IF EXISTS `publication_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `publication_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `is_public` int(11) DEFAULT '0',
  `in_search_engine` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publication_status`
--

LOCK TABLES `publication_status` WRITE;
/*!40000 ALTER TABLE `publication_status` DISABLE KEYS */;
INSERT INTO `publication_status` VALUES (1,'Brouillon',1,0,0),(2,'Publié',3,1,1),(3,'En attente d\'approbation',4,0,0),(4,'Archivé',5,0,0);
/*!40000 ALTER TABLE `publication_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rights_def`
--

DROP TABLE IF EXISTS `rights_def`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rights_def` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rights_def`
--

LOCK TABLES `rights_def` WRITE;
/*!40000 ALTER TABLE `rights_def` DISABLE KEYS */;
INSERT INTO `rights_def` VALUES (1,'admin','Tous les droits'),(2,'read','Lecture'),(3,'edit_page','Edition des pages'),(4,'edit_section','Edition des sections'),(5,'conf','Modification de la configuration'),(6,'user','Modification user & group'),(7,'headerfooter','Modification des headers et footers'),(8,'template','Edition des templates'),(9,'publication','Modification des droits de publication'),(10,'manage','Accès à la maintenance du site'),(11,'moderate','Modération des commentaires');
/*!40000 ALTER TABLE `rights_def` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `section`
--

DROP TABLE IF EXISTS `section`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `section` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `page_refid` int(11) DEFAULT NULL,
  `content` longtext,
  `title` varchar(150) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '1',
  `date_creation` timestamp NULL DEFAULT NULL,
  `date_derniere_maj` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `author_refid` int(11) DEFAULT NULL,
  `is_locked_for_edition` tinyint(4) DEFAULT '0',
  `associated_img` varchar(1500) DEFAULT NULL,
  `last_modif_by` int(11) DEFAULT NULL,
  `lang` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_refid` (`page_refid`,`order`),
  KEY `page_refid_2` (`page_refid`),
  KEY `title` (`title`),
  KEY `author_refid` (`author_refid`),
  KEY `last_modif_by` (`last_modif_by`),
  CONSTRAINT `section_ibfk_1` FOREIGN KEY (`page_refid`) REFERENCES `page` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `section_ibfk_2` FOREIGN KEY (`author_refid`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `section_ibfk_3` FOREIGN KEY (`last_modif_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `section`
--

LOCK TABLES `section` WRITE;
/*!40000 ALTER TABLE `section` DISABLE KEYS */;
/*!40000 ALTER TABLE `section` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `section_revision`
--

DROP TABLE IF EXISTS `section_revision`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `section_revision` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `section_refid` int(11) DEFAULT NULL,
  `page_revision_refid` int(11) DEFAULT NULL,
  `content` longtext,
  `title` varchar(150) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '1',
  `date_creation` timestamp NULL DEFAULT NULL,
  `date_derniere_maj` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `author_refid` int(11) DEFAULT NULL,
  `is_locked_for_edition` tinyint(4) DEFAULT '0',
  `associated_img` varchar(1500) DEFAULT NULL,
  `last_modif_by` int(11) DEFAULT NULL,
  `lang` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_refid` (`page_revision_refid`,`order`),
  KEY `page_refid_2` (`page_revision_refid`),
  KEY `title` (`title`),
  KEY `author_refid` (`author_refid`),
  KEY `last_modif_by` (`last_modif_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `section_revision`
--

LOCK TABLES `section_revision` WRITE;
/*!40000 ALTER TABLE `section_revision` DISABLE KEYS */;
/*!40000 ALTER TABLE `section_revision` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sitemapFreq`
--

DROP TABLE IF EXISTS `sitemapFreq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sitemapFreq` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sitemapFreq`
--

LOCK TABLES `sitemapFreq` WRITE;
/*!40000 ALTER TABLE `sitemapFreq` DISABLE KEYS */;
INSERT INTO `sitemapFreq` VALUES (1,'daily'),(2,'yearly');
/*!40000 ALTER TABLE `sitemapFreq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `social_media`
--

DROP TABLE IF EXISTS `social_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `social_media` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `social_media`
--

LOCK TABLES `social_media` WRITE;
/*!40000 ALTER TABLE `social_media` DISABLE KEYS */;
INSERT INTO `social_media` VALUES (1,'facebook'),(2,'linkedin'),(3,'tweeter'),(4,'flickr'),(5,'foursquare'),(6,'google_plus'),(7,'tumblr'),(8,'instagram'),(9,'pinterrest');
/*!40000 ALTER TABLE `social_media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) DEFAULT NULL,
  `firstname` varchar(300) DEFAULT NULL,
  `login` varchar(30) DEFAULT NULL,
  `pass` varchar(150) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `description` longtext,
  `avatar_path` varchar(200) DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1',
  `md5` varchar(150) DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `remember_me` varchar(64) DEFAULT NULL,
  `last_failed_login` timestamp NULL DEFAULT NULL,
  `failed_logins` int(11) DEFAULT NULL,
  `public_profile` tinyint(4) DEFAULT '0',
  `tmp_token` text,
  `date_tmp_token` timestamp NULL DEFAULT NULL,
  `is_locked` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `md5_2` (`md5`),
  UNIQUE KEY `email_2` (`email`),
  KEY `login` (`login`),
  KEY `email` (`email`),
  KEY `md5` (`md5`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'admin','admin','admin','f5ff147ac29f9220f8790a0a99de3d5f','admin@mysite.com','<p>My Description 11234567</p>','/img/avatars/f6c65b6f10ff2e386c8714bfc7782de0.jpg',1,'f6c65b6f10ff2e386c8714bfc7782de0','2015-04-07 12:35:55','82b5d750b56a4fd523333c01bdee11bf28592b86dacca12b3cd04015beded71f','2015-04-07 12:35:15',0,0,NULL,NULL,0);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_rights`
--

DROP TABLE IF EXISTS `user_rights`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_rights` (
  `user_refid` int(11) NOT NULL,
  `rights_def_refid` int(11) NOT NULL,
  PRIMARY KEY (`user_refid`,`rights_def_refid`),
  KEY `rights_def_refid` (`rights_def_refid`),
  CONSTRAINT `user_rights_ibfk_1` FOREIGN KEY (`user_refid`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `user_rights_ibfk_2` FOREIGN KEY (`rights_def_refid`) REFERENCES `rights_def` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `submenu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `submenu` (
  `content` text,
  `img_src` varchar(255) DEFAULT NULL,
  `page_refid` int(11) NOT NULL DEFAULT '0',
  `sublink` text,
  `active` int(11) DEFAULT NULL,
  PRIMARY KEY (`page_refid`),
  CONSTRAINT `submenu_ibfk_1` FOREIGN KEY (`page_refid`) REFERENCES `page` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_rights`
--

LOCK TABLES `user_rights` WRITE;
/*!40000 ALTER TABLE `user_rights` DISABLE KEYS */;
INSERT INTO `user_rights` VALUES (1,1);
/*!40000 ALTER TABLE `user_rights` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-04-08  1:22:54

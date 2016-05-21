# Host: localhost  (Version: 5.6.17)
# Date: 2016-05-21 18:28:29
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES latin1 */;

#
# Structure for table "usuarios"
#

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) COLLATE utf8_general_mysql500_ci DEFAULT 'Guilherme Borges Bastos',
  `email` varchar(255) COLLATE utf8_general_mysql500_ci NOT NULL,
  `senha` varchar(255) COLLATE utf8_general_mysql500_ci NOT NULL,
  `status` bigint(20) DEFAULT '1',
  `registration_id` varchar(255) COLLATE utf8_general_mysql500_ci DEFAULT '' COMMENT 'id do dispositivo Android',
  `ultimo_acesso` bigint(20) DEFAULT '0',
  `atualizado_em` bigint(20) DEFAULT '0',
  `criado_em` bigint(20) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

#
# Data for table "usuarios"
#

INSERT INTO `usuarios` VALUES (1,'Guilherme Borges Bastos','guilhermeborgesbastos@gmail.com','teste',1,'APA91bGnCFM5c2vhWxKG4aEgjqe3x7dpVtCf-DHta2JxDbVkB7hra-5K6cM2phNZLIJtjH0hpdFMH2J0Lm8DhX-lrIwivAdmHpkCYTfGCGXP7qCR_Vawy8_VX47ZpVFMUTrMpyJaCYQI',0,0,0);

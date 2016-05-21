# Host: localhost  (Version: 5.6.17)
# Date: 2016-05-21 17:04:34
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

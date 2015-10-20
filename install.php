<?php
// install DB

require_once 'app'.DIRECTORY_SEPARATOR.'Bootstrap.php';

$config = Config::get();
$basename = $config['db']['name'];
$table_users = $config['tables']['users'];
$table_msgs = $config['tables']['msgs'];

$db = new Db(false);

$query = "CREATE DATABASE IF NOT EXISTS $basename";
$db->query($query);

$db->query("use $basename");

$db->query("DROP TABLE IF EXISTS `".$table_users."`");
$query = "
CREATE TABLE IF NOT EXISTS `".$table_users."` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nick` varchar(20) NOT NULL,
  `profile` varchar(255) NOT NULL DEFAULT '',  
  `date` int(10) NOT NULL DEFAULT '0',
  `ip_addr` CHAR(16) binary NOT NULL DEFAULT '0' DEFAULT '',  
  `site` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;";
$db->query($query);

$db->query("DROP TABLE IF EXISTS `".$table_msgs."`");
$query="
CREATE TABLE IF NOT EXISTS `".$table_msgs."` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_nick` int(10) NOT NULL DEFAULT '0',  
  `textmsg` varchar(255) NOT NULL,  
  `date` int(10) NOT NULL DEFAULT '0',  
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;";
$db->query($query);

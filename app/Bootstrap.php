<?php

error_reporting (E_ALL);
//session_start();

define ('DIRSEP', DIRECTORY_SEPARATOR); 
$site_path = dirname(__FILE__);
$site_path = str_replace(DIRSEP.'app', '', $site_path);
define ('site_path', $site_path);

$doc_root = str_replace('/', DIRSEP, $_SERVER["DOCUMENT_ROOT"]);
$path = str_replace($doc_root.DIRSEP, '', site_path);
$path = str_replace(DIRSEP, '/', $path);
$path = '/'.$path;
define ('mch_path', $path);


require_once 'controller'.DIRSEP.'Config.php';
require_once 'controller'.DIRSEP.'Mchat.php';
require_once 'controller'.DIRSEP.'Form.php';
require_once 'controller'.DIRSEP.'Db.php';
require_once 'controller'.DIRSEP.'DbPdo.php';
require_once 'controller'.DIRSEP.'DbMysql.php';
require_once 'controller'.DIRSEP.'GetInp.php';
require_once 'controller'.DIRSEP.'Resp.php';
require_once 'controller'.DIRSEP.'Date.php';
require_once 'controller'.DIRSEP.'Nick.php';
require_once 'controller'.DIRSEP.'Msg.php';
require_once 'controller'.DIRSEP.'Ip.php';
require_once 'controller'.DIRSEP.'Token.php';
require_once 'controller'.DIRSEP.'UserAgent.php';
require_once 'controller'.DIRSEP.'Tmpl.php';

require_once 'model'.DIRSEP.'MchatMod.php';
require_once 'model'.DIRSEP.'NickMod.php';
require_once 'model'.DIRSEP.'MsgMod.php';

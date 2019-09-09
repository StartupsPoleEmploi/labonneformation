<?php
	$path=pathinfo(__FILE__)['dirname'].DIRECTORY_SEPARATOR;

	$protocol=(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off' || (array_key_exists('SERVER_PORT',$_SERVER) && $_SERVER['SERVER_PORT']==443))?'https':'http';
	if(!defined('DOMAIN')) define('DOMAIN',array_key_exists('HTTP_HOST',$_SERVER)?$_SERVER['HTTP_HOST']:'labonneformation.pole-emploi.fr');
	if(!defined('URL_BASE')) define('URL_BASE',$protocol.'://'.DOMAIN);
	if(!defined('URL_LBF')) define('URL_LBF',URL_BASE);
	if(!defined('COMBINE_SCRIPT')) define('COMBINE_SCRIPT',true);
	if(!defined('SHOW_INTERCARIF')) define('SHOW_INTERCARIF',false);
	if(!defined('SMTP_SERVER')) define('SMTP_SERVER','lbfsmtp');
	if(!defined('MAILTO_404')) define('MAILTO_404','');
	if(!defined('ENV_DEV')) define('ENV_DEV',true);
	if(!defined('ENV_NAME')) define('ENV_NAME','oss');
	if(!defined('DOWNLOAD_PATH')) define('DOWNLOAD_PATH','');
	if(!defined('EMAIL_MODALCONTACT')) define('EMAIL_MODALCONTACT','');
	if(!defined('EMAIL_CONTACT')) define('EMAIL_CONTACT','');
	
	if(!isset($databaseRead)) $databaseRead=array('host'=>'database','user'=>'root','password'=>'','db'=>'labonneformation');
	if(!isset($databaseWrite)) $databaseWrite=array('host'=>'database','user'=>'root','password'=>'','db'=>'labonneformation');
	if(!isset($database)) $database=$databaseWrite;
?>

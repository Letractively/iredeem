<?php
function _DaoLog($event, $object) {
	static $logger = null;
	if($logger == null) {
		$logger = Logger::getLogger('Dao');
	}
	$object_id = $object->id();
	$object_class = get_class($object);
	
	$rp = new ReflectionProperty($object_class, '_FIELDS_');
	$properties = $rp->getValue($object);

	$from = array();
	$to = array();
	if(isset(Dao::$_cache[$object_class][$object_id])) {
		$o = Dao::$_cache[$object_class][$object_id];
		foreach($properties as $pn) {
			$fv = $o->$pn();
			$tv = $object->$pn();
			if($fv != $tv) {
				$from[$pn] = $o->$pn();
				$to[$pn] = $tv;
			}
		}
	} else {
		foreach($properties as $pn) {
			$to[$pn] = $object->$pn();
		}
	}
	if($logger->isDebugEnabled()) {
		$logger->debug("$event $object_class"."[$object_id]\nFrom " . var_export($from, true) . "\nTo " . var_export($to, true));
	}
}
Dao::$listner = '_DaoLog';

function SiteConfigValue($name = null) {
	static $configs = null;
	if($configs === null) {
		$configs = require _DATA_ . '/siteconfig.php';
	}
	if($name === null) {
		return $configs;
	} else {
		return $configs[$name];
	}
	//return SiteConfig::getOne(array('name'=>$name))->value();
}

function _loadSiteConfigs($paramRegister, $smarty) {
	$smarty->assign('siteConfig', SiteConfigValue());
}
event_register('smarty.init', '_loadSiteConfigs');

function pswHash($password) {
	require_once _ROOT_ . '/libs/local/fn.php';
	
	$salt = SiteConfigValue('member_password_salt');
	$hash = sha1($salt . '.' . $password);
	return $hash;
}

define('_USER_', '_USER_');
define('_MUSER_', '_MUSER_');

function memberid() {
	if(isset($_SESSION[_USER_]['id']))
		return $_SESSION[_USER_]['id'];
	else 
		return null;
}

$exceptionHandlers['LoginNeededException'] = '_exception_needlogin';
function _exception_needlogin($e){
	header('Location: '._BASE_.'/mobile/login.php?r=' . urlencode($_SERVER['REQUEST_URI']));
}
$exceptionHandlers['ManagerNeededException'] = '_exception_needmanager';
function _exception_needmanager($e){
	header('Location: '._BASE_.'/admin/login.php?r=' . urlencode($_SERVER['REQUEST_URI']));
}


if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')) {
	throw new Exception('请使用Opera/Chrome/Safari/FireFox浏览此页面，IE什么的最讨厌了');
}
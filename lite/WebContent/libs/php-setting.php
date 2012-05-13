<?php
date_default_timezone_set('Asia/Shanghai');
ini_set('session.gc_maxlifetime', 3600*12);
ini_set('session.cookie_lifetime', 3600*12);

// Strip magic quotes from request data.
if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
    // Create lamba style unescaping function (for portability)
    $quotes_sybase = strtolower(ini_get('magic_quotes_sybase'));
    $unescape_function = (empty($quotes_sybase) || $quotes_sybase === 'off') ? 'stripslashes($value)' : 'str_replace("\'\'","\'",$value)';
    $stripslashes_deep = create_function('&$value, $fn', '
        if (is_string($value)) {
            $value = ' . $unescape_function . ';
        } else if (is_array($value)) {
            foreach ($value as &$v) $fn($v, $fn);
        }
    ');
    
    // Unescape data
    $stripslashes_deep($_POST, $stripslashes_deep);
    $stripslashes_deep($_GET, $stripslashes_deep);
    $stripslashes_deep($_COOKIE, $stripslashes_deep);
    $stripslashes_deep($_REQUEST, $stripslashes_deep);
}

/***** smarty *********************************/
function _autoload_smarty($name){
	if($name == 'Smarty') {
		require_once _ROOT_ .'/libs/smarty3/Smarty.class.php';
		return true;
	} else {
		return false;
	}
}
spl_autoload_register('_autoload_smarty');

$_smarty = NULL;
/**
 * 
 * @return Smarty
 */
function global_smarty(){
	global $_smarty;
	global $__controller,$__action;
	if(!isset($_smarty)) {
		$smarty = new Smarty();
		$smarty->error_reporting = E_ALL & ~E_NOTICE;
//		$smarty->template_dir = _ROOT_ . '/data/minified';
		$smarty->template_dir = _ROOT_;
		$smarty->compile_dir  = _TMP_.'/smarty/templates_c/';
		$smarty->plugins_dir = array(SMARTY_DIR . 'plugins', SMARTY_DIR . 'myplugins');
		//$smarty->config_dir   = _TMP_.'/smarty/configs/';
		$smarty->cache_dir    = _TMP_.'/smarty/cache/';
		$smarty->left_delimiter = '{#';
		$smarty->right_delimiter = '#}';
		$smarty->php_handling = Smarty::PHP_ALLOW;
		
		if(defined('_INDEX_FILE_')){
			$smarty->assign('_CURL_', "/index.php?c=$__controller") ;
		} else {
			$smarty->assign('_CURL_', "{$_SERVER['SCRIPT_NAME']}?") ;
		}
		event_trigger('smarty.init', $smarty);
		$_smarty = $smarty;
	}
	return $_smarty;
}
/**** classes **************************************/
function _autoload_classes($name){
	//TODO: 上线前使用数组替换file_exists
	if(file_exists(_ROOT_ . "/libs/classes/$name.class.php")) {
		require_once _ROOT_ . "/libs/classes/$name.class.php";
		return true;
	} else {
		return false;
	}
}
spl_autoload_register('_autoload_classes');

if(!isset($_SERVER['argc'])) {
	if(!isset($_SERVER['SCRIPT_URI'])) {
		$_SERVER['SCRIPT_URI'] = (strpos($_SERVER['SERVER_PROTOCOL'], 'HTTPS')===false?'http://':'https://') . $_SERVER["SERVER_NAME"] . ($_SERVER["SERVER_PORT"]==80? '' : ":{$_SERVER["SERVER_PORT"]}") . $_SERVER["SCRIPT_NAME"];
	}
} else if($_SERVER['argc'] == 2) {
	parse_str($argv[1], $params);
	if($params) $_GET = $params;
}

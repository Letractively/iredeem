<?php 
// webapp的文件绝对路径
$__time_start = microtime(true);

define('_ROOT_', realpath(dirname(__FILE__).'/..') /*$_SERVER['DOCUMENT_ROOT']*/);
define('_BASE_', str_replace('\\', '/', substr(_ROOT_, strlen($_SERVER['DOCUMENT_ROOT']))));
define('_DATA_', _ROOT_.'/data');
define('_TMP_', _DATA_.'/tmp');

require_once _ROOT_.'/libs/events.php';
require_once _ROOT_.'/libs/php-setting.php';
require_once _ROOT_.'/libs/exception.php';
require_once _ROOT_. '/libs/exceptionHandler.php';
require_once _ROOT_.'/libs/config.php';

session_start();

$__logger = Logger::getLogger("global");
define('__ACTION_PREFIX', 'do');
define('__CONTROLLER_PREFIX', _ROOT_);

$__exception = NULL;
try {
	require_once _ROOT_.'/libs/dao/Dao.php';
	
	$__controller = (isset($_GET['c']) && strpos($_GET['c'], '/')===false && strpos($_GET['c'], '\\')===false )? $_GET['c'] : 'default';
	$__controller = str_replace('_', '/', $__controller);
	$__action = isset($_GET['action'])? $_GET['action'] : (isset($_GET['a'])? $_GET['a'] : 'default');
	if(defined('_INDEX_FILE_')){
		$__logger->info("$__controller:$__action");
		require_once __CONTROLLER_PREFIX."/$__controller.php";
	} else {
		$__logger->info("{$_SERVER['SCRIPT_NAME']}:$__action");
	}
	
	$__actionMethod = __ACTION_PREFIX . ucfirst($__action);

	require_once _ROOT_ . '/libs/_before_action_.php';
	$_initResult = true;
	if($__actionMethod !== NULL && function_exists('_doInit')) {
		$_initResult = _doInit($__action);
		if(!isset($_init_result)) $_initResult = true;
	}	
	if($__actionMethod !== NULL && $_initResult) {
		if(!function_exists($__actionMethod)) {
			throw new PageNotFoundException();
		} else {
			$__actionMethod();
		}
	}
	
	require_once _ROOT_ . '/libs/_after_action_.php';
	
	Dao::commit(false);
	
} catch(Exception $e) {
	
    Dao::rollback(false);

	$__exception = $e;
	//ob_clean();
	
	handleExceptions($e);
}

$__time_end = microtime(true);
if(defined('_INDEX_FILE_')){
    $__logger->info("$__controller:$__action time: " . ($__time_end - $__time_start) . "s");
} else {
    $__logger->info("{$_SERVER['SCRIPT_NAME']}:$__action time: " . ($__time_end - $__time_start) . "s");
}


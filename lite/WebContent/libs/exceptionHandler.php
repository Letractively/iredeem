<?php
/*
function exception_error_handler($errno, $errstr, $errfile, $errline ) {
    switch ($errno) {
    case E_USER_ERROR:
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        break;
    case E_USER_WARNING:
        echo "<b>My WARNING</b> [$errno] $errstr<br />\n";
        break;
    case E_USER_NOTICE:
        echo "<b>My NOTICE</b> [$errno] $errstr<br />\n";
        break;
    default:
        echo "Unknown error type: [$errno] $errstr<br />\n";
        break;
    }
    // Don't execute PHP internal error handler
    return true;
}

set_error_handler("exception_error_handler");
*/
$exceptionHandlers = array();
function handleExceptions($e){
	global $exceptionHandlers;
	$exceptionLogger = Logger::getLogger('ExceptionHandler');
	foreach(array(get_class($e))+class_implements($e)+class_parents($e) as $p) {
		if(isset($exceptionHandlers[$p])) {
			$exceptionLogger->info('Exception type: ' . get_class($e));
			$exceptionHandlers[$p]($e);
			return;
		}
	}
	
	throw $e;
}

$exceptionHandlers['Exception'] = 'handleException';
function handleException($e){
	if(isset($_REQUEST['_json']) && $_REQUEST['_json'] === 'true') {
		$json = array('result'=>false, 'message'=>$e->getMessage());
		echo json_encode($json);
	} else {
		$smarty = global_smarty();
		$smarty->assign('exception', $e);
		
		$smarty->display('sys/error/Exception.htm');
	}
}

$exceptionHandlers['PageNotFoundException'] = 'handlePageNotFoundException';
function handlePageNotFoundException($e){
	if(!headers_sent()) {
		header('HTTP/1.1 404 Not Found');
	}
	header('Content-Type: text/html; charset=utf-8');
	echo $e->getMessage();
}

$exceptionHandlers['MessageException'] = 'handleMessageException';
function handleMessageException($e){
	if(isset($_REQUEST['_json']) && $_REQUEST['_json'] === 'true') {
		$json = array('result'=>false, 'message'=>$e->getMessage());
		echo json_encode($json);
	} else {
		$smarty = global_smarty();
		$smarty->assign('handle', 'BACK');
		$smarty->assign('message', $e->getMessage());
		$smarty->display('sys/response.htm');
	}
}

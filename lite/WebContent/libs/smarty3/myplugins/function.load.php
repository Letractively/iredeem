<?php
/**
 * Enter description here...
 *
 * @param array $params ['page': .php]
 * @param Smarty $smarty
 */
function smarty_function_load($params, &$smarty)
{
	$page = $params['page'];
	foreach($params as $paramName=>$paramValue) {
		$$paramName = $paramValue;
	}	
	include _ROOT_ . '/' . $page;
}

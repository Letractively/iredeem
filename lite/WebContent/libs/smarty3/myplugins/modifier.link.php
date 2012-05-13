<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty plugin
 *
 * Type:     modifier<br>
 * Name:     link<br>
 * @return string
 */
function smarty_modifier_link($value, $name = 'page', $array = false)
{
	$params = array_merge($_GET, $_POST);
	if($array)
		$params[$name][] = $value;
	else
		$params[$name] = $value;
	$url = '?';
	
	foreach($params as $paramname=>$paramvalue) {
		if(is_array($paramvalue)) {
			foreach($paramvalue as $p)
				$url .= '&'.urlencode($paramname).'='.urlencode($p);
		} else {
			$url .= '&'.urlencode($paramname).'='.urlencode($paramvalue);
		}
	}
    return $url;
}

/* vim: set expandtab: */

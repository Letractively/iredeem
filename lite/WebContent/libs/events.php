<?php
$GLOBALS['_events'] = array();
$GLOBALS['_events_params'] = array();
function event_register($event, $callback, $params = NULL) {
	if(!isset($GLOBALS['_events'][$event])) $GLOBALS['_events'][$event] = array();
	$GLOBALS['_events'][$event][] = $callback;
	$GLOBALS['_events_params'][$event][] = $params;
}
function event_unregister($event, $callback = NULL) {
	if(!isset($GLOBALS['_events'][$event])) $GLOBALS['_events'][$event] = array();
	if($callback === NULL) {
		$GLOBALS['_events'][$event] = array();
		$GLOBALS['_events_params'][$event] = array();
	} else {
		$key = array_search($callback, $GLOBALS['_events'][$event]);
		if($key !== false) {
			unset($GLOBALS['_events'][$event][$key]);
			unset($GLOBALS['_events_params'][$event][$key]);
		}
	}
}
function event_trigger($event, $paramsTrigger = NULL) {
	if(!isset($GLOBALS['_events'][$event])) return;
	foreach ($GLOBALS['_events'][$event] as $i => $callback) {
		$paramsRegister = $GLOBALS['_events_params'][$event][$i];
		$callback($paramsRegister, $paramsTrigger);
	}
}

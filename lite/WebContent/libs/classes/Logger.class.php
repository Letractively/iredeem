<?php 
class Logger {
	const OFF = 2147483647;
	const FATAL = 50000;
	const ERROR = 40000;
	const WARN = 30000;
	const INFO = 20000;
	const DEBUG = 10000;
	public static $DEFAULT_LOG_LEVEL = Logger::DEBUG;
	public static $logFile = '';
	public static $enableFirePHP = true;
	public static $loggers = array();

	public static function getLogger($name) {
		 return new Logger();
	}
	
	public function isDebugEnabled() {
		return false;
	}		
	public function isInfoEnabled() {
		return false;
	}		
	
	public function debug($message) {
	}

	public function info($message) {
	}

	public function warn($message) {
	}
	
	public function error($message) {
	}
	
	public function fatal($message) {
	}
	
	public function trace($message) {
	}
}


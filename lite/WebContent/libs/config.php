<?php 
$_DB_ = array();

$_sqlite_config = array();
$_sqlite_config['type'] = 'sqlite';
$_sqlite_config['dsn'] = 'sqlite:' . _DATA_ . '/db.sqlite';
$_sqlite_config['user'] = 'root';
$_sqlite_config['psw'] = '';
$_DB_['sqlite'] = $_sqlite_config;

function GetOption($opt){
    static $opts = array(
        'upload.path' => '/userfiles',
    	'user.upload.path' => '/userfiles/user',
    	'convocation.upload.path' => '/userfiles/convocation',
    	'ikaihui.upload.path' => '/userfiles/ikaihui',
    );
    if(isset($opts[$opt])) return $opts[$opt];
    else return null;
}

define('DEBUG', false);

if(class_exists('Logger')) {
	Logger::$DEFAULT_LOG_LEVEL = DEBUG? Logger::DEBUG : Logger::WARN;
	Logger::$enableFirePHP = isset($_COOKIE['FirePHP']) && $_COOKIE['FirePHP'];
	Logger::$logFile = _DATA_ . "/log/log4php.log";
	Logger::$loggers['system']			= array(_DATA_ . "/log/system.log", Logger::INFO);
}


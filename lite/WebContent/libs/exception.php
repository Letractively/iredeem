<?php 
class DataBaseException extends Exception {
	public function __construct ($message = "" , $code = 0) {
		parent::__construct($message, $code);
	}
}

class PageNotFoundException extends Exception {
	public function __construct ($message = 'Page Not Found' , $code = 404) {
		parent::__construct($message, $code);
	}
}

class MessageException extends Exception {
	public function __construct ($message = "" , $code = 0) {
		parent::__construct($message, $code);
	}
}

class AccessDenidedException extends Exception {
	public function __construct ($message = "" , $code = 0) {
		parent::__construct($message, $code);
	}
}

class LoginNeededException extends AccessDenidedException {
	public function __construct ($message = "") {
		parent::__construct($message);
	}
}

class ManagerNeededException extends AccessDenidedException {
	public function __construct ($message = "") {
		parent::__construct($message);
	}
}

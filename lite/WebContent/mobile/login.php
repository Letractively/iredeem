<?php require_once realpath(dirname(__FILE__).'/..').'/libs/global.php';

function doDefault(){
	doLogin();
}

function doLogin() {
	if(isset($_GET['r']) && $_GET['r']) {
		$r = $_GET['r'];
	} else {
		$r = _BASE_.'/mobile/';
	}

	if(isset($_SESSION[_USER_])) {
		header('Location: ' . $r);
		return;
	}
	
	$error = false;
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		if(isset($_POST['email']) && $_POST['email']) {

			$email = strtolower($_POST['email']);
			$password = $_POST['password'];

			$member = Member::getOne(array('email'=>$email));
			if(!$member) {
				$error = '用户不存在';
			} else {
				if($member->password() !== pswHash($password)) {
					$error = '用户名或密码错误';
				} else {
					$_SESSION[_USER_] = array('id'=>$member->id(),
											  'email'=>$member->email(), 
											  'loginTime'=>time(),
											  'lastLoginTime'=>$member->lastLoginTime(),);
					$member->lastLoginTime(date('Y-m-d H:i:s'));
					$member->save();
					
					header('Location: ' . $r);
					return;
				}
			}
		}
	}
	
	if(isset($_SESSION['_checkcode_']['login'])) unset($_SESSION['_checkcode_']['login']);	
	
	$smarty = global_smarty();
	
	$smarty->assign('error', $error);
	$smarty->display('mobile/login.htm');
}

function doLogout() {
	if(isset($_GET['r']) && $_GET['r']) {
		$r = $_GET['r'];
	} else {
		$r = _BASE_.'/mobile/';
	}
	
	unset($_SESSION[_USER_]);
	header('Location: ' . $r);
}

function doIForgot() {
	$smarty = global_smarty();

	$error = false;
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		if(isset($_POST['email']) && $_POST['email']) {

			$email = strtolower($_POST['email']);

			$member = Member::getOne(array('email'=>$email), 'id asc');
			if(!$member) {
				$error = '用户不存在';
			} else {
				//iforgot
				$error = '不提供此功能';
			}
		}
	}
	
	$smarty->assign('error', $error);

	$smarty->display('mobile/login-iforgot.htm');

}

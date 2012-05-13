<?php require_once realpath(dirname(__FILE__).'/../libs/global.php');

function doDefault(){
	$error = false;
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$email = isset($_POST['email'])? trim($_POST['email']) : false;
		require_once _ROOT_ . '/libs/local/fn.php';
		if(!isMailValid($email)) {
			$error = '邮箱无效';
		} else if($email) {
			$member = Member::getOne(array('email'=>$email));
			if($member) {
				$error = '用户已存在，请直接登录或者重置密码';
			} else {
				// 创建用户
				
				$member = new Member();
				$member->email($email);
				$member->signupTime(date('Y-m-d H:i:s'));
				$password = '123456';
				$member->password(pswHash($password));
				$member->save();
								
				$smarty = global_smarty();
				$smarty->assign('title', '账户已创建');
				$smarty->assign('message', '您的账户已创建，初始密码为 '.$password.'。建议您尽快修改初始密码。');
				$smarty->display('mobile/response.htm');
				
				return;
			}
		} else {
			$error = '请填写电子邮箱';
		}
	}
	$smarty = global_smarty();
	$smarty->assign('error', $error);
	$smarty->display('mobile/signup.htm');
}
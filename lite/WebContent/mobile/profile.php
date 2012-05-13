<?php require_once realpath(dirname(__FILE__).'/../libs/global.php');
function _doInit() {
	$memberid = memberid();
	if(!$memberid) throw new LoginNeededException();
}
function doDefault(){
	$member = Member::get(memberid());
	$smarty = global_smarty();
	$smarty->assign('member', $member);
	$smarty->display('mobile/profile.htm');
}

function doBasic(){
	$error = false;
	$member = Member::get(memberid());
	if($_SERVER["REQUEST_METHOD"] == 'POST'){
		if(isset($_POST['member_notifyNewPromotion'])) {
			$v = trim($_POST['member_notifyNewPromotion']);
			if($v !== '') {
				$member->notifyNewPromotion($v);
			} else if($v === '') {
				$member->notifyNewPromotion( NULL );
			}
		}
		if(isset($_POST['member_notifyNewGift'])) {
			$v = trim($_POST['member_notifyNewGift']);
			if($v !== '') {
				$member->notifyNewGift($v);
			} else if($v === '') {
				$member->notifyNewGift( NULL );
			}
		}
		$member->save();
		
	}
	$smarty = global_smarty();
	$smarty->assign('error', $error);
	$smarty->assign('member', $member);
	$smarty->display('mobile/profile-basic.htm');
}

function doPassword() {
	$error = false;
	$member = Member::get(memberid());
	if($_SERVER["REQUEST_METHOD"] == 'POST'){
		if(!isset($_POST['newpassword']) || $_POST['newpassword'] === '') {
			$error = '新密码不能为空';
		}else if($_POST['newpassword'] !== $_POST['newpassword2']) {
			$error = '两次密码不一致';
		} else if($member->password() !== pswHash($_POST['password'])) {
			$error = '密码错误';
		} else {
			$member->password(pswHash($_POST['newpassword']));
			$member->save();
		}
	}
	$smarty = global_smarty();
	$smarty->assign('error', $error);
	$smarty->assign('member', $member);
	$smarty->display('mobile/profile-password.htm');
}

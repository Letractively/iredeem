<?php require_once realpath(dirname(__FILE__).'/../libs/global.php');
function _doInit() {
	$memberid = memberid();
	if(!$memberid) throw new LoginNeededException();
}

function doDefault(){
	$smarty = global_smarty();
	
	$memberId = memberid();
	$deviceCount = Device::getSize(array('memberid'=>$memberId));
	$smarty->assign('deviceCount', $deviceCount);
	$codeCount = Code::getSize(array('memberid'=>$memberId));
	$smarty->assign('codeCount', $codeCount);
	$codeTypes = Code::getGroupSize(array('memberid'=>$memberId), array('app'));
	$smarty->assign('codeTypeCount', count($codeTypes));
	
	$smarty->display('mobile/index-tpl.htm');
}
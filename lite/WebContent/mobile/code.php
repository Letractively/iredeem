<?php require_once realpath(dirname(__FILE__).'/..').'/libs/global.php';
function _doInit() {
	$memberid = memberid();
	if(!$memberid) throw new LoginNeededException();
}

function doDefault(){
	doList();
}
function doList(){
	$smarty = global_smarty();
	if(isset($_GET['size']) && !empty($_GET['size'])) {
		$size = intval($_GET['size']);
		if($size < 1) $size = 1;
	} else {
		$size = 100;
	}
	$c = array();
	$c['memberId='] = memberid();

	if(isset($_GET['app']) && $_GET['app']!=='') {
		$c['app'] = $_GET['app']; //text
		
		$data = AppData::getOne(array('memberId'=>memberid(), 'name'=>$_GET['app']));
		if($data) $smarty->assign('itunesData', $data->itunesData());
		
	}
	$count = Code::getSize($c);
	$pages = ceil($count/$size) == 0? 1 : ceil($count/$size);
	if(isset($_GET['page']) && !empty($_GET['page'])) {
		$page = intval($_GET['page']);
		if($page < 1 || $page > $pages) $page = 1;
	} else {
		$page = 1;
	}
	//
	// order
	$codeList = Code::getList($c, 'app asc, state asc, id desc', ($page - 1)*$size, $size);
	$smarty->assign('codeList', $codeList);
	$smarty->assign('count',$count);
	$smarty->assign('page', $page);
	$smarty->assign('size', $size);
	$smarty->assign('pages', $pages);
	$smarty->display('mobile/code-list.htm');
}

function doType() {
	$smarty = global_smarty();
	$c = array();
	$c['memberId='] = memberid();

	$codeCounts = Code::getGroupSize($c, array('app'));
	$appDatas = array();
	foreach($codeCounts as $app) {
		$data = AppData::getOne(array('memberId'=>memberid(), 'name'=>$app[0]->app()));
		if($data) $appDatas[$app[0]->app()] = $data->itunesData();
	}
	$smarty->assign('appDatas', $appDatas);
	$smarty->assign('codeCounts', $codeCounts);
	$smarty->display('mobile/code-type.htm');
}
function doEdit(){
	$smarty = global_smarty();
	
	$error = false;
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		if(isset($_GET['id']) && !empty($_GET['id'])) {
			$code = Code::get($_GET['id']);
			if($code && $code->memberId() != memberid()) {
				throw new AccessDenidedException();
			}
		} else {
			$code = new Code();
			$code->memberId(memberid());
		}
		if($code === NULL) {
			throw new MessageException('记录不存在');
		}
		if(isset($_POST['code_countryId'])) {
			$v = trim($_POST['code_countryId']);
			if($v === '') {
				$code->countryId( NULL );
			} else {
				$country = Country::get($v);
				if($country != NULL) {
					$code->countryId($country->id());
				} else {
					throw new MessageException('区域不存在');
				}
			}
		}
		if(isset($_POST['code_app'])) {
			$v = trim($_POST['code_app']);
			if($v !== '') {
				$code->app($v);
			} else if($v === '') {
				$code->app( NULL );
			}
		}
		if(isset($_POST['code_code'])) {
			$v = trim($_POST['code_code']);
			if($v !== '') {
				$code->code($v);
			} else if($v === '') {
				$code->code( NULL );
			}
		}
		if(isset($_POST['code_state'])) {
			$v = trim($_POST['code_state']);
			if($v !== '') {
				$code->state($v);
			} else if($v === '') {
				$code->state( NULL );
			}
		}
		if(isset($_POST['code_note'])) {
			$v = trim($_POST['code_note']);
			if($v !== '') {
				$code->note($v);
			} else if($v === '') {
				$code->note( NULL );
			}
		}
		
		if(!$error) {
			if($code->app() === NULL) {
				$code->app('未知');
			}
			$code->save();
		}
	}
	
	$smarty->assign('error', $error);

	$countryList = Country::getList();
	$smarty->assign('countryList', $countryList);
	
	$appNames = array();
	$codes = Code::getList(array('memberId'=>memberid()));
	foreach($codes as $c) {
		$appNames[] = $c->app();
	}
	$appNames = array_unique($appNames);
	$smarty->assign('appNames', $appNames);
	
	if(isset($_GET['id']) && !empty($_GET['id'])) {
		$code = Code::get($_GET['id']);
		if($code) {
			if($code->memberId() != memberid()) {
				throw new AccessDenidedException();
			}
		}
	} else {
		$code = new Code();
		if(isset($_GET['app']) && !empty($_GET['app'])) {
			$code->app($_GET['app']);
		}
	}
	$appData = AppData::getOne(array('memberId'=>memberid(), 'name'=>$code->app()));
	$smarty->assign('appData', $appData);
	$smarty->assign('code', $code);
	$smarty->display('mobile/code-edit.htm');
}

function doDelete(){
	if(isset($_GET['id']) && !empty($_GET['id'])) {
		$code = Code::get($_GET['id']);
		if($code === NULL) {
			throw new MessageException('记录不存在');
		} else if($code->memberId() != memberid()) {
			throw new AccessDenidedException();
		}
		
		$code->delete();
	}
	echo json_encode(array('result'=>true, 'message'=>'已删除'));
}

function doITunes() {
	$smarty = global_smarty();
	
	if(isset($_GET['app'])) {
		$app = $_GET['app'];
		
		$appData = null;
		if($app !== '') {
			$appData = AppData::getOne(array('memberId'=>memberid(), 'name'=>$app));
		}
		if(!$appData) {
			$appData = new AppData();
			$appData->memberId(memberid());
			$appData->name($app);
		}
		
		$error = false;
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$itunesUrl = $_POST['appdata_itunesUrl'];
			if($itunesUrl == '') {
				$error = '链接不能为空';
			} else if(!preg_match('@^http://itunes.apple.com/([^/]+/)?app/([^/]+/)id(\d+)[^\d]@i', $itunesUrl, $match)) {
				$error = '不认识这个链接~';
			}
			if(!$error) {
				$appData->itunesUrl($itunesUrl);
				
				$appid = $match[3];
				$json = file_get_contents('http://itunes.apple.com/'.$match[1].'lookup?id=' . $appid);
				if($json && ($data = json_decode($json, true)) && isset($data['resultCount']) && $data['resultCount'] == 1) {
					$itunesData = $data['results'][0];
					$appData->itunesData($itunesData);
					
					$appData->save();
				} else {
					$error = 'iTunes API调用出错';
				}
			}
		}
		$smarty->assign('error', $error);
		
		$smarty->assign('appData', $appData);
		$smarty->display('mobile/code-itunes.htm');		
	}
}

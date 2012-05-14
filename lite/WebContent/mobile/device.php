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

	if(isset($_GET['device_name']) && $_GET['device_name']!=='') {
		$c['name%'] = $_GET['device_name']; //text
	}
	if(isset($_GET['device_fma']) && $_GET['device_fma']!=='') {
		$c['fma%'] = $_GET['device_fma']; //text
	}
	if(isset($_GET['device_udid']) && $_GET['device_udid']!=='') {
		$c['udid%'] = $_GET['device_udid']; //text
	}
	if(isset($_GET['device_credits']) && $_GET['device_credits']!=='') {
		$c['credits='] = $_GET['device_credits']; //integer
	}
	$count = Device::getSize($c);
	$pages = ceil($count/$size) == 0? 1 : ceil($count/$size);
	if(isset($_GET['page']) && !empty($_GET['page'])) {
		$page = intval($_GET['page']);
		if($page < 1 || $page > $pages) $page = 1;
	} else {
		$page = 1;
	}
	//
	// order
	list($ordercolumn,$orderdir) = explode(',', isset($_GET['orderby'])? $_GET['orderby'] : 'id,desc');
	if(!in_array($ordercolumn, Device::$_FIELDS_) && !in_array($ordercolumn, Device::$_REF_FIELDS_)) $ordercolumn = 'id';
	$orderdir = $orderdir !== 'asc'? 'desc' : 'asc';
	$smarty->assign('orderby',array('column'=> $ordercolumn, 'dir'=>$orderdir));
	$deviceList = Device::getList($c, "$ordercolumn $orderdir" . ($ordercolumn=='id'?'':',id desc'), ($page - 1)*$size, $size);
	$smarty->assign('deviceList', $deviceList);
	$smarty->assign('count',$count);
	$smarty->assign('page', $page);
	$smarty->assign('size', $size);
	$smarty->assign('pages', $pages);
	$smarty->display('mobile/device-list.htm');
}
function doEdit(){
	$smarty = global_smarty();

	if(isset($_GET['id']) && !empty($_GET['id'])) {
		$device = Device::get($_GET['id']);
		if($device) {
			if($device->memberId() != memberid()) {
				throw new AccessDenidedException();
			}
		}
	} else {
		$device = new Device();
	}
	
	
	$error = false;
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		if(isset($_GET['id']) && !empty($_GET['id'])) {
			$device = Device::get($_GET['id']);
			if($device && $device->memberId() != memberid()) {
				throw new AccessDenidedException();
			}
		} else {
			$device = new Device();
			$device->memberId(memberid());
		}
		if($device === NULL) {
			throw new MessageException('记录不存在');
		}
		if(isset($_POST['device_name'])) {
			$v = trim($_POST['device_name']);
			if($v !== '') {
				$device->name($v);
			} else if($v === '') {
				$device->name(NULL);
			}
		}
		if(isset($_POST['device_state'])) {
			$v = trim($_POST['device_state']);
			if($v !== '') {
				$device->state($v);
			} else if($v === '') {
				$device->state( NULL );
			}
		}
		if(isset($_POST['device_fma'])) {
			$v = trim($_POST['device_fma']);
			if($v !== '') {
				$device->fma($v);
				
				if(!preg_match('@^https://m.freemyapps.com/welcome\?user_device_token=(([a-z0-9]|%2B|%3D)+)--[0-9a-f]{40}$@i', $v, $match)) {
					$error = '错误的FMA认证链接1';
				} else {
					$udid = substr(base64_decode(urldecode($match[1])), -40);
					$device->udid($udid);
					if(!preg_match('@[0-9a-f]{40}@', $udid)) {
						$error = '错误的FMA认证链接';
					} else {
						$sameDevice = Device::getOne(array('udid'=>$udid));
						if($sameDevice && $sameDevice->id() != $device->id()) {
							$error = '数据库已存在此设备'; 
						}
					}
				}
			} else if($v === '') {
				$device->fma( NULL );
				$error = '缺少FMA认证链接';
			}
		} else {
			$error = '缺少FMA认证链接';
		}
		if(!$error) {
			if($device->name() === NULL) {
				$names = array();
				$deviceList = Device::getList(array('memberId'=>memberid()));
				foreach($deviceList as $d) {
					$names[] = $d->name();
				}
				$i = 1;
				do {
					$name = "设备 {$i}";
					$device->name( $name );
					$i++;
				} while(in_array($name, $names));
			}
			$device->save();
		}
	}
	
	$smarty->assign('error', $error);
	
	$smarty->assign('device', $device);
	$smarty->display('mobile/device-edit.htm');
}

function doDelete(){
	if(isset($_GET['id']) && !empty($_GET['id'])) {
		$device = Device::get($_GET['id']);
		if($device === NULL) {
			throw new MessageException('记录不存在');
		} else if($device->memberId() != memberid()) {
			throw new AccessDenidedException();
		}
		
		$cookie = _TMP_ . '/cookie/' . $device->udid() . '.cookie';
		if(is_file($cookie)) {
			unlink($cookie);
		}
		$device->delete();
	}
	echo json_encode(array('result'=>true, 'message'=>'已删除'));
}

function doCheck(){
	require_once _ROOT_ . '/libs/local/bz.php';
	
	if(isset($_GET['id']) && !empty($_GET['id'])) {
		$device = Device::get($_GET['id']);
		if($device === NULL) {
			throw new MessageException('记录不存在');
		} else if($device->memberId() != memberid()) {
			throw new AccessDenidedException();
		}
		$gifts = getFMAGifts($device, $credits);
		if($credits !== false) {
			$device->creditsUpdatedTime(date('Y-m-d H:i:s'));
			$device->credits($credits);
			$device->fmaGifts($gifts);
			$device->save();
			echo json_encode(array('result'=>true, 'message'=>'已更新', 'credits'=>$credits));
			return;
		}
	}
	echo json_encode(array('result'=>false, 'message'=>'发生错误'));
}

function doGifts() {
	require_once _ROOT_ . '/libs/local/bz.php';
	
	$smarty = global_smarty();

	if(isset($_GET['id']) && !empty($_GET['id'])) {
		$device = Device::get($_GET['id']);
		if($device === NULL) {
			throw new MessageException('记录不存在');
		} else if($device->memberId() != memberid()) {
			throw new AccessDenidedException();
		}
		if($device->creditsUpdatedTime() && strtotime($device->creditsUpdatedTime()) < strtotime('-10 minutes')) {
			$gifts = getFMAGifts($device, $credits);
			if($credits !== false) {
				$device->creditsUpdatedTime(date('Y-m-d H:i:s'));
				$device->credits($credits);
				$device->fmaGifts($gifts);
				$device->save();
			}
		}
		
		$codes = array();
		if($device->fmaGifts()) {
			foreach($device->fmaGifts() as $gift) {
				if(!isset($gift[2]) || !$gift[2]) continue;
				$code = $gift[2];
				$codes[$code] = Code::getSize(array('memberId'=>memberid(), 'code'=>$code));
			}
		}

		$smarty->assign('device', $device);
		$smarty->assign('codes', $codes);
		$smarty->display('mobile/device-gifts.htm');
	}
}

function doGift2Code() {
	require_once _ROOT_ . '/libs/local/bz.php';
	
	$code = new Code();
	$code->memberId(memberid());
	$code->app($_POST['app']);
	$code->code($_POST['code']);
	if(isset($_POST['country'])) {
		$country = Country::getOne(array('name'=>$_POST['country']));
		if($country) {
			$code->countryId($country->id());
		}
	}
	$code->save();
	echo json_encode(array('result'=>true, 'message'=>'已保存兑换码'));
}

function doPayout() {
	require_once _ROOT_ . '/libs/local/bz.php';
	
	if(isset($_GET['id']) && !empty($_GET['id'])) {
		$device = Device::get($_GET['id']);
		if($device === NULL) {
			throw new MessageException('记录不存在');
		} else if($device->memberId() != memberid()) {
			throw new AccessDenidedException();
		}
		$giftId = $_POST['gift'];
		$giftcode = fmaPayout($giftId, $device);
		$code = new Code();
		$code->memberId(memberid());
		$code->app($_POST['app']);
		$code->code($giftcode);
		if(isset($_POST['country'])) {
			$country = Country::getOne(array('name'=>$_POST['country']));
			if($country) {
				$code->countryId($country->id());
			}
		}
		$code->save();
		
		$gifts = getFMAGifts($device, $credits);
		if($credits !== false) {
			$device->creditsUpdatedTime(date('Y-m-d H:i:s'));
			$device->credits($credits);
			$device->fmaGifts($gifts);
			$device->save();
		}
		
		echo json_encode(array('result'=>true, 'message'=>'已兑换 ' . $code->app(), 'credits'=>$device->credits()));
		return;
	}
	echo json_encode(array('result'=>false, 'message'=>'发生错误'));
	
}

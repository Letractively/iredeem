<?php
function rebuildSiteConfig() {
	$siteConfigFile = _DATA_ . '/siteconfig.php';
	if(is_writable(dirname($siteConfigFile))) {
	
		$siteConfigList = SiteConfig::getList();
		$content = array();
		foreach ($siteConfigList as $config) {
			$type = $config->type();
			if($type == 'boolean') {
				$content[$config->name()] = !!($config->value());
			} else if($type == 'integer') {
				$content[$config->name()] = intval($config->value());
			} else {
				$content[$config->name()] = $config->value();
			}
		}
		$content = "<?php\n return " . var_export($content, true) . ";\n";
	
		file_put_contents($siteConfigFile, $content);
	}
	
}

/**
 * 
 * @param string $url
 * @param Device $device
 */
function get_fma_page($url, Device $device = null, $redirect = 1){
	if($device === null) {
		$device = new stdClass();
		$device->fma = SiteConfigValue('fma_system_device');
		$device->udid = 'system.' . md5($device->fma);
	}
	
	$cookie = _TMP_ . '/cookie/' . $device->udid . '.cookie';
	
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
	curl_setopt($ch, CURLOPT_USERAGENT, SiteConfigValue('ios_user_agent'));
	curl_setopt($ch, CURLOPT_TIMEOUT, 15);

	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $redirect);

	if(!is_file($cookie)) {
		curl_setopt($ch, CURLOPT_URL, $device->fma);
		$ret = curl_exec($ch);
		if(!$ret) return false;
	}
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
	
	$ret = curl_exec($ch);
	curl_close($ch);
	return $ret;
}


function getFMACredits($device, $html = null) {
	if($html === null) {
		$str = get_fma_page('http://m.freemyapps.com/promotions', $device);
	} else {
		$str = $html;
	}
	if($str && preg_match('@<div id="header-credit-count">(\d+)</div>@', $str, $match)) {
		return intval($match[1]);
	} else {
		return false;
	}
}

function getFMAPromotions($device = null, &$credits = null) {
	$promotions = array();
	
	$str = get_fma_page('http://m.freemyapps.com/promotions', $device);
	$strtr = strtr($str, "\r\n", "  ");
	if($str && preg_match_all('@<a href="http://m.freemyapps.com/verify_download/\d+">.+?<div class="app-text app-title">([^<]+)</div>.+?<div class="title"><span class="chunky">\+</span>(\d+)</div>@', $strtr, $matches, PREG_SET_ORDER)) {
		foreach($matches as $match) {
			$name = htmlspecialchars_decode(trim($match[1]));
			$promotions[$name] = array(intval($match[2]));
		}
	}
	if($str) {
		$credits = getFMACredits($device, $str);
	} else {
		$credits = false;
	}
	return $promotions;
}

function getFMAGifts($device = null, &$credits = null) {
	$gifts = array();

	$str = get_fma_page('http://m.freemyapps.com/payout', $device);
	$strtr = strtr($str, "\r\n", "  ");
	if($str && preg_match_all('@<a href="/select_gift\?id=(\d+)" .+?<div class="app-title">([^<]*)</div>.+?<div class="title">(\d+)</div>@', $strtr, $matches, PREG_SET_ORDER)) {
		foreach($matches as $match) {
			$name = htmlspecialchars_decode(trim($match[2]));
			$gifts[$name] = array(intval($match[1]), intval($match[3]));
		}
	}
	if($str && preg_match_all('@<a href="/promotions" .+?<div class="app-title">([^<]*)</div>.+?<div class="title">(\d+)</div>@', $strtr, $matches, PREG_SET_ORDER)) {
		foreach($matches as $match) {
			$name = htmlspecialchars_decode(trim($match[1]));
			$gifts[$name] = array(0, intval($match[2]), null);
		}
	}
	if($str && preg_match_all('@<a href="(https://[^"]+)".+?<div class="app-text app-title app-text-downloaded">([^<]*)</div>@', $strtr, $matches, PREG_SET_ORDER)) {
		foreach($matches as $match) {
			parse_str(parse_url(htmlspecialchars_decode($match[1]), PHP_URL_QUERY), $params);
			$code = $params['code'];
			$name = htmlspecialchars_decode(trim($match[2]));
			$gifts[$name] = array(0, null, $code);
		}
	}
	if($str) {
		$credits = getFMACredits($device, $str);
	} else {
		$credits = false;
	}
	return $gifts;
}

function fmaPayout($deviceGiftId, $device) {
	$ret = get_fma_page('http://m.freemyapps.com/select_gift?id=' . $deviceGiftId, $device, 0);
// 	$ret = 'HTTP/1.1 302 Found
// Cache-Control: no-cache, private
// Content-Type: text/html; charset=utf-8
// Date: Mon, 07 May 2012 21:19:24 GMT
// Location: http://m.freemyapps.com/promotions?selected_gift_code=https%3A%2F%2Fbuy.itunes.apple.com%2FWebObjects%2FMZFinance.woa%2Fwa%2FfreeProductCodeWizard%3Fcode%3DHH4YHH7KFYMP
// Server: nginx/1.0.8 + Phusion Passenger 3.0.11 (mod_rails/mod_rack)
// Set-Cookie: _device_enrollment_session=BAh7ByIQX2NzcmZfdG9rZW4iMTNlaHJOM25heEFRcHRNS0l6MCt4bU9SRms5bXZBWXhVb0JSVEJOZ3VnQVk9Ig9zZXNzaW9uX2lkIiVjNjZjZDJlYTNmMTVjOWQzZTZmMzBhZWYxODI0MzE3ZA%3D%3D--e7af74b229f116f315228f8994bc3ba834068ded; path=/; HttpOnly
// Set-Cookie: user_device_token=BAgiLWNhMDY1NTdmY2E1YTU2YjMwM2Q5OWEzZjYwMTY2NTZjMDM1YzY2Y2U%3D--baf741fc6da056bc6ebb7da995579da3a9e19c62; path=/; expires=Tue, 07-May-2013 21:19:23 GMT
// Set-Cookie: custom_events=%5B%5B%22Download%22%2C%22Gift+App%22%5D%5D; path=/
// Status: 302
// X-Powered-By: Phusion Passenger (mod_rails/mod_rack) 3.0.2
// X-Rack-Cache: miss
// X-Request-Id: 65d7d907c3d6d1d8d1429c244aef865a
// X-Runtime: 0.380307
// X-UA-Compatible: IE=Edge,chrome=1
// Content-Length: 234
// Proxy-Connection: Keep-Alive

// <html><body>You are being <a href="http://m.freemyapps.com/promotions?selected_gift_code=https%3A%2F%2Fbuy.itunes.apple.com%2FWebObjects%2FMZFinance.woa%2Fwa%2FfreeProductCodeWizard%3Fcode%3DHH4YHH7KFYMP">redirected</a>.</body></html>';
	
	if($ret && preg_match('@^Location: (http://m.freemyapps.com/promotions\?selected_gift_code=.*?)$@m', $ret, $match)) {
		$url = trim($match[1]);
		parse_str(parse_url($url, PHP_URL_QUERY), $params);
		$selected_gift_code = $params['selected_gift_code'];
		parse_str(parse_url($selected_gift_code, PHP_URL_QUERY), $params2);
		$giftcode = $params2['code'];
		return $giftcode;
	}
	return false;
	
}
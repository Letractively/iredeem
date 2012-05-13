<?php

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
			$gifts[$name] = array(0, intval($match[2]));
		}
	}
	if($str) {
		$credits = getFMACredits($device, $str);
	} else {
		$credits = false;
	}
	return $gifts;
}

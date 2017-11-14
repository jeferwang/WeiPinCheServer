<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

/**
 * 数组转换为带cdata的xml
 * @param $params
 * @return bool|string
 */
function arr_get($arr, $key, $default = null)
{
	if (key_exists($key, $arr)) {
		return $arr[$key];
	}
	return $default;
}

function array_to_cdata_xml($params)
{
	if (!is_array($params) || count($params) <= 0) {
		return false;
	}
	$xml = "<xml>";
	foreach ($params as $key => $val) {
		if (is_numeric($val)) {
			$xml .= "<" . $key . ">" . $val . "</" . $key . ">";
		} else {
			$xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
		}
	}
	$xml .= "</xml>";
	return $xml;
}

function array_to_string($params)
{
	$string = '';
	if (!empty($params)) {
		$array = [];
		foreach ($params as $key => $value) {
			$array[] = $key . '=' . $value;
		}
		$string = implode("&", $array);
	}
	return $string;
}

function make_sign($params, $key)
{
	ksort($params);
	$string = array_to_string($params);  //参数进行拼接key=value&k=v
	//签名步骤二：在string后加入KEY
	$string = $string . "&key=" . $key;
	//签名步骤三：MD5加密
	$string = md5($string);
	//签名步骤四：所有字符转为大写
	$result = strtoupper($string);
	return $result;
}

function postXmlCurl($url, $xml, $useCert = false, $second = 5)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_TIMEOUT, $second);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	if ($useCert == true) {
		curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
		curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
	}
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
	$data = curl_exec($ch);
	if ($data) {
		curl_close($ch);
		return $data;
	} else {
		$error = curl_errno($ch);
		curl_close($ch);
		return false;
	}
}

function xml_to_array($xml)
{
	$res = simplexml_load_string($xml, 'SimpleXmlElement', LIBXML_NOCDATA);
	return json_decode(json_encode($res), true);
}
function confirm_sign ($data,$key,$sign_key='sign') {
	$sign=arr_get($data, $sign_key,false);
	if (!$sign){
		return false;
	}
	$data2=$data;
	unset($data2['sign']);
	$sign2=make_sign($data2, $key);
	return $sign==$sign2;
}
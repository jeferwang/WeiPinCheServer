<?php

namespace app\api\controller;


use app\common\model\Setting;
use GuzzleHttp\Client;
use think\Controller;
use think\Validate;

class Applet extends Controller
{
	/**
	 * 根据Code去请求openid
	 */
	public function getOpenIdByCode()
	{
		$data = request()->param();
		$validate = new Validate([
			'applet_id' => 'require',
			'js_code' => 'require'
		]);
		if (!$validate->check($data)) {
			return [
				'status' => 'error',
				'msg' => $validate->getError()
			];
		}
		$setting = (new Setting())->getSetting($data['applet_id']);
		$client = new Client();
		$req = $client->request('GET', 'https://api.weixin.qq.com/sns/jscode2session', [
			'query' => [
				'appid' => $setting->appid,
				'secret' => $setting->appsecret,
				'grant_type' => 'authorization_code',
				'js_code' => $data['js_code']
			],
			'verify' => false
		]);
		$body = $req->getBody();
		return [
			'status' => 'success',
			'msg' => '请求成功',
			'data' => json_decode($body),
		];
	}
}
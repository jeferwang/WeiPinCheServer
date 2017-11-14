<?php

namespace app\api\controller;


use think\Controller;
use think\Validate;
use app\common\model\User as UserModel;

class User extends Controller
{
	/**
	 * 用户登录(新用户和老用户)
	 */
	public function setUser()
	{
		$data = request()->param();
		$validate = new Validate([
			'openid' => 'require',
			'applet_id' => 'require'
		]);
		if (!$validate->check($data)) {
			return [
				'status' => 'error',
				'msg' => $validate->getError(),
			];
		}
		$find = model('user')
			->where('applet_id', $data['applet_id'])
			->where('openid', $data['openid'])->find();
		if ($find) {
			//进行个人信息的修改
			$attrs = ['name', 'sex', 'phone', 'addr', 'summary', 'user_img'];
			foreach ($attrs as $v) {
				if (arr_get($data, $v)) {
					$find->$v = arr_get($data, $v);
				}
			}
			$find->save();
			return [
				'status' => 'success',
				'msg' => '请求成功',
				'data' => $find,
				'params' => $data
			];
		} else {
			$user = new UserModel();
			$attrs = ['applet_id', 'openid', 'name', 'sex', 'phone', 'addr', 'summary', 'user_img'];
			foreach ($attrs as $v) {
				if (arr_get($data, $v)) {
					$user->$v = arr_get($data, $v);
				}
			}
			$user->save();
			return [
				'status' => 'success',
				'msg' => '请求成功',
				'data' => $user,
				'params' => $data
			];
		}
	}
	
}
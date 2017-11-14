<?php

namespace app\admin\controller;


use app\admin\base\BaseController;

class User extends BaseController
{
	/*
	 * 用户列表
	 */
	public function index()
	{
		$users = db('user')->where('applet_id',$this->applet_id)->paginate(10);
		$this->assign('users', $users);
		$user_count = db('user')->where('applet_id',$this->applet_id)->count();
		$this->assign('user_count', $user_count);
		return $this->fetch();
	}
	
	public function orders()
	{
		$user_id = request()->param('user_id');
		if ($user_id == '') {
			return 'user_id参数不存在';
		}
		$user = model('user')->where('applet_id',$this->applet_id)->where('id',$user_id)->find();
		if (!$user) {
			return '用户不存在';
		}
		$this->assign('user', $user);
		return $this->fetch();
	}
}
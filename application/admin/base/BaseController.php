<?php

namespace app\admin\base;

use think\Controller;

class BaseController extends Controller
{
	protected $applet_id;
	
	public function _initialize()
	{
		parent::_initialize();
		// $applet_id = session('applet_id');
    $applet_id = 666;
		if (!$applet_id) {
			exit();
		}
		$this->applet_id = $applet_id;
		define('APPLET_ID',$applet_id);
	}
}
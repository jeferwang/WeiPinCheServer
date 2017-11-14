<?php

namespace app\common\model;


use think\Model;
use \app\common\model\Setting as SettingModel;

class Setting extends Model
{
	public function getSetting($applet_id)
	{
		$setting=model('setting')->where('applet_id',$applet_id)->find();
		if (!$setting){
			$setting=new SettingModel();
			$setting->applet_id=$applet_id;
			$setting->save();
		}
		$setting=model('setting')->where('applet_id',$applet_id)->find();
		return $setting;
	}
}
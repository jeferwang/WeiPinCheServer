<?php

namespace app\admin\controller;


use app\admin\base\BaseController;
use think\Validate;

class Setting extends BaseController
{
  public function index()
  {
    $setting = model('setting')->getSetting($this->applet_id);
    if (request()->method() == 'GET') {
      $this->assign('setting', $setting);
      return $this->fetch();
    } else {
      $data = request()->param();
      $validate = new Validate([
        'appid' => 'require',
        'appsecret' => 'require',
      ]);
      if (!$validate->check($data)) {
        return [
          'status' => 'error',
          'msg' => $validate->getError()
        ];
      }
      $setting->appid = $data['appid'];
      $setting->appsecret = $data['appsecret'];
      $setting->save();
      return [
        'status' => 'success',
        'msg' => '保存成功'
      ];
    }
  }
}
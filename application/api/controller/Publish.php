<?php

namespace app\api\controller;


use think\Controller;
use think\Exception;
use think\Validate;

class Publish extends Controller
{
  protected function _initialize()
  {
    parent::_initialize();
    $applet_id = input('applet_id');
    if (!$applet_id) {
      exit();
    }
  }
  
  public function add()
  {
    $vali = new Validate([
      'applet_id' => 'require',
      'start_place' => 'require',
      'end_place' => 'require',
      'gender' => 'require',
      'people_num' => 'require',
      'phone' => 'require',
      'publish_type' => 'require',
      'user_name' => 'require',
    ]);
    if (!$vali->check(input())) {
      return json([
        'status' => 'error',
        'msg' => '请完整填写表单'
      ]);
    }
    $openid = input('openid');
    $user = \app\common\model\User::getUser(input('applet_id'), $openid);
    $data = [
      'user_id' => $user->id,
      'applet_id' => input('applet_id'),
      'start_place' => input('start_place'),
      'end_place' => input('end_place'),
      'gender' => (int)input('gender'),
      'people_num' => (int)input('people_num'),
      'phone' => input('phone'),
      'publish_type' => input('publish_type'),
      'remark' => input('remark'),
      'user_name' => input('user_name'),
      'publish_time'=>time(),
    ];
    $start_time = sprintf('%s %s:00', input('date'), input('time'));
    try {
      $start_time = strtotime($start_time);
    } catch (Exception $e) {
      return json([
        'status' => 'error',
        'msg' => '时间格式错误'
      ]);
    }
    $data['start_time'] = $start_time;
    //保存
    $publish = new \app\common\model\Publish($data);
    $publish->save();
    return json([
      'status' => 'success',
      'msg' => '发布成功',
      'data' => $data
    ]);
  }
}
<?php

namespace app\common\model;


use think\Model;

class User extends Model
{
  protected $dateFormat = false;
  
  public static function getUser($applet_id, $openid)
  {
    $user = model('User')
      ->where('applet_id', $applet_id)
      ->where('openid', $openid)
      ->find();
    if (!$user) {
      $user = new \app\common\model\User();
      $user->openid = $openid;
      $user->save();
    }
    return $user;
  }
}
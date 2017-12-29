<?php
/**
 * Created by PhpStorm.
 * User: qiangshizhi
 * Date: 2017/12/14
 * Time: 下午5:51
 */

namespace app\index\service;
use app\admin\model\User;

class UserService
{
    public static function getUserByQQ($qq){
        $user=User::get(["qq"=>$qq]);
        return $user;
    }
    public static function getUserById($userid){
        $user=User::get($userid);
        return $user;
    }
    public static function addUser($u){
        $user=new User();
        $user->username=$u['username'];
        $user->qq=$u['qq'];
        $user->email=$u['email'];
        $user->pic=$u['pic'];
        $user->save();
        return $user;
    }
}
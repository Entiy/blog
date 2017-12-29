<?php
/**
 * Created by PhpStorm.
 * User: qiangshizhi
 * Date: 2017/12/19
 * Time: 下午5:16
 */

namespace app\admin\service;
use app\admin\model\Admin;

class AdminService
{
    public static function getAdminInfo(){
        return Admin::get(1);
    }
    public static function modAdminInfo($info){
        $admin=Admin::get(1);
        $admin->nick=$info["nick"];
        $admin->address=$info["address"];
        $admin->slogan=$info["slogan"];
        $admin->weibo=$info["weibo"];
        $admin->pwd=$info["pwd"];
        $admin->save();
        return "success";
    }

    public static function addAdmin($info){
        Admin::create($info);
        return "success";
    }

    public static function getAdminByID($adminid){
        return Admin::get($adminid);
    }
}
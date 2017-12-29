<?php
/**
 * Created by PhpStorm.
 * User: qiangshizhi
 * Date: 2017/12/21
 * Time: 下午6:06
 */

namespace app\helper;


use think\Session;

class AdminController extends BaseController
{

    function _initialize()
    {
        $allow_actions = explode(",","admin/admin/index,admin/admin/logindeal");
        $curr_action =request()->module()."/".strtolower(request()->controller())."/".request()->action();
        if (!in_array($curr_action,$allow_actions)){
            $szqer=Session::get("admin");
            if ($szqer!=""){
                if ($szqer!="szqer"){
                    header('location:'.url('admin/admin/index'));
                    exit;
                }

            }else{
                header('location:'.url('admin/admin/index'));
                exit;
            }
        }

    }

}
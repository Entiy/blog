<?php
/**
 * Created by PhpStorm.
 * User: qiangshizhi
 * Date: 2017/12/21
 * Time: 下午3:10
 */

namespace app\admin\service;
use app\admin\model\Pv;

class PvService
{
    public static function getPv(){
        $pv=Pv::get(1);
        return $pv;
    }
    public static function updatePv(){
        $pv=Pv::get(1);
        $pv->pv=$pv->pv+1;
        $pv->save();
    }
}
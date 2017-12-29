<?php
/**
 * Created by PhpStorm.
 * User: qiangshizhi
 * Date: 2017/12/14
 * Time: 下午9:27
 */

namespace app\helper;


class UAParser
{
    public static function getBrowser($browser){
        $list=["chrome","ie","baidu","firefox","opera","liebao","safari","sogou","uc","360","aoyou","qq"];
        foreach ($list as $e){
            if(strpos($browser,$e)!==false){
                return $e;
            }
        }
        return $browser;
    }

    public static function getOS($os)
    {
        $list = ["windows", "mac", "linux","ios","android"];
        foreach ($list as $e) {
            if (strpos($os, $e) !==false) {
                return $e;
            }
        }
        return $os;
    }
}
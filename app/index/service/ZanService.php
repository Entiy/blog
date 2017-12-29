<?php
/**
 * Created by PhpStorm.
 * User: qiangshizhi
 * Date: 2017/12/24
 * Time: 上午9:47
 */

namespace app\index\service;
use app\admin\model\Zan;

class ZanService
{
    public static function addZan($zaninfo){
        Zan::create($zaninfo);
        return "success";
    }
    public static function getZanCountByPostId($postid){
        $good=count(Zan::all([
            "tid"=>$postid,
            "type"=>1,
            "status"=>1
        ]));
        $bad=count(Zan::all([
            "tid"=>$postid,
            "type"=>1,
            "status"=>0
        ]));
        return [
            "good"=>$good,
            "bad"=>$bad
        ];
    }
    public static function getZanByUseridForOnePost($postid,$userid){
        $zan=Zan::get([
            "userid"=>$userid,
            "tid"=>$postid
        ]);
        return $zan;
    }
    public static function getZanByIp($ip){
        $zan=Zan::get([
            "ip"=>$ip
        ]);
        return $zan;
    }
    public static function updateStatusByUseridForOnePost($postid,$userid,$status){
        $zan=Zan::get([
            "userid"=>$userid,
            "tid"=>$postid
        ]);
        $zan->status=$status;
        $zan->save();
        return $zan;
    }
    public static function getAllCount(){
        $good=Zan::all([
            "status"=>1
        ]);
        $bad=Zan::all([
            "status"=>0
        ]);
        return [
            "good"=>count($good),
            "bad"=>count($bad)
        ];
    }
}
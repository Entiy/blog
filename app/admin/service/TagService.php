<?php
/**
 * Created by PhpStorm.
 * User: qiangshizhi
 * Date: 2017/12/17
 * Time: 下午3:54
 */

namespace app\admin\service;
use app\admin\model\Tag;

class TagService
{
    public static function getAllTag(){
        return Tag::all();
    }

    public static function addTag($tag){
        Tag::create([
            "tagname"=>$tag
        ]);
        return "success";
    }
    public static function updateTagByID($tagid,$tagname){
        Tag::update([
            "id"=>$tagid,
            "tagname"=>$tagname
        ]);
        return "success";
    }

    public static function getTagByPage($num){
        $list=new Tag();
        $list=$list->paginate($num);
        return $list;
    }

    public static function TagInfo(){
        $list=Tag::all();
        $tag=array();
        foreach ($list as $t){
            $tagname=$t->tagname;
            $list=PostService::getPostCountByTag($tagname);
            $taginfo=[
                "tagname"=>$tagname,
                "tagcount"=>count($list)
            ];
            array_push($tag,$taginfo);
        }
        return $tag;
    }
}
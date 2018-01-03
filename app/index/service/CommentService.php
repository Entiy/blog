<?php
/**
 * Created by PhpStorm.
 * User: qiangshizhi
 * Date: 2017/12/14
 * Time: 下午5:25
 */

namespace app\index\service;
use app\admin\model\Comment;
use app\admin\model\Post;
use think\Db;

class CommentService
{

    public static function addComment($commet){
        Comment::create($commet);
        return "success";
    }
    public static function getCommentList($tid,$type){
        $list=new Comment();
        $list=$list->where([
            "type"=>$type,
            "tid"=>$tid,
            "dflag"=>0
        ])->order('datetime','desc')->select();
        return $list;
    }

    public static function getCommentListByType($type,$num){
        $list=new Comment();
        $list=$list->where([
            "type"=>$type,
        ])->order('datetime','desc')->paginate($num);
        return $list;
    }

    public static function getAllCommentByDatetime()
    {
        $list=Comment::all(function ($query){
            $query->order("datetime");
        });
        return $list;
    }

    public static function getAllCommentCount(){
        $comments=new Comment();
        return $comments->count();
    }
    public static function getCommentCountByType($type){
        $list=new Comment();
        $count=$list->where([
            "type"=>$type
        ])->count();
        return $count;
    }

    public static function getCommentById($id){
        $post=Comment::get([
            "id"=>$id,
        ]);
        return $post;
    }
    public static function disCommentById($id){
        $comment=Comment::get($id);
        $comment->dflag=1;
        $comment->save();
        return "success";
    }
    public static function ableCommentById($id){
        $comment=Comment::get($id);
        $comment->dflag=0;
        $comment->save();
        return "success";
    }
    public static function getCommentListByNew($type){
        $list=Db::table("comment")->where(["type"=>$type])->order("datetime","desc")->limit(10)->select();
        return $list;
    }
    public static function getCommentCountByPostId($postid){
        $count=Db::table("comment")->where("tid",$postid)->count();
        return $count;
    }
}
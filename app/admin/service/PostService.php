<?php
/**
 * Created by PhpStorm.
 * User: qiangshizhi
 * Date: 2017/12/16
 * Time: 下午3:19
 */

namespace app\admin\service;
use app\admin\model\Comment;
use app\admin\model\Post;
use app\admin\model\Zan;
use think\Db;

class PostService
{
  public static function addPost($post){
      Post::create($post);
      return "success";
  }
  public static function getPostList(){
      $list=new Post();
      $list=$list->where("dflag","0")->order("datetime","desc")->select();
      return $list;
  }
  public static function getPostByID($postid){
      return Post::get([
          "id"=>$postid,
          ]);
  }
    public static function getPostByIDAndDflag($postid,$dflag){
        return Post::get([
            "id"=>$postid,
            "dflag"=>$dflag
        ]);
    }

  public static function conutPlus($postid){
        $post=Post::get($postid);
        $post->count=$post->count+1;
        $post->save();
  }
  public static function getPostsByTag(){
        $list=Post::all(function ($query){
            $query->group("tag")->order("datetime","desc")->select();
        });
        return $list;
  }
    public static function updateByCSwitch($switch,$postid){
        Post::update([
            "id"=>$postid,
            "cswitch"=>$switch
        ]);
        return "success";
    }
    public static function updateByFSwitch($switch,$postid){
        Post::update([
            "id"=>$postid,
            "fswitch"=>$switch
        ]);
        return "success";
    }
    public static function updateByMSwitch($switch,$postid){
        Post::update([
            "id"=>$postid,
            "mswitch"=>$switch
        ]);
        return "success";
    }
    public static function getPostCount(){
      $posts=new Post();
      return $posts->count();
    }

    public static function getReadCount(){
        $posts=new Post();
        $readCount=$posts->sum("count");
        return $readCount;
    }
    public static function getPostCountByTag($tagname){
        $list=new Post();
        $list=$list->where([
            "tag"=>$tagname,
            "dflag"=>0
        ])->select();
        return $list;
    }
    public static function getPostByReadCount(){
      $list=new Post();
      $list=$list->where(["dflag"=>0])->order("count","desc")->limit(10)->select();
      return $list;
    }

    public static function getPostListByTag($tag,$num){
      $list=new Post();
      $list=$list->where(["tag"=>$tag,"dflag"=>0])->order("datetime","desc")->paginate($num,false,['query'=>['tag'=>$tag]]);
      return $list;
    }
    public static function disablePostById($postid){
        $post=Post::get($postid);
        $post->dflag=1;
        $post->save();
        return "success";
    }
    public static function ablePostById($postid){
        $post=Post::get($postid);
        $post->dflag=0;
        $post->save();
        return "success";
    }
    public static function updatePostById($postinfo){
        $post=Post::get($postinfo["id"]);
        $post->title=$postinfo["title"];
        $post->author=$postinfo["author"];
        $post->content=$postinfo["content"];
        $post->rawcontent=$postinfo["rawcontent"];
        $post->datetime=$postinfo["datetime"];
        $post->tag=$postinfo["tag"];
        $post->save();
        return "success";
    }

    public static function getPostsOrderByCount(){
        $list=new Post();
        $list=$list->order("count","desc")->limit(10)->select();
        return $list;
    }
    public static function getPostsOrderByGood(){
        $list=Db::table("zan")->field(['tid','count(tid) as gc'])->where(["status"=>1])->group("tid")->order("gc","desc")->limit(10)->select();
        return $list;
    }
    public static function getPostsOrderByCommentCount(){
        $list=Db::table("comment")->field(['tid','count(tid) as cc'])->where(["type"=>1])->group("tid")->order("cc","desc")->limit(10)->select();
        return $list;
    }
    public static function getPostsByPage($num){
        $listpages=Db::table("post")->where(["dflag"=>0])->paginate($num);
        return $listpages;
    }

}
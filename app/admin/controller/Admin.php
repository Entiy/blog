<?php
/**
 * Created by PhpStorm.
 * User: qiangshizhi
 * Date: 2017/12/11
 * Time: 下午10:31
 */
namespace app\admin\controller;

use app\admin\service\AdminService;
use app\helper\AdminController;
use app\index\service\CommentService;
use app\helper\Parser;
use app\index\service\ZanService;
use think\Request;
use app\admin\service\PostService;
use app\admin\service\TagService;
use think\Session;

class Admin extends  AdminController{


    public function dstp(){
        return $this->total();
    }
    public function mark(){
        return $this->fetch("mark");
    }
    public function parserM($field){
        $parser = new Parser();
        $html = $parser->makeHtml($field);
        $this->assign("content",$html);
        return $this->fetch("test");
    }
    public function write(){
        $tags=TagService::getAllTag();
        $this->assign("tags",$tags);
        return $this->fetch("write");
    }
    public function total(){
        $postcount=PostService::getPostCount();
        $commentcount=CommentService::getCommentCountByType(1);
        $readcount=PostService::getReadCount();
        $tagcount=TagService::getAllTag();
        $bbscount=CommentService::getCommentCountByType(2);
        $zcount=ZanService::getAllCount();
        $rlist=PostService::getPostsOrderByCount();
        $glist=PostService::getPostsOrderByGood();
        for ($i=0; $i<count($glist); $i++) {
            $p=PostService::getPostByID( $glist[$i]['tid']);
            $glist[$i]['title']=$p->title;
        }
        $clist=PostService::getPostsOrderByCommentCount();
        for ($i=0; $i<count($clist); $i++) {
            $p=PostService::getPostByID($clist[$i]['tid']);
            $clist[$i]['title']=$p->title;
        }

        $nclist=CommentService::getCommentListByNew(1);
        for ($i=0; $i<count($nclist); $i++) {
            $p=PostService::getPostByID( $nclist[$i]['tid']);
            $nclist[$i]['title']=$p->title;
        }
        $bclist=CommentService::getCommentListByNew(2);
        $this->assign("nclist",$nclist);
        $this->assign("bclist",$bclist);
        $this->assign("rlist",$rlist);
        $this->assign("glist",$glist);
        $this->assign("clist",$clist);
        $this->assign("postcount",$postcount);
        $this->assign("readcount",$readcount);
        $this->assign("commentcount",$commentcount);
        $this->assign("zcount",$zcount);
        $this->assign("tagcount",$tagcount);
        $this->assign("bbscount",$bbscount);
        return $this->fetch("total");
    }
    public function postManager(){
        $list=PostService::getPostsByTag();
        $this->assign("list",$list);
        return $this->fetch("postmanager");
    }
    public function  commentManager(){
        $list=CommentService::getCommentListByType(1);
        $this->assign("list",$list);
        return $this->fetch("commentmanager");
    }
    public function commentOnOrOff(){
        $list=PostService::getPostList();
        $this->assign("list",$list);
        return $this->fetch("commentonoroff");
    }
    public function commentOnOrOffOp(Request $request){
        $postid=$request->post("postid");
        $switch=$request->post("cswitch");
        PostService::updateByCSwitch($switch,$postid);
        return "1009";
    }
    public function shareOnOrOff(){
        $list=PostService::getPostList();
        $this->assign("list",$list);
        return $this->fetch("shareonoroff");
    }
    public function shareOnOrOffOp(Request $request){
        $postid=$request->post("postid");
        $switch=$request->post("fswitch");
        PostService::updateByFSwitch($switch,$postid);
        return "1010";
    }
    public function moneyOnOrOff(){
        $list=PostService::getPostList();
        $this->assign("list",$list);
        return $this->fetch("moneyonoroff");
    }
    public function moneyOnOrOffOp(Request $request){
        $postid=$request->post("postid");
        $switch=$request->post("mswitch");
        PostService::updateByMSwitch($switch,$postid);
        return "1011";
    }
    public function navManager(){
        return $this->fetch("navmanager");
    }
    public function tagManager(){
        $tags=TagService::getAllTag();
        $this->assign("tags",$tags);
        return $this->fetch("tagmanager");
    }

    public function bbsManager(){
        $list=CommentService::getCommentListByType(2);
        $this->assign("list",$list);
        return $this->fetch("bbsmanager");
    }

    public function writePost(Request $request){
        $author=$request->post("author");
        $title=$request->post("title");
        $content=$request->post("content");
        $content=htmlentities($content);
        $rawcontent=$request->post("rawcontent");
        $rawcontent=htmlentities($rawcontent);
        $datetime=$request->post("datetime");
        $tag=$request->post("tag");
        $post=[
            "author"=>$author,
            "title"=>$title,
            "content"=>$content,
            "rawcontent"=>$rawcontent,
            "datetime"=>$datetime,
            "tag"=>$tag
        ];
        $res=PostService::addPost($post);
        return $res;
    }

    public function adminInfo(){
        $admininfo=AdminService::getAdminByID(1);
        $this->assign("admin",$admininfo);
        return $this->fetch("admininfo");
    }

    public function addTag(Request $request){
        $tag=$request->post("tag");
        $res=TagService::addTag($tag);
        return $res;
    }
    public function modTag(Request $request){
        $tagid=$request->post("id");
        $tagname=$request->post("tag");
        $res=TagService::updateTagByID($tagid,$tagname);
        return $res;
    }

    public function index(){
        return  $this->fetch("login");
    }

    public function loginDeal(Request $request){
        $pwd=$request->post("pwd");
        $admin=AdminService::getAdminByID(1);
        if ($pwd==$admin->pwd){
            Session::set("admin","szqer");
            return  "1013";
        }else{
            return "1012";
        }
    }

    public function disablepost(Request $request){
        $postid=$request->get("postid");
        PostService::disablePostById($postid);
        return "1000";
    }

    public function ablepost(Request $request){
        $postid=$request->get("postid");
        PostService::ablePostById($postid);
        return "1006";
    }

    public function editPost(Request $request){
        $postid=$request->get("postid");
        $post=PostService::getPostByID($postid);
        if ($post!==null){
            $tags=TagService::getAllTag();
            $this->assign("tags",$tags);
            $this->assign("post",$post);
            return $this->fetch("editpost");
        }else{
            return "1000";
        }
    }
    public function editPostDeal(Request $request){
        $postid=$request->post("id");
        $author=$request->post("author");
        $title=$request->post("title");
        $content=$request->post("content");
        $content=htmlentities($content);
        $rawcontent=$request->post("rawcontent");
        $rawcontent=htmlentities($rawcontent);
        $datetime=$request->post("datetime");
        $tag=$request->post("tag");
        $postinfo=[
            "id"=>$postid,
            "author"=>$author,
            "title"=>$title,
            "content"=>$content,
            "rawcontent"=>$rawcontent,
            "datetime"=>$datetime,
            "tag"=>$tag
        ];
        $res=PostService::updatePostById($postinfo);
        return $res;
    }

    public function vieComment(Request $request){
        $cid=$request->get("cid");
        $comment=CommentService::getCommentById($cid);
        $comment=json_encode($comment->toArray());
        return json($comment);
    }

    public function disableComment(Request $request){
        $cid=$request->get("cid");
        CommentService::disCommentById($cid);
        return "1004";
    }

    public function ableComment(Request $request){
        $cid=$request->get("cid");
        CommentService::ableCommentById($cid);
        return "1005";
    }

    public function viewPost(Request $request){
        $postid=$request->get("postid");
        if ($postid!==null){
            $post=PostService::getPostByID($postid);
            if ($post!==null){
                PostService::conutPlus($postid);
                $post->content=html_entity_decode($post->content);
                $post->rawcontent=html_entity_decode($post->rawcontent);
                $this->assign("post",$post);
                $list=CommentService::getCommentList($postid,1);
                $this->assign("list",$list);
                $userid=Session::get("userid");
                $zcount=ZanService::getZanCountByPostId($postid);
                $zcount["status"]=2;
                if ($userid!==null){
                    $zan=ZanService::getZanByUseridForOnePost($postid,$userid);
                    if($zan!=null){
                        $zcount["status"]=$zan->status;
                    }
                }
                $this->assign("azan",$zcount);
                return $this->fetch("viewpost");
            }

        }else{
            return "不存在此文章";
        }
    }

    public function uploadFace(Request $request){
        $file = $request->file('face');
        $weixin = $request->file('weixin');
        $qq = $request->file('qq');
        $nick=$request->post("nick");
        $address=$request->post("address");
        $slogan=$request->post("slogan");
        $weibo=$request->post("weibo");
        $pwd=$request->post("pwd");
        $ainfo=[
            "nick"=>$nick,
            "address"=>$address,
            "slogan"=>$slogan,
            "weibo"=>$weibo,
            "pwd"=>$pwd
        ];
        if($file&&$weixin&&$qq){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'upload',"touxiang");
            $info1 = $weixin->move(ROOT_PATH . 'public' . DS . 'upload',"weixin");
            $info2 = $qq->move(ROOT_PATH . 'public' . DS . 'upload',"qq");
            if($info&&$info1&&$info2){
                AdminService::modAdminInfo($ainfo);
                return "1007";
            }else{
                return "1008";
            }
        }
    }

    public function vieBBS(Request $request){
        $cid=$request->get("cid");
        $comment=CommentService::getCommentById($cid);
        $comment=json_encode($comment->toArray());
        return json($comment);
    }

    public function disableBBS(Request $request){
        $cid=$request->get("cid");
        CommentService::disCommentById($cid);
        return "1004";
    }
}
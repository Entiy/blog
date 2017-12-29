<?php
namespace app\index\controller;

use app\admin\service\AdminService;
use app\admin\service\TagService;
use app\helper\Mygettime;
use app\helper\TimeFormat;
use app\helper\UAParser;
use app\index\service\ZanService;
use think\Request;
use app\index\service\CommentService;
use app\index\service\UserService;
use think\Session;
use app\admin\service\PostService;
use app\admin\service\PvService;
use app\helper\BaseController;
use location\IpLocation;
class Index extends BaseController
{

    public function index(){
        $list=PostService::getPostList();
        $this->assign("list",$list);
        $admininfo=AdminService::getAdminInfo();
        $pv=PvService::getPv();
        $this->assign("admin",$admininfo);
        $this->assign("pv",$pv);
        $tag=TagService::TagInfo();
        $this->assign("tag",$tag);
        $readcountlist=PostService::getPostByReadCount();
        $this->assign("readcountlist",$readcountlist);
        return $this->fetch("index");

    }
    public function post(Request $request){
        $postid=$request->get("postid");
        if ($postid!==null){
            $post=PostService::getPostByIDAndDflag($postid,0);
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
                return $this->fetch("posts");
            }else{
                return "1000";
            }

        }else{
            return $this->index();
        }

    }

    public function getQQ($qq){
        header("Content-type: application/json; charset=utf-8");
        $html = file_get_contents('http://r.pengyou.com/fcg-bin/cgi_get_portrait.fcg?uins='.$qq);
        $nic = explode(",",$html);
        $name = trim(mb_convert_encoding($nic[6], "UTF-8", "GBK"),'"');
        $face= file_get_contents('https://ptlogin2.qq.com/getface?appid=1006102&uin='.$qq.'&imgtype=3');
        preg_match("/pt.setHeader\((.*?)\)/",$face,$pic);
        $pic = json_decode($pic[1]);
        $json['name'] = $name;
        $json['pic'] = $pic->$qq;
        $user=UserService::getUserByQQ($qq);
        if ($user==null){
            $u=[
                "username"=>$name,
                "qq"=>$qq,
                "email"=>$qq."@qq.com",
                "pic"=>$pic->$qq
            ];
            $user=UserService::addUser($u);
        }
        Session::set("userid",$user->id);
        Session::set("userqq",$user->qq);
        Session::set("username",$user->username);
        Session::set("disable","disabled");
        Session::set("pic",$user->pic);

        return json($json);
    }

    public function subpl(Request $request){

        $fromid=$request->session("userid");
        $fusername=$request->post("fusername");
        $toid=$request->post("toid");
        $tusername=$request->post("tusername");
        $browser=UAParser::getBrowser($request->post("browser"));
        $os=UAParser::getOS($request->post("os"));
        $content=$request->post("content");
        $type=$request->post("type");
        $tid=$request->post("tid");
        $datetime=new \DateTime();
        $ip=$request->ip();
        $location = new IpLocation();
        $addr=$location->getlocation($ip);
        $ip=$addr['country'];
        $admin=Session::get("admin");
        if ($admin!==null){
            $fromid=0;
            $fusername="";
        }
        if($fromid===null) {
            return "1003";
        }
        $comment=[
            "tid"=> $tid,
            "type"=> $type,
            "fromid"=> $fromid,
            "fusername"=> $fusername,
            "toid"=> $toid,
            "tusername"=> $tusername,
            "browser"=>$browser,
            "os"=>$os,
            "datetime"=>$datetime->format('Y-m-d H:i:s'),
            "content"=>$content,
            "ip"=>$ip
        ];
        CommentService::addComment($comment);
        return "1014";
    }

    public function bbs(){
        $list=CommentService::getCommentList(-1,2);
        $this->assign("list",$list);
        return $this->fetch("bbs");
    }

    public function zan(Request $request){
        $postid=$request->post("postid");
        $ztag=$request->post("ztag");
        $userid=Session::get("userid");
        $ip=$request->ip();
        if ($userid!==null){
            $zan=ZanService::getZanByUseridForOnePost($postid,$userid);
            if($zan!=null){
                if ($ztag==1&&$zan->status==1)
                    return "1001";
                else if ($ztag==0&&$zan->status==0)
                    return "1002";
                else {
                    $zan=ZanService::updateStatusByUseridForOnePost($postid,$userid,$ztag);
                    $zcount=ZanService::getZanCountByPostId($postid);
                    $zaninfo=[
                        "s"=>$zan->status,
                        "g"=>$zcount["good"],
                        "b"=>$zcount["bad"]
                    ];
                    $zaninfo=json_encode($zaninfo);
                    return json($zaninfo);
                }
            }
        }else{
            return "1003";
        }
        $z=[
            "tid"=>$postid,
            "type"=>1,
            "userid"=>$userid,
            "status"=>$ztag,
            "ip"=>$ip
        ];
        ZanService::addZan($z);
        $zcount=ZanService::getZanCountByPostId($postid);
        $zaninfo=[
            "s"=>$ztag,
            "g"=>$zcount["good"],
            "b"=>$zcount["bad"]
        ];
        $zaninfo=json_encode($zaninfo);
        return json($zaninfo);
    }

    public function tagList(Request $request){

        $tag=$request->get("tag");
        $list=PostService::getPostListByTag($tag);
        $this->assign("list",$list);

        $admininfo=AdminService::getAdminInfo();
        $pv=PvService::getPv();
        $this->assign("admin",$admininfo);
        $this->assign("pv",$pv);
        $tag=TagService::TagInfo();
        $this->assign("tag",$tag);
        $readcountlist=PostService::getPostByReadCount();
        $this->assign("readcountlist",$readcountlist);
        return $this->fetch("taglist");
    }
    public function test(){
        $location = new IpLocation();
        $addr=$location->getlocation("172.21.14.42");
        $ip=$addr['country'];
    }
}

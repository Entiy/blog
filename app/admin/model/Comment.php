<?php
/**
 * Created by PhpStorm.
 * User: qiangshizhi
 * Date: 2017/12/14
 * Time: 下午2:51
 */

namespace app\admin\model;
use think\Model;
class Comment extends Model
{
    public function user(){
        return $this->belongsTo("user","fromid")->field("pic");
    }
}
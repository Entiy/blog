<?php
/**
 * Created by PhpStorm.
 * User: qiangshizhi
 * Date: 2017/12/16
 * Time: 下午3:19
 */

namespace app\admin\model;
use think\Model;
class Post extends Model
{
    public function comments(){
        return $this->hasMany("comment","tid");
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: qiangshizhi
 * Date: 2017/12/21
 * Time: 下午3:07
 */

namespace app\helper;
use think\Controller;
use app\admin\service\PvService;
class BaseController extends Controller
{
    function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        PvService::updatePv();
    }
}
<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;

/**
 * 省市区控制器
 */
class AreaController extends HomeController {
  //栏目列表
  public function index() {
    $parent_id = I('get.parent_id', 0, 'intval');
    $select_id = I('get.select_id', 0, 'intval');
    $allow_blank = I('get.allow_blank', 1, 'intval');
    $select_name = I('get.name', "");
    $name_lists = array("province_id" => "请选择省份", "city_id" => "请选择城市", "state_id" => "区/县");
    if($allow_blank){
      $blank_name = $name_lists[$select_name];
    }
    if(empty($blank_name)){
      $blank_name = "请选择";
    }
    $Area = D("Common/Area");
    $areas = $Area->children($parent_id);
    $this->assign("select_id", $select_id);
    $this->assign("areas", $areas);
    $this->assign("allow_blank", $allow_blank);
    $this->assign("blank_name", $blank_name);
    $this->display();
  }
}
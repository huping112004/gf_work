<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Addons\Keywords\Controller;
use Home\Controller\AddonsController; 


class KeywordsController extends AddonsController{
	var $model;
 
	// 删除对象
  public function delete() {
    $id = I('request.id');
    $id = intval($id);
    if($id > 0){
      M("keywords")->delete($id);
    }
    $this->success('删除成功！', U('Admin/Addons/adminList', array('name' => 'Keywords')));
  }
	
}

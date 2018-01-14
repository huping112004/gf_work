<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Addons\EditorForAdmin\Controller;
use Home\Controller\AddonsController; 


class WxeditorController extends AddonsController{
	var $model;

	public function load(){
		
    $type = trim(I('request.type'));
    if(!in_array($type,array('title','text','img','tpl','scene','follow')))
    {
      echo '错误的模板类型 T_T';
      exit;
    }
    //$this->display('temp_'.$type);
    $this->display (T('Addons://EditorForAdmin@Wxeditor/'.'temp_'.$type));
  }

	
}

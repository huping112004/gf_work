<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;
use OT\DataDictionary;
use Think\Page;

/**
 * 联系我们
 */
class ContactController extends HomeController {


  //学校信息首页
  public function index(){
		
    $seo = seo();
    $seo["title"] = '联系我们';
    $this->assign("SEO",$seo);
    $this->display(); 
  }
 	public function do_info(){
    $infos = I("post.");

    $collectid = D("Contact")->saveinfo($infos);
    if($collectid){
      die("<script>top.alert('您已成功提交信息');top.location.href='".C("DOMAIN")."/Index/art_list.html';</script>");
    }

  }
 
    

}
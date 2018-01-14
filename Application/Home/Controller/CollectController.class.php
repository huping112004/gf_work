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
 * 学校信息
 */
class CollectController extends HomeController {


  //学校信息首页
  public function index(){
    $seo = seo();
    $seo["title"] = '征集信息';
    $this->assign("SEO",$seo);
		$this->display();
  }
 	public function do_info(){
    $infos = I("post.");
    if($_FILES['photo']['name']){

      $full_path = "Collect/";
      mkdir($full_path, 0777, true);
      $upload = new \Think\Upload();
      $upload->maxSize   =     3145728 ;
      $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');
      $upload->savePath =  $full_path;
      $info   =   $upload->upload();
      //dump($upload->getError());
      //exit;    
      if(!$info) { 
        //$this->error($upload->getError());
        die("<script>top.alert('".$upload->getError()."');</script>");
      }
      $_POST['img_url'] = '/Uploads/'.$info['photo']['savepath'].$info['photo']['savename'];
    }

   // $infos["img_url"] = $_POST['img_url'];
    $collectid = D("Collect")->saveinfo($infos);
    if($collectid){
      die("<script>top.alert('您已成功提交信息，感谢您参与为爱上色2016年学校征集活动！');top.location.href='".C("DOMAIN")."/Index/art_list.html';</script>");
    }

  }
  public function do_upload(){
    $filename = iconv('UTF-8', 'GBK', $_FILES['file']['name']);
    $return  = array('status' => 1, 'info' => '上传成功', 'data' => ''); 
    if($_FILES){

      $full_path = "Collect/";
      mkdir($full_path, 0777, true);
      $upload = new \Think\Upload();
      $upload->maxSize   =     3145728 ;
      $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');
      $upload->savePath =  $full_path;
      $info   =   $upload->upload();
      //dump($upload->getError());
      //exit;    
      if(!$info) {  
        $return['status'] = 0;
        $return['info']   = $upload->getError();
      }
      $return['path'] = '/Uploads/'.$info['photo']['savepath'].$info['photo']['savename'];
      //$return = array_merge($info['download'], $return);
    }
     

      /* 返回JSON数据 */
      $this->ajaxReturn($return);

  }
 
    

}
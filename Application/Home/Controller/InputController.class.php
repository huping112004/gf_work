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


class InputController extends HomeController {

    //首页
  public function index()
  {

    header("Content-Type:text/html; charset=utf-8");
  
    //$Question = M("bym_question");
    include './Excel/reader.php';

    $data = new \Spreadsheet_Excel_Reader();
    $data->setOutputEncoding('utf-8');
    $data->read('./content.xls');
    $j=1;
    for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++){
      if($data->sheets[0]['cells'][$i][2]!=''){

        $datas["title"] = $data->sheets[0]['cells'][$i][2];
        $datas["description"] = $data->sheets[0]['cells'][$i][5];
        $datas["category_id"]   = 2;
        $datas["status"]    = 1;
        $datas["model_id"]    = 2;
        $datas["create_time"]   = $data->sheets[0]['cells'][$i][13];
        $datas["update_time"]   = $data->sheets[0]['cells'][$i][13];
        
        $id = M("document")->add($datas);
        if($id){
          if($data->sheets[0]['cells'][$i][7]){
            $pictures["path"] = $data->sheets[0]['cells'][$i][7];
            $pictures["status"] = 1;
            $img_id = M("picture")->add($pictures);
            $data_article["new_img"] = $img_id;
          }
            
          $data_article["id"] = $id;
          $data_article["content"] = $data->sheets[0]['cells'][$i][11];
          M("document_article")->add($data_article);
        }
        $j++;
      } 
    } 
    echo "共录入".$j."条记录!";

  }
  public function updatetag(){

    header("Content-Type:text/html; charset=utf-8");
  
    //$Question = M("bym_question");
    include './Excel/reader.php';

    $data = new \Spreadsheet_Excel_Reader();
    $data->setOutputEncoding('utf-8');
    $data->read('./content.xls');
    $j=1;
    for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++){
      if($data->sheets[0]['cells'][$i][2]!=''){

        $map["title"] = $data->sheets[0]['cells'][$i][2];
        $keywords = $data->sheets[0]['cells'][$i][3];
        $result = M("document")->where($map)->find();

        if($result){
          $data_article["id"] = $result[id];
          if($keywords){
            M("document_article")->where($data_article)->save(array("keywords"=>$keywords));
            echo M()->getlastsql();
          }
          //exit;
        }
        $j++;
      } 
    } 
    echo "共录入".$j."条记录!";

  }
  
  


}

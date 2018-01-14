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
 * 文档模型控制器
 * 文档模型列表和详情
 */
class ArticleController extends HomeController {

    /* 文档模型频道页 */
  public function index(){
    /* 分类信息 */
    $category = $this->category();

    //频道页只显示模板，默认不读取任何内容
    //内容可以通过模板标签自行定制

    /* 模板赋值并渲染模板 */
    $this->assign('category', $category);
    $this->display($category['template_index']);
  }

  /* 文档模型列表页 */
  public function lists($p = 1){
    /* 分类信息 */
    $category = $this->category();

    /* 获取当前分类列表 */
    $Document = D('Document');
    $list = $Document->page($p, $category['list_row'])->lists($category['id']);
    if(false === $list){
      $this->error('获取列表数据失败！');
    }

    /* 模板赋值并渲染模板 */
    $this->assign('category', $category);
    $this->assign('list', $list);
    $this->display($category['template_lists']);
  }

  /* 文档模型详情页 */
  public function detail($id = 0, $p = 1){
    header("content-type:text/html;charset=utf-8");
    $footer_new = D("Document")->news_list();
    $this->assign("footer_new",$footer_new);
    $footer_case = D("Document")->cases_list();
    $this->assign("footer_case",$footer_case); 
    /* 标识正确性检测 */
    if(!($id && is_numeric($id))){
      $this->error('文档ID错误！');
    }

    /* 页码检测 */
    $p = intval($p);
    $p = empty($p) ? 1 : $p;

    /* 获取详细信息 */
    $Document = D('Document');
    $info = $Document->detail($id);
    if(!$info){
      $this->error($Document->getError());
    }
    if($info["user_name"]){
      $author_arr = M("document_author")->where("id=".$info["user_name"])->find();
      $this->assign("author_arr",$author_arr);
    } 
    /* 分类信息 */
    $category = $this->category($info['category_id']);

    /* 获取模板 */
    if(!empty($info['template'])){//已定制模板
      $tmpl = $info['template'];
    } elseif (!empty($category['template_detail'])){ //分类已定制模板
      $tmpl = $category['template_detail'];
    } else { //使用默认模板
      $tmpl = 'Article/'. get_document_model($info['model_id'],'name') .'/detail';
    }
    /* 更新浏览数 */
    $map = array('id' => $id);
    $Document->where($map)->setInc('view');
    $category = D('Category')->getTree(C("News"));

    if(isset($category["_"])){
      foreach($category["_"] as $row){
        //$arr_category[]= $row["id"];
        $categorys[] = $row;
      }
    }

    $this->assign("categorys",$categorys); 
    $arr_category = array();
    //读保持推荐得案例
    $category = D('Category')->getTree(C("News"));
    if(isset($category["_"])){
      foreach($category["_"] as $row){
        $arr_category[]= $row["id"];
        //$categorys[] = $row;
      }
    }
    $map["category_id"] = array("in",$arr_category);
    $map["status"] = 1;
    $map["recommend"] = 1;
    $case_lists    = D('Document')->new_list($map,1,3); 
    $this->assign('case_lists', $case_lists);
    //start get tag
    $tagresult = M("document as d ")->join("inner join dr_document_article as da on da.id = d.id")->field('da.keywords')->where(array("d.category_id"=>array("in",$arr_category)))->select();

    foreach($tagresult as $k=>$atagr){
     if($atagr['keywords']){
        $atagr = explode(",",$atagr['keywords']);
        foreach($atagr as $j=>$v){
          $atagr[$j]=$v;
        }
        $atagresult[$k]=$atagr;
     }
    }
    $k=0;
    foreach($atagresult as $atagr){

      foreach($atagr as $v){

      $newtag[$k]=array("tagname"=>$v);

      $k++;

        }

    }
    $newtag = $this->assoc_title($newtag,"tagname");
    $this->assign("newtag",$newtag);
    //end tag
    //取新闻详情页右边内容
    $category = D('Category')->getTree(C("News"));

    if(isset($category["_"])){
      foreach($category["_"] as $row){
        $new_category[] = $row["id"];
        //$categorys[] = $row;
      }
    }
    $map = array();
    $map["category_id"] = array("in",$new_category);
    $map["status"] = 1;
    $new_lists    = D('Document')->new_list($map,1,3);  
    //echo M()->getlastsql();
  //  exit;
    //print_r($new_lists);
    //exit;
    $this->assign('new_lists', $new_lists);
    /* 模板赋值并渲染模板 */
    $this->assign('category', $category);
    $this->assign('info', $info);
    $this->assign('page', $p); //页码
  
    $seo = seo();

    $seo["title"] = $info['title']." ".C('WEB_SITE_TITLE');
    $seo["description"] = $info['title']." ".C('WEB_SITE_TITLE');
    $seo["keyword"] = $info['title']." ".C('WEB_SITE_TITLE');
    $this->assign("SEO",$seo); 
    $this->display($tmpl);
  }
  /* 文档模型详情页 */
  public function case_detail($id = 0, $p = 1){
    $footer_new = D("Document")->news_list();
    $this->assign("footer_new",$footer_new);
    $footer_case = D("Document")->cases_list();
    $this->assign("footer_case",$footer_case); 
    /* 标识正确性检测 */
    if(!($id && is_numeric($id))){
      $this->error('文档ID错误！');
    }

    /* 页码检测 */
    $p = intval($p);
    $p = empty($p) ? 1 : $p;

    /* 获取详细信息 */
    $Document = D('Document');
    $info = $Document->detail($id);
    if(!$info){
      $this->error($Document->getError());
    }
    //取编辑者信息
    if($info["user_name"]){
      $author_arr = M("document_author")->where("id=".$info["user_name"])->find();
      $this->assign("author_arr",$author_arr);
    } 
    /* 分类信息 */
    $category = $this->category($info['category_id']);

    /* 获取模板 */
    if(!empty($info['template'])){//已定制模板
      $tmpl = $info['template'];
    } elseif (!empty($category['template_detail'])){ //分类已定制模板
      $tmpl = $category['template_detail'];
    } else { //使用默认模板
      $tmpl = 'Article/'. get_document_model($info['model_id'],'name') .'/detail';
    }

    /* 更新浏览数 */
    $map = array('id' => $id);
    $Document->where($map)->setInc('view');
    $category = D('Category')->getTree(C("CASES"));

    if(isset($category["_"])){
      foreach($category["_"] as $row){
        //$arr_category[]= $row["id"];
        $categorys[] = $row;
      }
    }

    $this->assign("categorys",$categorys); 
    //读保持推荐得案例
    $category = D('Category')->getTree(C("CASES"));
    if(isset($category["_"])){
      foreach($category["_"] as $row){
        $arr_category[]= $row["id"];
        //$categorys[] = $row;
      }
    }
    $map["category_id"] = array("in",$arr_category);//得到案例下所有分类
    $map["status"] = 1;
    $map["recommend"] = 1;
    $case_lists    = D('Document')->new_list($map,1,3); //推荐案例
    $this->assign('case_lists', $case_lists);
    
    $map = array(); 
    $map["category_id"] = array("in",$arr_category);
    $map["status"] = 1;
    $new_lists    = D('Document')->new_list($map,1,3);//右边3条案例  
    $this->assign('new_lists', $new_lists);
    
    
  //start get cases tag
   $tagresult = M("document as d ")->join("inner join dr_document_article as da on da.id = d.id")->field('da.keywords')->where(array("d.category_id"=>array("in",$arr_category)))->select();

    foreach($tagresult as $k=>$atagr){
     if($atagr['keywords']){
        $atagr = explode(",",$atagr['keywords']);
        foreach($atagr as $j=>$v){
          $atagr[$j]=$v;
        }
        $atagresult[$k]=$atagr;
     }
    }
    $k=0;
    foreach($atagresult as $atagr){

      foreach($atagr as $v){
        
      $newtag[$k] = array("tagname"=>trim($v));

      $k++;

     }

    }
    $newtag = $this->assoc_title($newtag,"tagname");
    $this->assign("newtag",$newtag);
    //end tag
    
    /* 模板赋值并渲染模板 */
    $this->assign('category', $category);
    $this->assign('info', $info);
    $this->assign('page', $p); //页码
    $tmpl = 'Article/article/case_detail';


    $seo["title"] = $info['title']." ".C('WEB_SITE_TITLE');
    $seo["description"] = $info['description'];
    $customername = $Document->getCustomerName($info["customer"]);//取客户名称
    $seo["keyword"] = $customername." ".$info['title'];
    $this->assign("SEO",$seo); 
    $this->display($tmpl);
  }

  /* 文档分类检测 */
  private function category($id = 0){
    /* 标识正确性检测 */
    $id = $id ? $id : I('get.category', 0);
    if(empty($id)){
      $this->error('没有指定文档分类！');
    }

    /* 获取分类信息 */
    $category = D('Category')->info($id);
    if($category && 1 == $category['status']){
      switch ($category['display']) {
        case 0:
          $this->error('该分类禁止显示！');
          break;
        //TODO: 更多分类显示状态判断
        default:
          return $category;
      }
    } else {
      $this->error('分类不存在或被禁用！');
    }
  }
  
   //二维数组去重复数据

  public function assoc_title($arr, $key) { 

      $tmp_arr = array(); 

      foreach($arr as $k => $v) { 

      if(in_array($v[$key], $tmp_arr)) { 

          unset($arr[$k]); 

      } else { 

          $tmp_arr[] = $v[$key]; 

      } 

    } 

      return $arr; 

  }
}

<?php// +----------------------------------------------------------------------// | OneThink [ WE CAN DO IT JUST THINK IT ]// +----------------------------------------------------------------------// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.// +----------------------------------------------------------------------// | Author: huajie <banhuajie@163.com>// +----------------------------------------------------------------------namespace Admin\Controller;use Admin\Model\AuthGroupModel;use Think\Page;/** * 后台内容控制器 * @author huajie <banhuajie@163.com> */class OrderController extends AdminController {    public function _initialize(){        $memu = array(            0=>array(                'title'=>'订单列表',                'url'=>'Order/index'            ),            1=>array(                'title'=>'订单导出',                'url'=>'Order/outinput'            ),        );        $this->assign('_extra_menu',array(            '订单管理'=> $memu,        ));       // parent::_initialize();    }    public function index(){        $this->meta_title = '插件列表';        $order_result       =   D('Order')->getList();        $request    =   (array)I('request.');        $total      =   $order_result['total'] ;        $listRows   =   C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;      //  $page       =   new \Think\Page($total, $listRows, $request);      //  $voList     =   array_slice($list, $page->firstRow, $page->listRows);        //$p          =   $page->show();        $this->assign('_list', $order_result['list']);       $this->assign('_page', $order_result['page']);        // 记录当前列表页的cookie        Cookie('__forward__',$_SERVER['REQUEST_URI']);        $this->display();    }    public function outinput(){        echo 'outinput';    }}
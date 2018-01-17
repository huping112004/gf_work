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
use Think\Exception;
use Think\Page;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class IndexController extends BaseController
{


    public function index()
    {
        $seo["title"] = '平安人寿广分套餐领取';
        $seo["description"] = '平安人寿广分套餐领取';
        $seo["keyword"] = '平安人寿广分套餐领取';
        $this->assign("SEO", $seo);
        $this->display();
    }

    /**
     * 订单确认
     * @throws \Exception
     */
    public function confrim()
    {
        $param = $_GET;
        $product_id = intval($param['product_id']);
        $seo["title"] = '平安人寿广分订单确认';
        $seo["description"] = '平安人寿广分订单确认';
        $seo["keyword"] = '平安人寿广分订单确认';
        $user_id = is_user_login();
        $order = M("order")->where(array('user_id'=>$user_id))->find();
        $this->assign("order", $order);
        $this->assign("SEO", $seo);
        $this->assign("product_id", $product_id);
        $this->display();
    }
    public function chekStock()
    {
        $param = $_POST;
        $product_id = $param['product_id'];
        $user_id = is_user_login();
        if(empty($product_id)){
            throw new \Exception('参数错误');
        }
        try {
            $result = D('Product')->chekStock($product_id,$user_id);

        }catch(\Exception $e){
            $result = array(
                'msg_code'=>'1001',
                'msg'=>$e->getMessage()
            );
        }

        $this->ajaxReturn($result);

    }

    /**
     * 保存订单信息
     * @throws \Exception
     */
    public function saveOrder()
    {
        $param = $_POST;

        $product_id = $param['product_id'];
        $user_id = is_user_login();
        if(empty($product_id)){
            throw new \Exception('参数错误');
        }
        if(preg_match("/^1[34578]{1}\d{9}$/",$param['mobile'])){

        }else{

            $result = array(
                'msg_code'=>'4000',
                'msg'=>'手机号码格式错误'
            );
            $this->ajaxReturn($result);
        }
        try {
            $result = D('Product')->chekStock($product_id,$user_id);
            if($result['msg_code'] ==200){//检验成功
               $order_id = D('Order')->saveOrder($param,$user_id);
               if($order_id){
                   $result = array(
                       'msg_code'=>'200',
                       'msg'=>'success'
                   );
               }
            }

        }catch(\Exception $e){
            $result = array(
                'msg_code'=>'4000',
                'msg'=>$e->getMessage()
            );
            print_r($result);
        }

        $this->ajaxReturn($result);

    }

    public function checkOrder()
    {
        $param = $_POST;
        $user_id = is_user_login();
        $order = M("order")->where(array('user_id'=>$user_id))->find();
        if($order){//领取过
            $result = array(
                'msg_code'=>'200',
                'msg'=>1
            );
        }else{
            $result = array(
                'msg_code'=>'200',
                'msg'=>0
            );
        }

        $this->ajaxReturn($result);

    }
}
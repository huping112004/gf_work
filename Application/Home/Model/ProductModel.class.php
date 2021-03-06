<?php

namespace Home\Model;

use Think\Exception;
use Think\Model;

class ProductModel extends Model
{
    /* 自动完成规则 */
    protected $_auto = array(

        array('stock', 'htmlspecialchars', self::MODEL_BOTH, 'function'),
        array('lock', 'htmlspecialchars', self::MODEL_BOTH, 'function'),
        array('create_time', NOW_TIME, self::MODEL_INSERT),

    );

    public function chekStock($product_id,$user_id)
    {
        $product_id = abs($product_id);
        $result = array(
            'msg_code'=>'200',
            'msg'=>'ok'
        );
        $product = M('product')->where(array('id' => $product_id))->find();
        if (empty($product)) {
            throw new \ErrorException('选择的套餐不存在');
        }
        if (($product['stock'] - $product['locks']) <= 0) {
            if ($product_id == 1) {//套餐A
               $result = array(
                   'msg_code'=>'1001',
                   'msg'=>'a-finsh'
               );
            }else{////套餐B
                $result = array(
                    'msg_code'=>'1002',
                    'msg'=>'b-finsh'
                );
            }
        }
        $order = M("order")->where(array('user_id'=>$user_id))->find();
        if($order){
            $result = array(
                'msg_code'=>'1003',
                'msg'=>'recive2'
            );
        }
        return $result;

    }

    /**
     * 获取商品库存
     */
    public function getProductStock(){
        $row = array();
        $product = M('product')->select();
        if($product){
            foreach ($product as $item){
                $left_stock = $item['stock']-$item['locks'];
                $item['left_stock'] = $left_stock;
                $row[$item['id']] = $item;
            }
        }
        return $row;
    }


}
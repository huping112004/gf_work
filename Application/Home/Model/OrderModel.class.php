<?php

namespace Home\Model;

use Think\Exception;
use Think\Model;

class OrderModel extends Model
{
    /* 自动完成规则 */
    protected $_auto = array(

        array('stock', 'htmlspecialchars', self::MODEL_BOTH, 'function'),
        array('lock', 'htmlspecialchars', self::MODEL_BOTH, 'function'),
        array('create_time', NOW_TIME, self::MODEL_INSERT),

    );

    public function saveOrder($params, $user_id)
    {
        $product_id = abs($params['product_id']);
        $order = M('Order');
        $order->startTrans();
        $info = [
            'product_id' => $product_id,
            'user_id' => $user_id,
            'consignee' => $params['consignee'],
            'mobile' => $params['mobile'],
            'address' => $params['address'],
            'province' => $params['province'],
            'city' => $params['city'],
            'state' => $params['states'],
            'create_time' => time(),
            'update_time' => time(),
        ];
        $order_id = M('Order')->add($info);
        $data['locks'] =array('exp','locks+1');
        $result = M("product")->where(['id' => $product_id])->save($data);
        if($order_id&&$result){
            $order->commit();
            return $order_id;
        }else{
            $order->rollback();//不成功，回滚
            return 0;
        }

    }


}
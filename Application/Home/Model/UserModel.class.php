<?php

namespace Home\Model;

use Think\Model;

class UserModel extends Model
{
    /* 自动完成规则 */
    protected $_auto = array(

        array('job_number', 'htmlspecialchars', self::MODEL_BOTH, 'function'),
        array('mobile', 'htmlspecialchars', self::MODEL_BOTH, 'function'),
        array('create_time', NOW_TIME, self::MODEL_INSERT),

    );

    public function login($param)
    {
        $job_number = $param['job_number'];
        $mobile = $param['mobile'];
        $id_card = $param['id_card'];

        $result = array();
        $user = M('user')->where(array('job_number' => $job_number))->find();
        if (empty($user)) {
            $result = array(
                'msg_code' => '1001',
                'msg' => '您输入的工号不正确',
                'msg_en' => 'job_number'
            );
            return $result;
        }
        if (preg_match("/^1[34578]{1}\d{9}$/", $param['mobile'])) {

        } else {

            $result = array(
                'msg_code' => '1002',
                'msg' => '您输入的手机号码不正确',
                'msg_en' => 'mobile'
            );
            return $result;
        }
        $user = M('user')->where(array('mobile' => $mobile))->find();
        if (empty($user)) {
            $result = array(
                'msg_code' => '1002',
                'msg' => '您输入的手机号码不正确',
                'msg_en' => 'mobile'
            );
            return $result;
        }
        $user = M('user')->where(array('id_card' => $id_card))->find();
        if (empty($user)) {
            $result = array(
                'msg_code' => '1003',
                'msg' => '您输入的手机号码不正确',
                'msg_en' => 'id_card'
            );
            return $result;
        }
        $user = M('user')->where(array('job_number' => $job_number, 'mobile' => $mobile, 'id_card' => $id_card))->find();
        if (empty($user)) {
            $result = array(
                'msg_code' => '1004',
                'msg' => '您输入的手机号码不正确',
                'msg_en' => 'not_result'
            );
            return $result;
        } else {//登录成功
            //session('user_id', $user['id']);
            //session('user_id', $user['id']);
            cookie('user_id', $user['id'], 3600*30*24);
            session('job_number', $user['job_number']);
            $result = array(
                'msg_code' => '200',
                'msg' => '您输入的手机号码不正确',
                'msg_en' => 'id_card'
            );
        }
        return $result;

    }


}
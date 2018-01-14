<?php

namespace Addons\Contact;
use Common\Controller\Addon;

/**
 * 联系我们插件
 * @author tayue
 */

    class ContactAddon extends Addon{

        public $info = array(
            'name'=>'Contact',
            'title'=>'联系我们',
            'description'=>'这是一个临时描述',
            'status'=>1,
            'author'=>'tayue',
            'version'=>'0.1'
        );

        public $admin_list = array(
            'model'=>'Contact',		//要查的表
			'fields'=>'*',			//要查的字段
			'map'=>'',				//查询条件, 如果需要可以再插件类的构造方法里动态重置这个属性
			'order'=>'id desc',		//排序,
			'list_grid'=>array( 		//这里定义的是除了id序号外的表格里字段显示的表头名和模型一样支持函数和链接
                'realname:姓名',
                'email:email',
                'content:内容',
                'create_at|time_format:更新时间',
                'id:操作:[DELETE]|删除'
            ),
        );

        public function install(){
            return true;
        }

        public function uninstall(){
            return true;
        }


    }
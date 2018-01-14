<?php

namespace Addons\Collect;
use Common\Controller\Addon;

/**
 * 学校征集信息插件
 * @author tayue
 */

    class CollectAddon extends Addon{

        public $info = array(
            'name'=>'Collect',
            'title'=>'学校征集信息',
            'description'=>'这是一个临时描述',
            'status'=>1,
            'author'=>'tayue',
            'version'=>'0.1'
        );

        public $admin_list = array(
            'model'=>'Collect',		//要查的表
			'fields'=>'*',			//要查的字段
			'map'=>'',				//查询条件, 如果需要可以再插件类的构造方法里动态重置这个属性
			'order'=>'id desc',		//排序,
			'list_grid'=>array( 		//这里定义的是除了id序号外的表格里字段显示的表头名和模型一样支持函数和链接

                'school_name:学校名称',
                'address:地址',
                'school_contacts:学校联系人',
                'reason:推荐理由',
                'real_info:真实信息',
                'email:email',
                'phone:联系电话',
                'source:信息来源',
                'img_url|getImage:学校照片',
                'create_at|time_format:征集时间',
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
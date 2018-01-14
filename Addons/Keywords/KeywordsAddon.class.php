<?php

namespace Addons\Keywords;
use Common\Controller\Addon;

/**
 * tag管理插件
 * @author 无名
 */

    class KeywordsAddon extends Addon{

        public $info = array(
            'name'=>'Keywords',
            'title'=>'tag管理',
            'description'=>'这是一个临时描述',
            'status'=>1,
            'author'=>'无名',
            'version'=>'0.1'
        );
         public $admin_list = array(
            'model'=>'keywords',      //要查的表
                'fields'=>'*',          //要查的字段
                'map'=>'',              //查询条件, 如果需要可以再插件类的构造方法里动态重置这个属性
                'order'=>'id desc',     //排序,
                'listKey'=>array(       //这里定义的是除了id序号外的表格里字段显示的表头名
                    'id'=>'编号',
                    'tag_name'=>'关键词',
                    'create_date'=>'创建时间',
                ),
        );
        public $custom_adminlist = 'adminlist.html';    
        public function install(){
            return true;
        }

        public function uninstall(){
            return true;
        }


    }
<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Model;
use Think\Model;
use Think\Page;

/**
 * 文档基础模型
 */
class DocumentModel extends Model{

    /* 自动验证规则 */
    protected $_validate = array(
        array('name', '/^[a-zA-Z]\w{0,30}$/', '文档标识不合法', self::VALUE_VALIDATE, 'regex', self::MODEL_BOTH),
        array('name', '', '标识已经存在', self::VALUE_VALIDATE, 'unique', self::MODEL_BOTH),
        array('title', 'require', '标题不能为空', self::VALUE_VALIDATE, 'regex', self::MODEL_BOTH),
        array('category_id', 'require', '分类不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_INSERT),
        array('category_id', 'require', '分类不能为空', self::EXISTS_VALIDATE , 'regex', self::MODEL_UPDATE),
        array('category_id,type', 'check_category', '内容类型不正确', self::MUST_VALIDATE , 'function', self::MODEL_INSERT),
        array('category_id', 'check_category', '该分类不允许发布内容', self::EXISTS_VALIDATE , 'function', self::MODEL_BOTH),
        array('model_id,category_id,pid', 'check_category_model', '该分类没有绑定当前模型', self::MUST_VALIDATE , 'function', self::MODEL_INSERT),
    );

    /* 自动完成规则 */
    protected $_auto = array(
        array('uid', 'session', self::MODEL_INSERT, 'function', 'user_auth.uid'),
        array('title', 'htmlspecialchars', self::MODEL_BOTH, 'function'),
        array('description', 'htmlspecialchars', self::MODEL_BOTH, 'function'),
        array('root', 'getRoot', self::MODEL_BOTH, 'callback'),
        array('attach', 0, self::MODEL_INSERT),
        array('view', 0, self::MODEL_INSERT),
        array('comment', 0, self::MODEL_INSERT),
        array('extend', 0, self::MODEL_INSERT),
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        //array('reply_time', NOW_TIME, self::MODEL_INSERT),
        array('update_time', NOW_TIME, self::MODEL_BOTH),
        array('status', 'getStatus', self::MODEL_BOTH, 'callback'),
    );

    public $page = '';

    /**
     * 获取文档列表
     * @param  integer  $category 分类ID
     * @param  string   $order    排序规则
     * @param  integer  $status   状态
     * @param  boolean  $count    是否返回总数
     * @param  string   $field    字段 true-所有字段
     * @return array              文档列表
     */
    public function lists($category, $order = '`id` DESC', $status = 1, $field = true){
        $map = $this->listMap($category, $status);
        return $this->field($field)->where($map)->order($order)->select();
    }

      /**
     * 获取文档列表
     * @param  integer  $category 分类ID
     * @param  string   $order    排序规则
     * @param  integer  $status   状态
     * @param  boolean  $count    是否返回总数
     * @param  string   $field    字段 true-所有字段
     * @return array              文档列表
     */
    public function new_list($parmas, $current_page, $page_size, $order_type = "default", $fields = array()){
      $search_res = $this->NewSearchMap($parmas);

      $perfix = C('DB_PREFIX');
      if(empty($fields)){
        $fields = array($perfix."document.*", $perfix."document_article.*");
      }
      switch($order_type){
        case 'publish_date':
          $order = $perfix."document_article.publish_date desc";
          break;
        case 'sortnum':
          $order = $perfix."document_article.sortnum asc";
          break;    
        default:
          $order = $perfix."document.id DESC";
          break;
      }
  
      $offset = ($current_page - 1)*$page_size;
      return $this->join("INNER JOIN {$perfix}document_article ON {$perfix}document.id={$perfix}document_article.id")

                  ->field($fields)->where($search_res["map"])
                  ->order($order)->limit($offset.",".$page_size)
                  ->select();
   }
	/**
	 * 获取涂刷列表
	 * @param  boolean $field 要列出的字段
	 * @return array          列表
	 */
   public function brushing_list($parmas, $current_page, $page_size, $order_type = "default", $fields = array()){
      $search_res = $this->BrushingSearchMap($parmas);
 	
      $perfix = C('DB_PREFIX');
      if(empty($fields)){
        $fields = array($perfix."document.*", $perfix."document_brushing.*");
      }
      switch($order_type){
      default:
        $order = $perfix."document.id DESC";
        break;
      }
  
      $offset = ($current_page - 1)*$page_size;
          
      return $this->join("inner JOIN {$perfix}document_brushing ON {$perfix}document.id={$perfix}document_brushing.id")
                  ->field($fields)->where($search_res["map"])
                  ->order($order)->limit($offset.",".$page_size)
                  ->select();
   }
    
    /**
     * 获取模板相册
     * @param  boolean $field 要列出的字段
     * @return array          列表
     */
   public function photo_list($parmas, $current_page, $page_size, $order_type = "default", $fields = array()){
      $search_res = $this->PhotoSearchMap($parmas);
    
      $perfix = C('DB_PREFIX');
      if(empty($fields)){
        $fields = array($perfix."document.*", $perfix."document_photo.*");
      }
      switch($order_type){
      default:
        $order = $perfix."document.id DESC";
        break;
      }
  
      $offset = ($current_page - 1)*$page_size;
          
      return $this->join("inner JOIN {$perfix}document_photo ON {$perfix}document.id={$perfix}document_photo.id")
                  ->field($fields)->where($search_res["map"])
                  ->order($order)->limit($offset.",".$page_size)
                  ->select();
   }
	 /**
     * 获取模板视频
     * @param  boolean $field 要列出的字段
     * @return array          列表
     */
   public function video_list($parmas, $current_page, $page_size, $order_type = "default", $fields = array()){
      $search_res = $this->VideoSearchMap($parmas);
    
      $perfix = C('DB_PREFIX');
      if(empty($fields)){
        $fields = array($perfix."document.*", $perfix."document_video.*");
      }
      switch($order_type){
        case 'level':
          $order = $perfix."document.level DESC";
          break;
        default:
          $order = $perfix."document.id DESC";
          break;
      }
  
      $offset = ($current_page - 1)*$page_size;
          
      return $this->join("inner JOIN {$perfix}document_video ON {$perfix}document.id={$perfix}document_video.id")
                  ->field($fields)->where($search_res["map"])
                  ->order($order)->limit($offset.",".$page_size)
                  ->select();
   } 	
   /**
   * 通过页面标识查询对象
   * @param  string   $name 文档标识
   * @return array              文档列表
   */
    public function find_page_by_name($name){
      $perfix = C('DB_PREFIX');
      $fields = array("{$perfix}document.*", "{$perfix}document_page.*");
    
      $map = array(
        "status" => 1,
        "name" => $name,
      );

      return $this->field($fields)
                  ->join("INNER JOIN {$perfix}document_page ON {$perfix}document.id={$perfix}document_page.id")
                  ->where($map)->find();
    }
  /**
   * 获取文档列表
   * @param  integer  $category 分类ID
   * @param  string   $order    排序规则
   * @param  integer  $status   状态
   * @param  boolean  $count    是否返回总数
   * @param  string   $field    字段 true-所有字段
   * @return array              文档列表
   */
  private function NewSearchMap($params){
    $default_params = array(
      "status" => 1,
    );
    $params = array_merge($params, $default_params);

    $perfix = C('DB_PREFIX');
    $document_table_name = $perfix."document";
    $document_article_name = $perfix."document_article";
    $document_photo_name = $perfix."document_photo";
		$document_video_name = $perfix."document_video";
    $join_tables = array();
    $map = array();
    foreach($params as $key => $val){
      
      switch($key){
          case "status":
            $key = $document_table_name.".".$key;
            $map[$key] = intval($val);
            break;

          case  "keywords":
            $key = $document_article_name.".".$key;
            $map[$key] = array('like','%'.$val.'%');
            break;
          case  "sort":
            $key = $document_photo_name.".".$key;
            $map[$key] = intval($val);
            break;
					case  "sorts":
            $key = $document_video_name.".".$key;
            $map[$key] = intval($val);
            break;
          case  "recommend":
            $key = $document_table_name.".".$key;
            $map[$key] = intval($val);
            break;     	  
          case "category_id":

            $key = $document_table_name.".category_id";
            if(empty($val)){
              // 不允许在一个页面里列出所有document
              $map[$key] = -1;
            }else if(is_numeric($val)){
              $map[$key] = $val;
            } else {
              $map[$key] = array('in', isset($val[1])?$val[1]:'');
            }

            break;
          case "model_id":
            $key = $document_table_name.".model_id";
            if(empty($val)){
              // 不允许在一个页面里列出所有document
              $map[$key] = -1;
            }else if(is_array($val)){
              $map[$key] = array('IN', $val);
            } else {
              $map[$key] = $val;
            }
            break;
          
      }
    }
    return array(
      "map" => $map,
      "join_tables" => $join_tables,
    );
  }

  /**
   * 获取文档列表
   * @param  integer  $category 分类ID
   * @param  string   $order    排序规则
   * @param  integer  $status   状态
   * @param  boolean  $count    是否返回总数
   * @param  string   $field    字段 true-所有字段
   * @return array              文档列表
   */
  private function BrushingSearchMap($params){
    $default_params = array(
      "status" => 1,
    );
    $params = array_merge($params, $default_params);

    $perfix = C('DB_PREFIX');
    $document_table_name = $perfix."document";
    $document_brushing_name = $perfix."document_brushing";
    $join_tables = array();
    $map = array();
    foreach($params as $key => $val){
      
      switch($key){
        case "status":
          $key = $document_table_name.".".$key;
          $map[$key] = intval($val);
          break;
        case "province_id":
          $key = $document_brushing_name.".".$key;
          $map[$key] = intval($val);
          break; 
        case "city_id":
          $key = $document_brushing_name.".".$key;
          $map[$key] = intval($val);
          break; 
        case "city_id":
          $key = $document_brushing_name.".".$key;
          $map[$key] = intval($val);
          break;  
			 case "id":
          $key = $document_brushing_name.".".$key;
          $map[$key] = intval($val);
          break;  	     
        case "model_id":
          $key = $document_table_name.".model_id";
          if(empty($val)){
            // 不允许在一个页面里列出所有document
            $map[$key] = -1;
          }else if(is_array($val)){
            $map[$key] = array('IN', $val);
          } else {
            $map[$key] = $val;
          }
          break;
          
      }
    }
    return array(
      "map" => $map,
      "join_tables" => $join_tables,
    );
  }
  /**
   * 获取视频列表
   * @param  integer  $category 分类ID
   * @param  string   $order    排序规则
   * @param  integer  $status   状态
   * @param  boolean  $count    是否返回总数
   * @param  string   $field    字段 true-所有字段
   * @return array              文档列表
   */
  private function VideoSearchMap($params){
    $default_params = array(
      "status" => 1,
    );
    $params = array_merge($params, $default_params);

    $perfix = C('DB_PREFIX');
    $document_table_name = $perfix."document";
    $document_video_name = $perfix."document_video";
    $join_tables = array();
    $map = array();
    foreach($params as $key => $val){
      
      switch($key){
        case "status":
          $key = $document_table_name.".".$key;
          $map[$key] = intval($val);
          break;

        case "sorts":
          $key = $document_video_name.".".$key;
          $map[$key] = intval($val);
          break; 
        
       case "id":
          $key = $document_video_name.".".$key;
          $map[$key] = intval($val);
          break;         
        case "model_id":
          $key = $document_table_name.".model_id";
          if(empty($val)){
            // 不允许在一个页面里列出所有document
            $map[$key] = -1;
          }else if(is_array($val)){
            $map[$key] = array('IN', $val);
          } else {
            $map[$key] = $val;
          }
          break;
          
      }
    }
    return array(
      "map" => $map,
      "join_tables" => $join_tables,
    );
  }
  /**
   * 获取贺卡搜索条件
   * @param  integer  $category 分类ID
   * @param  string   $order    排序规则
   * @param  integer  $status   状态
   * @param  boolean  $count    是否返回总数
   * @param  string   $field    字段 true-所有字段
   * @return array              文档列表
   */
  private function PhotoSearchMap($params){
    $default_params = array(
      "status" => 1,
    );
    $params = array_merge($params, $default_params);

    $perfix = C('DB_PREFIX');
    $document_table_name = $perfix."document";
    $document_photo_name = $perfix."document_photo";
    $join_tables = array();
    $map = array();
    foreach($params as $key => $val){
      
      switch($key){
        case "status":
          $key = $document_table_name.".".$key;
          $map[$key] = intval($val);
          break;
        case "sort":
          $key = $document_photo_name.".".$key;
          $map[$key] = intval($val);
          break; 
        
       case "id":
          $key = $document_photo_name.".".$key;
          $map[$key] = intval($val);
          break;         
        case "model_id":
          $key = $document_table_name.".model_id";
          if(empty($val)){
            // 不允许在一个页面里列出所有document
            $map[$key] = -1;
          }else if(is_array($val)){
            $map[$key] = array('IN', $val);
          } else {
            $map[$key] = $val;
          }
          break;
          
      }
    }
    return array(
      "map" => $map,
      "join_tables" => $join_tables,
    );
  }
    /**
     * 计算列表总数
     * @param  number  $category 分类ID
     * @param  integer $status   状态
     * @return integer           总数
     */
    public function listDocumentCount($parmas){
      $perfix = C('DB_PREFIX');
      $search_res = $this->NewSearchMap($parmas);
      return $this->join("INNER JOIN {$perfix}document_article ON {$perfix}document.id={$perfix}document_article.id")->where($search_res["map"])->count();
    }
    /**
     * 计算列表总数
     * @param  number  $category 分类ID
     * @param  integer $status   状态
     * @return integer           总数
     */
    public function listCount($category, $status = 1){
        $map = $this->listMap($category, $status);
        return $this->where($map)->count('id');
    }

    /**
     * 获取详情页数据
     * @param  integer $id 文档ID
     * @return array       详细数据
     */
    public function detail($id){
        /* 获取基础数据 */
        $info = $this->field(true)->find($id);
        if ( !$info ) {
            $this->error = '文档不存在';
            return false;
        }elseif(!(is_array($info)) || 1 != $info['status']){
            $this->error = '文档被禁用或已删除！';
            return false;
        }
        /* 获取模型数据 */
        $logic  = $this->logic($info['model_id']);
        $detail = $logic->detail($id); //获取指定ID的数据

        if(!$detail){
            $this->error = $logic->getError();
            return false;
        }
        return array_merge($info, $detail);
    }

    /**
     * 返回前一篇文档信息
     * @param  array $info 当前文档信息
     * @return array
     */
    public function prev($info){
        $map = array(
            'id'          => array('lt', $info['id']),
            'pid'     => 0,
            'category_id' => $info['category_id'],
            'status'      => 1,
            //'create_time' => array('lt', NOW_TIME),
            //'_string'     => 'deadline = 0 OR deadline > ' . NOW_TIME,        
        );

        /* 返回前一条数据 */
        return $this->field(true)->where($map)->order('id DESC')->find();
    }

    /**
     * 获取下一篇文档基本信息
     * @param  array    $info 当前文档信息
     * @return array
     */
    public function next($info){
        $map = array(
            'id'          => array('gt', $info['id']),
            'pid'     => 0,
            'category_id' => $info['category_id'],
            'status'      => 1,
            //'create_time' => array('lt', NOW_TIME),
            //'_string'     => 'deadline = 0 OR deadline > ' . NOW_TIME,        
        );

        /* 返回下一条数据 */
        return $this->field(true)->where($map)->order('id')->find();
    }

    public function update(){
        /* 检查文档类型是否符合要求 */
        $Model = new \Admin\Model\DocumentModel();
        $res = $Model->checkDocumentType( I('type'), I('pid') );
        if(!$res['status']){
            $this->error = $res['info'];
            return false;
        }

        /* 获取数据对象 */
        $data = $this->field('pos,display', true)->create();
        if(empty($data)){
            return false;
        }

        /* 添加或新增基础内容 */
        if(empty($data['id'])){ //新增数据
            $id = $this->add(); //添加基础内容

            if(!$id){
                $this->error = '添加基础内容出错！';
                return false;
            }
            $data['id'] = $id;
        } else { //更新数据
            $status = $this->save(); //更新基础内容
            if(false === $status){
                $this->error = '更新基础内容出错！';
                return false;
            }
        }

        /* 添加或新增扩展内容 */
        $logic = $this->logic($data['model_id']);
        if(!$logic->update($data['id'])){
            if(isset($id)){ //新增失败，删除基础数据
                $this->delete($data['id']);
            }
            $this->error = $logic->getError();
            return false;
        }

        //内容添加或更新完成
        return $data;

    }

    /**
     * 获取段落列表
     * @param  integer $id    文档ID
     * @param  integer $page  显示页码
     * @param  boolean $field 查询字段
     * @param  boolean $logic 是否查询模型数据
     * @return array
     */
    public function part($id, $page = 1, $field = true, $logic = true){
        $map  = array('status' => 1, 'pid' => $id, 'type' => 3);
        $info = $this->field($field)->where($map)->page($page, 10)->order('id')->select();
        if(!$info) {
            $this->error = '该文档没有段落！';
            return false;
        }

        /* 不获取内容详情 */
        if(!$logic){
            return $info;
        }

        /* 获取内容详情 */
        $model = $logic = array();
        foreach ($info as $value) {
            $model[$value['model_id']][] = $value['id'];
        }
        foreach ($model as $model_id => $ids) {
            $data   = $this->logic($model_id)->lists($ids);
            $logic += $data;
        }

        /* 合并数据 */
        foreach ($info as &$value) {
            $value = array_merge($value, $logic[$value['id']]);
        }

        return $info;
    }

    /**
     * 获取指定文档的段落总数
     * @param  number $id 段落ID
     * @return number     总数
     */
    public function partCount($id){
        $map = array('status' => 1, 'pid' => $id, 'type' => 3);
        return $this->where($map)->count('id');
    }

    /**
     * 获取推荐位数据列表
     * @param  number  $pos      推荐位 1-列表推荐，2-频道页推荐，4-首页推荐
     * @param  number  $category 分类ID
     * @param  number  $limit    列表行数
     * @param  boolean $filed    查询字段
     * @return array             数据列表
     */
    public function position($pos, $category = null, $limit = null, $field = true){
        $map = $this->listMap($category, 1, $pos);

        /* 设置列表数量 */
        is_numeric($limit) && $this->limit($limit);

        /* 读取数据 */
        return $this->field($field)->where($map)->select();
    }

    /**
     * 获取数据状态
     * @return integer 数据状态
     * @author huajie <banhuajie@163.com>
     */
    protected function getStatus(){
        $cate = I('post.category_id');
        $check = M('Category')->getFieldById($cate, 'check');
        if($check){
            $status = 2;
        }else{
            $status = 1;
        }
        return $status;
    }

    /**
     * 获取根节点id
     * @return integer 数据id
     * @author huajie <banhuajie@163.com>
     */
    protected function getRoot(){
        $pid = I('post.pid');
        if($pid == 0){
            return 0;
        }
        $p_root = $this->getFieldById($pid, 'root');
        return $p_root == 0 ? $pid : $p_root;
    }

    /**
     * 获取扩展模型对象
     * @param  integer $model 模型编号
     * @return object         模型对象
     */
    private function logic($model){
        $name  = parse_name(get_document_model($model, 'name'), 1);
        $class = is_file(MODULE_PATH . 'Logic/' . $name . 'Logic' . EXT) ? $name : 'Base';
        $class = MODULE_NAME . '\\Logic\\' . $class . 'Logic';
        return new $class($name);     
    }

    /**
     * 设置where查询条件
     * @param  number  $category 分类ID
     * @param  number  $pos      推荐位
     * @param  integer $status   状态
     * @return array             查询条件
     */
    private function listMap($category, $status = 1, $pos = null){
        /* 设置状态 */
        $map = array('status' => $status, 'pid' => 0);

        /* 设置分类 */
        if(!is_null($category)){
            if(is_numeric($category)){
                $map['category_id'] = $category;
            } else {
                $map['category_id'] = array('in', str2arr($category));
            }
        }

        $map['create_time'] = array('lt', NOW_TIME);
        $map['_string']     = 'deadline = 0 OR deadline > ' . NOW_TIME;

        /* 设置推荐位 */
        if(is_numeric($pos)){
            $map[] = "position & {$pos} = {$pos}";
        }

        return $map;
    }
    public function news_list(){
      $news_arr = unserialize(S("news"));
      if(empty($news_arr)){
        $category = D('Category')->getTree(C("News"));
        if(isset($category["_"])){
            foreach($category["_"] as $row){
              $new_category[]= $row["id"];
            }
        }
        $map["category_id"] = array("in",$new_category);
        $map["status"] = 1;
        $new_lists    = $this->autor_list($map,1,2); 
        S('news',serialize($new_lists),3600);
        return $new_lists;
      }else{
        return $news_arr;
      }
   
    }
    public function cases_list(){
      $cases_arr = unserialize(S("cases"));
      if(empty($cases_arr)){
        $category = D('Category')->getTree(C("CASES"));
        if(isset($category["_"])){
            foreach($category["_"] as $row){
              $new_category[]= $row["id"];
            }
        }
        $map["category_id"] = array("in",$new_category);
        $map["status"] = 1;
        $new_lists    = $this->new_list($map,1,2); 
        S('cases',serialize($new_lists),3600);
        return $new_lists;
      }else{
        return $cases_arr;
      }
   
    }
    /**
     * 设置where查询条件
     * @param  string  $tagname   关键词 请以头号分隔
     * @param  bool $is_date   是否要加入时间
     * @return array             查询条件
     */
    public function keywordMap($tagname,$is_date){
      $keywords = $tagname;
      $keywords_arr = preg_split("/[,，]+/", $keywords);//对关键词进行分隔

      $map["d.status"] = 1;
      $perfix = C('DB_PREFIX');   
      if(count($keywords_arr) == 1){      
        $map["dr.keywords"] = array('like','%'.$keywords.'%');
      }else{
        foreach ($keywords_arr as $key => $keywords) {
          $row[$key] = '%'.$keywords.'%'; 
        }
        $map["dr.keywords"] = array('like',$row,'OR');
      }
      if($is_date){
        $map["FROM_UNIXTIME(dr.tag_date,'%m-%d')"] = array('like','%'.date("m-d").'%');
      }
      $map["d.category_id"] = C("TAG");
      return $map;
    }
    /*传入城市id 返回坐标*/
    public function get_lan($province_id){
      $province = M("area")->where("id=".$province_id)->field("name")->find();
      $result_arr = $this->get_lng_lat_by_baidu($province["name"]);
      return $result_arr;
    }
    // 根据地址名称查询经纬度
    public function get_lng_lat_by_baidu($site){
    
      $url = "http://api.map.baidu.com/geocoder/v2/?ak=7685db62854083340d64ba0b1618277e&output=json&address=$site&city=" . CURRENT_CITY;
    
      $contents = $this->curl_http($url);

  
      $location['lng'] = 0.00;
      $location['lat'] = 0.00;
      if ($contents == '') {
          return $location;
      }
  
      $json = json_decode($contents, true);
      if ($json['status'] == '0') {
          $result = $json['result'];
          if (!empty($result)) {
              $location = $result['location'];
              if (!empty($loc)) {
                  $location['lng'] = $loc['lng'];
                  $location['lat'] = $loc['lat'];
              }
          }
      }
      return array("lat"=>$location['lat'],"lng"=>$location['lng']);
    }
    public function curl_http ($url, $timeout = 1, $postfield = '') {
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
      //curl_setopt($ch, CURLOPT_NOSIGNAL, 1);     //注意，毫秒超时一定要设置这个
      //curl_setopt($ch, CURLOPT_TIMEOUT_MS, 200);  //超时毫秒，cURL 7.16.2中被加入。从PHP 5.2.3起可使用
      if ($postfield != '') {
          curl_setopt($ch, CURLOPT_POST, 1);//启用POST提交
          curl_setopt($ch, CURLOPT_POSTFIELDS, $postfield); //设置POST提交的字符串
      }
  
      $data = curl_exec($ch);
      $curl_errno = curl_errno($ch);
      $curl_error = curl_error($ch);
      curl_close($ch);
  
      if ($curl_errno == 0) {
          return $data;
      }
      return '';
    }
}

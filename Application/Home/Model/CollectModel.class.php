<?php
namespace Home\Model;
use Think\Model;

class CollectModel extends Model{
   /* 自动完成规则 */
    protected $_auto = array(

      array('school_name', 'htmlspecialchars', self::MODEL_BOTH, 'function'),
      array('address', 'htmlspecialchars', self::MODEL_BOTH, 'function'),
      array('email', 'email', '邮箱格式不正确'),
      array('create_at', NOW_TIME, self::MODEL_INSERT),
       
    );
  /*
    保存用户填写的信息
     * @param  array   $info     信息
     * @return int     $collect_id         
  */
	public function saveinfo($info){
		//$info["create_at"] = time(); 

		if($this->create($info)){
		  $collect_id =  $this->add();
      return $collect_id;
    }else{
      return $this->getError();
    }
    
		
		
	}
  


		
}
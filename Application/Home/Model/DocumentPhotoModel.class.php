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

/**
 * 分类模型
 */
class DocumentPhotoModel extends Model{

	/**
	 * 生成明信片类
	 * @param  string $img_url    要合成的图
	 * @param  string $key_text   传入要合成的文字
   * @param  int $is_preview    是否是预览图
	 * return  string             目标图带路径
	 */
	public function image_text($img_url,$key_text,$is_preview=0,$photo_id,$is_art=0){

    $app_root = APP_ROOT;
    $image = new \Think\Image();
		 
    $file_name = time().rand(1000, 999999).".jpg";
    //$file_name = "news.png";
    $source_image_path = $app_root."/Public/layouts/img/postcard.jpg";
		if($is_preview == 1){//预览
      $target_image = "Uploads/Picture/Preview/".date("Y-m-d")."/";
    }else{
      $target_image = "Uploads/Picture/".date("Y-m-d")."/";
    }

    mkdir($target_image, 0777, true);
    $target_image_path = $app_root."/".$target_image.$file_name;
    if($is_art == 1){//Art+图片

      $img_url_path = ART_ROOT.$img_url;
      $thumb_img_url = ART_ROOT.$this->getThumbName($img_url);
    }else{

      $img_url_path = $app_root.$img_url;
      $thumb_img_url = $app_root.$this->getThumbName($img_url);
    }


		$image->open($img_url_path)->thumb(235, 157,\Think\Image::IMAGE_THUMB_CENTER)->save($thumb_img_url);

		$font = $app_root.'/Public/layouts/font/xjlFont.fon';
    //$image->open($source_image_path);
		$image->open($source_image_path)->water($thumb_img_url,array("37","90"),100)->save($target_image_path); 

		$key_text = str_replace("\n","",$key_text);
		$content = D("DocumentPhoto")->autowrap(16,0,$font,$key_text,209);

		$image->open($target_image_path)->text($content,$app_root.'/Public/layouts/font/xjlFont.fon',16,'#000000',array("328","120"))->save($target_image_path); 
		//$image->open($target_image_path)->box_text($key_text,$app_root.'/Public/layouts/font/fzlth.TTF',100,20,'',20,'#000000',array("100","80"))->save($target_image_path); 



    return "/".$target_image.$file_name;
  }
	/**
	 * 功能 ： 控制合成文字换行
   * fontsize 字体大小
   * string 合成文字
   * angle 旋转角度
   * filepath 生成图片路径
   * fontface 字体路径
   * width 合成文字宽度
   */ 
  public function autowrap($fontsize, $angle, $fontface, $string, $width) {
    // 这几个变量分别是 字体大小, 角度, 字体名称, 字符串, 预设宽度
    $content = "";
    // 将字符串拆分成一个个单字 保存到数组 letter 中
    for ($i=0;$i<mb_strlen($string);$i++) {
      $letter[] = mb_substr($string, $i, 1,'utf-8');
    }
    
    foreach ($letter as $l) {
      $teststr = $content." ".$l;
      $testbox = imagettfbbox($fontsize, $angle, $fontface, $teststr);
      
      // 判断拼接后的字符串是否超过预设的宽度
      if (($testbox[2] > $width) && ($content !== "")) {
      $content .= "\n";
      }
      $content .= $l;
    }
    return $content;
  }
	/*
		保存明信片功能
		* @param  array $info    要保存的用户信息
		* return  int   $id      生成明星片id.
	*/
	public function saveCard($info){
		if(empty($info)){
			return 0;
		}
		$info["create_at"] = time();
		$id = M("postcard")->add($info);
		if($id){
			return $id;
		}
	}
	/*取用模板分类*/
	public function getSort(){
		$map["model_id"] = 18;
		$map["name"] = 'sort';
		$attribute_arr = M("attribute")->where($map)->field("extra")->find();


		return parse_field_attr($attribute_arr["extra"]);
	}
  /*
    转换小图路径
  */
  public function getThumbName($str){
    $arr = explode(".",$str);
    $new_name = $arr[0]."_small.".$arr[1];
    return $new_name;

  }

}

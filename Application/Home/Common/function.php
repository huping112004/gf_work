<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

/**
 * 前台公共库文件
 * 主要定义前台公共函数库
 */

/**
 * 检测验证码
 * @param  integer $id 验证码ID
 * @return boolean     检测结果
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function check_verify($code, $id = 1){
	$verify = new \Think\Verify();
	return $verify->check($code, $id);
}

/**
 * 获取列表总行数
 * @param  string  $category 分类ID
 * @param  integer $status   数据状态
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function get_list_count($category, $status = 1){
    static $count;
    if(!isset($count[$category])){
        $count[$category] = D('Document')->listCount($category, $status);
    }
    return $count[$category];
}

/**
 * 获取段落总数
 * @param  string $id 文档ID
 * @return integer    段落总数
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function get_part_count($id){
    static $count;
    if(!isset($count[$id])){
        $count[$id] = D('Document')->partCount($id);
    }
    return $count[$id];
}

/**
 * 获取导航URL
 * @param  string $url 导航URL
 * @return string      解析或的url
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function get_nav_url($url){
    switch ($url) {
        case 'http://' === substr($url, 0, 7):
        case '#' === substr($url, 0, 1):
            break;        
        default:
            $url = U($url);
            break;
    }
    return $url;
}

function get_cover_url($cover_id) {
	$url = get_cover ( $cover_id, 'path' );
	if (empty ( $url ))
		return '';
	
	return  $url;
}
/**
 * 获取缩略图
 * @param unknown_type $filename 原图路劲、url
 * @param unknown_type $width 宽度
 * @param unknown_type $height 高
 * @param unknown_type $cut 是否切割 默认不切割
 * @return string
 */
function getThumbImage($filename, $width = 100, $height = 'auto',$cut=false, $replace = false)
{
    define('UPLOAD_URL', '');
    define('UPLOAD_PATH', '');
    $filename = str_ireplace(UPLOAD_URL, '', $filename); //将URL转化为本地地址
    $info = pathinfo($filename);
    $oldFile = $info['dirname'] . DIRECTORY_SEPARATOR . $info['filename'] . '.' . $info['extension'];
    $thumbFile = $info['dirname'] . DIRECTORY_SEPARATOR . $info['filename'] . '_' . $width . '_' . $height . '.' . $info['extension'];

    $oldFile = str_replace('\\', '/', $oldFile);
    $thumbFile = str_replace('\\', '/', $thumbFile);


    $filename = ltrim($filename, '/');
    $oldFile = ltrim($oldFile, '/');
    $thumbFile = ltrim($thumbFile, '/');
    //原图不存在直接返回
    if (!file_exists(UPLOAD_PATH . $oldFile)) {
        @unlink(UPLOAD_PATH . $thumbFile);
        $info['src'] = $oldFile;
        $info['width'] = intval($width);
        $info['height'] = intval($height);
        return $info;
        //缩图已存在并且 replace替换为false
    } elseif (file_exists(UPLOAD_PATH . $thumbFile) && !$replace) {
        $imageinfo = getimagesize(UPLOAD_PATH . $thumbFile);
        //dump($imageinfo);exit;
        $info['src'] = $thumbFile;
        $info['width'] = intval($imageinfo[0]);
        $info['height'] = intval($imageinfo[1]);
        return $info;
        //执行缩图操作
    } else {
        $oldimageinfo = getimagesize(UPLOAD_PATH . $oldFile);
        $old_image_width = intval($oldimageinfo[0]);
        $old_image_height = intval($oldimageinfo[1]);
        if ($old_image_width <= $width && $old_image_height <= $height) {
            @unlink(UPLOAD_PATH . $thumbFile);
            @copy(UPLOAD_PATH . $oldFile, UPLOAD_PATH . $thumbFile);
            $info['src'] = $thumbFile;
            $info['width'] = $old_image_width;
            $info['height'] = $old_image_height;
            return $info;
        } else {
            //生成缩略图
            // tsload( ADDON_PATH.'/library/Image.class.php' );
            // if($cut){
            //     Image::cut(UPLOAD_PATH.$filename, UPLOAD_PATH.$thumbFile, $width, $height);
            // }else{
            //     Image::thumb(UPLOAD_PATH.$filename, UPLOAD_PATH.$thumbFile, '', $width, $height);
            // }
            //生成缩略图 - 更好的方法
            if ($height == "auto") $height = 0;
            if ($width == "auto") $width = 0;
            // import('phpthumb.PhpThumbFactory');
            require_once('ThinkPHP/Library/Vendor/PhpThumb/PhpThumbFactory.class.php');

            $thumb = PhpThumbFactory::create(UPLOAD_PATH . $filename);
            //dump($thumb);exit;
            if ($cut) {
                $thumb->adaptiveResize($width, $height);
            } else {
                $thumb->resize($width, $height);
            }
            $res = $thumb->save(UPLOAD_PATH . $thumbFile);
            //缩图失败
            if (!$res) {
                $thumbFile = $oldFile;
            }
            $info['width'] = $width;
            $info['height'] = $height;
            $info['src'] = $thumbFile;
            return $info;
        }
    }
}


function get_thumb_src($picture_path, $width = 100, $height = 'auto', $cut = false, $replace = false)
{
    if(empty($picture_path)){
        return "";
    }
    $attach = getThumbImage($picture_path, $width, $height, $cut, $replace);

    return "/".$attach['src'];
}

/**
* 获取签名
* @param array $arrdata 签名数组
* @param string $method 签名方法
* @return boolean|string 签名值
*/
function getSignature($arrdata, $method="sha1") {
    if(!function_exists($method)){
        return false;
    }
    ksort($arrdata);
    // debug_echo($arrdata);
    $paramstring = "";
    foreach($arrdata as $key => $value){
        if(empty($value)){
            continue;
        }else{
            $key = strtolower($key);
            $value = strtolower($value);
        }

        if($key == "signature"){
            continue;
        }
        if(strlen($paramstring) == 0){
            $paramstring .= $key . "=" . $value;
        }else{
            $paramstring .= "&" . $key . "=" . $value;
        }
    }
	$paramstring .="&secret=".C("TAG_TOKEN");
    if(empty($paramstring)){
        return false;
    }else{
        $Sign = $method($paramstring);
        return $Sign; 
    }
}

/**
* 验证签名是否正确
* @param array $arrdata 参数数组
* @param string $method 签名方法
* @return boolean 是否正确
*/
function checkSignature($arrdata, $method="sha1"){
	//$arrdata["secret"] = "asdZXCQWE123";
    $signature = $arrdata["signature"];
    $tmpStr = getSignature($arrdata, $method);

    if($tmpStr == $signature ){
        return true;
    }else{
        return false;
    }
}


function curlurl($url_get){
    $ch = curl_init();
    curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt ($ch, CURLOPT_URL, $url_get);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

 // 分析枚举类型字段值 格式 a:名称1,b:名称2
 // 暂时和 parse_config_attr功能相同
 // 但请不要互相使用，后期会调整
function parse_field_attr($string) {
	
		
    if(0 === strpos($string,':')){
			//echo $string;
       // 采用函数定义
			return eval('return '.substr($string,0).';');
				
      //return   eval('return '.substr($string,0).';');
    }elseif(0 === strpos($string,'[')){
        // 支持读取配置参数（必须是数组类型）

        return C(substr($string,1,-1));
    }

		
    $array = preg_split('/[,;\r\n]+/', trim($string, ",;\r\n"));
    if(strpos($string,':')){
        $value  =   array();
        foreach ($array as $val) {
            list($k, $v) = explode(':', $val);
            $value[$k]   = $v;
        }
    }else{
        $value  =   $array;
    }
    return $value;
}

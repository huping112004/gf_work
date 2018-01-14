<?php
namespace Common\Model;
use Think\Model;
/**
 * 经销商
 */
class AreaModel extends Model{

  public function children($parent_id){
    return $this->field("id,name")->where(array("parent_id" => $parent_id))->select();;
  }

  public function find_by_names($province_name, $city_name, $state_name){
    $perfix = C('DB_PREFIX');

    // echo("province_name: {$province_name}, city name: {$city_name}, state name: {$state_name}<br/>");
    switch($province_name){

    case "福州":
      $province_name = "福建省";
      break;
    case "川东":
      $province_name = "四川省";
      break;
    case "新疆":
      $province_name = "新疆维吾尔自治区";
      break;
    case "新疆维吾尔自治区省":
      $province_name = "新疆维吾尔自治区";
    break;  
    case "内蒙":
      $province_name = "内蒙古自治区";
      break;
    case "宁夏":
      $province_name = "宁夏回族自治区";
      break;
    case "广西":
      $province_name = "广西壮族自治区";
      break;
    case "广西省":
      $province_name = "广西壮族自治区";
      break;  
    case "宁夏":
      $province_name = "宁夏回族自治区";
      break;
    case "宁夏回族自治区省":
      $province_name = "宁夏回族自治区";
      break;  
      
    case "天津市":
      $province_name = "天津";
      break;
    case "重庆市":
      $province_name = "重庆";
      break;
    case "上海市":
      $province_name = "上海";
      break;
    case "北京市":
      $province_name = "北京";
      break;
    case "北京省":
      $province_name = "北京";
      break; 
    case "重庆省":
      $province_name = "重庆";
      break; 
    case "内蒙古自治区巴":
      $province_name = "内蒙古自治区";
      break;   
    }

    switch ($state_name){
     case "仙游县盖尾镇":
      $state_name = "仙游县";
      break;  
    case "仙桃":
    case "潜江":
    case "龙文区":
      $state_name = "";
      break;
    case "双清区":
      $state_name = "其它区";
      break;  
    case "天门":
      $state_name = "";
      break;
    case "襄阳区":
    case "楚州区":
      $state_name = "其它区";
      break;
    case "元坝区":
      $state_name = "昭化区";
      break;
    case "杨凌区":
      $state_name = "杨陵区";
      break;
    case "白云矿区":
      $state_name = "白云鄂博矿区";
      break;
    case "八道江区":
      $state_name = "浑江区";
      break;
    case "唐海县":
      $state_name = "曹妃甸区";
      break;
    case "荔蒲县":
      $state_name = "荔浦县";
      break;
    case "江洲区":
      $state_name = "江州区";
      break;
    case "石河子市":
    case "石河子":
    case "塔城地区":
    case "市中区":
    case "济源":
    case "毕节市":
    case "铜仁市":
    case "中山":
    case "东莞":
    case "嘉峪关":
      $state_name = 0;
      break;
    }


    switch($city_name){
    case "库尔勒":
      $city_name = "巴音郭楞蒙古自治州";
      break;
    case "库尔勒市":
      $city_name = "巴音郭楞蒙古自治州";
      break;
    case "荷泽市":
      $city_name = "菏泽市";
    break;  
    case "马店市":
      $city_name = "驻马店市";
      break;    
    case "惠城":
      $city_name = "惠州市";
      break;
    case "宜兴市":
      $city_name = "无锡市";
    break;  
    case "巢湖市":
      $state_name = $city_name;
      $city_name = "合肥市";
      break;
    case "襄樊市":
      $city_name = "襄阳市";
      break;
    case "花都":
      $city_name = "广州市";
      break;
    // case "楚雄州":
    //   $city_name = "楚雄彝族自治州";
    //   break;
    case "金水区":
    case "新郑市":
      $city_name = "郑州市";
      break;
    case "呼和浩特":
      $city_name = "呼和浩特市";
      break;
    case "安吉":
      $city_name = "湖州市";
      break;
    case "湖里":
      $city_name = "厦门市";
      break;
    case "江山市":
      $city_name = "衢州市";
      break;
    case "昆山":
      $city_name = "苏州市";
      break;

    case "瑞安市":
    case "泰顺县":
      $city_name = "温州市";
      break;
    case "洪湖":
    case "江陵":
      $city_name = "荆州市";
      break;
    case "文山州":
      $city_name = "文山壮族苗族自治州";
      break;
    case "宜兴":
      $city_name = "无锡市";
      break;
    case "辉县市":
      $city_name = "新乡市";
      break;
    case "毫州市":
      $city_name = "亳州市";
      break;
    case "重庆":
      $city_name = "重庆市";
      break;
    case "上海":
      $city_name = "上海市";
      break;
    case "北京":
      $city_name = "北京市";
      break;
    case "塘沽区":
    case "武清":
    case "天津":
      $city_name = "天津市";
      break;
    case "大理市":
      $city_name = "大理白族自治州";
      break;
    // case "红河州":
    //   $city_name = "红河哈尼族彝族自治州";
    //   break;
    // case "西双版纳州":
    //   $city_name = "西双版纳傣族自治州";
    //   break;
    // case "德宏州":
    //   $city_name = "德宏傣族景颇族自治州";
    //   break;
    // case "恩施":
    //   $city_name = "恩施土家族苗族自治州";
    //   break;
    case "丽水":
      $city_name = "丽水市";
      break;
    // case "恩施":
    //   $city_name = "恩施土家族苗族自治州";
      break;
    case "仙游":
      $city_name = "莆田市";
      break;
    case "涪陵":
      $city_name = "涪陵区";
      break;
    case "溧水县":
      $city_name = "南京市";
      break;
    case "云霄":
      $city_name = "漳州市";
      break;
    case "龙游县":
      $city_name = "衢州市";
      break;
    case "吕梁地区":
      $city_name = "吕梁市";
      break;
    case "海东地区":
      $city_name = "海东市";
      break;
    case "铜仁地区":
      $city_name = "铜仁市";
      break;
    case "毕节地区":
      $city_name = "毕节市";
      break;
    case "乌兰察布盟":
      $city_name = "乌兰察布市";
      break;
    case "巴彦淖尔盟":
      $city_name = "巴彦淖尔市";
      break;
    case "琼山区":
      $city_name = "海口市";
      break;
    case "定西地区":
      $city_name = "定西市";
      break;
    case "陇南地区":
      $city_name = "陇南市";
      break;
    case "省直辖县级行政单位":
      $city_name = 0;
      $state_name = 0;
      break;
    }

    $select_fields = array("p.id AS province_id, p.name AS province_name");
    $map = array("p.name" => $province_name);
    $res = $this->table("{$perfix}area p");
    if($city_name){
      $res = $res->join("LEFT JOIN {$perfix}area c ON p.id=c.parent_id");
      array_push($select_fields, "c.id AS city_id, c.name AS city_name");
      if(preg_match('/州$/', $city_name)){
        $reg_name = preg_replace('/州/', '%', $city_name);
        $map["c.name"] = array("LIKE", $reg_name);
      }else{
        $map["c.name"] = $city_name;
      }
    }

    echo("state_name: {$state_name}<br/>");
    if($state_name){
      $reg_name = preg_replace('/市|区|县|州|镇/', '%', $state_name);
      $res = $res->join("LEFT JOIN {$perfix}area s ON c.id=s.parent_id");
      array_push($select_fields, "s.id AS state_id, s.name AS state_name");
      $map["s.name"] = array("LIKE", $reg_name);
    }
    
    $item = $res->field($select_fields)
                ->where($map)->find();
    $sql = $this->getLastSql();
    echo($sql."<br/>");
    $area_id = 0;
    if(!empty($state_name) && !empty($item["state"])){
      $area_id = $item["state"];
    }else if(!empty($city_name) && !empty($item["city"])){
      $area_id = $item["city"];
    }else{
      $area_id = $item["province"];
    }

    $item["area_id"] = $area_id;
    return $item;
  }
}
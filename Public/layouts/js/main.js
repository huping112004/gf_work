//附件的学校
function near(longitude,latitude){
  $.ajax({
    url:app+'/Index/do_near',
    data: {'longitude':longitude,'latitude':latitude},
    type: 'post',
    dataType:"json",
    beforeSend: function (){
      //$('.submit_username').attr({'disabled':true});
    },
    success: function (data){
      //$('.submit_username').attr({'disabled':false});
      if(data.status == 1) {
        //alert(data.info.id);

        //show_info(longitude,latitude);
        location.href = app+"/Index/show_brushing/longitude/"+longitude+"/latitude/"+latitude+"/bid/"+data.info.id;
      }
      else if(data.status == "-2"){//老用户
        alert(data.error_msg);
      }
      else if(data.status == "-1"){
         alert(data.error_msg);
      }
    }
  });
}
/*显示单个信息*/
function show_info(longitude,latitude){
  $.getScript("http://api.map.baidu.com/api?type=quick&ak=h9tnSylNx2757WXy2ebTlqNr&v=1.0");
  var sContent =
    "<h4 style='margin:0 0 5px 0;padding:0.2em 0'>天安门</h4>" + 
    "<img style='float:right;margin:4px' id='imgDemo' src='http://app.baidu.com/map/images/tiananmen.jpg' width='139' height='104' title='天安门'/>" + 
    "<p style='margin:0;line-height:1.5;font-size:13px;text-indent:2em'>天安门坐落在中国北京市中心,故宫的南侧,与天安门广场隔长安街相望,是清朝皇城的大门...</p>" + 
    "</div>";
  var map = new BMap.Map("allmap");
  var point = new BMap.Point(longitude, latitude);
  var marker = new BMap.Marker(point);
  var infoWindow = new BMap.InfoWindow(sContent);  // 创建信息窗口对象
  map.centerAndZoom(point, 15);
  map.addControl(new BMap.ZoomControl());          //添加地图缩放控件
  map.addOverlay(marker);
  marker.addEventListener("click", function(){          
     this.openInfoWindow(infoWindow);
     //图片加载完毕重绘infowindow
     document.getElementById('imgDemo').onload = function (){
       infoWindow.redraw();   //防止在网速较慢，图片未加载时，生成的信息框高度比图片的总高度小，导致图片部分被隐藏
     }
  });
}
//点赞功能
/*
  id 是传入要点赞的图片
*/
function praise(id,type){
  $.ajax({
    url:app+'/Photo/do_praise',
    data: {'id':id,'type':type},
    type: 'post',
    dataType:"json",
    beforeSend: function (){
      //$('.submit_username').attr({'disabled':true});
    },
    success: function (data){
      //$('.submit_username').attr({'disabled':false});
      if(data.status == 1) {
        //alert(data.error_msg);
        switch(type){
          case 1:
            $("#praises").text(parseInt($("#praises").text())+1);
            break;
          case 2:
            break;
          case 3:
            $("#praises").text(parseInt($("#praises").text())+1);
            break;  
          case 4:
            $("#praises").text(parseInt($("#praises").text())+1);
            break;
          default:
            alert(data.error_msg);  
              
        }
      
      }
      else if(data.status == "-2"){//老用户
        alert(data.error_msg);
      }
      else if(data.status == "-1"){
         alert(data.error_msg);
      }
    }
  });
}

function waterFalllist(click_item, target_selector, show_type){
  $clickItem = $(click_item);
  totalPage = parseInt($clickItem.data("total-page"));
  currentPage = parseInt($clickItem.attr("data-current-page"));
  if(currentPage < totalPage){　　　 
    $.ajax({
      url: $clickItem.attr("href"),
      data: {"p": currentPage+1},
      dataType:"HTML",
      beforeSend:function(){
        $("#fall-loading").css('display','block'); 
      },
      complete:function(){
        $("#fall-loading").css('display','none');
      },
      success:function(data){
        var $container = $(target_selector);
        $elems = $(data);
        if(show_type=="append"){
          $container.append($elems);
        }else if(show_type == "before"){
          $container.before($elems);
        }
        
        $clickItem.attr("data-current-page", ++currentPage);
        if(currentPage >= totalPage){
          $("#pagination").hide();

        }
        return false;
      }
    });
  }else{
    $("#pagination").hide();
  }
  return false;
}

//正在加载
function blocktheui(){
  $.blockUI({
    message: '<div><img src="'+public+'/images/loading.gif"></div>',
    css: {
      border: 'none',
      padding: '5px',
      backgroundColor: '#1A1A1A',
      width:    '170px',
      top:    '35%',
      left:   '45%',
      '-webkit-border-radius': '10px',
      '-moz-border-radius': '10px',
      opacity: .5,
      color: '#fff'
    }
  });
}

function blockuiout(){
  $.unblockUI();
}

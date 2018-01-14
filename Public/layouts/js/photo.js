$(function(){
  $("#c-prev").click(function(){
    var page = $("#page").val();
    if(page ==1){
      alert('已经是第一页');
      return false;
    }
    var goPage = parseInt(page)-1;
    if(goPage<1||goPage>parseInt($("#total_page").val())){
      return false;
    }
    var sortid=$("#sortid").val();
    getList(goPage,sortid);
  })
  $("#c-next").click(function(){
    //alert('test');  
    var page = $("#page").val();
    var goPage = parseInt(page)+1;
    if(goPage>parseInt($("#total_page").val())){
      alert('最后一页');
      return false;
    }
    var sortid=$("#sortid").val();
    getList(goPage,sortid);
  })
})
function getList(goPage,sortid) {


  //获取数据
  $.ajax({
      url:app+'/Photo/ajax_show_photo',
      data: {page:goPage,sortid:sortid},
      type: 'post',
      dataType:"json",
      beforeSend: function ()
      {
       // $(".btn-more").html("正在加载....");
      },
      success: function (data){
        flag = true;
        //判断是否加载完毕 加载完毕触发
        if(data){
          //alert(data.html);
          $("#page").val(data.page);
          $("#total_page").val(data.total_page);
          $("#show_photo").html(data.html);
          

        }
      }
        

    }); 

  }
  


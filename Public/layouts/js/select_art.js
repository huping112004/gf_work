$(function(){
  $(".myprev").click(function(){
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
  $(".mynext").click(function(){
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
      url:app+'/Photo/ajax_select_art',
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
          //alert($(".swiper-wrapper").html());
          $(".swiper-wrapper").html(data.html);
          var mySwiper= $('.swiper').swiper({
          loop:false,
          slidesPerView:2,
          nextButton: '.swiper-button-prev',
          prevButton: '.swiper-button-next',
          spaceBetween: 24,
          })
          $('.swiper-wrapper img').on('click',function(){
            $('.swiper-wrapper img').not(this).removeClass('clickin');
            $(this).addClass('clickin');
            $("#photo_id").val($(this).attr('dataid'));
          })

        }
      }
        

    }); 

  };

$().ready(function() {
  //手机号码验证
  jQuery.validator.addMethod("isPhone", function(value,element) {
      var length = value.length;
      var mobile = /^(((13[0-9]{1})|(15[0-9]{1}))+\d{8})$/;
      var tel = /^\d{3,4}-?\d{7,9}$/;
      return this.optional(element) || (tel.test(value) || mobile.test(value));
    }, "请正确填写您的手机号码");
    //validate.js
    $("#commentForms").validate({
      rules:{
        content:{
          required: true,
          minlength:2,

        },
  
      },
      messages:{  
        content:{
          required:"请输入内容",
          minlength:"请输入内容",

        },
      }
    });
});
//预览贺卡js
  function preview_photo(){
   
    var photo_id = $("#photo_id").val();
    if(photo_id == ''){
      alert('请先择贺卡图片！');
      return false;
    }
    if($("#content").val() == ''){
      alert('内容不能为空！');
      return false;
    }

    $.ajax({
      url:app+'/Photo/preview_art',
      data: {photo_id:photo_id,content:$("#content").val()},
      type: 'post',
      dataType:"json",
      beforeSend: function ()
      {
       // $(".btn-more").html("正在加载....");
      },
      success: function (data){
        if(data.status == "-1"){
          alert(data.error_msg);

        }else{
          $(".pc-float").html("<img src='"+data.target_img_url+"'>");
          $('.pc-float-bg,.pc-float').show();
        }
        
      }
        

    }); 
  }  
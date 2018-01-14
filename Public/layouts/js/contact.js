$().ready(function() {
	//手机号码验证
	jQuery.validator.addMethod("isPhone", function(value,element) {
			var length = value.length;
			var mobile = /^(((13[0-9]{1})|(15[0-9]{1}))+\d{8})$/;
			var tel = /^\d{3,4}-?\d{7,9}$/;
			return this.optional(element) || (tel.test(value) || mobile.test(value));
		}, "请正确填写您的手机号码");
		//validate.js
		$("#commentForm").validate({
			rules:{
				realname:{
					required:true,
					minlength:2,
					maxlength:50,
				},
				email:{
					required: true,
					email:true,
				},
				content:{
					required: true,
					minlength:2,

				},
	
			},
			messages:{	
				realname:{
					required:"请输入姓名",
					minlength:"请输入姓名",
					maxlength:"请输入姓名",
				},
				email:{
					required: "请输入正确的email",
					email:"请输入正确的email",
				},
				content:{
					required:"请输入内容",
					minlength:"请输入内容",

				},
			}
		});
});
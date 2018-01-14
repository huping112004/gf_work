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
				school_name:{
					required:true,
					minlength:2,
					maxlength:50,
				},
				address:{
					required: true,
					minlength:2,
					maxlength:50,
				},
				school_contacts:{
					required: true,
					minlength:2,
					maxlength:50,
				},
	
				reason:{
					required: true,
					minlength:1,
					maxlength:100,
				},
				real_info:{
					required: true,
					minlength:2,
					maxlength:100,
				},
	
				email:{
					required: true,
					email:true,
				},
				source:{
					required: true,

				},
				photo:{
					required: true,
					minlength:2,
	
				},
	
			},
			messages:{
	
				school_name:{
					required:"请输入正确学校名称",
					minlength:"请输入正确学校名称",
					maxlength:"请输入正确学校名称",
				},
				address:{
					required: "请输入详细地址",
					minlength:"请输入详细地址",
					maxlength:"请输入详细地址",
				},
				school_contacts:{
					required: "请输入学校联系人",
					minlength:"请输入学校联系人",
					maxlength:"请输入学校联系人",
				},
				reason:{
					required: "请输入推荐理由",
					minlength:"请输入推荐理由",
					maxlength:"请输入推荐理由",
				},
				real_info:{
					required: "请输入真实信息",
					minlength:"请输入真实信息",
					maxlength:"请输入真实信息",
				},
				email:{
					required: "请输入正确的email",
					email:"请输入正确的email",
				},
				source:{
					required: "请选择信息来源",
				},
				
				photo:{
					required: "请上传文件",
					minlength:"请上传文件",
	
				},
			}
		});
});
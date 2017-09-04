<body>
<?php $this->load->view('front/nav'); ?>
<div class="container">
    <div class="row">
        <div class="col-sm-4 reg-msg">
            <img class="img-responsive" src="<?=site_url('images/zhuce-1.png')?>">
        </div>
        <div class="col-sm-8 reg-img hidden-xs">
            <div class="row">
                <div class="col-sm-3">
                    <img class="img-responsive" src="<?=site_url('images/zhuce-2.png')?>">
                </div>
                <div class="col-sm-3">
                    <img class="img-responsive" src="<?=site_url('images/zhuce-3.png')?>">
                </div>
                <div class="col-sm-3">
                    <img class="img-responsive" src="<?=site_url('images/zhuce-4.png')?>">
                </div>
                <div class="col-sm-3">
                    <img class="img-responsive" src="<?=site_url('images/zhuce-5.png')?>">
                </div>
            </div>
        </div>
        <div class="col-sm-12 reg-msg">        
            <p>
            注册义工申请流程：1、在本网站登记成为会员；2、参与义工服务满10小时，并参与义工入门培训课程；3、到义工中心提交小一寸照片一张，身份证复印件一份；4、等待片刻，领取“注册义工证”。容桂义工中心地址：佛山市顺德区容桂街道容桂大道北33号（沙尾酒家对面）。
            </p>
        </div>
    </div>
    <h4 class="reg-title">注册表格 FORM</h4>
    <div class="register">
        <div class="row">
            <div class="col-sm-12">  
                <form id="register" class="form-horizontal" action="<?=site_url('member_auth/register')?> ">
                    <div class="form-group has-feedback">
                        <label class="col-sm-2 control-label"><span class="require">*</span> 用户名</label>
                        <div class="col-sm-8 input-box">
                        <input type="text" class="form-control" name="username" placeholder="用户名" datatype="*" nullmsg="请填写用户名" value="">
                        <span aria-hidden="true" class="glyphicon form-control-feedback"></span>
                        </div>
                    </div>
                    <div class="form-group has-feedback">
                        <label class="col-sm-2 control-label"><span class="require">*</span> 密码</label>
                        <div class="col-sm-3 input-box">
                        <input type="password" class="form-control" name="password" placeholder="密码" datatype="*6-16" nullmsg="请填写密码" value="">
                        <span aria-hidden="true" class="glyphicon form-control-feedback"></span>
                        </div>
                        <label class="col-sm-2 control-label"><span class="require">*</span> 确认密码</label>
                        <div class="col-sm-3 input-box">
                        <input type="password" class="form-control" name="confirm_password" placeholder="确认密码" datatype="*6-16" recheck="password" nullmsg="请填写确认密码"  value="">
                        </div>
                    </div>
                    <?php $this->load->view('front/member_profile_tmp'); ?>
                    <div class="col-sm-12 text-center">
                        <button class="btn" id="sub">注&nbsp;&nbsp;&nbsp;册</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('front/footer'); ?>

<link rel="stylesheet" type="text/css" href="<?=site_url('/assets/plugins/jquery-datetimepicker/jquery.datetimepicker.css')?>">
<script type="text/javascript" src="<?=site_url('/assets/plugins/jquery-datetimepicker/jquery.datetimepicker.full.min.js')?> "></script>
<script type="text/javascript">
$(function(){

    $('#serviceteam_category').on('change', function(event) {
        event.preventDefault();
        $('.team_checkbox').hide();
        var $me = $(this);
        var classElement = $($me.val());
        classElement.show();
    });
    var firstOption = $('#serviceteam_category').find("option:first");
    $(firstOption.val()).show();

    $.datetimepicker.setLocale('ch');
    $('.datepick').datetimepicker({
          format:"Y-m-d",      //格式化日期
          timepicker:false,    //关闭时间选项
          todayButton:false    //关闭选择今天按钮
    });

    // $('.datepick').datepicker({
    //     language: 'zh-CN',
    //     format: 'yyyy-mm-dd',
    //     autoclose: true
    // });

    var cform = $("#register").Validform({
        btnSubmit: "#sub",
        tiptype:1,
        ajaxPost:true,
        callback:function(response){
            if(response.status == 'y'){
                // alert('点击确定后跳转到首页');
                setTimeout("window.location = '<?=base_url()?>'", 3000);
            }
        },
        beforeSubmit:function(curform){
            //在验证成功后，表单提交前执行的函数，curform参数是当前表单对象。
            //这里明确return false的话表单将不会提交;
            if($('.serviceteam:checked').length > 8){
                $.Showmsg('义工服务项目最多选择八个');
                $('.serviceteam').focus();
                return false;
            }
            if($('.service_times:checked').length <= 0){
                $.Showmsg('可参与服务时间必须选择一个');
                $('.service_times').focus();
                return false;
            }
        },
        datatype:{//传入自定义datatype类型【方式二】;
            "idcard":function(gets,obj,curform,datatype){
                //该方法由佚名网友提供;
            
                var Wi = [ 7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2, 1 ];// 加权因子;
                var ValideCode = [ 1, 0, 10, 9, 8, 7, 6, 5, 4, 3, 2 ];// 身份证验证位值，10代表X;
            
                if (gets.length == 15) {   
                    return isValidityBrithBy15IdCard(gets);   
                }else if (gets.length == 18){   
                    var a_idCard = gets.split("");// 得到身份证数组   
                    if (isValidityBrithBy18IdCard(gets)&&isTrueValidateCodeBy18IdCard(a_idCard)) {   
                        return true;   
                    }   
                    return false;
                }
                return false;
                
                function isTrueValidateCodeBy18IdCard(a_idCard) {   
                    var sum = 0; // 声明加权求和变量   
                    if (a_idCard[17].toLowerCase() == 'x') {   
                        a_idCard[17] = 10;// 将最后位为x的验证码替换为10方便后续操作   
                    }   
                    for ( var i = 0; i < 17; i++) {   
                        sum += Wi[i] * a_idCard[i];// 加权求和   
                    }   
                    valCodePosition = sum % 11;// 得到验证码所位置   
                    if (a_idCard[17] == ValideCode[valCodePosition]) {   
                        return true;   
                    }
                    return false;   
                }
                
                function isValidityBrithBy18IdCard(idCard18){   
                    var year = idCard18.substring(6,10);   
                    var month = idCard18.substring(10,12);   
                    var day = idCard18.substring(12,14);   
                    var temp_date = new Date(year,parseFloat(month)-1,parseFloat(day));   
                    // 这里用getFullYear()获取年份，避免千年虫问题   
                    if(temp_date.getFullYear()!=parseFloat(year) || temp_date.getMonth()!=parseFloat(month)-1 || temp_date.getDate()!=parseFloat(day)){   
                        return false;   
                    }
                    return true;   
                }
                
                function isValidityBrithBy15IdCard(idCard15){   
                    var year =  idCard15.substring(6,8);   
                    var month = idCard15.substring(8,10);   
                    var day = idCard15.substring(10,12);
                    var temp_date = new Date(year,parseFloat(month)-1,parseFloat(day));   
                    // 对于老身份证中的你年龄则不需考虑千年虫问题而使用getYear()方法   
                    if(temp_date.getYear()!=parseFloat(year) || temp_date.getMonth()!=parseFloat(month)-1 || temp_date.getDate()!=parseFloat(day)){   
                        return false;   
                    }
                    return true;
                }
                
            }
            
        }
    });
})
</script>
</body>
</html>

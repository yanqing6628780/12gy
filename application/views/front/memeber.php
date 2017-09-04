<body>
<?php $this->load->view('front/nav'); ?>
<div class="page-container">
    <div class="row margin-bottom-20"></div>
    <div class="container min-hight margin-bottom-40">
        <div class="row">
            <div class="col-sm-2 col-md-2">
                <ul class="list-unstyled article_list member-nav">
                    <li <?php if($tab == 1): ?>class="active"<?php endif; ?> ><a href="#tab_1" data-toggle="tab">我的信息</a></li>
                    <li <?php if($tab == 2): ?>class="active"<?php endif; ?> ><a href="#tab_2" data-toggle="tab">我参加的活动</a></li>
                    <li <?php if($tab == 3): ?>class="active"<?php endif; ?> ><a href="#tab_3" data-toggle="tab">我的礼品</a></li>
                    <li <?php if($tab == 4): ?>class="active"<?php endif; ?> ><a href="#tab_4" data-toggle="tab">密码修改</a></li>
                    <li <?php if($tab == 5): ?>class="active"<?php endif; ?> ><a href="#tab_5" data-toggle="tab">意见反馈</a></li>
                </ul>        
            </div>
            <div class="col-sm-10 col-md-10">
                <div class="tab-content">
                    <div class="tab-pane <?php if($tab == 1): ?>active<?php endif; ?>" id="tab_1">
                        <?php $this->load->view('front/member_profile'); ?>
                    </div>
                    <div class="tab-pane <?php if($tab == 2): ?>active<?php endif; ?>" id="tab_2">
                        <?php $this->load->view('front/member_campaign'); ?>
                    </div>
                    <div class="tab-pane <?php if($tab == 3): ?>active<?php endif; ?>" id="tab_3">
                        <?php $this->load->view('front/member_exchange'); ?>
                    </div>
                    <div class="tab-pane <?php if($tab == 4): ?>active<?php endif; ?>" id="tab_4">
                        <?php $this->load->view('front/member_reset_password'); ?>
                    </div>
                    <div class="tab-pane <?php if($tab == 5): ?>active<?php endif; ?>" id="tab_5">
                        <?php $this->load->view('front/feedback'); ?>
                    </div>
                </div>
            </div>            
        </div>
    </div>
</div>
<?php $this->load->view('front/footer'); ?>
<link rel="stylesheet" type="text/css" href="<?=site_url('/assets/plugins/jquery-datetimepicker/jquery.datetimepicker.css')?>">
<script type="text/javascript" src="<?=site_url('/assets/plugins/jquery-datetimepicker/jquery.datetimepicker.full.min.js')?> "></script>
<script type="text/javascript">
$(function () {
    var reset_password_form = $("#reset_password_form").Validform({
        btnSubmit: '#editPsw_sub',
        tiptype:1,
        ajaxPost:true,
        callback:function(response){
            if(response.status == 'y'){
                reset_password_form.resetForm();
            }
        }
    });
    
    var send_mail = $("#send_mail").Validform({
        btnSubmit: '#send_sub',
        tiptype:1,
        ajaxPost:true,
        callback:function(response){
            if(response.status == 'y'){
                send_mail.resetForm();
            }
        }
    });

    $.datetimepicker.setLocale('ch');
    $('.datepick').datetimepicker({
          format:"Y-m-d",      //格式化日期
          timepicker:false,    //关闭时间选项
          todayButton:false    //关闭选择今天按钮
    });

    $('#serviceteam_category').on('change', function(event) {
        event.preventDefault();
        $('.team_checkbox').hide();
        var $me = $(this);
        var classElement = $($me.val());
        classElement.show();
    });
    var firstOption = $('#serviceteam_category').find("option:first");
    $(firstOption.val()).show();

    var user_edit_form = $("#user_edit").Validform({
        btnSubmit: '#editProfile_sub',
        tiptype:1,
        ajaxPost:true,
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
        callback:function(response){
            if(response.status == 'y'){
                window.location = '<?=site_url("member")?>';
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
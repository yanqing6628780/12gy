<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i></div>
    </div>
    <div class="portlet-body form">
        <form id='addForm' class="form-horizontal" action="<?php echo site_url("admin/member/servicetime_save")?>">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">用户名/姓名</label>
                    <div class="col-md-8">
                        <p class="form-control-static"><?php echo $username ?>/<?php echo $name ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">服务时长</label>
                    <div class="col-md-4">
                        <div class="input-group">
                            <input class="form-control" type='text' name="servicetime" value='' datatype="stock" nullmsg="请输入数量！"/>
                            <div class="help-block">正数为添加,负数为减少.例:-10为减少10</div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-offset-3 col-md-9"><div id="errormsg"></div></div>
                </div>
            </div>
            <div class="form-actions fluid">
                <div class="col-md-offset-3 col-md-9">
                    <input type='button' id="btn_sub" class="btn blue btn-lg" value='保存'/>
                    <input type="hidden" value="<?php echo $user_id ?>" name="user_id">
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
$(function () {
    var form = $("#addForm").Validform({
        btnSubmit: '#btn_sub',
        tiptype:function(msg,o,cssctl){
            var objtip=$("#errormsg");
            cssctl(objtip,o.type);
            objtip.text(msg);
        },
        datatype:{
            "stock" : /^-{0,1}\d+$/
        },
        ajaxPost:true,
        callback:function(response){
            if(response.status == "y"){         
                form.resetForm();
                $('#myModal').modal('hide');
                $('#member_view').click();
            }
        }
    });    
})
</script>
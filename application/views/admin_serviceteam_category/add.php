<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i>服务队类别添加</div>
    </div>
    <div class="portlet-body form">
        <form id='addForm' class="form-horizontal" action="<?php echo site_url($controller_url."add_save")?>">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">名称</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="name" value='' datatype="*" nullmsg="请输入名称！"/>
                    </div>
                </div>
            </div>
            <div class="form-actions fluid">
                <div class="col-md-offset-3 col-md-9">
                    <input type='button' id="btn_sub" class="btn blue btn-lg" value='保存'/>
                    <div id="errormsg"></div>
                </div>
            </div>
        </form>
    </div>
</div>
<link type="text/css" href="<?=base_url()?>assets/plugins/bootstrap-fileupload/bootstrap-fileupload.css" rel="stylesheet"/>
<link type="text/css" href="<?=base_url()?>assets/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet"/>
<script type="text/javascript">
$(function () {
    var form = $("#addForm").Validform({
        btnSubmit: '#btn_sub',
        tiptype:function(msg,o,cssctl){
            var objtip=$("#errormsg");
            cssctl(objtip,o.type);
            objtip.text(msg);
        },
        ajaxPost:true,
        callback:function(response){
            if(response.status == "y"){ 
                alert('添加成功');
                if(confirm('是否继续添加')){
                    form.resetForm();
                }else{
                    $("<?=$ajax_view?>").click();
                }
            }else{
                alert('添加失败');
            }
        }
    });    
})
</script>
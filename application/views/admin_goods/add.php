<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i></div>
    </div>
    <div class="portlet-body form">
        <form id='addForm' class="form-horizontal" action="<?php echo site_url($controller_url."add_save")?>">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">礼品名</label>
                    <div class="col-md-6">
                        <input class="form-control" type='text' name="name" value='' datatype="*" nullmsg="请输入名称！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">单价</label>
                    <div class="col-md-6">
                        <div class="input-group">
                            <input class="form-control" type='text' name="price" value='' datatype="n" nullmsg="请输入价格！"/>
                            <span class="input-group-addon">V币</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">礼品图</label>
                    <div class="col-md-7">
                        <div class=" input-group input-group-fixed">
                            <div class="input-group-btn"> 
                                <span class="btn green fileinput-button">
                                <i class="icon-paper-clip"></i> 
                                <span>上传</span>
                                <input type="file" name="files" id="upload" class="default">
                                </span>
                                <input class="form-control" datatype='*' type="text" id="picurl" name="img" placeholder="http://" value="" >
                            </div>
                        </div>
                        <span class="text-danger help-block" id="uploadstatus"></span>
                        <div class="thumbnail help-block col-md-12"><img class="img-responsive" src="<?=site_url('images/sample_img.png')?>" alt=""></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-offset-3 col-md-9"><div id="errormsg"></div></div>
                </div>
            </div>
            <div class="form-actions fluid">
                <div class="col-md-offset-3 col-md-9">
                    <input type='button' id="btn_sub" class="btn blue btn-lg" value='保存'/>
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
        datatype:{
            "price" : /^\d+\.{0,1}\d{0,2}$/
        },
        ajaxPost:true,
        callback:function(response){
            if(response.status == "y"){ 
                alert('添加成功');
                if(confirm('是否继续添加')){
                    form.resetForm();
                    $('#goods_view').click();
                }else{
                    $('#myModal').modal('hide');
                    $('#goods_view').click();
                }
            }else{
                alert('添加失败');
            }
        }
    });    

    $('#upload').fileupload({
        url: '<?=site_url("files/imgUpload/?dir=goods")?>',
        dataType: 'json',
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        maxFileSize: 1000000, // 1 MB
        done: function (e, data) {
            if(data.result.file){
                $('.thumbnail img').attr('src', data.result.file.url);
                $("#uploadstatus").html('上传成功');
                $("#picurl").val(data.result.file.photo_path);
            }
            else if(data.result.error){            
                $("#uploadstatus").html(data.result.error);
                $("#uploadstatus").show();
            }
        }
    });
})
</script>
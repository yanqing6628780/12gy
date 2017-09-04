<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i><?=$campaign_type[$type]?> 添加</div>
    </div>
    <div class="portlet-body form">
        <form id='addForm' class="form-horizontal" action="<?php echo site_url($controller_url."add_save")?>">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">标题</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="title" value='' datatype="*" nullmsg="请输入标题！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">缩略图</label>
                    <div class="col-md-4">
                        <div class=" input-group input-group-fixed">
                            <div class="input-group-btn"> 
                                <span class="btn green fileinput-button">
                                <i class="icon-paper-clip"></i> 
                                <span>上传</span>
                                <input type="file" name="files" id="upload" class="default">
                                </span>
                                <input class="form-control" datatype='*' type="text" id="picurl" name="thumb" placeholder="http://" value="" >
                            </div>
                        </div>
                        <span class="text-danger help-block" id="uploadstatus"></span>
                        <span class="text-danger help-block">请上传至少1600x900图片;小于该尺寸图片会造成留白</span>
                        <div class="thumbnail help-block col-md-6"><img class="img-responsive" src="<?=site_url('images/sample_img.png')?>" alt=""></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">地点</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="address" value='' datatype="*" nullmsg="请输入地点！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">发起人</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="sponsor" value='' datatype="*" nullmsg="请输入发起人！"/>
                    </div>
                </div>
                <?php if ($type == 1): ?>
                <div class="form-group">
                    <label class="col-md-3 control-label">服务时长</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="reward_time" value='' datatype="n" nullmsg="请输入整数时长！"/>
                    </div>
                </div>
                <?php endif ?>
                <div class="form-group">
                    <label class="col-md-3 control-label">时间</label>
                    <div class="col-md-4">
                        <input class="form-control date-picker" type='text' name="startdate" value='' datatype="*" nullmsg="请输入时间！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">是否关闭报名</label>
                    <div class="col-md-4">
                        <div class="radio-list">
                            <label class="pull-left">
                            <input type="radio" value="0" id="optionsRadios25" name="is_signup"> 关
                            </label>
                            <label class="pull-left">
                            <input type="radio" checked="" value="1" id="optionsRadios26" name="is_signup"> 开
                            </label> 
                         </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">是否关闭评论</label>
                    <div class="col-md-4">
                        <div class="radio-list">
                            <label class="pull-left">
                            <input type="radio" value="0" id="optionsRadios25" name="is_comment"> 关
                            </label>
                            <label class="pull-left">
                            <input type="radio" checked="" value="1" id="optionsRadios26" name="is_comment"> 开
                            </label> 
                         </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">是否关闭活动</label>
                    <div class="col-md-4">
                        <div class="radio-list">
                            <label class="pull-left">
                            <input type="radio" value="0" id="optionsRadios25" name="is_closed"> 关
                            </label>
                            <label class="pull-left">
                            <input type="radio" checked="" value="1" id="optionsRadios26" name="is_closed"> 开
                            </label> 
                         </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">活动详情</label>
                    <div class="col-md-9">
                        <textarea name="content" id="ck_editor" class="form-control"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">活动回顾</label>
                    <div class="col-md-9">
                        <textarea name="review" id="ck_editor2" class="form-control"></textarea>
                    </div>
                </div>
            </div>
            <div class="form-actions fluid">
                <div class="col-md-offset-3 col-md-9">
                    <input type='button' id="btn_sub" class="btn blue btn-lg" value='保存'/>
                    <input name="type" type="hidden" value="<?=$type?>">
                    <div id="errormsg"></div>
                </div>
            </div>
        </form>
    </div>
</div>
<link type="text/css" href="<?=base_url()?>assets/plugins/bootstrap-fileupload/bootstrap-fileupload.css" rel="stylesheet"/>
<link type="text/css" href="<?=base_url()?>assets/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet"/>
<script type="text/javascript" src="<?=base_url()?>assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js" charset="UTF-8"></script>
<script type="text/javascript">
$(function () {
    DatePicker.init1();

    var form = $("#addForm").Validform({
        btnSubmit: '#btn_sub',
        tiptype:function(msg,o,cssctl){
            var objtip=$("#errormsg");
            cssctl(objtip,o.type);
            objtip.text(msg);
        },
        ajaxPost:true,
        beforeSubmit:  function() {
            for ( instance in CKEDITOR.instances ) {
                CKEDITOR.instances[instance].updateElement();
            }
        },
        callback:function(response){
            if(response.status == "y"){ 
                alert('添加成功');
                if(confirm('是否继续添加')){
                    form.resetForm();
                }else{
                    $('#myModal').modal('hide');
                    $("<?=$ajax_view?>").click();
                }
            }else{
                alert('添加失败');
            }
        }
    });    

    $('#upload').fileupload({
        url: '<?=site_url("files/imgUpload/?dir=campaign")?>',
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

    CKEDITOR.replace( 'ck_editor',{
        filebrowserUploadUrl: '<?=site_url("files/ckUpload/")?>',
        on: {
            instanceReady: function() {
                this.dataProcessor.htmlFilter.addRules( {
                    elements: {
                        img: function( el ) {
                            if ( !el.attributes.class )
                                el.attributes.class = 'img-responsive';
                        }
                    }
                } );            
            }
        }
    });

    CKEDITOR.replace( 'ck_editor2',{
        filebrowserUploadUrl: '<?=site_url("files/ckUpload/")?>',
        on: {
            instanceReady: function() {
                this.dataProcessor.htmlFilter.addRules( {
                    elements: {
                        img: function( el ) {
                            if ( !el.attributes.class )
                                el.attributes.class = 'img-responsive';
                        }
                    }
                } );            
            }
        }
    });
})
</script>
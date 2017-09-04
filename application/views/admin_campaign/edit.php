<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i></div>
    </div>
    <div class="portlet-body form">
        <form id='editForm' class="form-horizontal" action="<?php echo site_url($controller_url."edit_save")?>">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">标题</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="title" value='<?=$row['title'] ?>' datatype="*" nullmsg="请输入标题！"/>
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
                                <input class="form-control" datatype='*' type="text" id="picurl" name="thumb" placeholder="http://" value="<?=$row['thumb'] ?>" >
                            </div>
                        </div>
                        <span class="text-danger help-block" id="uploadstatus"></span>
                        <div class="thumbnail help-block col-md-4"><img class="img-responsive" src="<?=get_image_url($row['thumb']) ?>" alt=""></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">地点</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="address" value='<?=$row['address'] ?>' datatype="*" nullmsg="请输入地点！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">发起人</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="sponsor" value='<?=$row['sponsor'] ?>' datatype="*" nullmsg="请输入发起人！"/>
                    </div>
                </div>
                <?php if ($row['type'] == 1): ?>
                <div class="form-group">
                    <label class="col-md-3 control-label">服务时长</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="reward_time" value='<?=$row['reward_time'] ?>' datatype="n" nullmsg="请输入整数时长！"/>
                    </div>
                </div>
                <?php endif ?>
                <div class="form-group">
                    <label class="col-md-3 control-label">时间</label>
                    <div class="col-md-4">
                        <input class="form-control date-picker" type='text' name="startdate" value='<?=$row['startdate'] ?>' datatype="*" nullmsg="请输入时间！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">关闭报名</label>
                    <div class="col-md-4">
                        <div class="radio-list">
                            <label class="pull-left">
                            <input <?=radio_check(0, $row['is_signup']) ?> type="radio" value="0" id="optionsRadios25" name="is_signup"> 关
                            </label>
                            <label class="pull-left">
                            <input <?=radio_check(1, $row['is_signup']) ?> type="radio" value="1" id="optionsRadios26" name="is_signup"> 开
                            </label> 
                         </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">关闭评论</label>
                    <div class="col-md-4">
                        <div class="radio-list">
                            <label class="pull-left">
                            <input <?=radio_check(0, $row['is_comment']) ?>  type="radio" value="0" id="optionsRadios25" name="is_comment"> 关
                            </label>
                            <label class="pull-left">
                            <input <?=radio_check(1, $row['is_comment']) ?>  type="radio" value="1" id="optionsRadios26" name="is_comment"> 开
                            </label> 
                         </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">关闭活动</label>
                    <div class="col-md-4">
                        <div class="radio-list">
                            <label class="pull-left">
                            <input <?=radio_check(0, $row['is_closed']) ?> type="radio" value="0" id="optionsRadios25" name="is_closed"> 关
                            </label>
                            <label class="pull-left">
                            <input <?=radio_check(1, $row['is_closed']) ?> type="radio" value="1" id="optionsRadios26" name="is_closed"> 开
                            </label> 
                         </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">活动详情</label>
                    <div class="col-md-9">
                        <textarea name="content" id="ck_editor" class="form-control"><?=$row['content'] ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">活动回顾</label>
                    <div class="col-md-9">
                        <textarea name="review" id="ck_editor2" class="form-control"><?=$row['review'] ?></textarea>
                    </div>
                </div>
            </div>
            <div class="form-actions fluid">
                <div class="col-md-offset-3 col-md-9">
                    <input type='button' id="btn_sub" class="btn blue btn-lg" value='保存'/>
                    <input type='hidden' name="id" value='<?=$row['id']?>'/>
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
    var form = $("#editForm").Validform({
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
            }
            alert(response.info);
            $("<?=$ajax_view?>").click();
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
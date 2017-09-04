<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i>编辑</div>
    </div>
    <div class="portlet-body form">
        <form id='editForm' class="form-horizontal" action="<?php echo site_url($controller_url."edit_save")?>">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">标题</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="title" value='<?=$row['title']?>' datatype="*" nullmsg="请输入标题！"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">是否首页推荐</label>
                    <div class="col-md-4">
                        <label class="radio-inline">
                            <input <?=radio_check(0, $row['is_index'])?> type="radio" name="is_index" value="0"> 否
                        </label>
                        <label class="radio-inline">
                            <input <?=radio_check(0, $row['is_index'])?> type="radio" name="is_index" value="1"> 是
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">外链</label>
                    <div class="col-md-4">
                        <input class="form-control" type='text' name="url" value='<?=$row['url']?>' placeholder="http://" datatype="url" ignore="ignore" errormsg="请输入正确网址!"/>
                        <span class="help-block">添加外链后点击文章直接跳转到外链网址</span>
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
                    <label class="col-md-3 control-label">摘要</label>
                    <div class="col-md-9">
                        <textarea name="desc" class="form-control"><?=$row['desc']?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">内容</label>
                    <div class="col-md-9">
                        <textarea name="content" id="ck_editor" class="form-control"><?=$row['content']?></textarea>
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
        url: '<?=site_url("files/imgUpload/?dir=news")?>',
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
})
</script>
<body>
<?php $this->load->view('front/nav'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-7 col-xs-8">
            <img class="img-responsive" src="<?=site_url('images/denglu.png')?>" alt="">
        </div>
        <div class="col-md-5 col-xs-12">
            <h4 class="login-box-title"><?=$campaign_title?> 活动签到</h4>
            <div class="login-box">
                <?php if ($info): ?>
                <div class="login-box-title"><?=$info?></div>
                <?php else: ?>
                <form id="signin" class="form-horizontal" method="post" action="<?=site_url('/member_auth/signing')?>" role="form">
                    <div class="form-group">
                        <label for="login" class="col-sm-3 col-md-3 control-label">用户名</label>
                        <div class="col-sm-9 col-md-9">
                            <input class="form-control" type="text" id="login" placeholder="用户名" name="login">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-sm-3 col-md-3 control-label">密&nbsp;&nbsp;&nbsp;码</label>
                        <div class="col-sm-9 col-md-9">
                            <input type="password" class="form-control" id="password" name="password" placeholder="密码">
                            <span class="help-block"><?php echo form_error('password'); ?><?php echo isset($errors['password'])?$errors['password']:''; ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button id="submit" class="btn col-md-12">签到</button>
                            <input name="campaign_id" type="hidden" value="<?=$campaign_id?>">
                        </div>
                    </div>
                </form>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('front/footer'); ?>
<script type="text/javascript">
$(function(){
    var cform = $("#signin").Validform({
        btnSubmit: "#submit",
        tiptype:1,
        ajaxPost:true,
        callback:function(response){
            if(response.status == 'y'){
                location.reload();
            }
        }
    });
})
</script>
</body>
</html>

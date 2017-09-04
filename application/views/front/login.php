<body>
<?php $this->load->view('front/nav'); ?>
<div class="container login-page">
    <div class="row">
        <div class="col-md-7 col-sm-5 col-xs-6">
            <img class="img-responsive" src="<?=site_url('images/denglu.png')?>" alt="">
        </div>
        <div class="col-md-5 col-sm-7 col-xs-12">
            <h4 class="login-box-title">登录 LOGIN</h4>
            <div class="login-box">
                <form class="form-horizontal" method="post" action="<?=site_url('/member_auth')?>" role="form">
                    <div class="form-group">
                        <label for="login" class="col-sm-3 col-md-3 control-label">用户名</label>
                        <div class="col-sm-9 col-md-9">
                            <input class="form-control" type="text" id="login" placeholder="用户名" name="login">
                            <span class="help-block"><?php echo form_error('login'); ?><?php echo isset($errors['login'])?$errors['login']:''; ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-sm-3 col-md-3 control-label">密&nbsp;&nbsp;&nbsp;码</label>
                        <div class="col-sm-9 col-md-9">
                            <input type="password" class="form-control" id="password" name="password" placeholder="密码">
                            <span class="help-block"><?php echo form_error('password'); ?><?php echo isset($errors['password'])?$errors['password']:''; ?></span>
                        </div>
                    </div>
                    <?php if ($show_captcha): ?>
                    <div class="form-group">
                        <label for="captcha" class="col-xs-3 col-md-3 control-label">验证码</label>
                        <div class="col-xs-9 col-md-9">
                            <input class="form-control" class="captcha" type="text" maxlength="8" id="captcha" placeholder="captcha" name="captcha">
                            <span class="help-block"><?php echo $captcha_html; ?></span>
                            <span class="help-block"><?php echo form_error('password'); ?><?php echo form_error('captcha') ? form_error('captcha') : ''; ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <input type="hidden" name="back_url" value="<?=$back_url?>">
                            <button type="submit" class="btn col-md-12">登录</button>
                            <span class="pull-right"><a class="reg-link" href="<?=site_url('/member_auth/register_form')?>">立即注册成为正式义工</a></span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('front/footer'); ?>
</body>
</html>

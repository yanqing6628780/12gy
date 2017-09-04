<div class="member-title">
    <img class="img-responsive" src="<?=site_url('images/feedback_title.png')?>">
</div>
<div class="register">
    <form id="send_mail" class="form-horizontal" method="post" action="<?=site_url('/member/feedback')?>" role="form">
        <div class="form-group">
            <label for="login" class="col-xs-3 col-md-3 control-label">标题</label>
            <div class="col-xs-6 col-md-6">
                <input class="form-control" type='text' name='title' value='' datatype="*"/>
            </div>
        </div>
        <div class="form-group">
            <label for="login" class="col-xs-3 col-md-3 control-label">email</label>
            <div class="col-xs-6 col-md-6">
                <input class="form-control" type='text' name='email' value='<?=isset($profile->email) ? $profile->email : ""?>' datatype="e"/>
            </div>
        </div>
        <div class="form-group">
            <label for="password" class="col-xs-3 col-md-3 control-label">内容</label>
            <div class="col-xs-8 col-md-8">
                <textarea class="form-control" datatype="*" name="content"></textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-offset-3 col-xs-9">
                <input class="form-control" type='hidden' name='name' value='<?=isset($profile->name) ? $profile->name : $this->tank_auth->get_username()?>'/>
                <button class="btn btn-danger" id="send_sub" type='button'>提交</button>
            </div>
        </div>
    </form>
</div>
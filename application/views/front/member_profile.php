<div class="member-title">
    <img class="img-responsive" src="<?=site_url('images/my-profile.png')?>">
</div>
<div class="register">
    <form id='user_edit' class="form-horizontal" action="<?=site_url('/member/edit_save')?>">
        <div class="form-group ">
            <label class="col-sm-2 control-label">用户名</label>
            <div class="col-sm-3 input-box">
            <p class="form-control-static text-danger"><?=$this->tank_auth->get_username()?></p>
            </div>
            <label class="col-sm-2 control-label">义工编号 </label>
            <div class="col-sm-3 input-box">
            <p class="form-control-static text-danger"><?=$profile->sn?></p>
            </div>
        </div>
        <?php $this->load->view('front/member_profile_tmp'); ?>
        <div class="form-group fluid">
            <div class="col-xs-12 text-center">
                <input id="editProfile_sub" type='button' class="btn blue" value='保存'/>
            </div>
        </div>
    </form>
</div>
<?php $this->load->view('front/top_bar'); ?>
<?php $this->load->view('front/phone_nav'); ?>
<nav class="navbar <?php if ( isset($spc_id) && $spc_id == "login" ): ?>hidden<?php endif; ?>">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="<?=base_url()?>">
                <?php if (!isset($spc_id)): ?>
                <img src="<?=site_url('images/logo.png') ?>" />
                <?php endif; ?>
                <?php if ( isset($spc_id) && $spc_id == "login" ): ?>
                <?php else: ?>
                <img <?php if ( isset($spc_id) && $spc_id == "register" ): ?>class="hidden-xs"<?php endif; ?> src="<?=site_url('images/yigonglogo.png') ?>" />
                <?php endif; ?>
            </a>
        </div>
        <ul class="nav nav-pills navbar-right hidden-xs hidden-sm" role="menu">
            <?php $this->load->view('front/nav_li'); ?>
        </ul>
    </div>
</nav>
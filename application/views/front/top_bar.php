<!-- 顶部导航条 -->
<div id="top_bar">
    <div class="container">
        <div class="row">
            <?php if ($this->tank_auth->is_logged_in()): ?>
            <a class="register_link" href="<?=site_url('member') ?>">我的信息</a>             
            <a href="<?=site_url('member_auth/logout') ?>">退出</a>
            <?php else: ?>
            <a class="register_link" href="<?=site_url('member_auth/register_form') ?>">注册成为义工</a>
            <?php if ($title == "登录" || $title == "注册页"): ?>
            <a href="<?=base_url() ?>">返回身边公益</a>
            <?php endif; ?>
            <a href="<?=site_url('member_auth/') ?>">登录</a>
            <?php endif; ?>
        </div>
    </div>
</div>
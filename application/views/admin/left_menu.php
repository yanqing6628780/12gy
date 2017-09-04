<?php if(chk_perm_to_bool('user_view') || chk_perm_to_bool('perm_admin') || chk_perm_to_bool('role_view')):?>
<li class="start">
	<a href="javascript:;">
		<i class="icon-home"></i> 
		<span class="title">用户管理</span>
		<span class="selected"></span>
		<span class="arrow open"></span>
	</a>
	<ul class="sub-menu">
		<?php if(chk_perm_to_bool('user_view')):?>
		<li><a id="users_view" class="ajaxify" href="<?php echo site_url('admin/user_admin/users')?>" target="right">后台用户管理</a></li>
		<?php endif;?>
		<?php if(chk_perm_to_bool('perm_admin')):?>
		<li><a id="perm_view" class="ajaxify" href="<?php echo site_url('admin/user_admin/permissions')?>" target="right">权限管理</a></li>
		<?php endif;?>
		<?php if(chk_perm_to_bool('role_view')):?>
		<li><a id="roles_view" class="ajaxify" href="<?php echo site_url('admin/user_admin/roles')?>" target="right">后台用户角色管理</a></li>
		<?php endif;?>
	</ul>
</li>
<?php endif;?>

<li class="" style="display: none">
	<a href="javascript:;">
		<i class="icon-cogs"></i> 
		<span class="title">系统管理</span>
		<span class="selected"></span>
		<span class="arrow"></span>
	</a>
	<ul class="sub-menu">
		<?php if(chk_perm_to_bool('role_view')):?>
		<li><a id="sys_view" class="ajaxify" href="<?php echo site_url('admin/sys')?>" target="right">系统配置</a>
		<?php endif;?>
	</ul>
</li>

<li class="last active open">
	<a href="javascript:;">
		<i class="icon-cogs"></i> 
		<span class="title">内容管理</span>
		<span class="selected"></span>
		<span class="arrow"></span>
	</a>
	<ul class="sub-menu">
		<?php if(chk_perm_to_bool('member_admin')):?>
		<li><a id="member_view" class="ajaxify" href="<?php echo site_url('admin/member')?>" target="right">会员管理</a>
		<?php endif;?>
		<?php if(chk_perm_to_bool('goods_admin')):?>
		<li><a id="goods_view" class="ajaxify" href="<?php echo site_url('admin/goods')?>" target="right">礼品管理</a>
		<?php endif;?>
		<?php if(chk_perm_to_bool('aboutus_admin')):?>
		<li><a id="aboutus_view" class="ajaxify" href="<?php echo site_url('admin/news/?type=1')?>" target="right">组织介绍管理</a>
		<?php endif;?>
		<?php if(chk_perm_to_bool('business_admin')):?>
		<li><a id="business_news_view" class="ajaxify" href="<?php echo site_url('admin/news/?type=2')?>" target="right">慈善商家介绍管理</a>
		<?php endif;?>
		<?php if(chk_perm_to_bool('news_admin')):?>
		<li><a id="news_view" class="ajaxify" href="<?php echo site_url('admin/news/?type=3')?>" target="right">新闻管理</a>
		<?php endif;?>
		<?php if(chk_perm_to_bool('v_campaign_admin')):?>
		<li><a id="v_campaign_view" class="ajaxify" href="<?php echo site_url('admin/campaign/?type=1')?>" target="right">义工活动发布管理</a>
		<?php endif;?>
		<?php if(chk_perm_to_bool('c_campaign_admin')):?>
		<li><a id="c_campaign_view" class="ajaxify" href="<?php echo site_url('admin/campaign/?type=2')?>" target="right">社区活动发布管理</a>
		<?php endif;?>
		<?php if(chk_perm_to_bool('d_campaign_admin')):?>
		<li><a id="d_campaign_view" class="ajaxify" href="<?php echo site_url('admin/campaign/?type=3')?>" target="right">爱心捐助活动发布管理</a>
		<?php endif;?>
		<?php if(chk_perm_to_bool('b_campaign_admin')):?>
		<li><a id="b_campaign_view" class="ajaxify" href="<?php echo site_url('admin/campaign/?type=4')?>" target="right">商家活动发布管理</a>
		<?php endif;?>
		<?php if(chk_perm_to_bool('serviceteam_admin')):?>
		<li><a id="serviceteam_category_view" class="ajaxify" href="<?php echo site_url('admin/serviceteam_category')?>" target="right">服务队类别管理</a>
		<?php endif;?>
		<?php if(chk_perm_to_bool('serviceteam_admin')):?>
		<li><a id="serviceteam_view" class="ajaxify" href="<?php echo site_url('admin/serviceteam/')?>" target="right">服务队管理</a>
		<?php endif;?>

	</ul>
</li>
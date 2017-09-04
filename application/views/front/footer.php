<div class="foot">
    <div class="container foot_nav">
        <div class="row">
            <div class="col-md-6 hidden-xs">
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-6">
                        <ul class="list-unstyled">
                            <li><h4>组织介绍</h4></li>
                            <?php foreach ($foot_nav['about_news'] as $key => $item): ?>
                            <li><a href="<?=site_url('news/detail/'.$item->id)?>"><?=$item->title?></a></li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-6">
                        <ul class="list-unstyled">
                            <li><h4>参与活动</h4></li>
                            <li><a href="<?=site_url('campaign/index/volunteer')?>">义工活动</a>
                            <li><a href="<?=site_url('campaign/index/community')?>">社区活动</a></li>
                            <li><a href="<?=site_url('campaign/index/donation')?>">爱心捐赠</a></li></li>
                        </ul>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-6">
                        <ul class="list-unstyled">
                            <li><h4>友情链接</h4></li>
                            <li><h4><a href="#">商家优惠</a></h4></li>
                            <li><h4><a href="#">鸣谢商家</a></h4></li>
                            <!-- <li><h4><a href="<?=site_url('member')?>">邮件联系</a></h4></li> -->
                            <?php foreach ($foot_nav['business_news'] as $key => $item): ?>
                            <!-- <li><a <?php if($item->url): ?>target='_blank'<?php endif; ?> href="<?=$item->url ? $item->url : site_url('news/detail/'.$item->id)?>"><?=$item->title?></a></li> -->
                            <?php endforeach ?>
                        </ul>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-6">
                        <ul class="list-unstyled">
                            <li><h4>网站用户</h4></li>
                            <li><a href="<?=site_url('member_auth')?>">登录</a></li>
                            <li><a href="<?=site_url('member_auth/register_form')?>">注册</a></li>
                            <li><a href="<?=site_url('member')?>">我的信息</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-5 col-md-offset-1">
                <img class="img-responsive contact" src="<?=site_url('images/contact.png')?>" alt="">
                <p class="copyright">&copy; Copyright 2015. PengXing Social Worker  All rights reserved</p>
            </div>
            <div class="qr hidden-xs hidden-sm">
                <img class="img-responsive" src="<?=site_url('images/qr.jpg')?>" alt="">
                <span>扫二维码，关注身边公益 公众号</span>
            </div>
        </div>
    </div>
    <div class="copyright hidden">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-center">
                    <span></span> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--[if lt IE 9]> 
<div id="ie-alert-panel" role="alert" style="z-index: 999999" class="navbar-fixed-bottom alert alert-danger">
    <button data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
    <h4>天啊！你的浏览器版本太旧了！</h4>
    <p class="text-warning">您当前使用的浏览器版本过低,为了有更好的浏览体验</p>
    <p>请使用<span class="text-info">火狐浏览器</span>或者<span class="text-info">谷歌浏览器</span>.</p>
    <p>如果你是使用IE浏览器请升级到<span class="text-info">IE8以上</span>.</p>
    <p>如果你使用的是<span class="text-info">360浏览器、遨游浏览器、猎豹浏览器</span>请使用<span class="text-info">极速模式</span>浏览</p>
    <button data-dismiss="alert" class="btn btn-default pull-right" type="button">关闭</button>
</div>
<![endif]--> 
<script type="text/javascript" src="<?=site_url('js/jquery.min.js')?>"></script>
<script type="text/javascript" src="<?=site_url('js/bootstrap.min.js')?>"></script>

<script type="text/javascript" src="<?=base_url('js/validform_v5.3.2.js')?>"></script>

<script type="text/javascript">
var BASEURL = '<?php echo base_url()?>'
function resetPassword()
{
    $("#msgbox1").empty()
    $("#msgbox1").dialog({"title": "重置密码",width:400})
    $("#msgbox1").dialog("open")
    LoadAjaxPage('member/reset_password', {}, 'msgbox1')
}
</script>

<script type="text/javascript" src="<?=base_url('js/api.js')?>"></script>
<script type="text/javascript">
$(function () {
    $('.mydropdown').hover(
        function () {
            $(this).addClass('open');
        },
        function () {
            $(this).removeClass('open');
        }
    );

    $('.signup').hover(function() {
        $(this).find('img').attr('src', '<?=site_url("images/signup_hover.png")?>');
    }, function() {
        $(this).find('img').attr('src', '<?=site_url("images/signup.png")?>');
    });
    
    $('.reg').hover(function() {
        $(this).find('img').attr('src', '<?=site_url("images/reg_hover.png")?>');
    }, function() {
        $(this).find('img').attr('src', '<?=site_url("images/reg.png")?>');
    });

    $('.upgarde').hover(function() {
        $(this).find('img').attr('src', '<?=site_url("images/upgarde_hover.png")?>');
    }, function() {
        $(this).find('img').attr('src', '<?=site_url("images/upgarde.png")?>');
    });
})
</script>
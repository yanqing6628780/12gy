<body>
<!-- 顶部导航条 -->
<?php $this->load->view('front/top_bar'); ?>
<!-- banner -->
<div class="banner">
    <div class="container-fluid">
        <div class="row" style="position: relative;">
            <img class="img-responsive hidden-xs"  src="<?=site_url('images/banner.jpg') ?>" />
            <img class="img-responsive visible-xs-block"  src="<?=site_url('images/phone_banner.jpg') ?>" />
            <div class="container hidden-xs" style="position: absolute;top: 0;left: 0;right: 0">
                <div class="row">
                    <div class="col-xs-3" style="position: absolute;right: 10px;top:10px;">
                        <a class="reg" href="<?=site_url('/member_auth/register_form')?>"><img class="img-responsive" src="<?=site_url('images/reg.png') ?>" ></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('front/phone_nav'); ?>
<!-- 导航 -->
<div class="mid-nav hidden-xs hidden-sm">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <ul class="nav nav-pills">
                    <?php $this->load->view('front/nav_li'); ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- 最新动态 -->
<?php if ($news): ?>
<div class="container">    
    <div class="camp_carousel">
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <?php foreach ($news as $key => $item): ?>
                <li data-target="#carousel-example-generic" data-slide-to="<?=$key?>" <?php if($key==0): ?>class="active"<?php endif; ?>></li>
                <?php endforeach ?>
            </ol>
          <div class="carousel-inner" role="listbox">
            <?php foreach ($news as $key => $item): ?>                
            <div class="item <?php if($key==0): ?>active<?php endif; ?>">
                <a href="<?=site_url('news/detail/'.$item->id)?>">
                <?php if($item->thumb): ?>
                <img src="<?=get_image_url($item->thumb)?>" title="<?=$item->title?>" alt="<?=$item->title?>">
                <?php else: ?>
                <img src="http://placehold.it/1200x675/" title="<?=$item->title?>" alt="<?=$item->title?>">
                <?php endif; ?>
                </a>
            </div>
            <?php endforeach ?>
          </div>
        </div>
    </div>
</div>
<?php endif ?>
<!-- 活动 -->
<div class="camp_box">
    <div class="container">
        <div class="row">
            <?php foreach ($v_campaigns as $key => $item): ?>                
            <div class="col-md-6 col-sm-6 item-box">
                <div class="item">                
                    <a href="<?=site_url('campaign/detail/'.$item->id)?>">
                        <?php if($item->thumb): ?>
                        <img class="img-responsive" src="<?=get_image_url($item->thumb)?>" title="<?=$item->title?>" alt="<?=$item->title?>">
                        <?php else: ?>
                        <img class="img-responsive" src="http://placehold.it/640x360/?text=noImage" alt="...">
                        <?php endif; ?>
                        <span><?=$item->title?></span>
                    </a>
                    <img src="<?=site_url('images/v_camp.png')?>" class="ico">
                </div>
            </div>
            <?php endforeach ?>
            <?php foreach ($c_campaigns as $key => $item): ?>                
            <div class="col-md-6 col-sm-6 item-box">
                <div class="item">                
                    <a href="<?=site_url('campaign/detail/'.$item->id)?>">
                        <?php if($item->thumb): ?>
                        <img class="img-responsive" src="<?=get_image_url($item->thumb)?>" title="<?=$item->title?>" alt="<?=$item->title?>">
                        <?php else: ?>
                        <img class="img-responsive" src="http://placehold.it/640x360/?text=noImage" alt="...">
                        <?php endif; ?>
                        <span><?=$item->title?></span>
                    </a>
                    <img src="<?=site_url('images/c_camp.png')?>" class="ico">
                </div>
            </div>
            <?php endforeach ?>
            <?php foreach ($d_campaigns as $key => $item): ?>                
            <div class="col-md-6 col-sm-6 item-box">
                <div class="item">                
                    <a href="<?=site_url('campaign/detail/'.$item->id)?>">
                        <?php if($item->thumb): ?>
                        <img class="img-responsive" src="<?=get_image_url($item->thumb)?>" title="<?=$item->title?>" alt="<?=$item->title?>">
                        <?php else: ?>
                        <img class="img-responsive" src="http://placehold.it/640x360/?text=noImage" alt="...">
                        <?php endif; ?>
                        <span><?=$item->title?></span>
                    </a>
                    <img src="<?=site_url('images/d_camp.png')?>" class="ico">
                </div>
            </div>
            <?php endforeach ?>
            <?php foreach ($b_campaigns as $key => $item): ?>                
            <div class="col-md-6 col-sm-6 item-box">
                <div class="item">                
                    <a href="<?=site_url('campaign/detail/'.$item->id)?>">
                        <?php if($item->thumb): ?>
                        <img class="img-responsive" src="<?=get_image_url($item->thumb)?>" title="<?=$item->title?>" alt="<?=$item->title?>">
                        <?php else: ?>
                        <img class="img-responsive" src="http://placehold.it/640x360/?text=noImage" alt="...">
                        <?php endif; ?>
                        <span><?=$item->title?></span>
                    </a>
                    <img src="<?=site_url('images/b_camp.png')?>" class="ico">
                </div>
            </div>
            <?php endforeach ?>
        </div>
    </div>
</div>
<!-- 合作商家 -->
<div class="business">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h3><span></span>合作商家</h3>
                <p>鹏星社工与一众知名企业商户共同合作，共同打造这个全民公益活动平台，为各位义工带来便捷愉快的生活体验，为容桂公益事业出谋划策，贡献良多，在我们共同期待的将来，必定有更好的发展，我们共同见证着。</p>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <?php foreach ($b_news as $key => $item): ?>
                    <div class="col-md-4 col-sm-4 col-xs-6 business-logo">
                        <a <?php if($item->url): ?>target='_blank'<?php endif; ?> href="<?=$item->url ? $item->url : site_url('news/detail/'.$item->id)?>" >
                        <?php if($item->thumb): ?>
                        <img class="img-responsive" src="<?=get_image_url($item->thumb)?>" title="<?=$item->title?>" alt="<?=$item->title?>">
                        <?php else: ?>
                        <img class="img-responsive" src="http://placehold.it/640x360&text=no+Image" alt="...">
                        <?php endif; ?>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="testing">
    <img class="img-responsive" src="<?=site_url('images/testing.png')?>"></img>
</div>
<?php $this->load->view('front/footer'); ?>
<script type="text/javascript">
$(function(){
    // $('.testing').slideDown(800).on('click', function(event) {
    //     event.preventDefault();
    //     $(this).slideUp();
    // });
})
</script>
</body>
</html>
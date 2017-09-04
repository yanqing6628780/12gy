<body>
<?php $this->load->view('front/nav'); ?>
<div class="container">
    <div class="share-box">
        <div class="bdsharebuttonbox">
        <h4 class="pull-left">分享到: </h4>
        <a title="分享到新浪微博" href="#" class="bds_tsina" data-cmd="tsina"></a>
        <a title="分享到腾讯微博" href="#" class="bds_tqq" data-cmd="tqq"></a>
        <a title="分享到微信" href="#" class="bds_weixin" data-cmd="weixin"></a>
        <a title="分享到QQ空间" href="#" class="bds_qzone" data-cmd="qzone"></a>
        <a title="分享到QQ好友" href="#" class="bds_sqq" data-cmd="sqq"></a>
        </div>
        <script>
            window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"1","bdMiniList":false,"bdPic":"","bdStyle":"1","bdSize":"24"},"share":{}};
            with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];
        </script>
    </div>
    <div class="campaign">
        <div class="row">
            <div class="col-md-6">
                <div class="thumb">
                    <?php if($row->thumb): ?>
                    <img class="img-responsive" src="<?=get_image_url($row->thumb)?>" title="<?=$row->title?>" alt="<?=$row->title?>">
                    <?php else: ?>
                    <img class="img-responsive" src="http://placehold.it/640x360/?text=noImage" title="<?=$row->title?>" alt="<?=$row->title?>">
                    <?php endif; ?>
                    <img class="ico" src="<?=site_url('images/biaoqian.png')?>"/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12"><h1><?=$row->title?></h1></div>
                </div>
                <div class="row">                
                    <div class="col-sm-8 col-md-8 info">
                        <?=$row->desc?>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="row">
                <div class="col-md-12">
                    <span class="my-label">动态详情</span>
                    <div class="text"> <?=$row->content?> </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('front/footer'); ?>
</body>
</html>

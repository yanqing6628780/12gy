<body>
<?php $this->load->view('front/nav'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-sm-8 list-title">
            <img class="img-responsive" src="<?=$title_img?>">
            <p><?=$desc?></p>
        </div>
        <div class="col-md-4 col-sm-4 col-xs-8">
            <a class="upgarde" href="<?=site_url('news/alias/upgrade')?>"><img class="img-responsive" src="<?=site_url('images/upgarde.png')?>"></a>
        </div>
    </div>
    <div class="camp_box">
        <h4 class="title">●进行中的活动</h4>
        <div class="row">
            <?php foreach ($list as $key => $item): ?>                
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
                </div>
            </div>
            <?php endforeach ?>
        </div>
    </div>
    <div class="camp_box">
        <h4 class="title">●已结束的活动</h4>
        <div class="row">
            <?php foreach ($closed_list as $key => $item): ?>                
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
                </div>
            </div>
            <?php endforeach ?>
        </div>
    </div>
</div>
<?php $this->load->view('front/footer'); ?>
</body>
</html>

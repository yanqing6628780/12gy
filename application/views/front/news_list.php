<body>
<?php $this->load->view('front/nav'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-12 list-title">
            <img class="img-responsive" src="<?=$title_img?>">
            <p><?=$desc?></p>
        </div>
    </div>
    <div class="camp_box">
        <div class="row">
            <?php foreach ($list as $key => $item): ?>                
            <div class="col-md-6 col-sm-6 item-box">
                <div class="item">                
                    <a href="<?=site_url('news/detail/'.$item->id)?>">
                        <?php if($item->thumb): ?>
                        <img class="img-responsive" src="<?=get_image_url($item->thumb)?>" title="<?=$item->title?>" alt="<?=$item->title?>">
                        <?php else: ?>
                        <img class="img-responsive" src="http://placehold.it/640x360/?text=noImage" title="<?=$item->title?>" alt="<?=$item->title?>">
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

<body>
<?php $this->load->view('front/nav'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-12 list-title">
            <img class="img-responsive" src="<?=$title_img?>">
            <p><?=$desc?></p>
        </div>
    </div>
</div>
<div class="container">    
    <div class="camp_carousel">
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <?php foreach ($list as $key => $item): ?>
                <?php if($key < 3): ?>
                <li data-target="#carousel-example-generic" data-slide-to="<?=$key?>" <?php if($key==0): ?>class="active"<?php endif; ?>></li>
                <?php endif; ?>
                <?php endforeach ?>
            </ol>
          <div class="carousel-inner" role="listbox">
            <?php foreach ($list as $key => $item): ?>    
            <?php if($key < 3): ?>            
            <div class="item <?php if($key==0): ?>active<?php endif; ?>">
                <a href="<?=site_url('campaign/detail/'.$item->id)?>">
                <?php if($item->thumb): ?>
                <img src="<?=get_image_url($item->thumb)?>" title="<?=$item->title?>" alt="<?=$item->title?>">
                <?php else: ?>
                <img src="http://placehold.it/1200x675/" title="<?=$item->title?>" alt="<?=$item->title?>">
                <?php endif; ?>
                </a>
            </div>
            <?php endif; ?>
            <?php endforeach ?>
          </div>
        </div>
    </div>
</div>   
<div class="container">   
    <div class="camp_box b_camp_box">
        <div class="row">
        <?php foreach ($list as $key => $item): ?>
        <?php if($key > 2): ?>
            <div class="col-md-2 col-sm-2 col-xs-6 item-box">
                <div class="item">                
                    <a href="<?=site_url('campaign/detail/'.$item->id)?>">
                        <?php if($item->thumb): ?>
                        <img class="img-responsive" src="<?=get_image_url($item->thumb)?>" title="<?=$item->title?>" alt="<?=$item->title?>">
                        <?php else: ?>
                        <img class="img-responsive" src="http://placehold.it/640x360/?text=noImage" alt="...">
                        <?php endif; ?>
                    </a>
                </div>
            </div>
        <?php endif; ?>
        <?php endforeach ?>
        </div>
    </div>
    <div class="b_news_box">
        <div class="row">   
            <?php foreach ($news as $key => $item): ?>
            <div class="col-md-6 col-sm-6 col-xs-6 business-logo">
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
<?php $this->load->view('front/footer'); ?>
</body>
</html>

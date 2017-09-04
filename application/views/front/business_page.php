<body>
<?php $this->load->view('front/nav'); ?>
<div class="article">
    <div class="container">
        <div class="row">
            <div class="col-md-3 hidden-xs">
                <ul class="list-unstyled article_list">
                    <?php foreach ($others as $key => $item): ?>
                    <li><a <?php if($item->url): ?>target='_blank'<?php endif; ?> <?php if($row->id == $item->id): ?>class="active"<?php endif; ?> href="<?=$item->url ? $item->url : site_url('news/detail/'.$item->id)?>">
                    <?=$item->title?>
                    </a></li>
                    <?php endforeach ?>
                </ul>
            </div>
            <div class="col-md-9">
                <h1><?=$row->title?></h1>
                <div class="content">
                    <?=$row->content?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('front/footer'); ?>
</body>
</html>

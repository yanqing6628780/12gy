<body>
<?php $this->load->view('front/nav'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-12 list-title">
            <img class="img-responsive" src="<?=$title_img?>">
            <p><?=$desc?></p>
        </div>
    </div>
    <div class="camp_box goods_list">
        <div class="row">
            <?php foreach ($list as $key => $item): ?>                
            <div class="col-md-3 col-sm-4 item-box">
                <div class="item">                
                    <?php if($item->img): ?>
                    <img class="img-responsive" src="<?=get_image_url($item->img)?>" title="<?=$item->name?>" alt="<?=$item->name?>">
                    <?php else: ?>
                    <img class="img-responsive" src="http://placehold.it/640x360&text=noImage" title="<?=$item->name?>" alt="<?=$item->name?>">
                    <?php endif; ?>
                    <p class="name"><?=$item->name?></p>
                    <div class="row">
                        <div class="col-xs-6">
                            <p>
                            所需V币: <span class="price"><?=$item->price?></span>
                            </p>
                        </div>
                        <div class="col-xs-6">
                            <button onclick="exchange(<?=$item->id?>)" class="pull-right btn">立即领取</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach ?>
        </div>
    </div>
</div>
<?php $this->load->view('front/footer'); ?>
<script type="text/javascript">
function exchange (good_id) {
    if(confirm('是否确认领取该礼品')){    
        $.ajax({
            url: "<?=site_url('exchange/get')?>",
            type: 'POST',
            dataType: 'json',
            data: {good_id: good_id},
        })
        .done(function(response) {
            $.Showmsg(response.info);
        })
        .fail(function() {
            alert('网络繁忙,请稍后再试');
        })
    }
}
</script>
</body>
</html>

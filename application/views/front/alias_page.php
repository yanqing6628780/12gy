<body>
<?php $this->load->view('front/nav'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-12 list-title">
            <img class="img-responsive" src="<?=site_url('images/upgrade_title.png')?>">
            <p>注册义工通过参加义工活动可积累义工时数，时数越多，义工星级约高，所得到的绿币也越多。</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?=$row->content?>
        </div>
    </div>
</div>
<?php $this->load->view('front/footer'); ?>
</body>
</html>

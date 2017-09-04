<li role="presentation" class="mydropdown">
    <a href="#">组织介绍</a>
    <div class="mydropdown-menu">
    <?php foreach ($foot_nav['about_news'] as $key => $item): ?>
    <a href="<?=site_url('news/detail/'.$item->id)?>"><?=$item->title?></a>
    <?php endforeach ?>
    </div>
</li>
<li class="mydropdown" role="presentation">
    <a href="#">参与活动</a>
    <div class="mydropdown-menu">
        <ul class="list-unstyled">
        <?php $this->load->view('front/campaign_sub_links'); ?>
        </ul>
    </div>
</li>
<li class="mydropdown"  role="presentation">
    <a href="#">合作商家</a>
    <div class="mydropdown-menu">
        <ul class="list-unstyled">
        <?php $this->load->view('front/business_sub_links'); ?>
        </ul>
    </div>
</li>
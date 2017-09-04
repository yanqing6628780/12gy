<div aria-label="Justified button group" role="group" class="btn-group btn-group-justified navbar-fixed-bottom visible-xs-block visible-sm-block btngroup-nav">
    <div class="btn-group dropup">
        <button aria-expanded="false" aria-haspopup="true" data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">
            组织介绍
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <?php foreach ($foot_nav['about_news'] as $key => $item): ?>
            <li><a href="<?=site_url('news/detail/'.$item->id)?>"><?=$item->title?></a></li>
            <?php endforeach ?>
        </ul>
    </div>
    <div class="btn-group dropup">
        <button aria-expanded="false" aria-haspopup="true" data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">
            参与活动
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <?php $this->load->view('front/campaign_sub_links'); ?>
        </ul>
    </div>
    <div class="btn-group dropup">
        <button aria-expanded="false" aria-haspopup="true" data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">
            合作商家
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <?php $this->load->view('front/business_sub_links'); ?>
        </ul>
    </div>
</div>
<div class="member-title">
    <div class="row">
        <div class="col-md-9 col-sm-8">
            <img class="img-responsive" src="<?=site_url('images/my-campaign.png')?>">
        </div>
        <div class="col-md-3 col-sm-4">
            <dl class="reward_time">
                <dt>累积时数</dt>
                <dd><?=$profile->servicetime?></dd>
            </dl>
        </div>        
    </div>
</div>
<div class="register">
    <div class="table-responsive">
        <table class="table table-striped table-condensed">
            <thead>
            <tr>
                <th>活动名称</th>
                <th>活动时间</th>
                <th>活动地址</th>
                <th>获得时数(小时)</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($campaigns as $key => $item): ?>
            <tr>
                <td><?=$item->title?></td>
                <td><?=$item->startdate?></td>
                <td><?=$item->address?></td>
                <td><?=$item->reward_time?></td>
            </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
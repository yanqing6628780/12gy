<div class="member-title">
    <img class="img-responsive" src="<?=site_url('images/my-campaign.png')?>">
</div>
<div class="register">
    <div class="table-responsive">
        <table class="table table-striped table-condensed">
            <thead>
            <tr>
                <th>#</th>
                <th>礼品</th>
                <th>价值(V币)</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($goods as $key => $item): ?>
            <tr>
                <td><?=$key+1?></td>
                <td><?=$item->good_name?></td>
                <td><?=$item->vcoin?></td>
            </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
<?php if ($result): ?>
<ul class="download_list">
<?php foreach ($result as $key => $item): ?>
    <li> <?=$item?></li>
<?php endforeach ?>
</ul>
<button class="btn green" onclick="download_all()">下载全部</button>
<?php else: ?>
请选择要导出的会员数据
<?php endif ?>
<script type="text/javascript">
function download_all() {
    $('.download_list').find('a').each(function(index, el) {
        $(this)[0].click();
    });
}
</script>
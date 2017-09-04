<?php foreach ($comments as $key => $item): ?>
<li><?=$item->name?>: <?=strip_tags($item->content)?></li>
<?php endforeach ?>
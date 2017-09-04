<ul id="comment_list" class="list-unstyled">
<?php foreach ($comments as $key => $item): ?>
<li><?=$item->name?>: <?=strip_tags($item->content)?></li>
<?php endforeach ?>
</ul>
<?php if ($row->is_comment): ?>
<div class="row">
    <form id="comment" class="comment-form" action="<?=site_url('campaign/comment')?> ">
        <div class="form-group col-sm-2">
            <input type="text" class="form-control" name="name" placeholder="姓名" value="<?=$comment_name?>" datatype="*" nullmsg="请填写姓名">
        </div>
        <div class="form-group col-sm-8">
            <input type="text" datatype="*" class="form-control" name="content" placeholder="内容" nullmsg="请填写内容">
        </div>
        <div class="form-group col-sm-2">
            <input type="hidden" name="id" value="<?=$row->id?>">
            <input type="submit" class="btn" value="评论">
        </div>
        <div id="errormsg" class="col-xs-12"></div>
    </form>                            
</div>
<?php endif; ?>
<?php $this->load->view('admin/table_head');?>
<div class="row">
    <div class="col-md-12">
        <div class='portlet box light-grey'>
            <div class="portlet-title">
                <div class="caption"><i class="icon-globe"></i>列表</div>
                <div class="tools">
                    <a class="collapse" href="javascript:;"></a>
                </div>
                <div class="actions">
                    <a onclick="add(<?=$type?>)" class="btn blue" ><i class="icon-plus"></i> 添加</a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">                    
                        <form id="search" class="form-inline">
                            <div class="form-group">                                
                                <input name="q" type="text" value="<?=$q?>" class="form-control" placeholder="标题搜索">
                            </div>
                            <input type="button" value="搜索" class="btn btn-default" onclick="infoQuery()" >
                            <input name="type" type="hidden" value="<?=$type?>">
                        </form>
                    </div>
                </div>
                <div class="table-scrollable">
                    <table class='table table-striped table-bordered table-hover'>
                        <thead>
                            <tr>
                                <th>ID </th>
                                <th>标题</th>
                                <th>时间</th>
                                <th>地点</th>
                                <?php if ($type == 1): ?>
                                <th>奖励服务时长 (小时)</th>
                                <th>活动签到二编码</th>
                                <?php endif ?>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($result as $key => $row):?>
                            <tr>
                                <td><?=$row['id']?></td>
                                <td><?=$row['title']?></td>
                                <td><?=$row['startdate']?> <?=$row['enddate']?></td>
                                <td><?=$row['address']?></td>
                                <?php if ($type == 1): ?>
                                <td><?=$row['reward_time']?></td>
                                <td><a title="点击查看大图" target="_blank" href="http://qr.liantu.com/api.php?bg=f3f3f3&fg=ff0000&gc=222222&el=l&w=800&m=10&text=<?=site_url('member_auth/sign_campaign/'.$row['id'])?>"><img src="http://qr.liantu.com/api.php?bg=f3f3f3&fg=ff0000&gc=222222&el=l&w=50&m=5&text=<?=site_url('member_auth/sign_campaign/'.$row['id'])?>"/></a></td>
                                <?php endif ?>
                                <td>
                                    <a  onclick="edit(<?=$row['id']?>)" class="btn green"> <i class="icon-pencil icon-white"></i> 编辑</a>
                                    <button class="btn btn-small btn-danger" onclick='del(<?=$row['id']?>)'><i class="icon-remove icon-white"></i> 删除</button>
                                    <button class="btn btn-small btn-info" onclick='comments(<?=$row['id']?>,"<?=$row['title']?>")'><i class="icon-list-alt icon-white"></i> 评论管理</button>
                                    <?php if ($type == 3): ?>                                        
                                    <button class="btn btn-small yellow" onclick='donation_book_list(<?=$row['id']?>,"<?=$row['title']?>")'><i class="icon-user icon-white"></i> 捐赠预约</button>
                                    <?php else: ?>
                                    <button class="btn btn-small yellow" onclick='signup_log_list(<?=$row['id']?>,"<?=$row['title']?>")'><i class="icon-user icon-white"></i> 参与者</button>
                                    <?php endif ?>
                                    <button class="btn btn-small" onclick='export_participants(<?=$row['id']?>,"<?=$row['title']?>", <?=$type?>)'><i class="icon-user icon-white"></i> 导出报名人员</button>
                                </td>
                            </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
                <?php if (isset($page_links)): ?>         
                <div class="row">
                    <div class="col-md-12">
                        <ul class="bootpag pagination">
                            <li class="prev <?=$current_page==1 ? 'disabled' : '' ?>" data-lp="<?=$current_page?>">
                                <a class="ajaxify" href="<?=site_url($prev_link)?>">
                                    <icon class="icon-angle-left"></icon>
                                </a>
                            </li>
                            <?php foreach($page_links as $key => $lnk): ?>    
                            <li data-lp="<?=$key?>" class="<?=$key == $current_page ? 'disabled' : '' ?>">
                                <a class="ajaxify" href="<?=site_url($lnk)?>"><?=$key?></a>
                            </li>
                            <?php endforeach; ?>
                            <li class="next <?=$current_page==$page ? 'disabled' : '' ?>" data-lp="<?=$current_page?>">
                                <a class="ajaxify" href="<?=site_url($next_link)?>">
                                <icon class="icon-angle-right"></icon>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?=base_url()?>js/page.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/plugins/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
function add(type){
    LoadPageContentBody('<?=site_url($controller_url."add")?>',{type: type});
}
function edit(id){
    LoadPageContentBody('<?=site_url($controller_url."edit")?>',{id: id});
}
function del(id, table){
    common_del('<?=site_url($controller_url."del")?>', id, table, "<?=$ajax_view?>");
}
function comments(id, name){
    LoadPageContentBody('<?=site_url($controller_url."comments")?>',{rel_id: id, campaign_name: name, type: <?=$type?>});
}
function donation_book_list(id, name){
    LoadPageContentBody('<?=site_url($controller_url."donation_book_list")?>',{campaign_id: id, campaign_name: name, type: <?=$type?>});
}
function signup_log_list(id, name){
    LoadPageContentBody('<?=site_url($controller_url."signup_log_list")?>',{campaign_id: id, campaign_name: name, type: <?=$type?>});
}
function export_participants(id, title, type){
    var url = type == 3 ? '<?=site_url("admin/xls_output/donation")?>' : '<?=site_url("admin/xls_output/campaign_participants")?>';
    $.ajax({
        url: url,
        type: 'post',
        dataType: 'html',
        data: {campaign_id: id, campaign_title: title},
    })
    .done(function(html) {
        $.Showmsg(html);
    });
}
function infoQuery() {
    var formData = $('#search').serialize();
    LoadPageContentBody('<?=site_url($controller_url)?>', formData);
}
jQuery(document).ready(function() {       
    TableAdvanced.init();
});
</script>
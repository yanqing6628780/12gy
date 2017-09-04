<?php echo $this->load->view('admin/table_head'); ?>
<div class="row">
    <div class="col-md-12">
        <div class='portlet box light-grey'>
            <div class="portlet-title">
                <div class="caption"><i class="icon-globe"></i>用户列表</div>
                <div class="tools">
                    <a class="collapse" href="javascript:;"></a>
                </div>
                <div class="actions">
                    <?php if(chk_perm_to_bool('member_edit')):?> 
                    <a class="btn blue" href="#myModal" data-toggle="modal" onclick='addUser()'><i class="icon-plus"></i> 添加用户</a>
                    <?php endif;?>
                    <?php if($this->dx_auth->is_admin()):?> 
                    <a class="btn blue" onclick='export_member_summary()'><i class="icon-plus"></i> 导出会员汇总表</a>                    
                    <?php endif;?>
                    <a class="btn blue" onclick='export_member_xls()'><i class="icon-plus"></i> 导出选中会员</a>                    
                    <a class="btn green" onclick='export_campaign_xls()'><i class="icon-plus"></i> 导出选中参加过活动</a>                    
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">                    
                        <form id="search" class="form-inline">
                            <div class="form-group">                                
                                <input name="q" type="text" value="<?=$q?>" class="form-control" placeholder="编号/姓名查找">
                            </div>
                            <input type="button" value="搜索" class="btn btn-default" onclick="infoQuery()" >
                        </form>
                    </div>
                </div>
                <form class="table-scrollable" id="user_list">
                <table class='table table-striped table-bordered table-hover' id="member_list">
                    <thead>
                        <tr>
                            <th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#member_list .checkboxes" /></th>
                            <th>编号</th>
                            <th>用户名</th>
                            <th>姓名</th>
                            <th>电话</th>
                            <th width="80">地址</th>
                            <th width="80">V币</th>
                            <th width="150">服务时长(小时)</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($users as $key => $row):?>
                        <tr>
                            <td><input type="checkbox" class="checkboxes" name="id[]" value="<?=$row['user_id']?>"/></td>
                            <td><?=$row['sn']?></td>
                            <td><?=$row['username']?></td>
                            <td><?=$row['name']?></td>
                            <td><?=$row['phone']?></td>
                            <td><?=$row['address']?></td>
                            <td><?=$row['vcoin']?></td>
                            <td><?=$row['servicetime']?></td>
                            <td>
                                <?php if(chk_perm_to_bool('member_admin')):?>
                                <button href="#myModal" data-toggle="modal" class="btn btn-small btn-primary" onclick='editUser(<?=$row['user_id']?>)'> <i class="icon-pencil icon-white"></i> 编辑</button>
                                <button href="#myModal" data-toggle="modal" class="btn btn-small btn-inverse" onclick='resetPassword("<?=$row['username']?>")' ><i class="icon-refresh icon-white"></i> 重置密码</button>
                                <a class="btn btn-small btn-danger" onclick='del_user(<?=$row['user_id']?>, "<?=$row['code']?>")'><i class="icon-remove icon-white"></i> 删除</a>
                                <?php endif;?>
                                <?php if(chk_perm_to_bool('vcoin_admin')):?>
                                <button href="#myModal" data-toggle="modal" class="btn btn-small yellow" onclick='recharge_vcoin(<?=$row['user_id']?>,"<?=$row['username']?>","<?=$row['name']?>")' ><i class="icon-cny icon-white"></i> v币充值</button>                             
                                <button class="btn btn-small green" onclick='vcoin_log("<?=$row['user_id']?>")' ><i class="icon-list-ol icon-white"></i> 充值记录</button>
                                <?php endif;?>
                                <?php if(chk_perm_to_bool('servicetime_admin')):?>
                                <button href="#myModal" data-toggle="modal" class="btn btn-small yellow" onclick='servicetime(<?=$row['user_id']?>,"<?=$row['username']?>","<?=$row['name']?>")' >服务时长修改</button>                             
                                <?php endif;?>
                            </td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
                </form>
                <?php if (isset($page_links)): ?>         
                <div class="row">
                    <div class="col-md-12">
                        <ul class="bootpag pagination">
                            <?=$page_links?>
                        </ul>
                    </div>
                </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?=base_url()?>js/page.js"></script>
<script type="text/javascript">
jQuery(document).ready(function() {       
    App.initUniform();
    group_checkable();
});
function addUser(userId){
    LoadAjaxPage('admin/member/add', "", 'myModal',"添加用户");
}
function editUser(userId){
    LoadAjaxPage('admin/member/edit', {user_id: userId}, 'myModal',"编辑");
}

function del_user(id, code){
    common_del("admin/member/del", id, code,"#member_view");
}
function resetPassword(username)
{
    LoadAjaxPage('admin/member/reset_password', {username: username}, 'myModal',"修改密码")
}
function recharge_vcoin(userId, userName, Name){
    LoadAjaxPage('admin/member/recharge_vcoin', {user_id: userId, username: userName, name: Name}, 'myModal',"充值");
}
function servicetime(userId, userName, Name){
    LoadAjaxPage('admin/member/servicetime', {user_id: userId, username: userName, name: Name}, 'myModal',"服务时长");
}
function vcoin_log (userId) {
    LoadPageContentBody('admin/member/vcoin_log', {user_id: userId})
}
function infoQuery() {
    var formData = $('#search').serialize();
    LoadPageContentBody('<?=site_url("admin/member")?>', formData);
}
function export_member_summary() {
    $.ajax({
        url: 'admin/xls_output/member_summary',
        type: 'post',
        dataType: 'html',
        data: "",
    })
    .done(function(html) {
        $.Showmsg(html);
    });
}
function export_member_xls() {
    var post_data = $('#user_list').serialize();
    $.ajax({
        url: 'admin/xls_output/members',
        type: 'post',
        dataType: 'html',
        data: post_data,
    })
    .done(function(html) {
        $.Showmsg(html);
    });
}
function export_campaign_xls() {
    var post_data = $('#user_list').serialize();
    $.ajax({
        url: 'admin/xls_output/members_campaign',
        type: 'post',
        dataType: 'html',
        data: post_data,
    })
    .done(function(html) {
        $.Showmsg(html);
    });
}
</script>

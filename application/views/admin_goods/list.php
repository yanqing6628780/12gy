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
                    <a onclick="add()" class="btn blue"  href="#myModal" data-toggle="modal" ><i class="icon-plus"></i> 添加</a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">                    
                        <form id="search" class="form-inline">
                            <div class="form-group">                                
                                <input name="q" type="text" value="<?=$q?>" class="form-control" placeholder="名称搜索">
                            </div>
                            <input type="button" value="搜索" class="btn btn-default" onclick="infoQuery()" >
                        </form>
                    </div>
                </div>
                <div class="table-scrollable">
                    <table class='table table-striped table-bordered table-hover'>
                        <thead>
                            <tr>
                                <th>礼品名</th>
                                <th>V币</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($result as $key => $row):?>
                            <tr>
                                <td><?=$row['name']?></td>
                                <td><?=$row['price']?></td>
                                <td><?=$row['is_show'] ? '上架' : '下架' ?></td>
                                <td>
                                    <a href="#myModal" data-toggle="modal"  onclick="edit(<?=$row['id']?>)" class="btn green"> <i class="icon-pencil icon-white"></i> 编辑</a>
                                    <button class="btn btn-small btn-danger" onclick='del(<?=$row['id']?>)'><i class="icon-remove icon-white"></i> 删除</button>
                                    <button class="btn btn-small btn-success" onclick='is_show(<?=$row['id']?>,1)'><i class="icon-circle-arrow-up icon-white"></i> 上架</button>
                                    <button class="btn btn-small btn-warning" onclick='is_show(<?=$row['id']?>,0)'><i class="icon-circle-arrow-down icon-white"></i> 下架</button>
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
                            <?php foreach($page_links as $key => $lnk){ ?>    
                            <li data-lp="<?=$key?>" class="<?=$key == $current_page ? 'disabled' : '' ?>">
                                <a class="ajaxify" href="<?=site_url($lnk)?>"><?=$key?></a>
                            </li>
                            <?php } ?>
                            <li class="next <?=$current_page==$page ? 'disabled' : '' ?>" data-lp="<?=$current_page?>">
                                <a class="ajaxify" href="<?=site_url($next_link)?>">
                                <icon class="icon-angle-right"></icon>
                                </a>
                            </li>
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
function add(){
    LoadAjaxPage('<?=site_url($controller_url."add/")?>', '', 'myModal','添加')
}
function edit(id){
    LoadAjaxPage('<?=site_url($controller_url."edit/")?>', {id: id}, 'myModal','编辑')
}
function del(id, table){
    common_del('<?=site_url($controller_url."del")?>', id, table, "#goods_view");
}
function is_show(id, status){
    ajaxPost('<?=site_url($controller_url."edit_save/")?>', {id: id,is_show: status}, "#goods_view");
}
function infoQuery() {
    var formData = $('#search').serialize();
    LoadPageContentBody('<?=site_url($controller_url)?>', formData);
}
jQuery(document).ready(function() {       
    TableAdvanced.init();
});
</script>
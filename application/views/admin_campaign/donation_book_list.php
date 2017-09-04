<?php $this->load->view('admin/table_head');?>
<div class="row">
    <div class="col-md-12">
        <div class='portlet box light-grey'>
            <div class="portlet-title">
                <div class="caption"><i class="icon-globe"></i>列表</div>
                <div class="tools">
                    <a class="collapse" href="javascript:;"></a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">                    
                        <form id="search" class="form-inline">
                            <div class="form-group">                                
                                <input name="q" type="text" value="<?=$q?>" class="form-control" placeholder="预约人搜索">
                            </div>
                                <input name="campaign_id" type="hidden" value="<?=$campaign_id?>" >
                                <input name="type" type="hidden" value="<?=$type?>" >
                                <input name="campaign_name" type="hidden" value="<?=$campaign_name?>" >
                            <input type="button" value="搜索" class="btn btn-default" onclick="infoQuery()" >
                        </form>
                    </div>
                </div>
                <div class="table-scrollable">
                    <table class='table table-striped table-bordered table-hover'>
                        <thead>
                            <tr>
                                <th>预约人</th>
                                <th>电话</th>
                                <th>地址</th>
                                <th>预约时间</th>
                                <th>捐赠物品</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($result as $key => $row):?>
                            <tr>
                                <td><?=$row['name']?></td>
                                <td><?=$row['phone']?></td>
                                <td><?=$row['address']?></td>
                                <td><?=$row['book_time']?></td>
                                <td><?=$row['goods']?></td>
                                <td>
                                    <?php if(chk_perm_to_bool('d_campaign_admin')):?>
                                    <button class="btn btn-small btn-danger" onclick='del(<?=$row['id']?>)'><i class="icon-remove icon-white"></i> 删除</button>
                                    <?php endif; ?>
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
function del(id){
    $.ajax({
        url: '<?=site_url($controller_url."donation_book_del")?>',
        type: 'POST',
        dataType: 'json',
        data: {id: id},
        success: function(data, textStatus, xhr) {
            alert('删除成功');
            LoadPageContentBody('<?=site_url($controller_url2)?>',{campaign_id: <?=$campaign_id?>, campaign_name: "<?=$campaign_name?>", type: <?=$type?>});
        }
    });   
}
function infoQuery() {
    var formData = $('#search').serialize();
    LoadPageContentBody('<?=site_url($controller_url2)?>', formData);
}
jQuery(document).ready(function() {       
    TableAdvanced.init();
});
</script>
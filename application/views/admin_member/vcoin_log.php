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
                <table class='table table-striped table-bordered table-hover' id="sample_1">
                    <thead>
                        <tr>
                            <th>序号</th>
                            <th>操作人</th>
                            <th>备注</th>
                            <th>操作时间</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($result as $key => $row):?>
                        <tr>
                            <td><?=$key+1?></td>
                            <td><?=$row['name']?></td>
                            <td><?=$row['log']?></td>
                            <td><?=$row['datetime']?></td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {       
    TableAdvanced.init();
});
</script>
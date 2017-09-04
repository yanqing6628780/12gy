<body>
<?php $this->load->view('front/nav'); ?>
<div class="container">
    <div class="share-box">
        <div class="bdsharebuttonbox">
        <h4 class="pull-left">分享到: </h4>
        <a title="分享到新浪微博" href="#" class="bds_tsina" data-cmd="tsina"></a>
        <a title="分享到腾讯微博" href="#" class="bds_tqq" data-cmd="tqq"></a>
        <a title="分享到微信" href="#" class="bds_weixin" data-cmd="weixin"></a>
        <a title="分享到QQ空间" href="#" class="bds_qzone" data-cmd="qzone"></a>
        <a title="分享到QQ好友" href="#" class="bds_sqq" data-cmd="sqq"></a>
        </div>
        <script>
            window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"1","bdMiniList":false,"bdPic":"","bdStyle":"1","bdSize":"24"},"share":{}};
            with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];
        </script>
    </div>
    <div class="campaign">
        <div class="row">
            <div class="col-md-6">
                <div class="thumb">
                    <img class="img-responsive" src="<?=get_image_url($row->thumb)?>" />
                    <img class="ico" src="<?=$camp_ico?>"/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12"><h1><?=$row->title?></h1></div>
                </div>
                <div class="row">                
                    <div class="col-sm-8 col-md-8 info">
                        <ul class="list-unstyled">
                            <li>地点：<?=$row->address?></li>
                            <li>时间：<?=$row->startdate?></li>
                            <li>发起人：<?=$row->sponsor?></li>
                        </ul>
                    </div>
                    <div class="col-sm-12 col-md-12">
                        <?php if ($row->is_signup && $row->is_closed): ?>
                        <form id="c_camp_form" class="form-horizontal c_camp_form" action="<?=site_url('campaign/bc_campaign_signup')?> ">
                        <div class="row">
                            <div class="col-md-6">
                                    <div class="form-group has-feedback">
                                        <label class="col-md-3 control-label">姓名</label>
                                        <div class="col-md-9 input-box">
                                        <input type="text" class="form-control" name="name" placeholder="姓名" datatype="*" nullmsg="请填写姓名">
                                        <span aria-hidden="true" class="glyphicon form-control-feedback"></span>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback">
                                        <label class="col-sm-3 control-label">手机</label>
                                        <div class="col-md-9 input-box">
                                        <input type="text" class="form-control" name="phone" placeholder="手机号码" ajaxurl="<?=site_url('campaign/check_phone/?id='.$row->id)?>" datatype="m" nullmsg="请填写手机">
                                        <span aria-hidden="true" class="glyphicon form-control-feedback"></span>
                                        </div>
                                    </div>
                            </div>
                            <div class="col-md-6">
                                <a class="signup" href="javascript:"><img id="signup_btn" class="img-responsive"  src="<?=site_url('images/signup.png')?>"/></a>
                                <input type="hidden" name="id" value="<?=$row->id?>">
                            </div>
                        </div>    
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="row">
                <div class="col-md-12">
                    <span class="my-label">活动详情</span>
                    <div class="text"> <?=$row->content?> </div>
                </div>
                <div class="col-md-12">
                    <span class="my-label">成功报名</span>
                    <div class="text"><?=$signup_members?></div>
                </div>
                <div class="col-md-12">
                    <span class="my-label">活动评论</span>
                    <div class="text">
                        <?php $this->load->view('front/campaign_comment'); ?>
                    </div>
                </div>
                <div class="col-md-12">
                    <span class="my-label">活动回顾</span>
                    <div class="text"> <?=$row->review?> </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('front/footer'); ?>
<script type="text/javascript">
$(function () {
    var form = $("#comment").Validform({
        tiptype:function(msg,o,cssctl){
            var objtip=$("#errormsg");
            cssctl(objtip,o.type);
            objtip.text(msg);
        },
        ajaxPost:true,
        callback:function(response){
            $.Showmsg(response.info);
            if(response.status == "y"){
                LoadAjaxPage("<?=site_url('campaign/get_comments')?>", {id: <?=$row->id?>}, "comment_list");
            }
        }
    }); 

    var cform = $("#c_camp_form").Validform({
        btnSubmit: "#signup_btn",
        tiptype:function(msg,o,cssctl){
            if(o.type == 2) {
                o.obj.next().removeClass('glyphicon-remove').addClass('glyphicon-ok');
            }
            if(o.type == 3) {
                o.obj.next().removeClass('glyphicon-ok').addClass('glyphicon-remove');
            }
            o.obj.attr('placeholder', msg);
        },
        ajaxPost:true,
        callback:function(response){
            $.Showmsg(response.info);
        }
    }); 
})
function signup(id){
    $.ajax({
        url: ' <?=site_url("campaign/v_campaign_signup")?> ',
        type: 'POST',
        dataType: 'json',
        data: {id: id},
    })
    .done(function(response) {
        $.Showmsg(response.info);
        if(response.success) location.reload();
    })
    .fail(function() {
        alert('网络异常!请检查网络');
    })
}
</script>
</body>
</html>

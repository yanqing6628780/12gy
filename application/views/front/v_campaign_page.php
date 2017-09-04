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
                    <div class="col-sm-7 col-md-7 info">
                        <ul class="list-unstyled">
                            <li>地点：<?=$row->address?></li>
                            <li>时间：<?=$row->startdate?></li>
                            <li>发起人：<?=$row->sponsor?></li>
                        </ul>
                    </div>
                    <div class="col-sm-4 col-md-4">                    
                        <dl class="reward_time">
                            <dt>义工时长</dt>
                            <dd><?=$row->reward_time?></dd>
                        </dl>
                    </div>
                    <div class="col-sm-12 col-md-12">
                        <?php if ($row->is_signup && $row->is_closed): ?>    
                        <a class="signup" href="javascript:"><img onclick="signup(<?=$row->id?>)" class="img-responsive"  src="<?=site_url('images/signup.png')?>"/></a>
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
        if(response.redirect_link) window.location = response.redirect_link + "?back_url="+window.location.href;
    })
    .fail(function() {
        alert('网络异常!请检查网络');
    })
}
</script>
</body>
</html>

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['news_type'][1] = "组织介绍文章";
$config['news_type'][2] = "合作商家介绍文章";
$config['news_type'][3] = "新闻";

$config['campaign_type'][1] = "义工活动"; // v_campaign
$config['campaign_type'][2] = "社区活动"; // c_campaign
$config['campaign_type'][3] = "爱心捐赠"; // d_campaign
$config['campaign_type'][4] = "商家活动"; // b_campaign

$config['member_level'][100] = "一星义工";
$config['member_level'][200] = "二星义工";
$config['member_level'][300] = "三星义工";
$config['member_level'][400] = "四星义工";
$config['member_level'][500] = "五星义工";

$config['my_language'][1] = "粤语";
$config['my_language'][2] = "普通话";
$config['my_language'][3] = "英语";
$config['my_language'][4] = "其他";

$config['work_status'][1] = "学生";
$config['work_status'][2] = "在职";
$config['work_status'][3] = "主妇";
$config['work_status'][5] = "退休";
$config['work_status'][6] = "待业";
$config['work_status'][7] = "其他";

$config['health_care'] = array('中医学','拔火罐','针灸','推拿','保健讲座,1','其他,1');
$config['computer_tec'] = array('海报设计', '数据库软件', '动画制作', '活动视频制作', '网站设计','其他,1');
$config['handmade'] = array('缝纫','不织布制作','羊毛毡', '针织', '丝网制作','捏纸', '折纸', '剪纸', '其他,1');
$config['personal_talent'] = array('主持', '唱歌', '插花', '乐器,1', '舞蹈,1', '美食DIY,1', '私房菜', '其他,1');
$config['sports_teach'] = array('球类运动,1', '棋牌,1', '武术,1', '健身,1', '其他,1');
$config['other_areas'] = array('法律援助', '大型活动协助', '心理辅导', '摄影', '录影', '居家安全讲座','新闻写作', '绘画,1', '其他,1');
$config['pro_repair'] = array('电脑维修', '家电维修', '水工', '木工', '电工', '其他,1');
$config['service_items'] = array(
     '残障人士服务',
     '青少年成长服务',
     '长者服务',
     '社会活动服务',
     '法律援助服务',
     '网络维护服务',
     '社区服务',
     '环保义工服务',
     '启创社工•义工服务',
     '鹏星社工•星动义工服务'
);

$config['star_service_items'] = array(
    '星动家乐义工队（社区关爱服务）', 
    '星动可乐义工队（敬老院关爱）', 
    '星动Elephant teen（设计）', 
    '星动星乐演艺队（乐器表演、小品）', 
    '星动Live teen（公益宣传）   ',
    '星动服务调研队（社区调查、服务调查）',
    '星动义工训练员（义工培训）',
    '再生缘•数码培训服务队（数码培训）',
    '再生缘•再生义工队（环保再生）',
    '再生缘•数码维修服务队',
    '星动义工•社区义工服务队'
 );

$config['days'] =array('normal','Sat','Sun');
$config['times'] =array('上午','下午','晚上');

$config['page_config']['use_page_numbers'] = TRUE;
$config['page_config']['num_links'] = 5;
$config['page_config']['first_link'] = '首页';
$config['page_config']['first_tag_open'] = '<li>';
$config['page_config']['first_tag_close'] = '</li>';
$config['page_config']['last_link'] = '尾页';
$config['page_config']['last_tag_open'] = '<li>';
$config['page_config']['last_tag_close'] = '</li>';
$config['page_config']['next_link'] = '<i class="icon-angle-right"></i>';
$config['page_config']['next_tag_open'] = '<li>';
$config['page_config']['next_tag_close'] = '</li>';
$config['page_config']['prev_link'] = '<i class="icon-angle-left"></i>';
$config['page_config']['prev_tag_open'] = '<li>';
$config['page_config']['prev_tag_close'] = '</li>';
$config['page_config']['cur_tag_open'] = '<li class="active"><a>';
$config['page_config']['cur_tag_close'] = '</a></li>';
$config['page_config']['num_tag_open'] = '<li>';
$config['page_config']['num_tag_close'] = '</li>';
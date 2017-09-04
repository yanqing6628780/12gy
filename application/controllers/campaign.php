<?php //if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Campaign extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->library('tank_auth');

        $this->load->model('tank_auth/profiles_mdl', 'profiles_mdl');

        $this->config->load('recyclecity');
        $this->data['campaign_type'] = $this->config->item('campaign_type');

        $this->data['foot_nav'] = $this->global_mdl->foot_nav();
    }

    public function index($type)
    {
        $this->data['title_img'] = "http://placehold.it/450x53/?text=noImage";
        $this->data['desc'] = "暂无介绍";
        $view = 'front/campaign_list';
        switch ($type) {
            //义工活动
            case 'volunteer':
                $this->data['title'] = '义工活动';
                $this->data['title_img'] = site_url('images/v_list.png');
                $this->data['desc'] = "<strong>什么是义工活动？</strong>
                    义工活动是指以义工身份参与活动，需担当活动的工作人员，可获取义工服务时数，义工的服务时数越多，义工的等级（星级）也越高，升级规则请点击右边按钮。";
                $view = 'front/v_campaign_list';
                $type = 1;
                $where['is_closed'] = 1;
                break;
            //社区活动
            case 'community':
                $this->data['title'] = '社区活动';
                $this->data['title_img'] = site_url('images/c_list.png');
                $this->data['desc'] = "<strong>什么是社区活动？</strong>社区活动就是以群众身份参与活动，不可获取义工服务时数，但可以收获丰富的人生经历，和志同道合的人成为好友。";
                $view = 'front/c_campaign_list';
                $type = 2;
                $where['is_closed'] = 1;
                break;
            //捐赠活动
            case 'donation':
                $this->data['title'] = '捐赠活动';
                $this->data['title_img'] = site_url('images/d_list.png');
                $this->data['desc'] = "<strong>什么是爱心收集？</strong>爱心捐赠就是捐赠活动所需要的物资，先填写好资料和方便收取物资的时间，我们的工作人员会根据您填写的时间时段上门收取。";
                $view = 'front/c_campaign_list';
                $type = 3;
                $where['is_closed'] = 1;
                break;
            //商家活动
            case 'business':
                $this->data['title'] = '商家活动';
                $this->data['title_img'] = site_url('images/b_list.png');
                $this->data['desc'] = "<strong>感谢各合作商家对公益事业的大力支持！</strong>";
                $view = 'front/b_campaign_list';
                $type = 4;
                $this->data['news'] = $this->db->order_by('id','asc')->get_where('news', array('type'=>2))->result();
                break;
            default:
                redirect('home/show_error/404');
                break;
        }

        //已关闭的活动
        $this->data['closed_list'] = $this->db->order_by('id','desc')->get_where('campaign', array('type' => $type, 'is_closed' => 0))->result();

        $where['type'] = $this->data['type'] = $type;
        $this->data['list'] = $this->db->order_by('id','desc')->get_where('campaign', $where)->result();
        $this->load->view('front/head', $this->data);
        $this->load->view($view);
    }

    public function detail($id)
    {
        $query = $this->db->get_where('campaign', array('id' => $id));
        $row = $query->row();
        $this->data['row'] = $row;
        $this->data['title'] = $row->title; //标签标题

        //活动评论
        $query = $this->db->order_by('createtime', 'desc')->get_where('comments', array('rel_id' => $id));
        $this->data['comments'] = $query->result();

        //活动报名人员
        $this->db->select('u.username,up.name as up_name,cul.*');
        $this->db->join('users as u', 'cul.member_id = u.id', 'left');
        $this->db->join('user_profiles as up', 'cul.member_id = up.user_id', 'left');
        $query = $this->db->order_by('createtime', 'desc')->get_where('campaign_users_log as cul', array('campaign_id' => $id));
        $signup_members = $query->result();
        $tmp = array();
        foreach ($signup_members as $key => $value) {
            //先判断报名表有没有名字
            $tmp[] = $value->member_id ? ($value->up_name ? $value->up_name : $value->username) : $value->name;
        }
        $this->data['signup_members'] = implode(",", $tmp);

        $this->data['comment_name'] = $this->tank_auth->get_user_id();
        if($this->tank_auth->is_logged_in()){
            $member = $this->db->get_where('user_profiles', array('user_id' => $this->tank_auth->get_user_id()))->row();
            $this->data['comment_name'] = $member->name ? $member->name : $this->tank_auth->get_username();
        }

        if( empty($row) ){
            redirect('home/show_error/404');
        }

        switch ($row->type) {
            case 1: //义工活动
                $this->data['camp_ico'] = site_url('images/v_camp.png');
                $view = 'front/v_campaign_page';
                break;
            case 2: //社区活动
                $this->data['camp_ico'] = site_url('images/c_camp.png');
                $view = 'front/c_campaign_page';
                break;
            case 3: //爱心捐赠
                $this->data['camp_ico'] = site_url('images/d_camp.png');
                $view = 'front/d_campaign_page';
                break;            
            case 4: //商家活动
                $this->data['camp_ico'] = site_url('images/b_camp.png');
                $view = 'front/b_campaign_page';
                break;            
            default:
                redirect('home/show_error/404');
                break;
        }

        $this->load->view('front/head', $this->data);
        $this->load->view($view);
    }

    //义工活动报名
    public function v_campaign_signup()
    {
        $data['campaign_id'] = $this->input->post('id');

        $response['info'] = "系统繁忙,请稍后再试";
        $response['success'] = FALSE;

        $query = $this->db->get_where('campaign', array('id' => $data['campaign_id']));
        $campaign = $query->row();

        if(!$this->tank_auth->is_logged_in()){
            $response['info'] = "参加该活动需要先登录";
            $response['redirect_link'] = site_url('member_auth');
            die(json_encode($response));
        }

        if( !$campaign->is_closed ){
            $response['info'] = "本次活动已经结束";
            die(json_encode($response));            
        }

        if( !$campaign->is_signup ){
            $response['info'] = "报名时间已经截止了!";
            die(json_encode($response));            
        }

        $this->general_mdl->setTable('campaign_users_log');
        $data['member_id'] = $this->tank_auth->get_user_id();
        $query = $this->general_mdl->get_query_by_where($data);
        if($query->num_rows() > 0){
                $response['info'] = "请不要重复报名!";
        }else{            
            $data['createtime'] = date("Y-m-d H:i:s");
            if($this->general_mdl->create($data)){
                $response['info'] = "报名成功";
                $response['success'] = TRUE;
            }
        }

        echo json_encode($response);
    }

    //社区活动/商家活动报名
    public function bc_campaign_signup()
    {
        $data['campaign_id'] = $this->input->post('id');

        $response['info'] = "系统繁忙,请稍后再试";
        $response['success'] = FALSE;

        $query = $this->db->get_where('campaign', array('id' => $data['campaign_id']));
        $campaign = $query->row();

        if( !$campaign->is_closed ){
            $response['info'] = "本次活动已经结束";
            die(json_encode($response));            
        }

        if( !$campaign->is_signup ){
            $response['info'] = "报名时间已经截止了!";
            die(json_encode($response));            
        }

        $this->general_mdl->setTable('campaign_users_log');

        $data['phone'] = $this->input->post('phone');
        //查询数据库是否有重复数据
        $query = $this->general_mdl->get_query_by_where($data);

        if($query->num_rows() > 0){
                $response['info'] = "请不要重复报名!";
        }else{            
            $data['name'] = $this->input->post('name');
            $data['createtime'] = date("Y-m-d H:i:s");
            if($this->general_mdl->create($data)){
                $response['info'] = "报名成功";
                $response['success'] = TRUE;
            }
        }

        echo json_encode($response);
    }

    //检查活动报名表内是否有重复手机
    public function check_phone()
    {
        $where = array(
            'phone' => $this->input->post('param'),
            'campaign_id' => $this->input->get('id')
        );
        $query = $this->db->get_where('campaign_users_log', $where);
        
        $response['status'] = $query->num_rows() > 0 ? 'n' : 'y';
        $response['info'] = $query->num_rows() > 0 ? '该号码已经报名' : '该号码可用';

        echo json_encode($response);
    }

    //捐赠预约
    public function d_campaign_book()
    {
        $data['campaign_id'] = $this->input->post('id');

        $response['info'] = "系统繁忙,请稍后再试";
        $response['success'] = FALSE;

        $query = $this->db->get_where('campaign', array('id' => $data['campaign_id']));
        $campaign = $query->row();

        if( !$campaign->is_closed ){
            $response['info'] = "本次活动已经结束";
            die(json_encode($response));            
        }

        if( !$campaign->is_signup ){
            $response['info'] = "活动已经不再接受捐赠!";
            die(json_encode($response));            
        }

        $this->general_mdl->setTable('donation_book');

        $data['phone'] = $this->input->post('phone');
        $data['name'] = $this->input->post('name');
        //查询数据库是否有重复数据
        $query = $this->general_mdl->get_query_by_where($data);

        if($query->num_rows() > 0){
                $response['info'] = "请不要重复捐赠!";
        }else{            
            $data['address'] = $this->input->post('address');
            $data['book_time'] = $this->input->post('book_time');
            $data['goods'] = $this->input->post('goods');
            $data['createtime'] = date("Y-m-d H:i:s");
            if($this->general_mdl->create($data)){
                $response['info'] = '<img class="img-responsive" src="'.site_url("/images/success1.png").'">';
                $response['success'] = TRUE;
            }
        }

        echo json_encode($response);
    }

    //获取活动评论
    public function get_comments()
    {
        $rel_id = $this->input->post('id');

        //活动评论
        $query = $this->db->order_by('createtime', 'desc')->get_where('comments', array('rel_id' => $rel_id));
        $this->data['comments'] = $query->result();

        $this->load->view('front/ajax_comments', $this->data);
    }

    //发布评论
    public function comment()
    {
        $data['rel_id'] = $this->input->post('id');
        $data['name'] = $this->input->post('name');
        $data['content'] = $this->input->post('content');
        $data['createtime'] = date('Y-m-d H:i:s');

        $query = $this->db->get_where('campaign', array('id' => $data['rel_id']));
        $campaign = $query->row();

        if( !$campaign->is_comment ){
            $response['info'] = "活动已禁止评论!";
            die(json_encode($response));            
        }

        $this->general_mdl->setTable('comments');

        if($this->general_mdl->create($data))
        {
            $response['status'] = "y";
            $response['info'] = "评论成功";
        }else{
            $response['status'] = "n";
            $response['info'] = "评论失败";
        }

        echo json_encode($response);
    }
}

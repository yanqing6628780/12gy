<?php //if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->library('tank_auth');
        $this->load->model('tank_auth/profiles_mdl', 'profiles_mdl');
        $this->data['foot_nav'] = $this->global_mdl->foot_nav();
    }

    public function index()
    {
        $this->data['title'] = "身边公益";

        //义工活动
        $this->data['v_campaigns'] = $this->db->order_by('id','desc')->get_where('campaign', array('type'=>1),2,0)->result();
        //社区活动
        $this->data['c_campaigns'] = $this->db->order_by('id','desc')->get_where('campaign', array('type'=>2),2,0)->result();
        //爱心捐赠
        $this->data['d_campaigns'] = $this->db->order_by('id','desc')->get_where('campaign', array('type'=>3),2,0)->result();
        //商家活动
        // $this->data['b_campaigns'] = $this->db->order_by('id','desc')->get_where('campaign', array('type'=>4),2,0)->result();
        $this->data['b_campaigns'] = array();

        //合作商家
        $this->data['b_news'] = $this->db->order_by('id','asc')->get_where('news', array('type'=>2, 'is_index' => 1),6,0)->result();

        //新闻
        $this->data['news'] = $this->db->order_by('id','asc')->get_where('news', array('type'=>3, 'is_index' => 1),3,0)->result();

        $this->load->view('front/head', $this->data);
        $this->load->view('front/home');
    }

    public function show_error($code=404)
    {
        $this->data['title'] = "错误信息";
        $this->data['code'] = $code;
        switch ($code) {
            case 404:
                $this->data['msg'] = "找不到页面";
                break;
            case 403:
                $this->data['msg'] = "权限不足";
                break;
            default:
                $this->data['msg'] = "找不到页面";
                break;
        }
        $this->load->view('front/head');
        $this->load->view('front/error', $this->data);
    }

}

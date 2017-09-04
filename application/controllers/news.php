<?php //if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->library('tank_auth');

        $this->load->model('tank_auth/profiles_mdl', 'profiles_mdl');

        $this->config->load('recyclecity');
        $this->data['news_type'] = $this->config->item('news_type');

        $this->data['foot_nav'] = $this->global_mdl->foot_nav();
    }

    public function index($type)
    {
        $this->data['title_img'] = "http://placehold.it/450x53/?text=noImage";
        $this->data['desc'] = "暂无介绍";
        $view = 'front/news_list';
        switch ($type) {
            //最新动态
            case 'latest':
                $this->data['title'] = '最新动态';
                $this->data['title_img'] = site_url('images/latest_news.png');
                $this->data['desc'] = "搜罗社工、义工最新热点、最新情报、最新动态";
                $type = 3;
                break;
            default:
                redirect('home/show_error/404');
                break;
        }

        $where['type'] = $type;
        $this->data['list'] = $this->db->order_by('id','desc')->get_where('news', $where)->result();
        $this->load->view('front/head', $this->data);
        $this->load->view($view);
    }

    public function detail($id)
    {
        $query = $this->db->get_where('news', array('id' => $id));
        $row = $query->row();
        $this->data['row'] = $row;
        $this->data['title'] = $row->title."_身边公益";
        if( empty($row) ){
            redirect('home/show_error/404');
        }

        $this->load->view('front/head', $this->data);
        $this->data['others'] = $this->db->get_where('news', array('type' => $row->type))->result();
        switch ($row->type) {
            case 1:
                $this->load->view('front/about_page', $this->data);
                break;
            case 2:
                $this->load->view('front/business_page', $this->data);
                break;
            case 3:
                $this->load->view('front/news_page', $this->data);
                break;            
            default:
                redirect('home/show_error/404');
                break;
        }
    }

    public function alias($alias)
    {
        $query = $this->db->get_where('news', array('alias' => $alias));
        $row = $query->row();
        $this->data['row'] = $row;
        $this->data['title'] = $row->title."_再生缘";
        if( empty($row) ){
            redirect('home/show_error/404');
        }

        $this->load->view('front/head');
        $this->data['others'] = $this->db->get_where('news', array('type' => $row->type))->result();
        switch ($row->alias) {
            case 'org':
                $this->load->view('front/about_page', $this->data);
                break;         
            default:
                $this->load->view('front/alias_page', $this->data);
                break;
        }
    }
}

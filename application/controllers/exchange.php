<?php //if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class exchange extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->library('tank_auth');

        $this->load->model('tank_auth/profiles_mdl', 'profiles_mdl');

        $this->data['foot_nav'] = $this->global_mdl->foot_nav();
    }

    public function index()
    {
        $this->data['title_img'] = "http://placehold.it/450x53/?text=noImage";
        $this->data['title'] = 'V币兑换';
        $this->data['title_img'] = site_url('images/exchange.png');
        $this->data['desc'] = "注册义工通过参加义工活动和爱心捐赠可获得V币，使用V币可以兑换优惠券货奖品，兑换后在我的信息中查看，到店后出示给店员或者社工工作站即可获得兑换优惠。";
        $view = 'front/exchange_list';

        $where['is_show'] = 1;
        $this->data['list'] = $this->db->order_by('id','desc')->get_where('goods', $where)->result();
        $this->load->view('front/head', $this->data);
        $this->load->view($view);
    }

    public function get()
    {
        $data['good_id'] = $this->input->post('good_id');
        $data['member_id'] = $this->tank_auth->get_user_id();

        $good = $this->db->get_where('goods', array('id' =>  $data['good_id']))->row();
        $member = $this->profiles_mdl->get_by_user_id($data['member_id'])->row();

        if(!$this->tank_auth->is_logged_in()){
            $response['info'] = "请先登录后再领取";
            die(json_encode($response));
        }

        $response['info'] = "领取成功";
        if($member->vcoin >= $good->price){
            //扣减V币
            $this->profiles_mdl->field_arith ('vcoin', (0-$good->price), array('user_id' =>  $member->id));
            //创建记录
            $data['good_name'] = $good->name;
            $data['vcoin'] = $good->price;
            $data['datetime'] = date("Y-m-d");
            $this->db->insert('goods_trading_record', $data);
        }else{
            $response['info'] = "帐户V币不足";
        }
        echo json_encode($response);
    }
}

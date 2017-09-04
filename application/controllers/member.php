<?php
class Member extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->model('tank_auth/users', 'users');

        $this->load->library('form_validation');
        $this->load->library('tank_auth');
        $this->lang->load('tank_auth');

        $this->load->model('tank_auth/profiles_mdl','profiles_mdl');
        $this->data = $this->profiles_mdl->profile_config;

        $this->data['foot_nav'] = $this->global_mdl->foot_nav();

        if (!$this->tank_auth->is_logged_in()) {
            redirect('/member_auth/');
        }
    }

    public function index($tab = 1)
    {
        $this->data['title'] = "会员中心";
        $this->data['tab'] = $tab;
        $user_id = $this->tank_auth->get_user_id();

        //用户资料
        $profile = $this->profiles_mdl->get_by_user_id($user_id)->row();

        $checkbox_filed_key = array(
            'my_language', 
            'health_care', 
            'computer_tec', 
            'handmade', 'personal_talent', 
            'sports_teach', 
            'other_areas', 
            'pro_repair'
        );
        foreach ($checkbox_filed_key as $value) {
            $profile->$value = json_decode($profile->$value);
        }

        $this->data['serviceteam_category'] = $this->db->get("serviceteam_category")->result_array();
        $this->data['serviceteam'] = $this->db->get("serviceteam")->result_array();

        $profile->service_items = $profile->service_items == "" ? array()  : explode(',', $profile->service_items);
        $profile->star_service_items = $profile->star_service_items == "" ? array()  : explode(',', $profile->star_service_items);

        $this->data['profile'] = $profile;

        //用户参加过的活动
        $this->db->select('c.*, cul.*');
        $this->db->join('campaign as c', 'cul.campaign_id = c.id', 'inner');
        $this->db->where('cul.member_id', $user_id);
        $this->data['campaigns'] = $this->db->order_by('c.startdate','desc')->get('campaign_users_log as cul')->result();

        //用户兑换的礼品
        $this->db->where('member_id', $user_id);
        $this->data['goods'] = $this->db->order_by('datetime','desc')->get('goods_trading_record')->result();

        $this->load->view('front/head', $this->data);
        $this->load->view('front/memeber');
    }

    //用户信息修改
    public function edit_save()
    {
        $response['status'] = "n";
        $response['info'] = "";

        $user_id = $this->tank_auth->get_user_id();
        $profile = $this->input->post('profile');
        $field_content = $this->input->post('field_content');

        $is_selected_skill = FALSE;
        $checkbox_filed_key = array(
            'health_care', 
            'computer_tec', 
            'handmade', 'personal_talent', 
            'sports_teach', 
            'other_areas', 
            'pro_repair'
        );
        foreach ($checkbox_filed_key as $value) {
            if( isset($profile[$value]) ){
                $is_selected_skill = TRUE;
            }
        }
        if( !$is_selected_skill ) {
            $response['info'] = '义工储备库必须至少选择一个';
        }

        if( $user_id && empty($response['info']) ){        

            //写入数据库             
            $this->profiles_mdl->update_all(
                $user_id, 
                $profile, 
                $field_content
            );

            $response['status'] = "y";
            $response['info'] = "修改成功";
        }

        echo json_encode($response);
    }

    //重置密码
    public function reset_password()
    {
        $response['status'] = 'n';
        $username = $this->tank_auth->get_username();

        if($this->input->post('password'))
        {
            $this->form_validation->set_rules('password', '密码', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash');
            $this->form_validation->set_rules('confirm_password', '确认密码', 'trim|required|xss_clean|matches[password]');

            if ($this->form_validation->run())
            {
                $forgotten_data = $this->tank_auth->forgot_password($username);
                if(!is_null($forgotten_data))
                {
                    $reset_data = $this->tank_auth->reset_password($forgotten_data['user_id'], $forgotten_data['new_pass_key'], $this->form_validation->set_value('password'));

                    if(!is_null($reset_data))
                    {
                        $response['status'] = 'y';
                        $response['info'] = '密码修改成功';
                    }

                }else{
                    $errors = $this->tank_auth->get_error_message();
                    foreach ($errors as $k => $v)   $response['info'][$k] = $this->lang->line($v);
                }
            }
            else
            {
                $response['info']['password'] = form_error('password');
                $response['info']['confirm_password'] = form_error('confirm_password');
            }

            echo json_encode($response);
        }
        else
        {
            $this->load->view('front/memeber_reset_password.php', $data);
        }

    }

    public function feedback()
    {
        $title = $this->input->post('title');
        $email = $this->input->post('email');
        $content = $this->input->post('content');
        $name = $this->input->post('name');

        $config['protocol']     = 'smtp';
        $config['smtp_host']    = 'smtp.163.com';
        $config['smtp_user']    = 'pxzsygc@163.com';
        $config['smtp_pass']    = 'wangluo123';
        $config['smtp_port']    = '25';
        $config['charset']      = 'utf-8';
        $config['mailtype']     = 'text';
        $config['smtp_timeout'] = '5';

        $this->load->library('email', $config);

        $this->email->from($config['smtp_user']);
        $this->email->to ('pxzsygc@163.com'); //发送到的邮箱;
        $this->email->subject($title." 用户:".$name."的意见反馈");
        $this->email->message($content);

        $response['status'] = "n";
        $response['info'] = "邮件发送失败";

        if($this->email->send()){
            $response['status'] = "y";
            $response['info'] = "邮件发送成功";
        }

        echo json_encode($response);
        // echo $this->email->print_debugger();
    }
}
?>

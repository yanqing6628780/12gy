<?php
class Member extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        checkIsLoggedIn();

        $this->load->library('tank_auth');
        $this->lang->load('tank_auth');

        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->helper('form');

        $this->load->model('tank_auth/users', 'users');
        $this->load->model('tank_auth/profiles_mdl', 'profiles');

        checkPermission('member_admin');
    }

    public function index()
    {
        $this->load->model('dx_auth/user_profile', 'admin_profile');

        $admin_user_id = $this->dx_auth->get_user_id();
        $admin_user_profile = $this->admin_profile->get_profile($admin_user_id)->row();

        $this->db->start_cache();
        // $where_sql = "u.id > 0 or ";
        $where_sql = "";
        //非管理员
        if( !$this->dx_auth->is_admin() ) {
            //后台用户只能查看属于其项目下的所有会员
            if( $admin_user_profile->service_items != "" )
            {
                $items = explode(',', $admin_user_profile->service_items);
                foreach ($items as $key => $item) {
                    if($key == 0) {
                        $where_sql .= "find_in_set(".$item.", up.service_items) ";
                    }else{
                        $where_sql .= "or find_in_set(".$item.", up.service_items) ";
                    }
                }
            }
            
            // if( $admin_user_profile->star_service_items != "" )
            // {
            //     $items = explode(',', $admin_user_profile->star_service_items);
            //     foreach ($items as $key => $item) {
            //         if( $key == 0 and empty($where_sql) ) {
            //             $where_sql .= "find_in_set(".$item.", up.star_service_items) ";
            //         }else{
            //             $where_sql .= "or find_in_set(".$item.", up.star_service_items) ";
            //         }
            //     }
            // }
        }
        $this->db->select('u.username,u.password,up.*');
        $this->db->join('user_profiles as up', 'up.user_id = u.id', 'left');
        
        if( !$this->dx_auth->is_admin() ) { //非管理员
            if($where_sql) {
                $this->db->where($where_sql);
            }else{ //没有分配项目
                $this->db->where("u.id = 0");
            }
        }
        //查询
        $data['q'] = $q = $this->input->get_post('q');
        $like_field = array('up.name','up.sn');
        if($q){
            foreach ($like_field as $field) {
                $like_arr[$field] = $q;
            }
            $this->db->or_like($like_arr);
        }
        $this->db->stop_cache();

        if(!$q)
        {
            $this->load->library('pagination');

            $this->config->load('recyclecity');
            $page_config = $this->config->item('page_config');

            $page_config['base_url'] = site_url('/admin/member/index/page/');
            $page_config['uri_segment'] = 5;
            $page_config['total_rows'] = $this->db->count_all_results('users as u');;
            $page_config['per_page'] = $per_page = 15;

            $this->pagination->initialize($page_config);

            $offset = $this->uri->segment(5, 1);
            $query_page = $this->db->order_by('up.sn desc')->get('users as u', $per_page, ($offset-1)*$per_page);
            $data['page_links'] = $this->pagination->create_links();
        }else{
            $query_page = $this->db->order_by('up.sn desc')->get('users as u');        
        }
        // var_dump($this->db->last_query());
        $result = $query_page->result_array();

        foreach($result as $key => $row)
        {
            $result[$key]['photo'] = $row['photo'] ? $row['photo'] : base_url()."images/tavatar.gif";
            $result[$key]['code'] = md5($row['user_id'].$row['password']);
            unset($result[$key]['password']);
        }

        $data['users'] = $result;

        $this->load->view('admin_member/list', $data);
    }


    //添加用户
    public function add()
    {
        $this->general_mdl->setTable('users');
        $query = $this->general_mdl->get_query();
        $data['num_rows'] = $query->num_rows();

        $this->load->view('admin_member/add', $data);
    }

    //保存添加用户
    public function add_save()
    {
        $data['status'] = "n";
        $data['info'] = "";

        $profile = $this->input->get_post('profile');

        $this->form_validation->set_rules('username', '用户名', 'trim|required|xss_clean|min_length['.$this->config->item('username_min_length', 'tank_auth').']|max_length['.$this->config->item('username_max_length', 'tank_auth').']|alpha_dash|callback__username_check');

        $this->form_validation->set_rules('password', '密码', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash');
        $this->form_validation->set_rules('confirm_password', '确认密码', 'trim|required|xss_clean|matches[password]');

        $email_activation = $this->config->item('email_activation', 'tank_auth');

        if ($this->form_validation->run()) // validation ok
        { 
            if (!is_null($data = $this->tank_auth->create_user(
                    $this->form_validation->set_value('username'),
                    '',
                    $this->form_validation->set_value('password'),
                    $email_activation)))
            {                                  // success
                $data['status'] = "y";
                $data['info'] = "添加成功";
                unset($data['password']); // Clear password (just for any case)

                //保存用户profile
                $this->general_mdl->setTable('user_profiles');
                $this->general_mdl->setData($profile);
                $this->general_mdl->update(array("user_id" => $data['user_id']));
            } else {
                $errors = $this->tank_auth->get_error_message();
                foreach ($errors as $k => $v)   $data['info'] .= $this->lang->line($v)."/";
            }
        }else{
            $data['info'] .= form_error('username') ? form_error('username')."/" : "";
            $data['info'] .= form_error('password') ? form_error('password')."/" : "";
            $data['info'] .= form_error('confirm_password') ? form_error('confirm_password')."/" : "";
        }

        echo json_encode($data);
    }


    //用户信息修改
    public function edit()
    {

        $user_id = $this->input->post('user_id');

        $data['user_id'] = $user_id;
        $data['member'] = $this->users->get_user_by_id($user_id, TRUE);

        $data['profile'] = $this->profiles->get_profiles(array('user_id' => $user_id))->row();

        $this->load->view('admin_member/edit', $data);
    }

    //用户信息修改
    public function edit_save()
    {

        $data['status'] = "n";

        $user_id = $this->input->post('user_id');
        $username = $this->input->post('username');
        $profile = $this->input->post('profile');

        //保存用户profile
        $this->general_mdl->setTable('user_profiles');
        $this->general_mdl->update(array("user_id" => $user_id),$profile);

        $data['status'] = "y";
        $data['info'] = "修改成功";

        echo json_encode($data);
    }

    //重置密码
    public function reset_password()
    {

        $data['success'] = FALSE;
        $data['username'] = $username = $this->input->post('username');

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
                        $data['success'] = TRUE;
                    }

                }else{
                    $errors = $this->tank_auth->get_error_message();
                    foreach ($errors as $k => $v)   $data['errors'][$k] = $this->lang->line($v);
                }
            }
            else
            {
                $data['errors']['password'] = form_error('password');
                $data['errors']['confirm_password'] = form_error('confirm_password');
            }

            echo json_encode($data);
        }
        else
        {
            $this->load->view('admin_member/reset_password', $data);
        }

    }

    function _username_check($username)
    {
        $result = $this->tank_auth->is_username_available($username);
        if ( ! $result)
        {
            $this->form_validation->set_message('_username_check', '用户名已存在。请重新填写用户名');
        }
                
        return $result;
    }

    //检查用户名
    public function username_check()
    {
        $username = $this->input->post('param');
        $result = $this->users->is_username_available($username);

        if($result){
            $data['status'] = "y";
            $data['info'] = "用户名可用";
        }else{
            $data['status'] = "n";
            $data['info'] = "用户名已存在";
        }
        echo json_encode($data);
    }

    public function del()
    {

        $user_id = $this->input->post('id');
        $code = $this->input->post('code');

        $response['success'] = false;

        $row = $this->users->get_user_by_id($user_id,1);

        $confirm_code = md5($user_id.$row->password);
        if($code == $confirm_code)
        {
            $this->users->delete_user($user_id);
            $response['success'] = true;
        }

        echo json_encode($response);

    }


    public function ban()
    {

        $data['success'] = false;
        $user_id = $this->input->post('id');
        $reason = $this->input->post('reason') ? $this->input->post('reason') : "禁用帐户并非删除";

        $this->users->ban_user($user_id, $reason);

        $data['success'] = true;
    }

    //入库页面
    public function recharge_vcoin()
    {

        $this->data['user_id'] = $this->input->post('user_id');
        $this->data['username'] = $this->input->post('username');
        $this->data['name'] = $this->input->post('name');
        $this->load->view('admin_member/vcoin', $this->data);
    }

    //入库
    public function vcoin_save()
    {
        checkPermission('vcoin_admin');
        $this->load->library('dx_auth');

        $vcoin = $this->input->post('vcoin');
        $member_id = $this->input->post('user_id');
        $log = $this->input->post('log');

        $log = $this->input->post('log');
        
        $this->general_mdl->setTable('user_profiles');
        $isUpdated = $this->general_mdl->field_arith('vcoin', $vcoin, array('user_id'=>$member_id));

        if($isUpdated){
            $response['status'] = "y";
            $response['info'] = "操作成功";

            //充值记录
            $tmp = 'v币%s %s ';
            $default_remarks = sprintf(
                $tmp, 
                $vcoin > 0 ? '充值' : '扣减', abs($vcoin)
            );
            $log_data['member_id'] = $member_id;
            $log_data['user_id'] = $this->dx_auth->get_user_id();
            $log_data['log'] = $log."<br>".$default_remarks;
            $log_data['datetime'] = date('Y-m-d H:i:s');
            $this->db->insert('vcoin_log', $log_data);

        }else{
            $response['status'] = "n";
            $response['info'] = "操作失败";
        }

        echo json_encode($response);
    }

    //充值记录
    public function vcoin_log()
    {        
        checkPermission('vcoin_admin');
        $this->general_mdl->setTable('vcoin_log as vl');

        $this->data['id'] = $id = $this->input->get_post('user_id');

        $this->data['title'] = '充值记录';
        $this->db->join('admin_user_profile as up', 'vl.user_id = up.user_id','left');
        $this->data['result'] = $this->general_mdl->get_query_by_where(array('member_id' => $id))->result_array();
        
        $this->load->view('admin_member/vcoin_log', $this->data);
    }

    //入库页面
    public function servicetime()
    {
        $this->data['user_id'] = $this->input->post('user_id');
        $this->data['username'] = $this->input->post('username');
        $this->data['name'] = $this->input->post('name');
        $this->load->view('admin_member/servicetime', $this->data);
    }

    //入库
    public function servicetime_save()
    {
        checkPermission('servicetime_admin');
        $this->load->library('dx_auth');

        $servicetime = $this->input->post('servicetime');
        $member_id = $this->input->post('user_id');
        
        $this->general_mdl->setTable('user_profiles');
        $isUpdated = $this->general_mdl->field_arith('servicetime', $servicetime, array('user_id'=>$member_id));

        if($isUpdated){
            $response['status'] = "y";
            $response['info'] = "操作成功";
        }else{
            $response['status'] = "n";
            $response['info'] = "操作失败";
        }

        echo json_encode($response);
    }
}
?>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Campaign extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        checkIsLoggedIn();

        $this->load->model('dx_auth/users', 'users');
        $this->load->model('dx_auth/user_profile', 'profile');

        $this->config->load('recyclecity');
        $this->data['campaign_type'] = $this->config->item('campaign_type');
        $this->data['ajax_view'] = "";
        
        $this->data['controller_url'] = "admin/campaign/";
        $this->general_mdl->setTable('campaign');
    }

    private function check_type_perm($type)
    {
        switch ($type) {
            case 1:
                checkPermission('v_campaign_admin');
                $this->data['ajax_view'] = '#v_campaign_view';
                break;
            case 2:
                checkPermission('c_campaign_admin');
                $this->data['ajax_view'] = '#c_campaign_view';
                break;
            case 3:
                checkPermission('d_campaign_admin');
                $this->data['ajax_view'] = '#d_campaign_view';
                break;   
            case 4:
                checkPermission('b_campaign_admin');
                $this->data['ajax_view'] = '#b_campaign_view';
                break;   
            default:
                redirect('/admin');
                break;
        }
    }

    public function index()
    {
        $this->data['type'] = $type = $this->input->get_post('type');
        $this->check_type_perm($type);

        $campaign_data = array();

        $this->data['q'] = $q = $this->input->get_post('q');
        $this->data['start'] = $start = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
        $this->data['pageSize'] = $pageSize = $this->input->get_post('pageSize') ? $this->input->get_post('pageSize') : 20;
        
        $like = array();
        $where = array();
        if($q){$like['title'] = $q;}
        if($type){$where['type'] = $type;}

        //查询数据的总量,计算出页数
        $query = $this->general_mdl->get_query_or_like($like, $where);
        $this->data['total'] = $query->num_rows();
        $page = ceil($query->num_rows()/$pageSize);
        $this->data['page'] = $page;

        //取出当前面数据
        $query = $this->general_mdl->get_query_or_like($like, $where,  ($start-1)*$pageSize, $pageSize);
        $campaign_data = $query->result_array();
        $this->data['current_page'] = $start;

        $prev_link = $this->data['controller_url'].'?page='.($start == 1 ? $start : $start-1);
        $prev_link .= $q ? '&q='.$q : '';
        $prev_link .= $type ? '&type='.$type : '';

        $next_link = $this->data['controller_url'].'?page='.($start == $page ? $start : $start+1);
        $next_link .= $q ? '&q='.$q : '';
        $next_link .= $type ? '&type='.$type : '';

        $this->data['prev_link'] = $prev_link;
        $this->data['next_link'] = $next_link;

        $page_link = array();
        for ($i=1; $i <= $page; $i++){
            $page_link[$i] = $this->data['controller_url'].'?page='.$i;
            $page_link[$i] .= $q ? '&q='.$q : '';
            $page_link[$i] .= $type ? '&type='.$type : '';
        }
        $this->data['page_links'] = $page_link;

        $this->data['title'] = $this->data['campaign_type'][$type].'发布管理';
        $this->data['result'] = $campaign_data;

        $this->load->view('admin_campaign/list', $this->data);
    }

    //添加
    public function add()
    {
        $this->data['type'] = $type = $this->input->get_post('type');
        $this->check_type_perm($type);

        $this->load->view('admin_campaign/add',$this->data);
    }

    //添加保存
    public function add_save()
    {
        $data = $this->input->post(NULL, TRUE);
        $this->check_type_perm($data['type']);

        if($product_id = $this->general_mdl->create($data))
        {
            $response['status'] = "y";
            $response['info'] = "添加成功";
        }else{
            $response['status'] = "n";
            $response['info'] = "添加失败";
        }

        echo json_encode($response);
    }

    //修改
    public function edit()
    {
        $this->data['id'] = $this->input->get_post('id');

        $query = $this->general_mdl->get_query_by_where(array('id' => $this->data['id']));
        $row = $query->row_array();

        $this->data['row'] = $row;
        $this->check_type_perm($row['type']);

        $this->load->view('admin_campaign/edit', $this->data);
    }

    //修改保存
    public function edit_save()
    {
        $data = $this->input->post(NULL, TRUE);
        $id = $data['id'];
        unset($data['id']);
        unset($data['type']);
        //删除旧图片
        $old_img = $this->general_mdl->get_query_by_where(array('id'=>$id))->row()->thumb;
        if($old_img !== $data['thumb'] && is_file($old_img)){
            unlink($old_img);
        }
        $isUpdated = $this->general_mdl->update(array('id'=>$id),$data);

        if($isUpdated){
            $response['status'] = "y";
            $response['info'] = "修改成功";
        }else{
            $response['status'] = "n";
            $response['info'] = "修改完成";
        }

        echo json_encode($response);
    }

    //删除
    public function del()
    {
        $id = $this->input->post('id');

        $query = $this->general_mdl->get_query_by_where(array('id' => $id));
        $row = $query->row_array();
        $this->check_type_perm($row['type']);

        $response['success'] = false;
 
        $this->general_mdl->delete_by_id($id);
        $response['success'] = true;

        echo json_encode($response);
    }

    //相关表删除功能
    private function com_del($table)
    {
        $id = $this->input->post('id');

        $response['success'] = false;
 
        $this->general_mdl->setTable($table);
        $this->general_mdl->delete_by_id($id);
        $response['success'] = true;

        echo json_encode($response);
    }    

    //活动评论列表
    public function comments()
    {
        $campaign_data = array();

        $this->data['controller_url2'] = "admin/campaign/comments";
        $page_link_param = array('rel_id', 'campaign_name', 'q', 'type');

        $this->data['type'] = $type = $this->input->get_post('type');
        $this->check_type_perm($type);

        $this->data['rel_id'] = $rel_id = $this->input->get_post('rel_id');
        $this->data['campaign_name'] = $campaign_name = $this->input->get_post('campaign_name');
        $this->data['q'] = $q = $this->input->get_post('q');
        $this->data['start'] = $start = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
        $this->data['pageSize'] = $pageSize = $this->input->get_post('pageSize') ? $this->input->get_post('pageSize') : 20;
        
        $like = array();
        $where = array();
        if($q){$like['content'] = $q;}
        if($rel_id){$where['rel_id'] = $rel_id;}

        //查询数据的总量,计算出页数
        $this->general_mdl->setTable('comments');
        $query = $this->general_mdl->get_query_or_like($like, $where);
        $this->data['total'] = $query->num_rows();
        $page = ceil($query->num_rows()/$pageSize);
        $this->data['page'] = $page;

        //取出当前面数据
        $query = $this->general_mdl->get_query_or_like($like, $where, ($start-1)*$pageSize, $pageSize);
        $campaign_data = $query->result_array();
        $this->data['current_page'] = $start;

        $prev_link = $this->data['controller_url2'].'?page='.($start == 1 ? $start : $start-1);
        $next_link = $this->data['controller_url2'].'?page='.($start == $page ? $start : $start+1);

        foreach ($page_link_param as $param) {
            $prev_link .= $$param ? '&'.$param.'='.$$param : '';
            $next_link .= $$param ? '&'.$param.'='.$$param : '';
        }

        $this->data['prev_link'] = $prev_link;
        $this->data['next_link'] = $next_link;

        $page_link = array();
        for ($i=1; $i <= $page; $i++){
            $page_link[$i] = $this->data['controller_url2'].'?page='.$i;
            foreach ($page_link_param as $param) {
                $page_link[$i] .= $$param ? '&'.$param.'='.$$param : '';
            }
        }

        $this->data['page_links'] = $page_link;

        $this->data['title'] = $campaign_name.' 评论管理';
        $this->data['result'] = $campaign_data;

        $this->load->view('admin_campaign/comment_list', $this->data);
    }

    //评论删除
    public function comment_del()
    {
        $this->com_del('comments');
    }

    //捐赠预约列表
    public function donation_book_list()
    {
        $campaign_data = array();

        $this->data['controller_url2'] = "admin/campaign/donation_book_list";
        $page_link_param = array('campaign_id', 'campaign_name', 'q', 'type');

        $this->data['type'] = $type = $this->input->get_post('type');
        $this->check_type_perm($type);

        $this->data['campaign_id'] = $campaign_id = $this->input->get_post('campaign_id');
        $this->data['campaign_name'] = $campaign_name = $this->input->get_post('campaign_name');
        $this->data['q'] = $q = $this->input->get_post('q');
        $this->data['start'] = $start = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
        $this->data['pageSize'] = $pageSize = $this->input->get_post('pageSize') ? $this->input->get_post('pageSize') : 20;
        
        $like = array();
        $where = array();
        if($q){$like['name'] = $q;}
        if($campaign_id){$where['campaign_id'] = $campaign_id;}

        //查询数据的总量,计算出页数
        $this->general_mdl->setTable('donation_book');
        $query = $this->general_mdl->get_query_or_like($like, $where);
        $this->data['total'] = $query->num_rows();
        $page = ceil($query->num_rows()/$pageSize);
        $this->data['page'] = $page;

        //取出当前面数据
        $query = $this->general_mdl->get_query_or_like($like, $where, ($start-1)*$pageSize, $pageSize);
        $campaign_data = $query->result_array();
        $this->data['current_page'] = $start;

        $prev_link = $this->data['controller_url2'].'?page='.($start == 1 ? $start : $start-1);
        $next_link = $this->data['controller_url2'].'?page='.($start == $page ? $start : $start+1);

        foreach ($page_link_param as $param) {
            $prev_link .= $$param ? '&'.$param.'='.$$param : '';
            $next_link .= $$param ? '&'.$param.'='.$$param : '';
        }

        $this->data['prev_link'] = $prev_link;
        $this->data['next_link'] = $next_link;

        $page_link = array();
        for ($i=1; $i <= $page; $i++){
            $page_link[$i] = $this->data['controller_url2'].'?page='.$i;
            foreach ($page_link_param as $param) {
                $page_link[$i] .= $$param ? '&'.$param.'='.$$param : '';
            }
        }

        $this->data['page_links'] = $page_link;

        $this->data['title'] = $campaign_name.' 捐赠预约';
        $this->data['result'] = $campaign_data;

        $this->load->view('admin_campaign/donation_book_list', $this->data);
    }

    //捐赠预约删除
    public function donation_book_del()
    {
        $this->com_del('donation_book');
    }

    //活动报名列表
    public function signup_log_list()
    {
        $campaign_data = array();

        $this->data['controller_url2'] = "admin/campaign/signup_log_list";
        $page_link_param = array('campaign_id', 'campaign_name', 'q', 'type');

        $this->data['type'] = $type = $this->input->get_post('type');
        $this->check_type_perm($type);

        $this->data['campaign_id'] = $campaign_id = $this->input->get_post('campaign_id');
        $this->data['campaign_name'] = $campaign_name = $this->input->get_post('campaign_name');
        $this->data['q'] = $q = $this->input->get_post('q');
        $this->data['start'] = $start = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
        $this->data['pageSize'] = $pageSize = $this->input->get_post('pageSize') ? $this->input->get_post('pageSize') : 20;
        
        $like = array();
        $where = array();
        if($q){$like['cul.name'] = $q;}
        if($campaign_id){$where['cul.campaign_id'] = $campaign_id;}

        //查询数据的总量,计算出页数
        $this->general_mdl->setTable('campaign_users_log as cul');
        $query = $this->general_mdl->get_query_or_like($like, $where);
        $this->data['total'] = $query->num_rows();
        $page = ceil($query->num_rows()/$pageSize);
        $this->data['page'] = $page;

        //取出当前面数据
        $this->db->select('uc.name as uc_name,uc.phone  as uc_phone, cul.*');
        $this->db->join('user_profiles as uc', 'cul.member_id = uc.user_id', 'left');
        $query = $this->general_mdl->get_query_or_like($like, $where, ($start-1)*$pageSize, $pageSize);
        $campaign_data = $query->result_array();
        $this->data['current_page'] = $start;

        $prev_link = $this->data['controller_url2'].'?page='.($start == 1 ? $start : $start-1);
        $next_link = $this->data['controller_url2'].'?page='.($start == $page ? $start : $start+1);

        foreach ($page_link_param as $param) {
            $prev_link .= $$param ? '&'.$param.'='.$$param : '';
            $next_link .= $$param ? '&'.$param.'='.$$param : '';
        }

        $this->data['prev_link'] = $prev_link;
        $this->data['next_link'] = $next_link;

        $page_link = array();
        for ($i=1; $i <= $page; $i++){
            $page_link[$i] = $this->data['controller_url2'].'?page='.$i;
            foreach ($page_link_param as $param) {
                $page_link[$i] .= $$param ? '&'.$param.'='.$$param : '';
            }
        }

        $this->data['page_links'] = $page_link;

        $this->data['title'] = $campaign_name.' 参与者管理';
        $this->data['result'] = $campaign_data;

        $this->load->view('admin_campaign/signup_log_list', $this->data);
    }

    //活动报名删除
    public function signup_log_del()
    {
        $this->com_del('campaign_users_log');
    }
}

/* End of file campaign.php */
/* Location: ./application/controllers/campaign.php */

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        checkIsLoggedIn();

        $this->load->model('dx_auth/users', 'users');
        $this->load->model('dx_auth/user_profile', 'profile');

        $this->config->load('recyclecity');
        $this->data['news_type'] = $this->config->item('news_type');
        $this->data['ajax_view'] = '';

        $this->data['controller_url'] = "admin/news/";
        $this->general_mdl->setTable('news');
    }

    private function check_type_perm($type)
    {
        switch ($type) {
            case 1:
                checkPermission('aboutus_admin');
                $this->data['ajax_view'] = '#aboutus_view';
                break;
            case 2:
                checkPermission('business_admin');
                $this->data['ajax_view'] = '#business_news_view';
                break;
            case 3:
                checkPermission('news_admin');
                $this->data['ajax_view'] = '#news_view';
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

        $news_data = array();

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
        $this->db->order_by('alias DESC,id DESC');
        $query = $this->general_mdl->get_query_or_like($like, $where, ($start-1)*$pageSize, $pageSize);
        $news_data = $query->result_array();
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

        $this->data['title'] = $this->data['news_type'][$type]. '管理';
        $this->data['result'] = $news_data;

        $this->load->view('admin_news/list', $this->data);
    }

    //添加
    public function add()
    {
        $this->data['type'] = $type = $this->input->get_post('type');
        $this->check_type_perm($type);
        $this->load->view('admin_news/add',$this->data);
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
        $this->load->view('admin_news/edit', $this->data);
    }

    //修改保存
    public function edit_save()
    {
        $data = $this->input->post(NULL, TRUE);
        $id = $data['id'];
        unset($data['id']);
        unset($data['type']);

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

        $response['success'] = false;
 
        $this->general_mdl->delete_by_id($id);
        $response['success'] = true;

        echo json_encode($response);
    }
}

/* End of file news.php */
/* Location: ./application/controllers/news.php */

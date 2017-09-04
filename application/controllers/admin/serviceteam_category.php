<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Serviceteam_category extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        checkIsLoggedIn();

        $this->load->model('dx_auth/users', 'users');
        $this->load->model('dx_auth/user_profile', 'profile');

        $this->config->load('recyclecity');
        $this->data['ajax_view'] = '#serviceteam_category_view';

        $this->data['controller_url'] = "admin/serviceteam_category/";
        $this->general_mdl->setTable('serviceteam_category');
    }

    public function index()
    {
        checkPermission('serviceteam_admin');

        $serviceteam_category_data = array();

        $this->data['q'] = $q = $this->input->get_post('q');
        $this->data['start'] = $start = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
        $this->data['pageSize'] = $pageSize = $this->input->get_post('pageSize') ? $this->input->get_post('pageSize') : 20;
        
        $like = array();
        $where = array();
        if($q){$like['name'] = $q;}

        //查询数据的总量,计算出页数
        $query = $this->general_mdl->get_query_or_like($like, $where);
        $this->data['total'] = $query->num_rows();
        $page = ceil($query->num_rows()/$pageSize);
        $this->data['page'] = $page;

        //取出当前面数据
        $this->db->order_by('id DESC');
        $query = $this->general_mdl->get_query_or_like($like, $where, ($start-1)*$pageSize, $pageSize);
        $serviceteam_category_data = $query->result_array();
        $this->data['current_page'] = $start;

        $prev_link = $this->data['controller_url'].'?page='.($start == 1 ? $start : $start-1);
        $prev_link .= $q ? '&q='.$q : '';

        $next_link = $this->data['controller_url'].'?page='.($start == $page ? $start : $start+1);
        $next_link .= $q ? '&q='.$q : '';

        $this->data['prev_link'] = $prev_link;
        $this->data['next_link'] = $next_link;

        $page_link = array();
        for ($i=1; $i <= $page; $i++){
            $page_link[$i] = $this->data['controller_url'].'?page='.$i;
            $page_link[$i] .= $q ? '&q='.$q : '';
        }
        $this->data['page_links'] = $page_link;

        $this->data['title'] = '服务队类别管理';
        $this->data['result'] = $serviceteam_category_data;

        $this->load->view('admin_serviceteam_category/list', $this->data);
    }
    
    //添加
    public function add()
    {
        checkPermission('serviceteam_admin');
        $this->load->view('admin_serviceteam_category/add',$this->data);
    }

    //添加保存
    public function add_save()
    {
        checkPermission('serviceteam_admin');
        $data = $this->input->post(NULL, TRUE);

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
        checkPermission('serviceteam_admin');
        $this->data['id'] = $this->input->get_post('id');

        $query = $this->general_mdl->get_query_by_where(array('id' => $this->data['id']));
        $row = $query->row_array();

        $this->data['row'] = $row;
        $this->load->view('admin_serviceteam_category/edit', $this->data);
    }

    //修改保存
    public function edit_save()
    {
        $data = $this->input->post(NULL, TRUE);
        $id = $data['id'];
        unset($data['id']);
        
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

/* End of file serviceteam_category.php */
/* Location: ./application/controllers/serviceteam_category.php */

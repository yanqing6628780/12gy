<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Goods extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        checkIsLoggedIn();

        $this->load->model('dx_auth/users', 'users');
        $this->load->model('dx_auth/user_profile', 'profile');

        $this->data['controller_url'] = "admin/goods/";
        $this->general_mdl->setTable('goods');
        checkPermission('goods_admin');
    }

    public function index()
    {

        $goods_data = array();

        $this->data['q'] = $q = $this->input->get_post('q');
        $this->data['start'] = $start = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
        $this->data['pageSize'] = $pageSize = $this->input->get_post('pageSize') ? $this->input->get_post('pageSize') : 20;
        
        $like = array();
        if($q){
            $like['name'] = $q;
        }

        //查询数据的总量,计算出页数
        $query = $this->general_mdl->get_query_or_like($like, array());
        $this->data['total'] = $query->num_rows();
        $page = ceil($query->num_rows()/$pageSize);
        $this->data['page'] = $page;

        //取出当前面数据
        $query = $this->general_mdl->get_query_or_like($like, array(), ($start-1)*$pageSize, $pageSize);
        $query = $this->general_mdl->get_query_or_like($like, array());
        $goods_data = $query->result_array();
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

        $this->data['title'] = '礼品管理';
        $this->data['result'] = $goods_data;

        $this->load->view('admin_goods/list', $this->data);
    }

    //添加
    public function add()
    {
        $this->load->view('admin_goods/add',$this->data);
    }

    //添加保存
    public function add_save()
    {
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
        $this->data['id'] = $this->input->post('id');

        $query = $this->general_mdl->get_query_by_where(array('id' => $this->data['id']));
        $row = $query->row_array();

        $this->data['row'] = $row;

        $this->load->view('admin_goods/edit', $this->data);
    }

    //修改保存
    public function edit_save()
    {
        $data = $this->input->post(NULL, TRUE);
        $id = $data['id'];
        unset($data['id']);
        //删除旧图片
        $old_img = $this->general_mdl->get_query_by_where(array('id'=>$id))->row()->img;
        if($old_img !== $data['img'] && is_file($old_img)){
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

        $response['success'] = false;
 
        $this->general_mdl->delete_by_id($id);
        $response['success'] = true;

        echo json_encode($response);
    }
}

/* End of file goods.php */
/* Location: ./application/controllers/goods.php */

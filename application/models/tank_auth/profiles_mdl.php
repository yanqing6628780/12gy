<?php

class profiles_mdl extends General_mdl
{
    public $profile_config;

    function __construct()
    {
        parent::__construct();

        $this->setTable('user_profiles');
        $CI = &get_instance();

        $CI->config->load('recyclecity');
        $this->profile_config['my_language'] = $CI->config->item('my_language');
        $this->profile_config['work_status'] = $CI->config->item('work_status');
        $this->profile_config['health_care'] = $CI->config->item('health_care');
        $this->profile_config['computer_tec'] = $CI->config->item('computer_tec');
        $this->profile_config['handmade'] = $CI->config->item('handmade');
        $this->profile_config['personal_talent'] = $CI->config->item('personal_talent');
        $this->profile_config['sports_teach'] = $CI->config->item('sports_teach');
        $this->profile_config['other_areas'] = $CI->config->item('other_areas');
        $this->profile_config['pro_repair'] = $CI->config->item('pro_repair');
        $this->profile_config['service_items'] = $CI->config->item('service_items');
        $this->profile_config['star_service_items'] = $CI->config->item('star_service_items');
        $this->profile_config['days'] = $CI->config->item('days');
        $this->profile_config['times'] = $CI->config->item('times');
    }

    public function get_profiles($where = array())
    {
        return $this->get_query_by_where($where);
    }

    public function get_by_user_id($id)
    {
        return $this->get_query_by_where(array('user_id' => $id));
    }

    /**
     * 用户资料更新
     *
     * @param int $user_id 要更新的用户id
     * @param Array $profile 要更新的用户资料数据
     * @param Array $profile_field_content 用户资料表单内的复选框要求输入的内容
     */
    public function update_all($user_id, $profile, $profile_field_content)
    {
        $profile['service_items'] = isset($profile['service_items']) ? implode(',', $profile['service_items']) : '';
        $profile['star_service_items'] = isset($profile['star_service_items']) ? implode(',', $profile['star_service_items']) : '';
        
        //需要转换为json格式的字段
        $checkbox_filed_key = array(
            'my_language', 
            'health_care', 
            'computer_tec', 
            'handmade', 'personal_talent', 
            'sports_teach', 
            'other_areas', 
            'pro_repair'
        );
        
        //转换为json格式数据
        foreach ($checkbox_filed_key as $value) {
            if( isset($profile[$value]) ){
                $profile[$value] = $this->profile_checkbox_handler(
                    $profile[$value], 
                    $value,
                    $profile_field_content
                );
            }
        }
        //处理参与时间
        foreach ($this->profile_config['days'] as $value) {
            $tmp = '';
            foreach ($this->profile_config['times']as $key => $v) {
                if(isset($profile[$value])){
                    $tmp .= array_key_exists($key, $profile[$value]) ? 1 : 0;
                }else{
                    $tmp .= 0;
                }
            }
            $profile[$value] = $tmp;
        }

        return $this->update(array("user_id" => $user_id), $profile);
    }

    //将复制框值转换为json格式
    private function profile_checkbox_handler($data_arr, $k, $field_content = array())
    {
        $tmp = array();
        if($data_arr){
            foreach ($this->profile_config[$k] as $key => $item) {
                $tmp[$key]['status'] = FALSE;
                $values = explode(',', $item);
                $tmp[$key]['content'] = "";
                $skey = array_search($values[0], $data_arr);
                if($skey !== FALSE) {
                    $tmp[$key]['status'] = TRUE;
                    if( isset($field_content[$k][$skey]) ){
                        $tmp[$key]['content'] =  $field_content[$k][$skey];
                    }
                }
            }
        }
        return json_encode($tmp, JSON_UNESCAPED_UNICODE);
    }
}

?>

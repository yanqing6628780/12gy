<?php

class global_mdl extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function foot_nav()
    {
        $data['about_news'] = $this->db->get_where('news', array('type' => 1),3,0)->result();
        $data['business_news'] = $this->db->order_by("id", "desc")->get_where('news', array('type' => 2),3,0)->result();
        return $data;
    }

}

?>

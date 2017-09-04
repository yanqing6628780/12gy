<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class xls_output extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        checkIsLoggedIn();
        $this->load->library('Phpexcel');
        $this->load->library('MyIof', '', 'IoFactory');

        $this->_upload_path = $_SERVER["DOCUMENT_ROOT"] . dirname($_SERVER["SCRIPT_NAME"]) . '/excel';

    }

    public function index()
    {
        $data = array();
        $this->load->view('head', $data);
        $this->load->view('report/report_index');
    }

    public function members_campaign()
    {
        checkPermission('member_admin');

        $ids = $this->input->post('id');
        $data['result'] = array();
        if($ids){
            foreach ($ids as $id) {
                $data['result'][] = $this->member_campaign($id);
            }
        }

        $this->load->view('admin_member/download_list', $data);
    }

    public function member_campaign($id)
    {
        checkPermission('member_admin');

        $this->load->model('tank_auth/profiles_mdl', 'profiles');
        $profile_config = $this->profiles->profile_config;

        //会员资料
        $row = $this->profiles->get_by_user_id($id)->row();

        if(!$row){
            return "ID:".$id." 用户数据不存在";
            exit();
        }

        $dirpath = sprintf("%s/%s", $this->_upload_path, $dir = "member_campaign"); //保存文件的目录路径
        create_folders($dirpath); //创建目录

        $title = $row->name."_".$row->sn." 参加过的活动";
        $obj_phpexcel = $this->phpexcel;
        $obj_phpexcel->disconnectWorksheets(); //清除工作薄内容
        $obj_phpexcel->createSheet(); //创建一个新的工作薄
        $obj_phpexcel->setActiveSheetIndex(0);//設置打開excel時顯示哪個工作表

        $temp_active_sheet = $obj_phpexcel->getActiveSheet();
        $temp_active_sheet->getDefaultColumnDimension()->setAutoSize(true);//設置單元格寬度
        $temp_active_sheet->setTitle($title);//設置當前工作表的名稱
        $temp_active_sheet->getStyle("A1:I500")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); //垂直居中
        $temp_active_sheet->getStyle("A1:I500")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); //水平居中

        $variable = array(
            'name' => "姓名",
            'id_card' => "身份证号"
        );

        $i = 1;
        foreach ($variable as $key => $value)
        {
            $start_cell = "A".$i;
            $temp_active_sheet->setCellValue($start_cell, $value);

            $cell_value = $row->$key;
            $temp_active_sheet->setCellValueExplicit("B".$i,$cell_value);

            $i++;
        }

        $variable = array(
            'sn' => "义工编号",
            'phone' => "联系方式"
        );

        $i = 1;
        foreach ($variable as $key => $value)
        {
            $start_cell = "C".$i;
            $temp_active_sheet->setCellValue($start_cell, $value);

            $cell_value = $row->$key;
            $temp_active_sheet->setCellValueExplicit("D".$i,$cell_value);

            $i++;
        }

        //用户参加过的活动
        $this->db->select('c.title,c.startdate,c.address,c.reward_time');
        $this->db->join('campaign as c', 'cul.campaign_id = c.id', 'inner');
        $this->db->where('cul.member_id', $id);
        $campaigns = $this->db->order_by('c.startdate','desc')->get('campaign_users_log as cul')->result();

        $variable = array(
            'title' => "活动名称",
            'startdate' => "活动时间",
            'address' => "活动地址",
            'reward_time' => "活动时数",
        );

        $col = "A";//起始列
        foreach ($variable as $key => $value)
        {
            $cell = $col."4";
            $temp_active_sheet->setCellValue($cell, $value);
            $col++;
        }
        $i = 5;
        foreach ($campaigns as $key => $item)
        {
            $col = "A";//起始列
            foreach ($variable as $field => $value)
            {
                $cell = $col.$i;
                $temp_active_sheet->setCellValue($cell, $item->$field);
                $col++; //换列
            }

            $i++; //换行
        }

        $excel_name = $row->sn.".xls"; //保存的文件名

        $obj_writer = $this->IoFactory->createWriter($this->phpexcel, 'Excel5');
        $excelpath = sprintf("%s/%s", $dirpath ,$excel_name); //保存excel的路径
        $obj_writer->save($excelpath);

        $link = base_url().'excel/'.$dir.'/'.$excel_name;
        return sprintf("<a class='f14' target='_blank' download='%s' href='%s'>%s_下载</a>", $excel_name, $link, $title);
    }

    public function member_summary()
    {
        checkPermission('member_admin');

        $this->load->model('dx_auth/user_profile', 'admin_profile');
        $this->load->model('tank_auth/profiles_mdl', 'profiles');
        $profile_config = $this->profiles->profile_config;
        $serviceteam= $this->db->order_by('category_id Asc, id Asc')->get("serviceteam")->result_array();

        $where_sql = "u.id > 0";
        //非管理员
        if( !$this->dx_auth->is_admin() ) {
            //后台用户只能查看属于其项目下的所有会员
            $admin_user_id = $this->dx_auth->get_user_id();
            $admin_user_profile = $this->admin_profile->get_profile($admin_user_id)->row();
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

        $query_page = $this->db->order_by('up.sn desc')->get('users as u');
        $result = $query_page->result_array();

        $dirpath = sprintf("%s/%s", $this->_upload_path, $dir = "member"); //保存文件的目录路径
        create_folders($dirpath); //创建目录

        $title = "会员汇总资料";
        $obj_phpexcel = $this->phpexcel;
        $obj_phpexcel->disconnectWorksheets(); //清除工作薄内容
        $obj_phpexcel->createSheet(); //创建一个新的工作薄
        $obj_phpexcel->setActiveSheetIndex(0);//設置打開excel時顯示哪個工作表

        $temp_active_sheet = $obj_phpexcel->getActiveSheet();
        $temp_active_sheet->getDefaultColumnDimension()->setAutoSize(true);//設置單元格寬度
        $temp_active_sheet->setTitle($title);//設置當前工作表的名稱

        //计算服务队要占列数,计算服务时间列位置
        $serviceteam_start_col_index = PHPExcel_Cell::columnIndexFromString('W')-1;
        $serviceteam_end_col_index = $serviceteam_start_col_index + count($serviceteam)-1;
        $serviceteam_end_col = PHPExcel_Cell::stringFromColumnIndex($serviceteam_end_col_index);
        $servicetime_start_col = PHPExcel_Cell::stringFromColumnIndex($serviceteam_end_col_index+1);
        $this->merge_and_set_cell_value($temp_active_sheet, "A1", "N1", "基本信息");
        $this->merge_and_set_cell_value($temp_active_sheet, "O1", "V1", "义工数据库（你有何特长）");
        $this->merge_and_set_cell_value($temp_active_sheet, "W1", $serviceteam_end_col."1", "你选择的服务项目");
        $this->merge_and_set_cell_value($temp_active_sheet, $servicetime_start_col."1", $servicetime_start_col."2", "可选择的服务时间");

        $temp_active_sheet->getColumnDimension('A')->setWidth(12);
        $temp_active_sheet->getColumnDimension('C')->setWidth(21);
        $temp_active_sheet->getColumnDimension('I')->setWidth(12);
        $temp_active_sheet->getColumnDimension('F')->setWidth(16);
        $temp_active_sheet->getColumnDimension($servicetime_start_col)->setWidth(20);

        $all_cell = "A1:".$servicetime_start_col.(count($result)+2);
        $temp_active_sheet->getStyle($all_cell)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); //垂直居中
        $temp_active_sheet->getStyle($all_cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); //水平居中
        $temp_active_sheet->getStyle($all_cell)->getAlignment()->setWrapText(true);


        $variable = array(
            'sn' => "义工编号",
            'name' => "姓名",
            'id_card' => "身份证号码",
            'sex' => "性别",
            'native_place' => "籍贯",
            'birthday' => "出生年月日",
            'ed' => "文化程度",
            'qq' => "QQ",
            'phone' => "联系电话(手机)",
            'community' => "居住社区",
            'email' => "E-mail",
            'hobby' => "兴趣",
            'my_language' => "流利语言/方言",
            'address' => "地址",
            'work_status' => "工作现状",
            'servicetime' => "服务时长(小时)",
            'health_care' => "医疗保健",
            'computer_tec' => "电脑技术",
            'handmade' => "手工制作",
            'personal_talent' => "个人才艺",
            'sports_teach' => "运动教学",
            'other_areas' => "其他领域",
            'pro_repair' => "专业维修"
        );
        $json_data_field = array('health_care',  'computer_tec',  'handmade',  'personal_talent',  'sports_teach',  'other_areas',  'pro_repair');

        //题头
        $this->config->load('recyclecity');
        // $arr = $this->config->item('service_items');
        foreach ($serviceteam as $item) {
            $arr[] = $item['name'];
        }
        $table_head = array_merge($variable, $arr);

        $start_col = "A";
        foreach ($table_head as $key => $item) {
            $start_cell = $start_col."2";
            $temp_active_sheet->setCellValue($start_cell, $item);
            $start_col++;
        }

        $variable['service_items'] = "";
        $start_row = "3";
        foreach ($result as $row)
        {
            $start_col = "A";
            foreach ($variable as $field => $item)
            {
                $cell_value = $row[$field];
                //数据处理
                switch ($field)
                {
                    case 'sex':
                        $cell_value = $cell_value ? "男" : "女";
                        break;
                    case 'birthday':
                        $cell_value = trans_date_format($cell_value,"Y年m月d日");
                        break;
                    case 'work_status':
                        $cell_value = $this->trans_radio_to_str_style($profile_config['work_status'], $cell_value);
                        break;
                    case 'my_language':
                        $tmp_arr = array();
                        $my_language = json_decode($cell_value);
                        if($my_language) {
                            foreach ($my_language as $key => $item) {
                                $content = $profile_config['my_language'][$key];
                                if($item->status){
                                    $tmp_arr[] = "■".$content;
                                }
                            }
                        }
                        $cell_value = implode('          ', $tmp_arr);
                        break;

                    default:
                        if( in_array($field, $json_data_field) ) { //当键值在json_data_field数组内时,进行转换
                            $cell_value = $this->trans_checkbox_to_str_style($cell_value, $profile_config[$field]);
                        }
                        break;
                }

                //单元格写入数据
                switch ($field)
                {
                    case 'service_items':
                        $service_items = explode(",", $cell_value);
                        if($service_items) {
                            foreach ($serviceteam as $k => $item) {
                                if(in_array($item['id'], $service_items)){
                                    $col = PHPExcel_Cell::stringFromColumnIndex($serviceteam_start_col_index +$k);
                                    $cell = $col.$start_row;
                                    $temp_active_sheet->setCellValue($cell, "■");
                                }
                            }
                        }
                        break;
                    default:
                        $start_cell = $start_col.$start_row;
                        $temp_active_sheet->setCellValueExplicit($start_cell, $cell_value, PHPExcel_Cell_DataType::TYPE_STRING);
                        $start_col++;
                        break;
                }
            }
            //服务时间数据处理
            $service_time = "";
            foreach ($profile_config['days'] as $key => $value) {
                $tmp_arr = array();
                switch ($value) {
                    case 'normal':
                        $txt = "平时: ";
                        break;
                    case 'Sat':
                        $txt = "周六: ";
                        break;
                    case 'Sun':
                        $txt = "周日: ";
                        break;
                }
                foreach (str_split($row[$value], 1) as $k => $v) {
                    if($v) {
                        $tmp_arr[] = $profile_config['times'][$k];
                    }
                };
                if($tmp_arr){
                    $service_time .= $txt.implode(',', $tmp_arr)."\n";
                }
            }
            if($service_time){
                $temp_active_sheet->setCellValue($servicetime_start_col.$start_row, $service_time);
            }
            $start_row++;
        }

        $excel_name = "member_summary_".$this->dx_auth->get_user_id().".xls"; //保存的文件名

        $obj_writer = $this->IoFactory->createWriter($this->phpexcel, 'Excel5');
        $excelpath = sprintf("%s/%s", $dirpath ,$excel_name); //保存excel的路径
        $obj_writer->save($excelpath);

        $link = base_url().'excel/'.$dir.'/'.$excel_name;
        echo sprintf("<a class='f14' target='_blank' download='%s' href='%s'>%s_下载</a>", $excel_name, $link, $title);
    }

    public function members()
    {
        checkPermission('member_admin');

        $ids = $this->input->post('id');
        $data['result'] = array();
        if($ids){
            foreach ($ids as $id) {
                $data['result'][] = $this->member($id);
            }
        }

        $this->load->view('admin_member/download_list', $data);
    }

    public function member($id)
    {
        checkPermission('member_admin');

        $this->load->model('tank_auth/profiles_mdl', 'profiles');
        $profile_config = $this->profiles->profile_config;

        //会员资料
        $row = $this->profiles->get_by_user_id($id)->row();

        if(!$row){
            return "ID:".$id." 用户数据不存在";
            exit();
        }

        $dirpath = sprintf("%s/%s", $this->_upload_path, "member"); //保存文件的目录路径
        create_folders($dirpath); //创建目录

        $title = $row->name."_".$row->sn." 资料";
        $obj_phpexcel = $this->phpexcel;
        $obj_phpexcel->disconnectWorksheets(); //清除工作薄内容
        $obj_phpexcel->createSheet(); //创建一个新的工作薄
        $obj_phpexcel->setActiveSheetIndex(0);//設置打開excel時顯示哪個工作表

        $temp_active_sheet = $obj_phpexcel->getActiveSheet();
        $temp_active_sheet->getDefaultColumnDimension()->setAutoSize(true);//設置單元格寬度
        $temp_active_sheet->setTitle($title);//設置當前工作表的名稱
        $temp_active_sheet->getStyle("A1:P500")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); //垂直居中
        $temp_active_sheet->getStyle("A1:P500")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); //水平居中
        $temp_active_sheet->getStyle('A1:P500')->getAlignment()->setWrapText(true);
        $temp_active_sheet->getColumnDimension('A')->setWidth(12);

        $phpColor = new PHPExcel_Style_Color();
        $phpColor->setRGB('FF0000');

        $variable = array(
            'name' => "姓名",
            'sex' => "性别",
            'birthday' => "出生年月日",
            'qq' => "QQ",
            'community' => "居住社区",
            'hobby' => "兴趣",
            'address' => "地址",
            'work_status' => "工作现状"
        );
        $i = 1;
        foreach ($variable as $key => $value)
        {

            $start_cell = "A".$i;
            $temp_active_sheet->getStyle($start_cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); //水平居右
            $temp_active_sheet->setCellValue($start_cell, $value);
            $cell_value = $row->$key;

            $start_cell = "B".$i;
            $end_cell = "C".$i;

            // $temp_active_sheet->setCellValue("i".$i, "*");
            // $temp_active_sheet->getStyle("i".$i)->getFont()->setColor($phpColor);


            switch ($key) {
                case 'sex':
                    $temp_active_sheet->mergeCells($start_cell.":".$end_cell);
                    $cell_value = $cell_value ? "●男   ○女" : "○男   ●女";
                    break;
                case 'birthday':
                    $temp_active_sheet->mergeCells($start_cell.":".$end_cell);
                    $cell_value = trans_date_format($cell_value,"Y年m月d日");
                    break;
                case 'address':
                    $temp_active_sheet->mergeCells($start_cell.":"."i".$i);
                    break;
                case 'work_status':
                    $cell_value = $this->trans_radio_to_str_style($profile_config['work_status'], $cell_value, TRUE);
                    $this->merge_and_set_cell_value($temp_active_sheet, $start_cell, "i".$i, $cell_value);
                    break;
                default:
                    $temp_active_sheet->mergeCells($start_cell.":".$end_cell);
                    break;
            }
            $temp_active_sheet->setCellValue($start_cell, $cell_value);
            $i++;
        }

        $variable = array(
            'sn' => "义工编号",
            'native_place' => "籍贯",
            'id_card' => "身份证号码",
            'phone' => "联系电话(手机)",
            'email' => "E-mail",
            'my_language' => "流利语言/方言"
        );
        $i = 1;
        foreach ($variable as $key => $value)
        {
            $start_cell = "D".$i;
            $end_cell = "G".$i;
            $temp_active_sheet->getStyle($start_cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); //水平居右
            // $this->merge_and_set_cell_value($temp_active_sheet, $start_cell, $end_cell, $value);
            $temp_active_sheet->setCellValue($start_cell, $value);

            $start_cell = "E".$i;
            $cell_value = $row->$key;

            $temp_active_sheet->mergeCells($start_cell.":".$end_cell);

            switch ($key) {
                case 'id_card':
                    $cell_value = " ".$cell_value;
                    break;
                case 'my_language':
                    $my_language = json_decode($cell_value);
                    if($my_language){
                        foreach ($my_language as $key => $item) {
                            $content = $profile_config['my_language'][$key];
                            $tmp_arr[] = $item->status ? "■".$content : "□".$content;
                        }
                    }else{
                        foreach ($profile_config['my_language'] as $key => $item) {
                            $tmp_arr[] = "□".$item;
                        }

                    }
                    $cell_value = implode('          ', $tmp_arr);
                    break;
                default:
                    break;
            }

            $temp_active_sheet->setCellValueExplicit($start_cell,$cell_value);

            $i++;
        }
        $this->merge_and_set_cell_value($temp_active_sheet, "H1", "I6", '照片');

        $this->merge_and_set_cell_value($temp_active_sheet, "A9", "I9", '想加入的义工服务计划');
        $this->merge_and_set_cell_value($temp_active_sheet, "A10", "A16", '*义工储备库(你有何技能特长)');
        $temp_active_sheet->getStyle('A10')->getAlignment()->setWrapText(true);
        $this->merge_and_set_cell_value($temp_active_sheet, "A17", "A18", '*您所选择的服务类型');
        $temp_active_sheet->getStyle('A17:P18')->getAlignment()->setWrapText(true);

        $variable = array(
            'health_care' => "医疗保健",
            'computer_tec' => "电脑技术",
            'handmade' => "手工制作",
            'personal_talent' => "个人才艺",
            'sports_teach' => "运动教学",
            'other_areas' => "其他领域",
            'pro_repair' => "专业维修",
        );
        $i = 10;
        foreach ($variable as $key => $value)
        {
            $start_cell = "B".$i;
            $end_cell = "I".$i;
            $temp_active_sheet->setCellValue($start_cell, $value);

            $start_cell = "C".$i;
            $cell_value = $row->$key;
            $temp_active_sheet->mergeCells($start_cell.":".$end_cell);

            $cell_value = $this->trans_checkbox_to_str_style($cell_value, $profile_config[$key], TRUE);
            $temp_active_sheet->setCellValue($start_cell, $cell_value);

            $i++;
        }

        $serviceteam= $this->db->get("serviceteam")->result_array();
        $variable = array(
            'service_items' => "义工服务项目(可多选)",
            // 'star_service_items' => "鹏星社工星动义工服务(本项目最多填写八项目服务队伍)"
        );
        $i = 17;
        foreach ($variable as $key => $value)
        {
            $start_cell = "B".$i;
            $temp_active_sheet->setCellValue($start_cell, $value);

            $temp_active_sheet->mergeCells("C".$i.":"."I".$i);

            $cell_value = empty($row->$key) ? "" : explode(',', $row->$key);
            $tmp_arr = array();

            if($cell_value)
            {
                foreach ($serviceteam as $k => $item) {
                    if(in_array($item['id'], $cell_value)){
                        $tmp_arr[] = "■".$item['name'];
                    }
                }
            }

            // if($cell_value){
            //     foreach ($profile_config[$key] as $k => $item) {
            //         $tmp_arr[] = in_array($k, $cell_value) ? "■".$item  : "□".$item;
            //     }
            // }else{
            //     foreach ($profile_config[$key] as $item) {
            //         $tmp_arr[] = "□".$item;
            //     }
            // }

            $cell_value = implode("          ", $tmp_arr);
            $temp_active_sheet->setCellValue("C".$i, $cell_value);

            $i++;
        }

        $this->merge_and_set_cell_value($temp_active_sheet, "A19", "I19", '*可参与服务时间');
        $this->merge_and_set_cell_value($temp_active_sheet, "A20", "B23", '*选择服务时间');
        $temp_active_sheet->getStyle('A20:B23')->getAlignment()->setWrapText(true);
        $temp_active_sheet->setCellValue('C20', '时段');


        $i = 21;
        foreach ($profile_config['times'] as $key => $value) {
            // $this->merge_and_set_cell_value($temp_active_sheet, "C".$i, "D".$i, $value);
            $temp_active_sheet->setCellValue("C".$i, $value);
            $i++;
        }

        foreach ($profile_config['days'] as $key => $value)
        {

            $i = 20;

            switch ($value)
            {
                case 'normal':
                    $start_col = "D";
                    $end_col = "E";
                    $this->merge_and_set_cell_value($temp_active_sheet, $start_col.$i, $end_col.$i, '平时');
                    break;
                case 'Sat':
                    $start_col = "F";
                    $end_col = "G";
                    $this->merge_and_set_cell_value($temp_active_sheet, $start_col.$i, $end_col.$i, '周六');
                    break;
                case 'Sun':
                    $start_col = "H";
                    $end_col = "I";
                    $this->merge_and_set_cell_value($temp_active_sheet, $start_col.$i, $end_col.$i, '周日');
                    break;
            }

            $i = 21;
            $cell_value = $row->$value;
            foreach ( str_split($cell_value, 1) as $k => $item ) {
                $this->merge_and_set_cell_value($temp_active_sheet, $start_col.$i, $end_col.$i, $item ? "■"  : "□");
                $i++;
            }

        }

        $temp_active_sheet->insertNewRowBefore(1,1);
        $this->merge_and_set_cell_value($temp_active_sheet, 'A1', "I1", "佛山市顺德区容桂街道义工联合会义工登记表");
        $temp_active_sheet->getStyle("A1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); //水平居中

        $styleThinBlackBorderOutline = array(
               'borders' => array (
                     'outline' => array (
                           'style' => PHPExcel_Style_Border::BORDER_THIN   //设置border样式
                    ),
              ),
        );
        $cell_range = "A1:I24";
        $temp_active_sheet->getStyle($cell_range)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $excel_name = "member_".$row->sn.".xls"; //保存的文件名

        $obj_writer = $this->IoFactory->createWriter($this->phpexcel, 'Excel5');
        $excelpath = sprintf("%s/%s", $dirpath ,$excel_name); //保存excel的路径
        $obj_writer->save($excelpath);

        $link = base_url().'excel/member/'.$excel_name;
        return sprintf("<a class='f14' target='_blank' download='%s' href='%s'>%s_下载</a>", $excel_name, $link, $title);
    }

    //将单选项数据转换为字符串显示样式
    private function trans_radio_to_str_style($arr, $selected_value, $isShowNotSelect = FALSE)
    {
        $tmp_arr = array();
        foreach ($arr as $key => $value) {
            if($key == $selected_value){
                $tmp_arr[$key] = "■".$value;
            }else{
                if($isShowNotSelect){
                    $tmp_arr[$key] = "□".$value;
                }
            }
        }
        return implode('          ', $tmp_arr);
    }

    //将多选项数据转换为字符串显示样式
    private function trans_checkbox_to_str_style($field_data, $field_profile_config, $isShowNotSelect = FALSE)
    {
        $json_data = json_decode($field_data);
        $tmp_arr = array();
        if($json_data){
            foreach ($json_data as $k => $item) {
                $cfg_items = explode(',', $field_profile_config[$k]);
                $content = $cfg_items[0] == $item->content ? $item->content : $cfg_items[0]." ".$item->content;
                if($isShowNotSelect){
                    $tmp_arr[] = $item->status ? "■".$content : "□".$content;
                }else{
                    if($item->status){
                        $tmp_arr[] = "■".$content;
                    }
                }
            }
        }else{
            if($isShowNotSelect){
                foreach ($field_profile_config as $key => $item) {
                    $cfg_items = explode(',', $item);
                    $tmp_arr[] = "□".$cfg_items[0];
                }
            }
        }
        return implode('          ', $tmp_arr);
    }

    //合并和设置单元格的值
    private function merge_and_set_cell_value($active_sheet, $start_cell, $end_cell, $value = null)
    {
        $active_sheet->mergeCells($start_cell.':'.$end_cell);
        if($value){
            $active_sheet->setCellValue($start_cell, $value);
        }
    }

    public function campaign_participants()
    {
        $campaign_id = $this->input->post('campaign_id');
        $campaign_title = $this->input->post('campaign_title');

        $this->general_mdl->setTable('campaign');
        $query = $this->general_mdl->get_query_by_where(['id' => $campaign_id]);
        $campaign = $query->row_array();

        $where['campaign_id'] = $campaign_id;
        $this->general_mdl->setTable('campaign_users_log as cul');
        $this->db->select('uc.name as uc_name,uc.phone  as uc_phone, cul.*');
        $this->db->join('user_profiles as uc', 'cul.member_id = uc.user_id', 'left');
        $query = $this->general_mdl->get_query_by_where($where);
        $participants = $query->result_array();

        $dirpath = sprintf("%s/%s", $this->_upload_path, "campaign"); //保存文件的目录路径
        create_folders($dirpath); //创建目录

        $title = $campaign_title."活动 报名名单";
        $obj_phpexcel = $this->phpexcel;
        $obj_phpexcel->disconnectWorksheets(); //清除工作薄内容
        $obj_phpexcel->createSheet(); //创建一个新的工作薄
        $obj_phpexcel->setActiveSheetIndex(0);//設置打開excel時顯示哪個工作表

        $temp_active_sheet = $obj_phpexcel->getActiveSheet();
        $temp_active_sheet->getDefaultColumnDimension()->setAutoSize(true);//設置單元格寬度
        $temp_active_sheet->setTitle('报名名单');//設置當前工作表的名稱
        $temp_active_sheet->getStyle("A1:P500")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); //垂直居中
        $temp_active_sheet->getStyle("A1:P500")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); //水平居中
        $temp_active_sheet->getStyle("B2:C5")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); //水平居左

        $this->merge_and_set_cell_value($temp_active_sheet, "A1", "C1", '身边公益网活动汇总表');
        $temp_active_sheet->setCellValue("A2", '活动主题');
        $this->merge_and_set_cell_value($temp_active_sheet, "B2", "C2", $campaign['title']);
        $temp_active_sheet->setCellValue("A3", '活动地点');
        $this->merge_and_set_cell_value($temp_active_sheet, "B3", "C3", $campaign['address']);
        $temp_active_sheet->setCellValue("A4", '活动时间');
        $this->merge_and_set_cell_value($temp_active_sheet, "B4", "C4", $campaign['startdate']);
        $temp_active_sheet->setCellValue("A5", '服务时长');
        $this->merge_and_set_cell_value($temp_active_sheet, "B5", "C5", $campaign['reward_time'].'小时');
        $this->merge_and_set_cell_value($temp_active_sheet, "A6", "C6", '报名名单');
        $temp_active_sheet->getColumnDimension('A')->setWidth(15);
        $temp_active_sheet->getColumnDimension('B')->setWidth(25);
        $temp_active_sheet->getColumnDimension('C')->setWidth(45);
        $temp_active_sheet->getStyle("A1")->getFont()->setSize(20);
        $temp_active_sheet->getStyle("A1:A6")->getFont()->setBold(true);

        $variable = array('姓名','电话', '备注');
        $start_col = "A";
        $start_row = 7;
        foreach ($variable as $key => $value)
        {
            $start_cell = $start_col.$start_row;
            $temp_active_sheet->setCellValue($start_cell, $value);
            $temp_active_sheet->getStyle($start_cell)->getFont()->setBold(true);
            $start_col++;
        }
        $start_row++;
        foreach ($participants as $key => $items)
        {
            $start_col = "A";

            $start_cell = $start_col.$start_row;
            $temp_active_sheet->setCellValue($start_cell, $items['name'] ? $items['name'] : $items['uc_name']);
            $start_col++;

            $start_cell = $start_col.$start_row;
            $temp_active_sheet->setCellValueExplicit(
                $start_cell,
                $items['phone'] ? $items['phone'] : $items['uc_phone']
            );

            $start_row++;
        }


        $excel_name = "campaign_".$campaign_id.".xls"; //保存的文件名

        $obj_writer = $this->IoFactory->createWriter($this->phpexcel, 'Excel5');
        $excelpath = sprintf("%s/%s", $dirpath ,$excel_name); //保存excel的路径
        $obj_writer->save($excelpath);

        $link = base_url().'excel/campaign/'.$excel_name;

        echo sprintf("<a class='f14' target='_blank' href='%s'>%s_下载</a>", $link, $title);
    }

    public function donation()
    {
        $campaign_id = $this->input->post('campaign_id');
        $campaign_title = $this->input->post('campaign_title');

        $this->general_mdl->setTable('campaign');
        $query = $this->general_mdl->get_query_by_where(['id' => $campaign_id]);
        $campaign = $query->row_array();

        $where['campaign_id'] = $campaign_id;
        $this->general_mdl->setTable('donation_book');
        $query = $this->general_mdl->get_query_by_where($where);
        $participants = $query->result_array();

        $dirpath = sprintf("%s/%s", $this->_upload_path, "campaign"); //保存文件的目录路径
        create_folders($dirpath); //创建目录

        $title = $campaign_title."活动 捐赠名单";
        $obj_phpexcel = $this->phpexcel;
        $obj_phpexcel->disconnectWorksheets(); //清除工作薄内容
        $obj_phpexcel->createSheet(); //创建一个新的工作薄
        $obj_phpexcel->setActiveSheetIndex(0);//設置打開excel時顯示哪個工作表

        $temp_active_sheet = $obj_phpexcel->getActiveSheet();
        $temp_active_sheet->getDefaultColumnDimension()->setAutoSize(true);//設置單元格寬度
        $temp_active_sheet->setTitle('捐赠名单');//設置當前工作表的名稱
        $temp_active_sheet->getStyle("A1:P500")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); //垂直居中
        $temp_active_sheet->getStyle("A1:P500")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); //水平居中
        $temp_active_sheet->getStyle("B2:F4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); //水平居左

        $this->merge_and_set_cell_value($temp_active_sheet, "A1", "F1", '身边公益网捐赠名单');
        $temp_active_sheet->setCellValue("A2", '活动主题');
        $this->merge_and_set_cell_value($temp_active_sheet, "B2", "F2", $campaign['title']);
        $temp_active_sheet->setCellValue("A3", '活动地点');
        $this->merge_and_set_cell_value($temp_active_sheet, "B3", "F3", $campaign['address']);
        $temp_active_sheet->setCellValue("A4", '活动时间');
        $this->merge_and_set_cell_value($temp_active_sheet, "B4", "F4", $campaign['startdate']);
        $this->merge_and_set_cell_value($temp_active_sheet, "A5", "F5", '捐赠名单');
        $temp_active_sheet->getColumnDimension('A')->setWidth(15);
        $temp_active_sheet->getColumnDimension('B')->setWidth(20);
        $temp_active_sheet->getColumnDimension('C')->setWidth(26);
        $temp_active_sheet->getColumnDimension('D')->setWidth(15);
        $temp_active_sheet->getColumnDimension('E')->setWidth(25);
        $temp_active_sheet->getColumnDimension('F')->setWidth(30);
        $temp_active_sheet->getStyle("A1")->getFont()->setSize(20);
        $temp_active_sheet->getStyle("A1:A6")->getFont()->setBold(true);

        $variable = array('name' => '姓名', 'phone' => '电话', 'address' => '地址', 'book_time' => '预约时间', 'goods' => '捐赠物', 'remark' => '备注');
        $start_col = "A";
        $start_row = 6;
        foreach ($variable as $key => $value)
        {
            $start_cell = $start_col.$start_row;
            $temp_active_sheet->getStyle($start_cell)->getFont()->setBold(true);
            $temp_active_sheet->setCellValue($start_cell, $value);
            $start_col++;
        }
        $start_row++;
        foreach ($participants as $key => $items)
        {
            $start_col = "A";

            foreach ($variable as $field => $value) {
                $start_cell = $start_col.$start_row;
                if(isset($items[$field])){
                    $temp_active_sheet->setCellValueExplicit(
                        $start_cell,
                        $items[$field]
                    );
                }
                $start_col++;
            }

            $start_row++;
        }


        $excel_name = "donation_".$campaign_id.".xls"; //保存的文件名

        $obj_writer = $this->IoFactory->createWriter($this->phpexcel, 'Excel5');
        $excelpath = sprintf("%s/%s", $dirpath ,$excel_name); //保存excel的路径
        $obj_writer->save($excelpath);

        $link = base_url().'excel/campaign/'.$excel_name;

        echo sprintf("<a class='f14' target='_blank' href='%s'>%s_下载</a>", $link, $title);
    }
}

/* End of file report.php */
/* Location: ./application/controllers/report.php */

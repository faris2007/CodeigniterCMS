<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of slider
 *
 * @author faris2007
 */
class slider extends CI_Controller {
    
    
    public function __construct() {
        parent::__construct();
        $this->load->model("sliders");
    }
    
    public function index(){
        if($this->core->checkPermissions('slider','all','all')){
            $this->show();
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }
    
    public function show(){
        $segments = $this->uri->segment_array();
        $filter = isset($segments[3])? $segments[3]:'all';
        if($this->core->checkPermissions('slider','show','all')){
            if($_POST){
                foreach ($_POST as $key => $value)
                {
                    if(@ereg('slider_', $key)){
                        $keyArr = explode('_', $key);
                        $store = array(
                            'sort_id'   => $this->input->post($key,true)
                        );
                        $this->sliders->updateSlider($keyArr[1],$store);
                    }
                }
            }
            switch ($filter){
                case 'enable':
                    $this->db->where('isHidden',0);
                    break;
                
                case 'disable':
                    $this->db->where('isHidden',1);
                    break;
                
                case 'delete':
                    $this->db->where('isDelete',1);
                    break;
                
                case 'undelete':
                    $this->db->where('isDelete',0);
                    break;
                
                case 'all':
                default :
                    break;
            }
            $data['FILTER'] = $filter;
            $data['SLIDERS'] = $this->sliders->getSlider("all");
            $data['CONTENT'] = "slider";
            $data['STEP'] = 'show';
            $data['NAV'] = array(
                base_url()          => $this->lang->line('global_mainpage'),
                base_url().'admin'  => $this->lang->line('global_cpanel'),
                base_url().'slider' => $this->lang->line('slider_manage'),
            );
            $data['TITLE'] = $this->lang->line('slider_manage');
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
        
    }
    
    public function add(){
        if($this->core->checkPermissions('slider','add','all')){
            if($_POST){
                    $config['upload_path'] = './uploads/';
                    $config['allowed_types'] = 'gif|jpg|png';
                    $config['file_name'] = md5(rand(0, 995499));
                    
                    $this->load->library('upload', $config);

                    if ($this->upload->do_upload())
                    {
                        $data = $this->upload->data();
                        $picUrl = base_url()."uploads/".$data['file_name'];
                        $store = array(
                            'slider_name'   => $this->input->post('name',true),
                            'url'           => $this->input->post('url',true),
                            'sort_id'       => $this->input->post('sort',true),
                            'picture'       => $picUrl,
                            'desc'          => $this->input->post('desc',true),
                            'isHidden'      => 1,
                            'isDelete'      => 0
                        );
                        if($this->sliders->addNewSlider($store)){
                            $data['CONTENT'] = 'msg';
                            $data['TITLE'] = $this->lang->line('slider_manage');
                            $url = base_url().'slider';
                            $data['MSG'] = $this->lang->line('global_save_data_correctly').  anchor($url, $this->lang->line('slider_back_to_manage'));
                        }else{
                            $data['CONTENT'] = "slider";                                     
                            $data['STEP'] = 'add';
                            $data['ERROR'] = true;
                            $data['ERR_MSG'] = $this->lang->line('slider_problem_in_data');
                        }
                    }else{
                        $data['CONTENT'] = "slider";
                        $data['STEP'] = 'add';
                        $data['ERROR'] = true;
                        $data['ERR_MSG'] = $this->lang->line('slider_upload_problem');
                    }
            }else{
                $data['CONTENT'] = "slider";
                $data['STEP'] = 'add';
                $data['ERROR'] = false;
                $data['ERR_MSG'] = '';
            }
            $data['NAV'] = array(
                base_url()          =>$this->lang->line('global_mainpage'),
                base_url().'admin'  => $this->lang->line('global_cpanel'),
                base_url().'slider' => $this->lang->line('slider_manage'),//
            );
            $data['TITLE'] = $this->lang->line('slider_add_new_item');
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }
    
    public function edit(){
        $segments = $this->uri->segment_array();
        $sliderId = isset($segments[3])? $segments[3]:NULL;
        if($this->core->checkPermissions('slider','edit',$sliderId)){
            $sliderInfo = $this->sliders->getSlider($sliderId);
            if(is_bool($sliderInfo))
                redirect(STD_CMS_ERROR_PAGE);
            if($_POST){
                if($this->input->post("update",true) == 1){
                    
                    $config['upload_path'] = './uploads/';
                    $config['allowed_types'] = 'gif|jpg|png';
                    $config['file_name'] = md5(rand(0, 995499));
                    
                    $this->load->library('upload', $config);

                    if ($this->upload->do_upload())
                    {
                        $data = $this->upload->data();
                        $picUrl = base_url()."uploads/".$data['file_name'];
                    }
                    $store['picture'] = $picUrl;
                }
                $store = array(
                    'slider_name'   => $this->input->post('name',true),
                    'url'           => $this->input->post('url',true),
                    'sort_id'       => $this->input->post('sort',true),
                    'desc'          => $this->input->post('desc',true)
                );
                if($this->sliders->updateSlider($sliderId,$store)){
                    $data['CONTENT'] = 'msg';
                    $data['TITLE'] = $this->lang->line('slider_manage');
                    $url = base_url().'slider';
                    $data['MSG'] = $this->lang->line('global_save_data_correctly').  anchor($url, $this->lang->line('slider_back_to_manage'));
                }else{
                    $data['SLIDER_NAME'] = $sliderInfo[0]->slider_name;
                    $data['SLIDER_URL'] = $sliderInfo[0]->url;
                    $data['SLIDER_SORT'] = $sliderInfo[0]->sort_id;
                    $data['SLIDER_PICTURE'] = $sliderInfo[0]->picture;
                    $data['SLIDER_DESC'] = $sliderInfo[0]->desc;
                    $data['CONTENT'] = "slider";                                     
                    $data['STEP'] = 'edit';
                    $data['ERROR'] = true;
                    $data['ERR_MSG'] = $this->lang->line('slider_problem_in_data');
                }           
            }else{
                $data['SLIDER_NAME'] = $sliderInfo[0]->slider_name;
                $data['SLIDER_URL'] = $sliderInfo[0]->url;
                $data['SLIDER_SORT'] = $sliderInfo[0]->sort_id;
                $data['SLIDER_PICTURE'] = $sliderInfo[0]->picture;
                $data['SLIDER_DESC'] = $sliderInfo[0]->desc;
                $data['CONTENT'] = "slider";
                $data['STEP'] = 'edit';
                $data['ERROR'] = false;
                $data['ERR_MSG'] = '';
            }
            $data['NAV'] = array(
                base_url()          => $this->lang->line('global_mainpage'),
                base_url().'admin'  =>$this->lang->line('global_cpanel'),
                base_url().'slider' => $this->lang->line('slider_manage'),
            );
            $data['TITLE'] = $this->lang->line('slider_edit_pics');
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }


    public function action(){
        if($this->input->is_ajax_request()){
            $segments = $this->uri->segment_array();
            $type = isset($segments[3])? $segments[3]:NULL;
            $sliderId = isset($segments[4])? $segments[4]:0;
            $names = array(
                'enable'    => $this->lang->line('global_enable'),
                'disable'   => $this->lang->line('global_disable'),
                'delete'    => $this->lang->line('global_delete'),
                'restore'   => $this->lang->line('global_restore')
            );
            if(is_null($type) || $sliderId == 0)
                die($this->lang->line('slider_url_wrong'));
            
            $slider = $this->sliders->getSlider($sliderId);
            if(is_bool($slider))
                die($this->lang->line('slider_pic_unexsist'));
            
            if($type == 'delete' || $type == 'restore')
                if($this->core->checkPermissions('slider','delete','all')){
                    $store = array(
                        'isDelete' => ($type == 'delete')? 1:0
                    );
                }else
                    die($this->lang->line('slider_no_premission_delete'));
            else if($type == 'enable' || $type == 'disable')
                if($this->core->checkPermissions('slider','active','all')){
                    $store = array(
                        'isHidden' => ($type == 'enable')? 0 : 1
                    );
                }else
                    die($this->lang->line('slider_no_premission_active'));
            else
                die($this->lang->line('slider_worng_pic'));
            if($this->sliders->updateSlider($sliderId,$store))
                die($this->lang->line('slider_sucusse_process').$names[$type]);
            else
                die($this->lang->line('slider_fail_process').$names[$type]);
            
        }else
            redirect(STD_CMS_ERROR_PAGE);
    }
}

?>

<?php

/**
 * this class for add,edit and remove from lang table
 * 
 * @author Faris Al-Otaibi
 */
class langc extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('langs');
    }
    
    public function index(){
        if($this->core->checkPermissions('langc','all','all')){
            $this->show();
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }
    
    public function show(){
        if($this->core->checkPermissions('langc','show','all')){
            $data['LANGS'] = $this->langs->getLangs();
            $data['CONTENT'] = "lang";
            $data['STEP'] = 'show';
            $data['NAV'] = array(
                base_url()          => $this->lang->line('global_mainpage'),
                base_url().'admin'  => $this->lang->line('global_cpanel'),
                base_url().'langc'   => $this->lang->line('global_langc')
            );
            $data['TITLE'] = " -- " .$this->lang->line('global_langc');
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
        
    }
    
    public function add(){
        if($this->core->checkPermissions('langc','add','all')){
            if($_POST){
                    $store = array(
                        'name'      => $this->input->post('name',true),
                        'ext'       => $this->input->post('ext',true),
                        'folder'    => $this->input->post('folder',true)
                    );
                    if($this->langs->addNewLang($store)){
                        $data['CONTENT'] = 'msg';
                        $data['TITLE'] = " -- " .$this->lang->line('global_langc');
                        $url = base_url().'langc/show';
                        $data['MSG'] = $this->lang->line('global_msg_success') . br(1) .  anchor($url, $this->lang->line('lang_return_back'));
                    }else{
                        $data['CONTENT'] = "lang";
                        $data['STEP'] = 'add';
                        $folder = get_dir_file_info('./app/language/', $top_level_only = TRUE);
                        $data['FOLDER'] = array_keys($folder);
                        $data['ERROR'] = true;
                        $data['ERR_MSG'] = $this->lang->line("lang_error_data_ivalid");
                    }
            }else{
                $data['CONTENT'] = "lang";
                $data['STEP'] = 'add';
                $folder = get_dir_file_info('./app/language/', $top_level_only = TRUE);
                $data['FOLDER'] = array_keys($folder);
                $data['ERROR'] = false;
                $data['ERR_MSG'] = '';
            }
            $data['NAV'] = array(
                base_url()          => $this->lang->line('global_mainpage'),
                base_url().'admin'  => $this->lang->line('global_cpanel'),
                base_url().'langc'   => $this->lang->line('global_langc')
            );
            $data['TITLE'] = " -- " .$this->lang->line('global_langc');
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }
    
    public function edit(){
        $segments = $this->uri->segment_array();
        $langId = isset($segments[3])? $segments[3]:NULL;
        if($this->core->checkPermissions('langc','edit',$langId)){
            $langInfo = $this->langs->getLang($langId);
            if(is_bool($langInfo))
                redirect(STD_CMS_ERROR_PAGE);
            if($_POST){
                    $store = array(
                        'name'      => $this->input->post('name',true),
                        'ext'       => $this->input->post('ext',true),
                        'folder'    => $this->input->post('folder',true)
                    );
                    if($this->langs->updateLang($langId,$store)){
                        $data['CONTENT'] = 'msg';
                        $data['TITLE'] = " -- " .$this->lang->line('global_langc');
                        $url = base_url().'langc/show/';
                        $data['MSG'] = $this->lang->line('global_msg_success') . br(1) .  anchor($url, $this->lang->line('lang_return_back'));
                    }else{
                        $data['LANGNAME'] = $langInfo->name;
                        $data['LANGEXT'] = $langInfo->ext;
                        $data['LANGFOLDER'] = $langInfo->folder;
                        $folder = get_dir_file_info('./app/language/', $top_level_only = TRUE);
                        $data['FOLDER'] = array_keys($folder);
                        $data['CONTENT'] = "lang";
                        $data['STEP'] = 'edit';
                        $data['ERROR'] = true;
                        $data['ERR_MSG'] = $this->lang->line("lang_error_data_ivalid");
                    }
            }else{
                $data['LANGNAME'] = $langInfo->name;
                $data['LANGEXT'] = $langInfo->ext;
                $data['LANGFOLDER'] = $langInfo->folder;
                $folder = get_dir_file_info('./app/language/', $top_level_only = TRUE);
                $data['FOLDER'] = array_keys($folder);
                $data['CONTENT'] = "lang";
                $data['STEP'] = 'edit';
                $data['ERROR'] = false;
                $data['ERR_MSG'] = '';
            }
            $data['NAV'] = array(
                base_url()          => $this->lang->line('global_mainpage'),
                base_url().'admin'  => $this->lang->line('global_cpanel'),
                base_url().'langc'   => $this->lang->line('global_langc')
            );
            $data['TITLE'] = " -- " .$this->lang->line('global_langc');
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }


    public function action(){
        if($this->input->is_ajax_request()){
            $segments = $this->uri->segment_array();
            $type = isset($segments[3])? $segments[3]:NULL;
            $langId = isset($segments[4])? $segments[4]:0;
            $names = array(
                'delete'    => $this->lang->line('lang_action_delete')
            );
            if(is_null($type) || $langId == 0)
                die($this->lang->line('lang_action_url_error'));
            
            $lang = $this->langs->getLang($langId);
            if(is_bool($lang))
                die($this->lang->line('lang_action_not_found_langauge'));
            
            if($type == 'delete'){
                if($this->core->checkPermissions('langc','delete','all')){
                    if($this->langs->deleteLang($langId))
                        die($this->lang->line('lang_action_success_msg').$names[$type]);
                    else
                        die($this->lang->line('lang_action_not_work_error').$names[$type]);
                }else
                    die($this->lang->line('lang_action_permission_error'));
            }else
                die($this->lang->line('lang_action_url_error'));
            
        }else
            redirect(STD_CMS_ERROR_PAGE);
    }
}

?>

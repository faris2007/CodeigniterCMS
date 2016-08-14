<?php

/**
 * this class for add,edit and remove from page table
 * 
 * @author Faris Al-Otaibi
 */
class page extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model('pages');
        $this->load->model('langs');
    }
    
    public function index(){
        if($this->core->checkPermissions('page','show','all')){
            $this->show();
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }


    public function view(){
        $segments = $this->uri->segment_array();
        $pageId = isset($segments[3])? $segments[3]:NULL;
        if(is_null($pageId))
            redirect(STD_CMS_ERROR_PAGE);
        
        if(!$this->core->checkPermissions('page','show',$pageId)){
            $this->db->where('isHidden',0);
            $this->db->where('isDelete',0);
        }
        $pageInfo = $this->pages->getPage($pageId);
        if(is_bool($pageInfo))
            redirect(STD_CMS_ERROR_PAGE);
        
        $this->db->where('ext',$this->session->userdata('lang_ext'));
        $lang = $this->langs->getLangs();
        if($pageInfo->language_id != $lang[0]->id)
            redirect(STD_CMS_ERROR_PAGE);
        $data['NAV'] = $this->core->getPath($pageId);
        $this->db->where('isHidden',0);
        $this->db->where('isDelete',0);
        $this->db->where('language_id',$lang[0]->id);
        $data['RELATEDPAGES'] = $this->pages->getPages($pageId);
        $data['CONTENTPAGE'] = $pageInfo->content;
        $data['TITLEPAGE'] = $pageInfo->title;
        $data['PAGEID'] = $pageId;
        $data['CONTENT'] = "page";
        $data['STEP'] = 'view';
        $data['TITLE'] = " -- " .$pageInfo->title;
        $this->core->load_template($data);
        
    }
    
    public function show(){
        $segments = $this->uri->segment_array();
        $parent_id = isset($segments[3])? $segments[3]:NULL;
        $filter = isset($segments[4])? $segments[4]:'all';
        $parent_id = ($parent_id == 'all')? null:$parent_id;
        $value = (is_null($parent_id)) ? 'all' : $parent_id;
        if($this->core->checkPermissions('page','show',$value)){
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
                
                case 'lang':
                    $ext = (isset($segments[5]))? $segments[5] :null ;
                    if(is_null($ext) || strlen($ext) != 2)
                        redirect(STD_CMS_ERROR_PAGE);
                    $this->db->where('ext',$ext);
                    $lang = $this->langs->getLangs();
                    $this->db->where('language_id',$lang[0]->id);
                    break;
                             
                case 'all':
                default :
                    break;
            }
            $data['FILTER'] = ($filter == 'lang')? $segments[5] : $filter;
            $parentId = (is_null($parent_id)) ? 'all' : $parent_id;
            $pages = $this->pages->getPages($parentId);
            if($parentId == 'all'){
                if(is_array($pages)){
                    for($i=0;$i<count($pages);$i++){
                        if(!$this->core->checkPermissions('page','show',$pages[$i]->id))
                            unset ($pages[$i]);
                    }
                }
            }
            $data['PAGES'] = $pages;
            $data['LANGS'] = $this->langs->getLangs();
            if(!is_null($parent_id))
                $data['NAV'] = $this->core->getPath($parent_id,true);
            else
                $data['NAV'] = array(
                    base_url()          => $this->lang->line('global_mainpage'),
                    base_url().'admin'  => $this->lang->line('global_cpanel'),
                    base_url().'page'   => $this->lang->line('global_page'),
                );    
            $data['PARENTPAGE'] = $parent_id;
            $data['CONTENT'] = "page";
            $data['STEP'] = 'show';
            $data['TITLE'] = " -- ".$this->lang->line('global_page');
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
        
    }
    
    public function add(){
        $segments = $this->uri->segment_array();
        $parent_id = isset($segments[3])? $segments[3]:NULL;
        $value = (is_null($parent_id)) ? 'all' : $parent_id;
        if($this->core->checkPermissions('page','add',$value)){
            if($_POST){
                    $store = array(
                        'title'         => $this->input->post('title',true),
                        'content'       => $this->input->post('content'),
                        'keyword'       => $this->input->post('keyword',true),
                        'desc'          => $this->input->post('desc',true),
                        'parent_id'     => $parent_id,
                        'publish_start' => date('Y-m-d H:i'),
                        'language_id'   => $this->input->post('lang',true),
                        'isHidden'      => 1,
                        'isDelete'      => 0
                    );
                    if($this->pages->addNewPage($store)){
                        $data['CONTENT'] = 'msg';
                        $data['TITLE'] = " -- " . $this->lang->line('global_page');
                        $url = base_url().'page/show';
                        $url .= (!is_null($parent_id))?'/'.$parent_id:'';
                        $data['MSG'] = $this->lang->line('global_msg_success'). br(1).  anchor($url, $this->lang->line('page_return_back'));
                    }else{
                        $data['CONTENT'] = "page";
                        $data['STEP'] = 'add';
                        $data['LANGS'] = $this->langs->getLangs();
                        $data['ERROR'] = true;
                        $data['ERR_MSG'] = $this->lang->line('page_error_msg');
                    }
            }else{
                $data['CONTENT'] = "page";
                $data['STEP'] = 'add';
                $data['LANGS'] = $this->langs->getLangs();
                $data['ERROR'] = false;
                $data['ERR_MSG'] = '';
            }
            $data['NAV'] = array(
                base_url()          => $this->lang->line('global_mainpage'),
                base_url().'admin'  => $this->lang->line('global_cpanel'),
                base_url().'page'   => $this->lang->line('global_page'),
            ); 
            $data['TITLE'] = $this->lang->line('page_add_new_page');
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }
    
    public function edit(){
        $segments = $this->uri->segment_array();
        $pageId = isset($segments[3])? $segments[3]:NULL;
        if($this->core->checkPermissions('page','edit',$pageId)){
            $pageInfo = $this->pages->getPage($pageId);
            if(is_bool($pageInfo))
                redirect(STD_CMS_ERROR_PAGE);
            
            if($_POST){
                    $store = array(
                        'title'         => $this->input->post('title',true),
                        'content'       => $this->input->post('content'),
                        'keyword'       => $this->input->post('keyword',true),
                        'language_id'   => $this->input->post('lang',true),
                        'desc'          => $this->input->post('desc',true)
                    );
                    if($this->pages->updatePage($pageId,$store)){
                        $data['CONTENT'] = 'msg';
                        $data['TITLE'] = " -- ". $this->lang->line('global_page');
                        $url = base_url().'page/show';
                        $url .= (!is_null($pageInfo->parent_id))?'/'.$pageInfo->parent_id:'';
                        $data['MSG'] = $this->lang->line('global_msg_success'). br(1).  anchor($url, $this->lang->line('page_return_back'));
                    }else{
                        $data['PAGETITLE'] = $pageInfo->title;
                        $data['PAGECONTENT'] = $pageInfo->content;
                        $data['PAGEKEY'] = $pageInfo->keyword;
                        $data['PAGEDESC'] = $pageInfo->desc;
                        $data['PAGELANG'] = $pageInfo->language_id;
                        $data['CONTENT'] = "page";
                        $data['STEP'] = 'edit';
                        $data['LANGS'] = $this->langs->getLangs();
                        $data['ERROR'] = true;
                        $data['ERR_MSG'] = $this->lang->line('page_error_msg');
                    }
            }else{
                $data['PAGETITLE'] = $pageInfo->title;
                $data['PAGECONTENT'] = $pageInfo->content;
                $data['PAGEKEY'] = $pageInfo->keyword;
                $data['PAGEDESC'] = $pageInfo->desc;
                $data['PAGELANG'] = $pageInfo->language_id;
                $data['CONTENT'] = "page";
                $data['STEP'] = 'edit';
                $data['LANGS'] = $this->langs->getLangs();
                $data['ERROR'] = false;
                $data['ERR_MSG'] = '';
            }
            $data['NAV'] = array(
                base_url()          => $this->lang->line('global_mainpage'),
                base_url().'admin'  => $this->lang->line('global_cpanel'),
                base_url().'page'   => $this->lang->line('global_page'),
            ); 
            $data['TITLE'] = $this->lang->line('page_edit_page');
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }
    
    
    public function action(){
        if($this->input->is_ajax_request()){
            $segments = $this->uri->segment_array();
            $type = isset($segments[3])? $segments[3]:NULL;
            $pageId = isset($segments[4])? $segments[4]:0;
            $names = array(
                'enable'    => $this->lang->line('global_enable'),
                'disable'   => $this->lang->line('global_disable'),
                'delete'    => $this->lang->line('global_delete'),
                'restore'   => $this->lang->line('global_restore')
            );
            if(is_null($type) || $pageId == 0)
                die($this->lang->line('page_action_error_url'));
            
            $page = $this->pages->getPage($pageId);
            if(is_bool($page))
                die($this->lang->line('page_action_error_page_number'));
            
            if($type == 'delete' || $type == 'restore')
                if($this->core->checkPermissions('page','delete','all')){
                    $store = array(
                        'isDelete' => ($type == 'delete')? 1:0
                    );
                }else
                    die($this->lang->line('page_action_error_permission').$names[$type]);
            else if($type == 'enable' || $type == 'disable')
                if($this->core->checkPermissions('page','active','all')){
                    $store = array(
                        'isHidden' => ($type == 'enable')? 0 : 1
                    );
                }else
                    die($this->lang->line('page_action_error_permission').$names[$type]);
            else
                die($this->lang->line('page_action_error_url'));
            if($this->pages->updatePage($pageId,$store))
                die($this->lang->line('page_action_success').$names[$type]);
            else
                die($this->lang->line('page_action_error_not_work').$names[$type]);
            
        }else
            redirect(STD_CMS_ERROR_PAGE);
    }
    
    public function error_page(){
        
        $data['CONTENT'] = "page";
        $data['STEP'] = 'error';
        $data['TITLE'] = $this->lang->line('page_error_page');
        $this->core->load_template($data);
    }
}

?>

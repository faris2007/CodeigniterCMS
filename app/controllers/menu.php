<?php

/**
 * this class for add,edit and remove from menu table
 * 
 * @author Faris Al-Otaibi
 */
class menu extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('menus');
        $this->load->model('pages');
        $this->load->model('langs');
    }
    
    public function index(){
        if($this->core->checkPermissions('menu','all','all')){
            $this->show();
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }
    
    public function show(){
        $segments = $this->uri->segment_array();
        $parent_id = isset($segments[3])? $segments[3]:NULL;
        $filter = isset($segments[4])? $segments[4]:'all';
        $parent_id = ($parent_id == 'all')? null:$parent_id;
        $value = (is_null($parent_id)) ? 'all' : $parent_id;
        if($this->core->checkPermissions('menu','show',$value)){
            if($_POST){
                foreach ($_POST as $key => $value)
                {
                    if(@ereg('menu_', $key)){
                        $keyArr = explode('_', $key);
                        $store = array(
                            'sort_id'   => $this->input->post($key,true)
                        );
                        $this->menus->updateMenu($keyArr[1],$store);
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
            $data['MENUS'] = $this->menus->getMenus($parent_id);
            $data['LANGS'] = $this->langs->getLangs();
            $data['TYPEMENU'] = ($parent_id == NULL) ? $this->lang->line('menu_main'): $this->lang->line('menu_sub');
            $data['PARENTMENU'] = $parent_id;
            $data['CONTENT'] = "menu";
            $data['STEP'] = 'show';
            $data['NAV'] = array(
                base_url()          => $this->lang->line('global_mainpage'),
                base_url().'admin'  => $this->lang->line('global_cpanel'),
                base_url().'menu'   => $this->lang->line('global_menu'),
            );
            if(!is_null($parent_id))
            {
                $menuArray = $this->menus->getParentThisMenu($parent_id);
                $menus = explode(',', $menuArray);
                foreach ($menus as $val){
                    $menuOne = unserialize($val);
                    $data['NAV'][base_url().'menu/show/'.$menuOne->id] = $menuOne->title;
                }
            }
            $data['TITLE'] = $this->lang->line('global_menu');
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
        
    }
    
    public function add(){
        $segments = $this->uri->segment_array();
        $parent_id = isset($segments[3])? $segments[3]:NULL;
        $value = (is_null($parent_id)) ? 'all' : $parent_id;
        if($this->core->checkPermissions('menu','add',$value)){
            if($_POST){
                    $store = array(
                        'title'         => $this->input->post('title',true),
                        'url'           => ($this->input->post('type_url',true) == 'page')? base_url().'page/view/'.$this->input->post('page_num',true) :$this->input->post('url',true),
                        'sort_id'       => $this->input->post('sort',true),
                        'parent_id'     => $parent_id,
                        'language_id'   => $this->input->post('lang',true),
                        'isHidden'      => 1,
                        'isDelete'      => 0
                    );
                    if($this->menus->addNewMenu($store)){
                        $data['CONTENT'] = 'msg';
                        $data['TITLE'] = $this->lang->line('global_menu');
                        $url = base_url().'menu';
                        $url .= (!is_null($parent_id))?'/show/'.$parent_id:'';
                        $data['MSG'] = $this->lang->line('global_save_data_correctly').  anchor($url, $this->lang->line('menu_back_to_menu'));
                    }else{
                        $data['CONTENT'] = "menu";
                        $where = array(
                            'isDelete'  => 0,
                            'isHidden'  => 0
                        );
                        $this->db->where($where);
                        $data['PAGES'] = $this->pages->getPages('all');
                        $data['STEP'] = 'add';
                        $data['LANGS'] = $this->langs->getLangs();
                        $data['ERROR'] = true;
                        $data['ERR_MSG'] = $this->lang->line('menu_cannot_add_menu');
                    }
            }else{
                $data['CONTENT'] = "menu";
                $where = array(
                    'isDelete'  => 0,
                    'isHidden'  => 0
                );
                $this->db->where($where);
                $data['PAGES'] = $this->pages->getPages('all');
                $data['STEP'] = 'add';
                $data['LANGS'] = $this->langs->getLangs();
                $data['ERROR'] = false;
                $data['ERR_MSG'] = '';
            }
            $data['NAV'] = array(
                base_url()          => $this->lang->line('global_mainpage'),
                base_url().'admin'  => $this->lang->line('global_cpanel'),
                base_url().'menu'   => $this->lang->line('global_menu'),
            );
            if(!is_null($parent_id))
            {
                $menuArray = $this->menus->getParentThisMenu($parent_id);
                $menus = explode(',', $menuArray);
                foreach ($menus as $val){
                    $menuOne = unserialize($val);
                    $data['NAV'][base_url().'menu/show/'.$menuOne->id] = $menuOne->title;
                }
            }
            
            $data['TITLE'] = $this->lang->line('menu_add_new_menu');
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }
    
    public function edit(){
        $segments = $this->uri->segment_array();
        $menuId = isset($segments[3])? $segments[3]:NULL;
        if($this->core->checkPermissions('menu','edit',$menuId)){
            $menuInfo = $this->menus->getMenu($menuId);
            if(is_bool($menuInfo))
                redirect(STD_CMS_ERROR_PAGE);
            if($_POST){
                    $store = array(
                        'title'     => $this->input->post('title',true),
                        'url'       => ($this->input->post('type_url',true) == 'page')? base_url().'page/view/'.$this->input->post('page_num',true) :$this->input->post('url',true),
                        'sort_id'   => $this->input->post('sort',true),
                        'language_id'   => $this->input->post('lang',true)
                    );
                    if($this->menus->updateMenu($menuId,$store)){
                        $data['CONTENT'] = 'msg';
                        $data['TITLE'] = $this->lang->line('global_menu');
                        $url = base_url().'menu';
                        $url .= (!is_null($menuInfo->parent_id))?'/show/'.$menuInfo->parent_id:'';
                        $data['MSG'] = $this->lang->line('global_save_data_correctly').  anchor($url, $this->lang->line('menu_back_to_menu'));
                    }else{
                        $data['MENUTITLE'] = $menuInfo->title;
                        $data['MENUURL'] = $menuInfo->url;
                        $data['MENUSORT'] = $menuInfo->sort_id;
                        $data['MENULANG'] = $menuInfo->language_id;
                        $data['CONTENT'] = "menu";
                        $where = array(
                            'isDelete'  => 0,
                            'isHidden'  => 0
                        );
                        $this->db->where($where);
                        $data['PAGES'] = $this->pages->getPages('all');
                        $data['LANGS'] = $this->langs->getLangs();
                        $data['STEP'] = 'edit';
                        $data['ERROR'] = true;
                        $data['ERR_MSG'] = $this->lang->line('menu_cannot_update');
                    }
            }else{
                $data['MENUTITLE'] = $menuInfo->title;
                $data['MENUURL'] = $menuInfo->url;
                $data['MENUSORT'] = $menuInfo->sort_id;
                $data['MENULANG'] = $menuInfo->language_id;
                $data['CONTENT'] = "menu";
                $where = array(
                    'isDelete'  => 0,
                    'isHidden'  => 0
                );
                $this->db->where($where);
                $data['PAGES'] = $this->pages->getPages('all');
                $data['LANGS'] = $this->langs->getLangs();
                $data['STEP'] = 'edit';
                $data['ERROR'] = false;
                $data['ERR_MSG'] = '';
            }
            $data['NAV'] = array(
                base_url()          => $this->lang->line('global_mainpage'),
                base_url().'admin'  => $this->lang->line('global_cpanel'),
                base_url().'menu'   => $this->lang->line('global_menu'),
            );
            $menuArray = $this->menus->getParentThisMenu($menuId);
            if($menuArray){
                $menus = explode(',', $menuArray);
                foreach ($menus as $val){
                    $menuOne = unserialize($val);
                    $data['NAV'][base_url().'menu/show/'.$menuOne->id] = $menuOne->title;
                }
            }
            $data['TITLE'] = $this->lang->line('menu_edit_menu');
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }


    public function action(){
        if($this->input->is_ajax_request()){
            $segments = $this->uri->segment_array();
            $type = isset($segments[3])? $segments[3]:NULL;
            $menuId = isset($segments[4])? $segments[4]:0;
            $names = array(
                'enable'    => $this->lang->line('global_enable'),
                'disable'   => $this->lang->line('global_disable'),
                'delete'    => $this->lang->line('global_delete'),
                'restore'   => $this->lang->line('global_restore')
            );
            if(is_null($type) || $menuId == 0)
                die($this->lang->line('menu_wrong_url'));
            
            $menu = $this->menus->getMenu($menuId);
            if(is_bool($menu))
                die($this->lang->line('menu_unexist_menu'));
            
            if($type == 'delete' || $type == 'restore')
                if($this->core->checkPermissions('menu','delete','all')){
                    $store = array(
                        'isDelete' => ($type == 'delete')? 1:0
                    );
                }else
                    die($this->lang->line('menu_no_delete_permission'));
            else if($type == 'enable' || $type == 'disable')
                if($this->core->checkPermissions('menu','active','all')){
                    $store = array(
                        'isHidden' => ($type == 'enable')? 0 : 1
                    );
                }else
                    die($this->lang->line('menu_no_activation_premission'));
            else
                die($this->lang->line('menu_wrong_url'));
            if($this->menus->updateMenu($menuId,$store))
                die($this->lang->line('menu_success').$names[$type]);
            else
                die($this->lang->line('menu_unsuccess').$names[$type]);
            
        }else
            redirect(STD_CMS_ERROR_PAGE);
    }
}

?>

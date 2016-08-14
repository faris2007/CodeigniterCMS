<?php

/**
 * this class for add,edit and remove from group table
 * 
 * @author Faris Al-Otaibi
 */
class group extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model('groups');
        $this->load->model('permissions');
        $this->load->model('pages');
    }
    
    public function index(){
        if($this->core->checkPermissions('group','all','all')){
            $this->show();
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }
    
    public function show(){
        if($this->core->checkPermissions('group','all','all')){
            $segments = $this->uri->segment_array();
            $filter = isset($segments[3])? $segments[3]:'all';
            switch ($filter){
                
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
            $data['GROUPS'] = $this->groups->getGroups('all');
            $data['CONTENT'] = "group";
            $data['STEP'] = 'show';
            $data['NAV'] = array(
                base_url()          => $this->lang->line('global_mainpage'),
                base_url().'admin'  => $this->lang->line('global_cpanel'),
                base_url().'group'   => $this->lang->line('global_group'),
            );
            $data['TITLE'] = $this->lang->line('global_group');
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }
    
    public function add(){
        if($this->core->checkPermissions('group','add','all')){
            if($_POST){
                    $store = array(
                        'name'     => $this->input->post('name',true),
                        'isAdmin'  => $this->input->post('admin',true),
                        'isDelete' => 0
                    );
                    if($this->groups->addNewGroup($store)){
                        $data['CONTENT'] = 'msg';
                        $data['MSG'] = $this->lang->line('global_msg_success') . br(1) . anchor(base_url().'group', $this->lang->line('group_return_back_msg'));
                    }else{
                        $data['CONTENT'] = "group";
                        $data['STEP'] = 'add';
                        $data['ERROR'] = true;
                        $data['ERR_MSG'] = $this->lang->line('group_add_err');
                    }
            }else{
                $data['CONTENT'] = "group";
                $data['STEP'] = 'add';
                $data['ERROR'] = false;
                $data['ERR_MSG'] = '';
            }
            $data['NAV'] = array(
                base_url()          => $this->lang->line('global_mainpage'),
                base_url().'admin'  => $this->lang->line('global_cpanel'),
                base_url().'group'   => $this->lang->line('global_group'),
            );
            $data['TITLE'] = $this->lang->line('group_add_title');
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }

    public function edit(){
        $segments = $this->uri->segment_array();
        $groupId = isset($segments[3])? $segments[3]:NULL;
        $group = $this->groups->getGroups($groupId);
        if(is_bool($group))
            redirect(STD_CMS_ERROR_PAGE);
        if($this->core->checkPermissions('group','edit','all')){
            if($_POST){
                    $store = array(
                        'name'     => $this->input->post('name',true),
                        'isAdmin'  => $this->input->post('admin',true),
                        'isDelete' => 0
                    );
                    if($this->groups->updateGroup($groupId,$store)){
                        $data['CONTENT'] = 'msg';
                        $data['MSG'] = $this->lang->line('global_msg_success'). br(1) .  anchor(base_url().'group', $this->lang->line('group_return_back_msg'));
                    }else{
                        $data['GROUPNAME'] = $group[0]->name;
                        $data['GROUPADMIN'] = ($group[0]->isAdmin == 1)? true:false;
                        $data['CONTENT'] = "group";
                        $data['STEP'] = 'edit';
                        $data['ERROR'] = true;
                        $data['ERR_MSG'] = $this->lang->line('group_add_err');
;
                    }
            }else{
                $data['GROUPNAME'] = $group[0]->name;
                $data['GROUPADMIN'] = ($group[0]->isAdmin == 1)? true:false;
                $data['CONTENT'] = "group";
                $data['STEP'] = 'edit';
                $data['ERROR'] = false;
                $data['ERR_MSG'] = '';
            }
            $data['NAV'] = array(
                base_url()          => $this->lang->line('global_mainpage'),
                base_url().'admin'  => $this->lang->line('global_cpanel'),
                base_url().'group'   => $this->lang->line('global_group'),
            );
            $data['TITLE'] = $this->lang->line('group_add_title');
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }
    
    public function permission(){
        if($this->core->checkPermissions('group','add','all')){
            $segments = $this->uri->segment_array();
            $groupId = isset($segments[3])? $segments[3]:NULL;
            $group = $this->groups->getGroups($groupId);
            if(is_bool($group))
                redirect(STD_CMS_ERROR_PAGE);
            
            $data['GROUPID'] = $groupId;
            $data['GROUPNAME'] = $group[0]->name;
            $data['SERVICES'] = $this->core->getServicesName('all');
            $data['STEP'] = 'permission';
            $data['CONTENT'] = "group";
            $data['ERROR'] = false;
            $data['ERR_MSG'] = '';
            $data['NAV'] = array(
                base_url()          => $this->lang->line('global_mainpage'),
                base_url().'admin'  => $this->lang->line('global_cpanel'),
                base_url().'group'   => $this->lang->line('global_group'),
                base_url().'group/permission/'.$groupId => $this->lang->line('group_permission_show')
            );
            $data['TITLE'] = $this->lang->line('group_permission_title');
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }


    public function action(){
        if($this->input->is_ajax_request()){
            if($this->core->checkPermissions('group','delete','all')){
                $segments = $this->uri->segment_array();
                $type = isset($segments[3])? $segments[3]:NULL;
                $groupId = isset($segments[4])? $segments[4]:0;
                $names = array(
                    'delete'    => $this->lang->line('global_delete'),
                    'deletep'   => $this->lang->line('global_delete_premssion'),
                    'restore'   =>  $this->lang->line('global_restore'),
                    'addp'      => $this->lang->line('global_add')
                );
                if(is_null($type) || $groupId == 0)
                    die($this->lang->line('group_action_error_url'));

                $group = $this->groups->getGroups($groupId);
                if(is_bool($group) && ($type != 'deletep' && $type != 'addp'))
                    die($this->lang->line('group_action_error_group_number'));

                if($type == 'delete' || $type == 'restore')
                    $store = array(
                        'isDelete' => ($type == 'delete')? 1:0
                    );
                elseif($type == 'deletep'){
                    if($this->permissions->deletePermission($groupId))
                        die($this->lang->line('group_action_success').$names[$type]);
                    else
                        die($this->lang->line('group_action_error_not_work').$names[$type]);
                }elseif($type == 'addp'){
                    if($_POST){
                        $store = array(
                            'service_name'  => $this->input->post('service_name',true),
                            'function_name' => $this->input->post('functions',true),
                            'value'         => $this->input->post('value',true),
                            'group_id'      => $groupId
                        );
                        if($this->permissions->addNewPermission($store))
                            die($this->lang->line('group_action_success').$names[$type]);
                        else
                            die($this->lang->line('group_action_error_not_work').$names[$type]);
                    }else
                        die($this->lang->line('group_action_error_url'));  
                }else
                    die($this->lang->line('group_action_error_url'));
                if($this->groups->updateGroup($groupId,$store))
                    die($this->lang->line('group_action_success').$names[$type]);
                else
                    die($this->lang->line('group_action_error_not_work').$names[$type]);
            }else
                redirect (STD_CMS_PERMISSION_PAGE);
        }else
            redirect(STD_CMS_ERROR_PAGE);
    }
    
    public function getData(){
        if($this->input->is_ajax_request()){
            $segments = $this->uri->segment_array();
            $type = isset($segments[3])? $segments[3]:NULL;
            $value = isset($segments[4])? $segments[4]:NULL;
            if(is_null($type))
                die($this->__makeOptions('', $this->lang->line('group_getdata_error_url')));
            
            $functions = $this->core->getFunctionsName('all');
            if($type == 'page' && !is_null($value)){
                $data = $this->__makeOptions('all', $this->lang->line('global_everyone'));
                $this->db->where('parent_id IS NULL');
                $pages = $this->pages->getPages();
                foreach ($pages as $row){
                    $data .= $this->__makeOptions($row->id, $row->title);
                }
                die($data);
            }
            
            if(isset($functions[$type])){
                $data = '';
                foreach ($functions[$type] as $value => $title){
                    $data .= $this->__makeOptions($value, $title);
                }
                die($data);
            }else
                die($this->__makeOptions ('', $this->lang->line('group_getdata_error_not_found')));
        }else
            redirect(STD_CMS_ERROR_PAGE);
    }
    
    private function __makeOptions($value,$title){
        return "<option value=\"".$value."\">".$title."</option>";
    }
    

}

?>

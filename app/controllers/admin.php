<?php

/**
 * this class for add,edit and remove from admin table
 * 
 * @author Faris Al-Otaibi
 */
class admin extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        if($this->core->checkPermissions('admin','all','all')){
            $data['CONTENT'] = "admin";
            $data['NAV'] = array(
                base_url()          => $this->lang->line('global_mainpage'),
                base_url().'admin'  => $this->lang->line('global_cpanel')
            );
            $data['TITLE'] = "-- ".$this->lang->line('global_cpanel');
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }
}

?>

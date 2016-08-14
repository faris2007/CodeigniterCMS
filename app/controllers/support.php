<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of support
 *
 * @author salsferi
 */
class support extends CI_Controller {
    
    /**
     *
     * @var CI_DB_active_record
     */
    //private $supportDB;
    public function __construct() {
        parent::__construct();
        //$this->supportDB = $this->load->database('support',true); 
        $this->lang->load('login', 'english');
        $this->load->model('problem');
        $this->load->model('dept');
    }
    
    public function index(){
        if($this->users->isLogin()){
            $problems = $this->problem->get_problems('new');
            if(is_array($problems)){
                for($i=0;$i<count($problems);$i++){
                    $problems[$i]->receiver = $this->users->getUsername($problems[$i]->write_by);
                    $problems[$i]->dept_name = $this->dept->get_dept_name($problems[$i]->dept_id); 
                }
            }
            $data['problems'] = $problems;
            $data['STEP'] = 'home';
            $data['CONTENT'] = 'support';
            $data['isInstall'] = 'support';
            $this->core->load_template($data);
        }else
            redirect ('support/login');
    }
    
    public function add(){
        if($this->users->isLogin()){
            if($_POST){
                $store = array(
                    'type'      => $this->input->post('type',true),
                    'dept_id'   => $this->input->post('cat',true),
                    'priority'  => $this->input->post('priority',true),
                    'ext'       => $this->input->post('ext',true),
                    '_date'     => date('Y-m-d'),
                    '_time'     => date('H:i:s'),
                    'write_by'  => $this->users->getInfoUser()
                );
                if($this->problem->add_problem($store)){
                    $data['CONTENT'] = 'msg';
                    $data['MSG'] = "Done, it's added" . br(1) . anchor(base_url().'support', $this->lang->line('login_error_transfer'));
                }else{
                    $data['STEP'] = 'add';
                    $data['DEPARTMENTS'] = $this->dept->get_dept();
                    $data['ERROR'] = true;
                    $data['ERR_MSG'] = "There is error in data entry";
                    $data['CONTENT'] = 'support';   
                }
            }else{
                $data['STEP'] = 'add';
                $data['DEPARTMENTS'] = $this->dept->get_dept();
                $data['ERROR'] = FALSE;
                $data['ERR_MSG'] = "";
                $data['CONTENT'] = 'support';
            }
            $data['isInstall'] = 'support';
            $this->core->load_template($data);
        }  else 
            redirect ('support/login');
    }
    
    public function finish(){
        if($this->users->isLogin()){
            $segments = $this->uri->segment_array();
            $problemId = isset($segments[3])? $segments[3]:NULL;
            if(is_null($problemId)){
                $problems = $this->problem->get_problems('old');
                if(is_array($problems)){
                    for($i=0;$i<count($problems);$i++){
                        $problems[$i]->receiver = $this->users->getUsername($problems[$i]->write_by);
                        $problems[$i]->dept_name = $this->dept->get_dept_name($problems[$i]->dept_id);
                        $problems[$i]->done = $this->users->getUsername($problems[$i]->done_by);
                    }
                }
                $data['finishedProblems'] = $problems;
                $data['STEP'] = 'finish';
                $data['CONTENT'] = 'support';
            }else{
                $this->problem->update_problem($problemId,array(
                    'done_by'   => $this->users->getInfoUser()
                ));
                $data['CONTENT'] = 'msg';
                $data['TITLE'] = "-- Support";
                $data['MSG'] = "Done, It's add to your work".  br(1).  anchor(base_url().'support', $this->lang->line('login_error_transfer'));
                $data['HEAD'] =  meta(array('name' => 'refresh', 'content' => '3;url='.  base_url().'support', 'type' => 'equiv'));
            }
            $data['isInstall'] = 'support';
            $this->core->load_template($data);
        }else
            redirect ('support/login');
    }
    
    public function report(){
        if($this->users->isLogin())
        {
            $segments = $this->uri->segment_array();
            $reportType = isset($segments[3])? $segments[3]:NULL;
            if(is_null($reportType)){
                $data['STEP'] = 'startReport';
                $data['CONTENT'] = 'support';
            }else{
                if($reportType == 'users'){
                    $data['TYPE'] = 'user name';
                    $problems = $this->problem->Report('users');
                    if(is_array($problems)){
                        for($i=0;$i<count($problems);$i++){
                            $problems[$i]->type = $this->users->getUsername($problems[$i]->done_by);
                        }
                    }
                }elseif($reportType == 'type'){
                    $data['TYPE'] = 'Type of Problems';
                    $problems = $this->problem->Report('type');
                }elseif($reportType == 'dept'){
                    $data['TYPE'] = 'Departments';
                    $problems = $this->problem->Report('department');
                    if(is_array($problems)){
                        for($i=0;$i<count($problems);$i++){
                            $problems[$i]->type = $this->dept->get_dept_name($problems[$i]->dept_id);
                        }
                    }
                }else
                    redirect('support/permission');
                $data['REPORTS'] = $problems;
                $data['STEP'] = 'report';
                $data['CONTENT'] = 'support';
            }
            $data['isInstall'] = 'support';
            $this->core->load_template($data);
        }else
        redirect ('support/login');
    }

    public function login(){
        if(!$this->users->isLogin())
        {
            //$enable_register = $this->core->getSettingByName("cms_register_enable");
            if($_POST){
                $username = $this->input->post('username',true);
                $password = $this->input->post('password',true);
                if($this->users->login($username,$password)){
                    $this->core->add_log($this->lang->line('login_index'),$this->lang->line('global_site'));
                    $data['CONTENT'] = 'msg';
                    $data['TITLE'] = "-- ".$this->lang->line('login_index');
                    $data['MSG'] = $this->lang->line('login_success_msg').  anchor(base_url().'support', $this->lang->line('login_error_transfer'));
                    $data['HEAD'] =  meta(array('name' => 'refresh', 'content' => '3;url='.  base_url().'support', 'type' => 'equiv'));
                }else{
                    $data['CONTENT'] = "login";
                    $data['TITLE'] = "-- ".$this->lang->line('login_index');
                    $data['STEP'] = 'login';
                    $data['REGISTER'] = false;
                    $data['ERROR'] = true;
                }
            }else{
                $data['CONTENT'] = "login";
                $data['TITLE'] = "-- ".$this->lang->line('login_index');
                $data['STEP'] = 'login';
                $data['REGISTER'] = false;
                $data['ERROR'] = FALSE;
            }
                $data['isInstall'] = 'support';
                $this->core->load_template($data);
        }else
            redirect ('support');
    }
    
    public function logout(){
        if($this->users->isLogin()){
            $this->core->add_log($this->lang->line('login_logout'),$this->lang->line('global_site'));
            $this->users->logout();
        }
        $data['CONTENT'] = "login";
        $data['TITLE'] = "-- ".$this->lang->line('login_logout');
        $data['STEP'] = 'logout';
        $data['ERROR'] = FALSE;
        $data['isInstall'] = 'support';
        $this->core->load_template($data);
    }
    
    public function permission(){
        if($this->users->isLogin()){
            $this->core->add_log($this->lang->line('login_permission_log'),$this->lang->line('global_site'));
            $data['CONTENT'] = "login";
            $data['TITLE'] = "-- ".$this->lang->line('login_permission_title');
            $data['STEP'] = 'permission';
            $data['ERROR'] = FALSE;
            $data['isInstall'] = 'support';
            $this->core->load_template($data);
        }else
            redirect ('support/login');
    }
    
    //public function 
    
    
}

?>

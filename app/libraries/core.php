<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Core {
    
    /**
     * For load all class of CodeIgniter
     * @var CI_Controller $CI 
     */
    private $CI;
    private $Token,$Old_Token,$New_Token,$Security_Key,$User_Agent;
    public  $site_language = 'english',$site_language_ext = 'en';
    private $site_name = '',$site_style;
    private $site_style_default = "default";
    private $site_admin_area = false;
    private $site_current_url = false;
    const LANGUAGE_FOLDER = "./app/language/";
    const LANGUAGE_FILE_POSTFIX = "_lang.php";
    /**
     * load core of cms
     */
    public function __construct()
    {
            // Load Class CI
            $this->CI =& get_instance();
            
            // Check if the controller not install for load setting for web site
            if($this->CI->uri->segment(1, 0) !== 'install')
                $this->load_setting();
            
            // For detect the languages of website
            $this->detect_language();
            
            // Load global language file
            $this->CI->lang->load('global', $this->site_language);
            
            // Load language file for specific controller
            if(file_exists(self::LANGUAGE_FOLDER.$this->site_language."/".$this->CI->uri->segment(1, 0).self::LANGUAGE_FILE_POSTFIX))
                $this->CI->lang->load($this->CI->uri->segment(1, 0), $this->site_language);
            elseif ($this->CI->uri->segment(1, 0) === 'logout' && file_exists(self::LANGUAGE_FOLDER.$this->site_language."/login".self::LANGUAGE_FILE_POSTFIX)) {
                $this->CI->lang->load('login', $this->site_language);
            }
            
            // set the default charset to UTF-8
            ini_set('default_charset', 'UTF-8');
    }
    
    /**
     * Load all setting what we need in CMS
     */
    public function load_setting()
    {
            $this->generate_token();
            $this->initlizeForCms();
            
    }
    
    /**
     * to check if end user change the language and reset the language
     */
    public function detect_language(){
        if($_GET){
            $ext = $this->CI->input->get('lang',true);
            if($this->CI->uri->segment(1, 0) !== 'install'){
                $this->CI->load->model('langs');
                $this->CI->db->where('ext',$ext);
                $result = $this->CI->langs->getLangs();
            }
            $this->site_language = ($this->CI->uri->segment(1, 0) !== 'install')? $result[0]->folder : $ext;
            $this->site_language_ext = ($this->CI->uri->segment(1, 0) !== 'install')? $ext : substr($ext, 0, 2);
            $data['lang_folder'] = $this->site_language;
            $data['lang_ext'] = $this->site_language_ext;
            $this->CI->session->set_userdata($data);
        }else if($this->CI->session->userdata('lang_folder')){
            $this->site_language = $this->CI->session->userdata('lang_folder');
            $this->site_language_ext = $this->CI->session->userdata('lang_ext');
            $data['lang_folder'] = $this->site_language;
            $data['lang_ext'] = $this->CI->session->userdata('lang_ext');
            $this->CI->session->set_userdata($data);
        }else{
            $data['lang_folder'] = $this->site_language;
            $data['lang_ext'] = $this->site_language_ext;
            $this->CI->session->set_userdata($data);
        }
    }
    
    /**
     * Get setting of CMS by name
     * @param String $name
     * @return boolean or String
     */
    public function getSettingByName($name){
        $this->CI->load->model('settings');
        if(empty($name))
            return FALSE;
        if($name == 'all'){
            $result = $this->CI->settings->getSettings();
            if($result){
                foreach ($result as $value)
                    $setting[$value->name] = $value->value;
            }
            return $setting;
        }else
            return $this->CI->settings->getSettingByName($name)->value;
    }

    /**
     * init fo CMS
     */
    public function initlizeForCms(){
            $title = $this->getSettingByName("site_name");
            $this->site_name = (!is_bool($title))? $title : '' ;
            $style = $this->getSettingByName("style");
            $this->site_style = (!is_bool($style)) ? $style : $this->site_style_default;
            $enable = $this->getSettingByName("site_enable");
            if(!is_bool($enable)){
                if($enable == 0) {
                    $disable = TRUE;
                    $this->CI->load->model('users');
                    if($this->CI->users->isLogin()){
                            $enableForGroup = $this->getSettingByName("disable_except_group");
                            if($this->CI->users->getInfoUser('group') == $enableForGroup)
                                $disable = FALSE;
                        }
                    if($this->CI->uri->segment(1, 0) !== 'login' && $disable){
                        $disable_msg = $this->getSettingByName("disable_msg");
                        exit($this->load_template(array(
                            'CONTENT'   => 'msg',
                            'MSG'       => nl2br($disable_msg),
                            'DISABLE'   => TRUE
                        ),true));
                    }
                }
            }
            $this->CI->load->model('langs');
            $lang = $this->CI->langs->getLang($this->getSettingByName("cms_default_language"));
            $this->site_language = $lang->folder;
            $this->site_language_ext = $lang->ext;
            if($_POST && isset($_POST['token'])){
                $this->check_token();
            }
    }

    public function generate_token()
    {
            // Token
            $this->Security_Key = sha1(rand(1000,9999) * rand(1000,9999));
            $sessions = $this->CI->session->all_userdata();
            $this->Old_Token = (isset($sessions['Token']))?$this->CI->encrypt->decode($this->CI->session->userdata('Token')):'';
            //$this->Token = $this->CI->encrypt->decode($this->CI->session->userdata('New_Token'));
            $this->User_Agent = $this->CI->agent->agent_string();
            $this->New_Token = $this->CI->encrypt->encode($this->User_Agent . '|' . $this->Security_Key  . '|' . time());
            $this->Token = $this->New_Token;
            $this->CI->session->set_userdata(array(
                'Token' => $this->Token
            ));
            
    }

    public function token($generate = FALSE)
    {
            $generate = (is_bool($generate)) ? $generate : FALSE;

            if ($generate)
            {
                    return $this->New_Token;
            }else{
                    return $this->Token;
            }
    }

    public function check_token($redirect = TRUE,$token = '')
    {
            $redirect = (is_bool($redirect)) ? $redirect : FALSE;
            $token = ($token == '') ? $this->CI->input->post('token', TRUE) : $token;
            if ($this->CI->encrypt->decode($this->CI->input->post('token', TRUE)) == $this->Old_Token)
            {
                return TRUE;
            }else{
                if ($redirect)
                {
                    redirect();
                }else{
                    return FALSE;
                }
            }
    }
    
    /**
     * use this function when you want to load template of CMS
     * @param Array $temp_data
     * @param Boolean $load_only
     * @return String or Null
     */
    public function load_template($temp_data = array(),$load_only = FALSE)
    {

            // get Extra Menu If it have
            $data = $this->extraMenuInTemplate();
        
            // Page Title
            $data['HEAD']['TITLE'] = (isset($temp_data['TITLE'])) ? $this->site_name.' '.$temp_data['TITLE'] :$this->site_name;

            // Meta	
            $data['HEAD']['META']['ROBOTS'] = (isset($data['HEAD']['META']['ROBOTS'])) ? 'none' : 'all';
            $data['HEAD']['META']['DESC'] = (isset($data['HEAD']['META']['DESC'])) ? $data['HEAD']['META']['DESC'] : '';
            $data['HEAD']['META']['KW'] = (isset($data['HEAD']['META']['KW'])) ? $data['HEAD']['META']['KW'] : '';
            $data['HEAD']['META']['META'] = array(
                    array('name' => 'robots', 'content' => $data['HEAD']['META']['ROBOTS']),
                    array('name' => 'description', 'content' => $data['HEAD']['META']['DESC']),
                    array('name' => 'keywords', 'content' => $data['HEAD']['META']['KW'])
            );

            // Other Code ( HTML -> HEAD )
            $data['HEAD']['OTHER'] = (isset($temp_data['HEAD'])) ? $temp_data['HEAD'] : '';
            
            // Main Menu Data
            if(!isset($temp_data['isInstall'])){
                $menu['MAINMENU'] = $this->getMenuPyParentID(NULL);
                // Main Menu file
                $menuFile = (file_exists('./app/views/'.$this->site_style.'/menu.php'))? $this->site_style.'/menu' : $this->site_style_default.'/menu';

                // Main Menu
                $data['MENU'] = (isset($temp_data['MENU'])) ? $temp_data['MENU'] : $this->CI->load->view($menuFile,$menu,TRUE);
            }
            // Load Model Of sliders
            $this->CI->load->model("sliders");
            //condition for sliders
            
            $whereSlider = array(
                'isHidden' => 0,
                'isDelete' => 0
            );
            $this->CI->db->where($whereSlider);
            // data of slider
            $sliderData['SLIDERS'] = $this->CI->sliders->getSlider("all");
            
            // check if the data empty
            $sliderData['SLIDERS'] = (!is_array($sliderData['SLIDERS']))? array() : $sliderData['SLIDERS'];
            // Slider file
            $sliderFile = (file_exists('./app/views/'.$this->site_style.'/slider.php'))? $this->site_style.'/slider' : $this->site_style_default.'/slider';

            // Slider
            if(!isset($temp_data['isInstall'])){
                $data['SLIDERS_SHOW'] =  $this->CI->load->view($sliderFile,$sliderData,TRUE);
                $temp_data['SLIDERS_SHOW'] = $data['SLIDERS_SHOW'];
            }
            
            // Change style if install
            $this->site_style = (isset($temp_data['isInstall']))? $temp_data['isInstall'] : $this->site_style;
            
            $this->site_style = (empty($this->site_style)) ? $this->site_style_default :$this->site_style;
            
            // Url for folder of style
            $data['STYLE_FOLDER'] = (is_dir("./style/".$this->site_style)) ? base_url().'style/'.  $this->site_style.'/': base_url().'style/'.  $this->site_style_default.'/';
            
            // Url for folder of style for get from content file
            $temp_data['STYLE_FOLDER'] = $data['STYLE_FOLDER'];
            
            // Store name of file of content
            $data['CONTENTFILE'] = $temp_data['CONTENT'];
            // Check If the file is exist
            $contentFile = (file_exists('./app/views/'.$this->site_style.'/controller/'.$temp_data['CONTENT'].'.php') && $this->site_style != 'default') ? $this->site_style.'/controller/'.$temp_data['CONTENT'] : 'default/controller/'.$temp_data['CONTENT'];
            // Load Model Of users
            $this->CI->load->model('users');
            
            // Load User Profile
            $userInfo = ($this->CI->users->isLogin())? $this->CI->users->getUser($this->CI->users->getInfoUser('id')):FALSE;
            
            // Remove Improtant attrabute
            if($userInfo){
                
                // Remove Password
                unset($userInfo->password);
                
                // Remove  new Password
                unset($userInfo->new_password);
                
                // Remove isBanned
                unset($userInfo->isBanned);
                
                // Remove isDelete
                unset($userInfo->isDelete);
                
                // Remove isActive
                unset($userInfo->isActive);
                
                // Remove group_id
                unset($userInfo->group_id);
            
                // Check if the User is Admin
                $userInfo->isAdmin = $this->CI->users->isAdmin();
            }
            // Store Information about user in $temp_data
            $temp_data['userInfo'] = $userInfo;
            // Store Information about user in $data
            $data['userInfo'] = $userInfo;
            // Store Information about ext. of language in $temp_data
            $temp_data['LANG_EXT'] = $this->site_language_ext;
            // Store Information about ext. of language in $data
            $data['LANG_EXT'] = $this->site_language_ext;
            // Content
            $data['CONTENT'] = (isset($temp_data['CONTENT'])) ? $this->CI->load->view($contentFile,$temp_data,TRUE) : '' ;
            
            // Navbar for website
            $data['NAV'] = (isset($temp_data['NAV'])) ? $temp_data['NAV']: false;
            
            // Fetch model of Lnguages
            $this->CI->load->model('langs');
            
            // Load All Language in websites to store it in $data
            $data['LANGS'] = $this->CI->langs->getLangs();
            
            // Copy Right
            $data['DEVELOPMENT'] = 'تصميم وتطوير '.  anchor('https://std-hosting.com/', 'الشركة السعودية للتصاميم التقنية') .'.';
            
            // Disable Website
            $data['DISABLE'] = (isset($temp_data['DISABLE'])) ? $temp_data['DISABLE'] : false;
            
            // Check if The area for Admin then set adminmenu
            if($this->site_admin_area)
                $data['ADMINMENU'] = $this->getAdminMenu();
            else
                $data['ADMINMENU'] = false;
            
            // Main Template
            $load_only = (is_bool($load_only)) ? $load_only : FALSE;

            if ($load_only)
            {
                    return $this->CI->load->view($this->site_style.STD_CMS_SLASH.'template',$data,TRUE);
            }else{
                    $this->CI->load->view($this->site_style.STD_CMS_SLASH.'template',$data);
            }
    }
    
    public function extraMenuInTemplate(){
        $result = array(
            'EXTMENU1'  => $this->getExtraMenu(1),
            'EXTMENU2'  => $this->getExtraMenu(2),
            'EXTMENU3'  => $this->getExtraMenu(3),
            'EXTMENU4'  => $this->getExtraMenu(4),
            'EXTMENU5'  => $this->getExtraMenu(5)
        );
        
        return $result;
    }

    public function getAdminMenu(){
        $this->CI->lang->load('admin', $this->site_language);
        $data = array(
            'setting'           => $this->CI->lang->line('admin_view_setting'),
            'user'              => $this->CI->lang->line('admin_view_manage_member'),
            'group'             => $this->CI->lang->line('admin_view_manage_group'),
            'page'              => $this->CI->lang->line('admin_view_manage_page'),
            'menu'              => $this->CI->lang->line('admin_view_manage_menu'),
            'log'               => $this->CI->lang->line('admin_view_manage_record'),
            'slider'            => $this->CI->lang->line('admin_view_manage_intrface'),
            'langc'             => $this->CI->lang->line('admin_view_manage_language')
        );
        foreach ($data AS $key => $val)
            if(!$this->checkPermissions($key,'all','all'))
                if($key == 'user'){
                    if(!$this->checkPermissions($key.'s','all','all'))
                        unset ($data[$key]);
                }else{
                    unset ($data[$key]);
                }
           
        return $data;
    }
    
    /**
     * Check If the user have permission
     * @param String $service_name
     * @param String $function_name
     * @param String $value
     * @return boolean
     */
    public function checkPermissions($service_name = "admin",$function_name = "all",$value = "all")
    {
        if(empty($service_name) || empty($function_name) || empty($value) )
            return false;
        $this->CI->load->model("users");
        if(!$this->CI->users->isLogin())
            return false;
        
        if(!$this->CI->users->isAdmin())
            return FALSE;
        
        $functions = $this->getFunctionsName();
        if ($service_name == "admin")
        {
            if($this->CI->users->isAdmin()){
                /*$action = $functions[$service_name][$function_name];
                $this->add_log($action);*/
                $this->site_admin_area = true;
                return true;
            }else 
                return false;
        }else
        {
            $segments = $this->CI->uri->segment_array();
            if($this->CI->users->checkIfHavePremission($service_name,$function_name,$value)){
                $action = $functions[$service_name][$function_name];
                $this->add_log($action);
                if($service_name != 'page' || !isset($segments[2]) || (isset($segments[2]) && $segments[2] != 'view'))
                    $this->site_admin_area = true;
                return true;
            }else{
                if($service_name == 'menu' && $value != 'all'){
                    $this->CI->load->model('menus');
                    $parentData = $this->CI->menus->getParentThisMenu($value);
                    $data = explode(',', $parentData);
                    foreach ($data as $row){
                        if($this->CI->users->checkIfHavePremission($service_name,$function_name,$row->id))
                        {        
                            $action = $functions[$service_name][$function_name];
                            $this->add_log($action);
                            $this->site_admin_area = true;
                            return true;
                        }
                    }
                    return false;
                }else if($service_name == 'page' && $value != 'all'){
                    $this->CI->load->model('pages');
                    $parentData = $this->CI->pages->getParentThisPage($value);
                    $data = explode(',|,', $parentData);
                    foreach ($data as $row){
                        $row = unserialize($row);
                        if($this->CI->users->checkIfHavePremission($service_name,$function_name,$row->id))
                        {
                            $action = $functions[$service_name][$function_name];
                            $this->add_log($action);
                            if(!isset($segments[2]) || (isset($segments[2]) && $segments[2] != 'view'))
                                $this->site_admin_area = true;
                            return true;
                        }
                    }
                    return false;
                }else
                    return false;
            }
        }
        
    }
    
    public function getServicesName($service_name = 'all'){
        $data = array(
            "page"              => $this->CI->lang->line('global_page') ,
            "menu"              => $this->CI->lang->line('global_menu'),
            "user"              => $this->CI->lang->line('global_users'),
            "group"             => $this->CI->lang->line('global_group'),
            "setting"           => $this->CI->lang->line('global_setting'),
            "log"               => $this->CI->lang->line('global_log'),
            "slider"            => $this->CI->lang->line('global_slider'),
            "langc"             => $this->CI->lang->line('global_langc'),
            "support"           => $this->CI->lang->line('global_support'),
            );
        if($service_name == 'all' )
            return $data;
        elseif($service_name == 'myprofile' || $service_name == 'user')
            return $data['user'];
        elseif($service_name == 'myorder')
            return $data['order'];
        elseif(isset($data[$service_name]))
            return $data[$service_name];
        else
            return 'غير معروف';
    }
    
    public function getFunctionsName($service_name ="all"){
        $data = array(
            "page"      => array(
                    "all"       => $this->CI->lang->line('global_all'),
                    "active"    => $this->CI->lang->line('global_active'),
                    "show"      => $this->CI->lang->line('global_show'),
                    "add"       => $this->CI->lang->line('global_add'),
                    "edit"      => $this->CI->lang->line('global_edit'),
                    "delete"    => $this->CI->lang->line('global_delete')
                    ),
            "menu"      => array(
                    "all"       => $this->CI->lang->line('global_all'),
                    "active"    => $this->CI->lang->line('global_active'),
                    "show"      => $this->CI->lang->line('global_show'),
                    "add"       => $this->CI->lang->line('global_add'),
                    "edit"      => $this->CI->lang->line('global_edit'),
                    "delete"    => $this->CI->lang->line('global_delete')
                    ),
            "user"     => array(
                    "all"       => $this->CI->lang->line('global_all'),
                    "active"    => $this->CI->lang->line('global_active'),
                    "show"      => $this->CI->lang->line('global_show'),
                    "add"       => $this->CI->lang->line('global_add'),
                    "edit"      => $this->CI->lang->line('global_edit'),
                    "delete"    => $this->CI->lang->line('global_delete')
                    ),
            "group"     => array(
                    "all"       => $this->CI->lang->line('global_all'),
                    "active"    => $this->CI->lang->line('global_active'),
                    "show"      => $this->CI->lang->line('global_show'),
                    "add"       => $this->CI->lang->line('global_add'),
                    "edit"      => $this->CI->lang->line('global_edit'),
                    "delete"    => $this->CI->lang->line('global_delete')
                    ),
            "setting"   => array(
                    "all"       => $this->CI->lang->line('global_all'),
                    "show"      => $this->CI->lang->line('global_show'),
                    "add"       => $this->CI->lang->line('global_add'),
                    "edit"      => $this->CI->lang->line('global_edit')
                    ),
            "log"       => array(
                    "all"       => $this->CI->lang->line('global_all'),
                    "show"      => $this->CI->lang->line('global_show'),
                    "delete"    => $this->CI->lang->line('global_delete')
                    ),
            "slider"     => array(
                    "all"       => $this->CI->lang->line('global_all'),
                    "active"    => $this->CI->lang->line('global_active'),
                    "show"      => $this->CI->lang->line('global_show'),
                    "add"       => $this->CI->lang->line('global_add'),
                    "edit"      => $this->CI->lang->line('global_edit'),
                    "delete"    => $this->CI->lang->line('global_delete')
                    ),
            "langc"      => array(
                    "all"       => $this->CI->lang->line('global_all'),
                    "show"      => $this->CI->lang->line('global_show'),
                    "add"       => $this->CI->lang->line('global_add'),
                    "edit"      => $this->CI->lang->line('global_edit'),
                    "delete"    => $this->CI->lang->line('global_delete')
                    ),
            "support"      => array(
                    "all"       => $this->CI->lang->line('global_all'),
                    "show"      => $this->CI->lang->line('global_show'),
                    "add"       => $this->CI->lang->line('global_add'),
                    "edit"      => $this->CI->lang->line('global_edit'),
                    "delete"    => $this->CI->lang->line('global_delete')
                    )
            
            );
        return (isset($data[$service_name]))? $data[$service_name] : $data  ;
    }
    

    public function getMenuPyParentID($parentID = NULL){
        
        $this->CI->load->model('menus');
        $this->CI->load->model('langs');
        $this->CI->db->where("ext",  $this->site_language_ext);
        $lang = $this->CI->langs->getLangs();
        $where = array(
            'isDelete'      => 0,
            'isHidden'      => 0,
            'language_id'   => $lang[0]->id
        );
        $result = $this->CI->menus->getMenuWithChild($parentID,$where);
        $data = $attr = array();
        if(!is_bool($result)){
            foreach ($result as $val){
                $child = $this->extractSubMenu($val['child']);
                if($this->checkCurrentUrl($val['content']->url) || $this->site_current_url)
                   $attr['class'] = 'current'; 
                $data[] = (!is_bool($child))? anchor('#',$val['content']->title,$attr).' '.$this->getSubMenu($val['child']): anchor($val['content']->url,$val['content']->title,$attr);
            }
        }
        
        return $data;
    }
    
    public function checkCurrentUrl($url){
        if($url != 'home'){
            $arrayCurrent = $this->CI->uri->segment_array();
            $urln = str_replace(base_url(),'',$url);
            $urln = "cms/".$urln;
            $arrayUrl = explode('/', $urln);
            unset($arrayUrl[0]);
            return ($arrayUrl == $arrayCurrent);
        }  else {
            $current = current_url();
            $current = str_replace('http://', '', $current);
            $currentn = explode('/', $current);
            unset($currentn[2]);
            return (count($currentn) <= 2) ? true : false;
        }
    }


    private function extractSubMenu($content){
        return unserialize($content); 
    }
    
    private function getSubMenu($content){
        
        $content = $this->extractSubMenu($content);
        $data = array();
        if(!is_bool($content)){
            foreach ($content as $val){
                $subMenu  = $this->getSubMenu($val['child']);
                $this->site_current_url = ($this->checkCurrentUrl($val['content']->url)) ? true : false;
                $data[] = (!is_bool($subMenu))? anchor('#',$val['content']->title).' '.$subMenu : anchor($val['content']->url,$val['content']->title);
            }
        }else
            return false;
        
        return ul($data, array());
    }
    
    public function getExtraMenu($parentId = NULL){
        $this->CI->load->model('menus');
        $where = array(
            'isDelete'      => 0,
            'isHidden'      => 0
        );
        $this->CI->db->where($where);
        $result = $this->CI->menus->getMenus($parentId);
        return $result;
    }



    public function perpage($url = '',$total = 0,$cur_page = 0,$per_page = 30)
    {
        $this->CI->load->library('pagination');
        $config['base_url'] = base_url() . $url;
        $config['total_rows'] = $total;
        $config['per_page'] = $per_page;
        $config['uri_segment'] = 6;
        $config['num_links'] = 3;
        $config['cur_page'] = $cur_page;
        $this->CI->pagination->initialize($config);
        return $this->CI->pagination->create_links();        
    }
    
    public function message($_message = '',$_url = '',$_title = '',$_time = 200)
    {
        $data['MSG']['TITLE'] = $_title;
        $data['MSG']['MESSAGE'] = $_message;
        $data['MSG']['URL'] = $_url;
        $data['MSG']['TIME'] = $_time;
        $data['HEAD'] =  meta(array('name' => 'refresh', 'content' => $_time.';url='.$_url, 'type' => 'equiv'));
        $data['TITLE'] = 'MSG';
        $data['CONTENT'] = 'message';
        die($this->load_template($data,TRUE));
    }

    public function getTimeAsArray($time){
        $timeH = explode(":", $time);
        $data['hour'] = $timeH[0];
        $timeM = explode(" ", $timeH[1]);
        $data['min'] = $timeM[0];
        $data['am'] = $timeM[1];
        return $data;
    }
    
    public function add_log($action = Null,$location = NULL)
    {
        $this->CI->load->model('users');
        $this->CI->load->model('logs');
        $location = (is_null($location))? $this->getServicesName($this->CI->uri->segment(1, 0)) : $location;
        $data = array(
            'date'      => time(),
            'activity'  => (!is_null($action)) ? $action . ' -- '.$location : $location,
            'ip'        => $this->CI->input->ip_address(),
            'users_id'  => $this->CI->users->getInfoUser('id') 
        );
        $this->CI->logs->addNewLog($data);
    }
    
    function changeIfWindows($string){
        $this->CI->load->library('user_agent');
        if(empty($string))
            return "";
        if(@ereg('windows', strtolower($this->CI->agent->platform())))
            $newString = @iconv('UTF-8', 'Windows-1256', $string);
        else
            $newString = $string;
        return $newString;
    }
    
    public function getPath($pageId,$isAdmin = false){
        if(empty($pageId))
            return FALSE;
        $this->CI->load->model("pages");
        
        $parentPage = $this->CI->pages->getParentThisPage($pageId);
        $result = FALSE;
        if(!is_bool($parentPage)){
            $path = explode(',|,', $parentPage);
            $result["".base_url().""] = $this->CI->lang->line('global_mainpage');
            if(function_exists("serialize") && function_exists("unserialize")){
                if($isAdmin){
                    $result["".base_url()."admin"] = $this->CI->lang->line('global_cpenal');
                    $result["".base_url()."page"] = $this->CI->lang->line('global_page');
                    foreach ($path as $value){
                        $item = unserialize($value);
                        $result[base_url().'page/show/'.$item->id] = $item->title;
                    }
                }else{
                    foreach ($path as $value){
                        $item = unserialize($value);
                        $result[base_url().'page/view/'.$item->id] = $item->title;
                    }
                }
            }else{
                if($isAdmin){
                    $result["".base_url()."admin"] = $this->CI->lang->line('global_cpenal');
                    $result["".base_url()."page"] = $this->CI->lang->line('global_page');
                    foreach ($path as $value){
                        $item = explode(':',$value);
                        $result[base_url().'page/show/'.$item[0]] = $item[1];
                    }
                }else{
                    foreach ($path as $value){
                        $item = explode(':',$value);
                        $result[base_url().'page/view/'.$item[0]] = $item[1];
                    }
                }
            }
                
        }
        return $result;
    }
}

?>

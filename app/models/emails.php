<?php

/**
 * this class for add,edit and remove from emails table
 * 
 * @author Faris Al-Otaibi
 */
class emails extends CI_Model {
    
    private $_username = "";
    private $_password = "";
    private $_hostMail = "";
    private $_port = "";
    private $_service_flags = "";
    private $_mailbox = 'INBOX';
    private $imap_resource ;
    private $current_id = 0 ;
    private $CI;
    private $debug = "";

    public function __construct() {
        parent::__construct();
        $this->CI =& get_instance();
    }
    
    public function initialize($config){
        if(!is_array($config))
        {
            $this->_error(' $config isn\'t array in initialize()');
            return false;
        }else{
            $this->_username = (isset($config['login'])) ? $config['login'] : NULL;
            $this->_password = (isset($config['pass'])) ? $config['pass'] : NULL;
            $this->_hostMail = (isset($config['host'])) ? $config['host'] : NULL;
            $this->_port = (isset($config['port'])) ? $config['port'] : NULL;
            $this->_service_flags = (isset($config['service_flags'])) ? $config['service_flags'] : NULL;
            if($this->_checkAttr())
                return true;
            else{
                $this->_error('problem in attrabute in initialize()');
                return false;
            }
        }
    }
    
    private function _checkAttr(){
        return (is_null($this->_username) || is_null($this->_password) || is_null($this->_hostMail)
                || is_null($this->_port) || is_null($this->_service_flags))? false : true;
    }
    
    public function open(){
        if(!$this->isConnected() && $this->_checkAttr())
            $this->imap_resource = imap_open ($this->_getMailBox (), $this->_username, $this->_password)or $this->_error();
        else
            $this->imap_resource = imap_reopen ($this->imap_resource, $this->_getMailBox())or $this->_error();
    }
    
    private function _getMailBox(){
        return "{".$this->_hostMail.":".$this->_port.$this->_service_flags."}".$this->_mailbox;
    }
    
    public function get_num_msg(){
        if($this->isConnected())
            return imap_num_msg($this->imap_resource);
        else{
            $this->_error('use get_num_msg() before open()');
            return -1;
        }
    }
    
    
    public function get_message($msgId){
        if(empty($msgId) || !is_numeric($msgId) || $msgId > $this->get_num_msg() ){
            $this->_error('$msgId ='.$msgId.' isn\'t number in function get_message()');
            return FALSE;
        }
        $this->current_id = $msgId;
        
        if($this->isConnected()){
            $msg = array();
            $msg['header'] = $this->_get_header_message($msgId);
            $msg['body'] = $this->_get_body_message($msgId);

            return $msg;
        }else{
            $this->_error("can't use get_message() before open()");
            return FALSE;
        }
        
    }
    
    
    public function get_messages(){
       $msgNo = $this->get_num_msg();
       
       $msgs = array();
       if($msgNo > 0)
       {
           for($i = $msgNo;$i > 0;$i--){
               $msgs[$i]['sort_id'] = ($msgNo - $i)+1;
               $msgs[$i] = $this->get_message($i);
               unset($msgs[$i]['body']);
           }
       }
       return $msgs;
    }
    private function _get_header_message($msgId){
        
        $header = imap_header($this->imap_resource, $msgId) or $this->_error();
        $header->subject = $this->_mime_decode(0, $header->subject);
        $header->toaddress = $this->_mime_decode(0, $header->toaddress);
        $header->fromaddress = $this->_mime_decode(0, $header->fromaddress);
        $header->reply_toaddress = $this->_mime_decode(0, $header->reply_toaddress);
        $header->senderaddress = $this->_mime_decode(0, $header->senderaddress);
        $header->Msgno = trim($header->Msgno);
        return $header;
    }
    
    private function _get_body_message($msgId){
        $structure = imap_fetchstructure($this->imap_resource, $msgId)or $this->_error();
        $msg = array();
        if(isset($structure->parts) || count($structure->parts) > 1){
            $msg = $this->_extract_parts($structure->parts);
        }else{
            $sub_type = strtoupper($structure->subtype);
            if($sub_type === "PLAIN"){
                $body = imap_body($this->imap_resource,  $this->current_id ) or $this->_error();
                $msg["PLAIN"] = $this->_mime_decode($structure->encoding, $body);
            }elseif($sub_type === "HTML"){
                $body = imap_body($this->imap_resource,  $this->current_id ) or $this->_error();
                $msg["HTML"] = $this->_mime_decode($structure->encoding, $body);
            }else{
                $this->_error("type $sub_type isn't defined in _get_body_message()");
                return FALSE;
            }
        }
        $msg['structure'] = $structure;
        return $msg;
        
    }
    
    private function _mime_decode($encoding,$text){
        
        switch ($encoding){
            case 3:
                return base64_decode($text);
                break;
            case 4:
                return quoted_printable_decode($text);
                break;

            case 0:
            case 1:
            case 2:
            default :
                return imap_utf8($text);
                break;

        }
    }
    
    private function characterEncodeing($text,$type){
        return iconv($type, "UTF-8//TRANSLIT//IGNORE", $text);
    }


    private function _extract_parts($parts){
        $data = array();
        for($i=1;$i<=count($parts);$i++)
        {
            if (isset($parts[$i-1]->disposition))
            { 
                if ($parts[$i-1]->disposition == 'ATTACHMENT')
                { 
                    $data['attachment'][$i] = $this->_mime_decode(1,$parts[$i-1]->dparameters[0]->value); 
                }   
            }else{ 
                $body = imap_fetchbody($this->imap_resource,  $this->current_id , $i) or $this->_error();
                $body = $this->_mime_decode($parts[$i-1]->encoding,$body);
                if($parts[$i-1]->ifparameters == 1){
                    
                    if($parts[$i-1]->parameters[0]->attribute == "CHARSET"){
                        $convert = $this->characterEncodeing($body,$parts[$i-1]->parameters[0]->value);
                        $body = (!$convert) ? $body : $convert;
                        
                    }
                }
                $data[$parts[$i-1]->subtype] = $body;
            }
        }
        return $data;
    }
    
    public function getAttachment($msgId,$attachNo){
        if(empty($msgId) || empty($attachNo) || !is_numeric($attachNo) || !is_numeric($msgId))
            return false;
        
        $data = array();
        $structure = imap_fetchstructure($this->imap_resource, $msgId)or $this->_error();
        $body = imap_fetchbody($this->imap_resource,  $msgId , $attachNo) or $this->_error();
        $data['data'] = $this->_mime_decode($structure->parts[$attachNo-1]->encoding,$body);
        $data['filename'] = $this->_mime_decode(1,$structure->parts[$attachNo-1]->dparameters[0]->value);
        return $data;
    }


    public function send_message($to,$subject,$message){
        if(empty($to) || empty($subject) ||empty($message))
            return FALSE;
        
        $this->CI->load->library('email');
        $this->CI->load->library('core');
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = $this->_hostMail;
        $config['smtp_user'] = $this->_username;
        $config['smtp_pass'] = $this->_password;
        $config['smtp_port'] = 25;
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = 'html';
        
        $this->CI->email->initialize($config);
        $site_name = $this->CI->core->getSettingByName("site_name");
        $site_email = $this->_username;
        $error_number = 0;
        if(strpos($to, ',') !== false){
            $toArray = explode(',', $to);
            foreach ($toArray as $value){
                $this->CI->email->clear();

                $this->CI->email->from($site_email, '('.$site_name.'):');
                $this->CI->email->to($to);

                $this->CI->email->subject($subject);
                $this->CI->email->message($message);
                
                $error_number = ($this->CI->email->send()) ? 0 : $error_number+1;
                $this->debug .= "|<br />".$this->email->print_debugger();
            }
            return ($error_number == 0 || $error_number < count($toArray))? true : false;
        }else{
            $this->CI->email->clear();

            $this->CI->email->from($site_email, '('.$site_name.'):');
            $this->CI->email->to($to);

            $this->CI->email->subject($subject);
            $this->CI->email->message($message);

            $out = $this->CI->email->send();
            $this->debug = $this->email->print_debugger();
            return $out;
        }
    }
    
    public function getDebug(){
        return $this->debug;
    }


    public function changeMailBox($newmailbox){
        if(empty($newmailbox))
            return false;
        
        $this->_mailbox = $newmailbox;
        if($this->isConnected())
            $this->open ();
    }

    public function close(){
        if($this->isConnected())
            imap_close ($this->imap_resource);
    }
    
    public function isConnected(){
        return (is_resource($this->imap_resource)) ? true : false;
    }

    private function _error($typeOfError = NULL){
        if(is_null($typeOfError))
            log_message("error", "Email Error -- " . imap_last_error());
        else
            log_message("error", "Email Error -- " . $typeOfError);
    }
    
    
}

?>

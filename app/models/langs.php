<?php

/**
 * this class for add,edit and remove from languages table
 * 
 * @author Faris Al-Otaibi
 */
class langs extends CI_Model {
    
    private $_table = "language";
    
    public function __construct() {
        parent::__construct();
    }
    
    public function addNewLang($data){
        if(!is_array($data))
            return false;
        
        $this->db->trans_start();
        $this->db->insert($this->_table,$data);
        $this->db->trans_complete();
        if($this->db->trans_status() === FALSE)
        {
            $this->error();
            return false;
        }else
            return True;
    }
    
    public function updateLang($id,$data){
        if(empty($id) || !is_array($data) || !is_numeric($id))
            return FALSE;
        
        $this->db->trans_start();
        $this->db->where("id",$id);
        $this->db->update($this->_table,$data);
        $this->db->trans_complete();
        if($this->db->trans_status() === FALSE)
        {
            $this->error();
            return false;
        }else
            return True;
    }
    
    public function deleteLang($id){
        if(empty($id) || !is_numeric($id))
            return false;
        
        $this->db->trans_start();
        $this->db->where('id', $id);
        $this->db->delete($this->_table);
        $this->db->trans_complete();
        if($this->db->trans_status() === FALSE)
        {
            $this->error();
            return false;
        }else
            return True;
    }
    
    public function getLang($id){
        if(empty($id) || !is_numeric($id))
            return false;
        
        $this->db->trans_start();
        $this->db->where('id', $id);
        $query = $this->db->get($this->_table);
        $this->db->trans_complete();
        if($this->db->trans_status() === FALSE){
            $this->error();
            return false;
        }else{
            return ($query->num_rows() > 0)? $query->row() : false;
        }
            
    }
    
    public function getLangs(){
        
        $this->db->trans_start();
        $query = $this->db->get($this->_table);
        $this->db->trans_complete();
        if($this->db->trans_status() === FALSE){
            $this->error();
            return false;
        }else{
            return ($query->num_rows() > 0)? $query->result() : false;
        }
            
    }
    
    
    private function error(){
        $query = $this->db->last_query();
        $typeOfError =  $this->db->_error_message();
        log_message("error", "In This Query -- " . $query . " -- " . $typeOfError);
    }
}

?>

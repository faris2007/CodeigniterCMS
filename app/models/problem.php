<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of problem
 *
 * @author salsferi
 */
class problem extends CI_Model{
    
   
    
    
    private $table = "problem";
  
   
    
    function __construct() {
        parent::__construct();
     
    }
    
   
    public function add_problem($data){
        
        if(!is_array($data))
            return FALSE;
        
        return $this->db->insert($this->table,$data);
     }
    
    public function delete_problem($id){
        
       if(!is_numeric($id) || empty($id))
           return FALSE;
        
        $this->db->where('id', $id);
        return $this->db->delete($this->table); 
    }
    // change to data array
    public function update_problem($id,$data){
         if(empty($id)||!is_numeric($id))
            return FALSE;
        
        if(!is_array($data))
            return FALSE;
       

        $this->db->where('id',$id);
        return $this->db->update($this->table,$data);        

    }
    
    public function get_problem($id = NULL){
        if(!is_null($id) && !is_numeric($id))
            return FALSE;
    
        if($id){
             $this->db->where('id',$id);
        }
        $this->db->order_by('id');
        $query = $this->db->get($this->table);
        
        if($query->num_rows() > 0)
            return $query->result();
        else
            return FALSE;
    }
    
    public function get_problems($type){
        if(!is_null($type) && !is_string($type))
            return FALSE;
    
        if($type == 'new')
            $this->db->where('done_by IS NULL');
        else
            $this->db->where('done_by IS NOT NULL');
        $this->db->order_by('id');
        $query = $this->db->get($this->table);
        
        if($query->num_rows() > 0)
            return $query->result();
        else
            return FALSE;
    }
    
    public function Report($type){
        if(!is_null($type) && !is_string($type))
            return FALSE;
        
        if($type == 'users'){
            $this->db->group_by('done_by');
            $this->db->select('done_by,count(*) as count');
        }elseif($type == 'type'){
            $this->db->group_by('type');
            $this->db->select('type,count(*) as count');
        }elseif($type == 'department'){
            $this->db->group_by('dept_id');
            $this->db->select('count(*) as count,dept_id');
        }else
            return FALSE;
    
        $this->db->order_by('id');
        $query = $this->db->get($this->table);
        
        if($query->num_rows() > 0)
            return $query->result();
        else
            return FALSE;
    }
    
    
    
}



   
    


?>

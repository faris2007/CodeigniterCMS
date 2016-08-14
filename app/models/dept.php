<?php


class Dept extends CI_Model {
    
    
    private $table = "dept";
  
   
    
    function __construct() {
        parent::__construct();
        
    }
    
   
    public function add_dept($data){
        
        if(!is_array($data))
            return FALSE;
        
        return $this->db->insert($this->table,$data);
     }
    
    public function delete_dept($id){
        
       if(!is_numeric($id) || empty($id))
           return FALSE;
        
        $this->db->where('id', $id);
        return $this->db->delete($this->table); 
    }
    // change to data array
    public function update_dept($id,$data){
         if(empty($id)||!is_numeric($id))
            return FALSE;
        
        if(!is_array($data))
            return FALSE;
       

        $this->db->where('id',$id);
        return $this->db->update($this->table,$data);        

    }
    
    public function get_dept($id = NULL){
        if(!is_null($id) && !is_numeric($id))
            return FALSE;
    
        if(!is_null($id)){
             $this->db->where('id',$id);
        }
        $this->db->order_by('id');
        $query = $this->db->get($this->table);
        if($query->num_rows() > 0)
            return $query->result();
        else
            return FALSE;
    }
    
    public function get_dept_name($id){
        if(is_null($id))
            return FALSE;
    
        if($id){
             $this->db->where('id',$id);
        }
        $this->db->order_by('id');
        $query = $this->db->get($this->table);
        
        if($query->num_rows() > 0){
            $data = $query->result();
            return $data[0]->dept_name;
        }else
            return FALSE;
    }
    
    
}

?>

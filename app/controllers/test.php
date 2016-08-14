<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of test
 *
 * @author ffalotaibi
 */
class test  extends CI_Controller{
 
    public function index(){
        //show_404();
        $data['CONTENT'] = "test";
        $this->core->load_template($data);
    }
}

?>

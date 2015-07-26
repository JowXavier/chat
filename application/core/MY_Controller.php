<?php

class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        /*if (! $this->session->userdata('usuario')) {
            redirect('admin/login');
        }*/
        
        $this->data = array();
    }
}

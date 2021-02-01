<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Tutor extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }


    function Tutoring_client_id_exists($key)
    {
        $this->db->where('Tutoring_client_id',$key);
        $query = $this->db->get('int_schools_x_sessions_log');
        //print_r($query);die;
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }



    function drhomework_ref_id_exists($key)
    {
        $this->db->where('drhomework_ref_id',$key);
        $query = $this->db->get('int_schools_x_sessions_log');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }

}
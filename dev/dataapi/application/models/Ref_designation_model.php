<?php
 
class Ref_designation_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get ref_designation by id
     */
    function get_ref_designation($id)
    {
        return $this->db->get_where('ref_designation',array('id'=>$id))->row_array();
    }
        
    /*
     * Get all ref_designation
     */
    function get_all_ref_designation()
    {
        $this->db->order_by('id', 'desc');
        return $this->db->get('ref_designation')->result_array();
    }
        
    /*
     * function to add new ref_designation
     */
    function add_ref_designation($params)
    {
        $this->db->insert('ref_designation',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update ref_designation
     */
    function update_ref_designation($id,$params)
    {
        $this->db->where('id',$id);
        return $this->db->update('ref_designation',$params);
    }
    
    /*
     * function to delete ref_designation
     */
    function delete_ref_designation($id)
    {
        return $this->db->delete('ref_designation',array('id'=>$id));
    }
}

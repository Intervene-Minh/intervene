<?php
 
class Ref_sub_task_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get ref_sub_task by id
     */
    function get_ref_sub_task($id)
    {
        return $this->db->get_where('ref_sub_task',array('id'=>$id))->row_array();
    }
        
    /*
     * Get all ref_sub_task
     */
    function get_all_ref_sub_task()
    {
        $this->db->order_by('id', 'desc');
        return $this->db->get('ref_sub_task')->result_array();
    }
        
    /*
     * function to add new ref_sub_task
     */
    function add_ref_sub_task($params)
    {
        $this->db->insert('ref_sub_task',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update ref_sub_task
     */
    function update_ref_sub_task($id,$params)
    {
        $this->db->where('id',$id);
        return $this->db->update('ref_sub_task',$params);
    }
    
    /*
     * function to delete ref_sub_task
     */
    function delete_ref_sub_task($id)
    {
        return $this->db->delete('ref_sub_task',array('id'=>$id));
    }
}

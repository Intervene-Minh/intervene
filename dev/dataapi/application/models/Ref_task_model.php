<?php
 
class Ref_task_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get ref_task by id
     */
    function get_ref_task($id)
    {
        return $this->db->get_where('ref_task',array('id'=>$id))->row_array();
    }
        
    /*
     * Get all ref_task
     */
    function get_all_ref_task()
    {
        $this->db->order_by('id', 'desc');
        return $this->db->get('ref_task')->result_array();
    }
        
    /*
     * function to add new ref_task
     */
    function add_ref_task($params)
    {
        $this->db->insert('ref_task',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update ref_task
     */
    function update_ref_task($id,$params)
    {
        $this->db->where('id',$id);
        return $this->db->update('ref_task',$params);
    }
    
    /*
     * function to delete ref_task
     */
    function delete_ref_task($id)
    {
        return $this->db->delete('ref_task',array('id'=>$id));
    }
}

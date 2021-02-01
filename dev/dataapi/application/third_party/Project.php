<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Project extends CI_Model
{

    const _STATUS_DRAFT = 2;
	const _STATUS_ACTIVE = 1;
	
	const _GET_CLIENT = 1;
	const _GET_SITE_CONTRACTOR = 2;	
	const _GET_PROJECT_DIRECTOR = 3;
	const _GET_PROJECT_MANAGER = 4;
	const _GET_SITE_MANAGER = 5;
	const _GET_SITE_FOREMAN = 6;
	const _GET_SITE_ENGINEER = 7;
	const _GET_HEALTH_AND_SAFETY_REP = 8;
	const _GET_CONTRACT_ADMINISTRATOR = 9;


    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }

    /*
    public function addProject($data)
    {
    //echo '<pre>';print_r($data);die;
    $this->db->insert('checkmate_project',$data);
    return $this->db->insert_id();
    }
     */

    public function getProjectData($pro_id)
    {
        $this->db->select('*');
        $this->db->from('checkmate_project');

        if ($pro_id > 0) {
            $this->db->where('id', $pro_id);
        }

        $result = $this->db->get()->result_array();
        return $result;
    }

    /*
     * New function added to create project in steps
     */

    public function addProject($data)
    {
        $this->db->insert('checkmate_project', $data);
        return $this->db->insert_id();
    }

    public function updateProject($project_id, $data)
    {
        $this->db->where('id', $project_id);
        $this->db->update('checkmate_project', $data);
        return $this->db->affected_rows();
    }

    public function getById($id, $table, $column = null)
    {
        if ($column) {
            $this->db->select($column);
        }

        $this->db->where('id', $id);
        $this->db->from($table);
        if ($column) {
            return $this->db->get()->row()->{$column};
        }

        return $this->db->get()->row();
    }

    public function getByCriteria($criteria, $table, $fields=null)
    {
        if(!empty($fields)) 
        $this->db->select("*");
        $this->db->where($criteria);
        $this->db->from($table);
        return $this->db->get()->result();
    }

    
    public function getUsersById($criteria, $table, $fields=null)
    {
        $this->db->select(implode(',', $fields));
        $this->db->where($criteria);
        $this->db->from($table);

        foreach ($this->db->get()->result() as $row)
        {                      
            $userin = $row->employee_id;   
        }

       if(!empty($userin)){
            $myArray = explode(',', $userin);
            $this->db->select("*");
            $this->db->from('checkmate_team');
            $this->db->where_in('id', $myArray);
            return $this->db->get()->result_array();
       }

    }

    public function getTeamLeadByMilestoneId($criteria, $table, $fields=null)
    {
        // echo "test";
        $this->db->select(implode(',', $fields));
        $this->db->where($criteria);
        $this->db->from($table);

        foreach ($this->db->get()->result() as $row)
        {                      
            $userin = $row->teamlead_id;   
        }

       if(!empty($userin)){
            $myArray = explode(',', $userin);
            $this->db->select("*");
            $this->db->from('checkmate_team');
            $this->db->where_in('id', $myArray);
            return $this->db->get()->result_array();
       }

    }

    public function createSiteMapHistory($data)
    {
        $this->db->insert('checkmate_sitemap_history', $data);
        return $this->db->insert_id();
    }

    public function addProjectPictures($data)
    {
        $this->db->insert('checkmate_project_pictures', $data);
        return $this->db->insert_id();
    }

    /*
     * Date 23-Oct-2018, New API's for master
     */
    public function getSiteContracterList()
    {
        $this->db->select('id, contractor_name, contractor_phone_no');
        $this->db->from('checkmate_site_contractor');

        return $this->db->get()->result_array();
    }

    public function getClientList()
    {
        $this->db->select('id, client_name, client_phone_no');
        $this->db->from('checkmate_project_clients');

        return $this->db->get()->result_array();
    }

    public function getDirectorList()
    {
        $this->db->select('id, director_name, director_phone_no');
        $this->db->from('checkmate_project_director');

        return $this->db->get()->result_array();
    }

    public function getManagerList()
    {
        $this->db->select('id,manager_name, manager_phone_no');
        $this->db->from('checkmate_project_manager');

        return $this->db->get()->result_array();
    }

    public function getSiteManagerList()
    {
        $this->db->select('id,site_manager_name, site_manager_phone_no');
        $this->db->from('checkmate_project_site_manager');

        return $this->db->get()->result_array();
    }

    public function foreManList()
    {
        $this->db->select('id,foreman_name, foreman_phone_no');
        $this->db->from('checkmate_project_foreman');

        return $this->db->get()->result_array();
    }

    public function siteEngineerList()
    {
        $this->db->select('id,site_engineer_name, site_engineer_phone_no');
        $this->db->from('checkmate_project_site_engineer');

        return $this->db->get()->result_array();
    }

    public function healthSafetyRepresentativeList()
    {
        $this->db->select('id,rep_name, rep_phone_no');
        $this->db->from('checkmate_project_health_safty_representative');

        return $this->db->get()->result_array();
    }

    public function contractAdministratorList()
    {
        $this->db->select('id,ca_name, ca_phone_no');
        $this->db->from('checkmate_project_contracts_administrator');

        return $this->db->get()->result_array();
    }

    /*
     * Date 24th Oct 2018
     * Added code to get project list based on user id
     * and type of request whether user want all the
     * projects or the latest one
     */

    public function getProjectByUser($user_id, $type, $fields = null)
    {
        print_r($type);
        print_r($fields);exit;
        if(!empty($fields)) $this->db->select(implode(',', $fields));
        $this->db->from('checkmate_project');
        $this->db->where('user_id', $user_id);
   
        if (isset($type)) {
            $this->db->order_by('created', 'desc');
            $this->db->limit(1);
            return $this->db->get()->result();
        }else{
            return $this->db->get()->result();
        }

        

    }


    public function getProjectByUserAndId($user_id, $project_id, $fields = null)
    {
        if(!empty($fields)) $this->db->select(implode(',', $fields));
        $this->db->from('checkmate_project');
        $this->db->where(['user_id'=> $user_id, 'id'=>$project_id]);        

        return $this->db->get()->result();

    }

	public function get_designation($designation)
	{
		switch($designation)
		{
			case "GET_SITE_CONTRACTOR" : return 2; break;
			case "GET_CLIENT" : return 1; break;
			case "GET_PROJECT_DIRECTOR" : return 3; break;
			case "GET_PROJECT_MANAGER" : return 4; break;
			case "GET_SITE_MANAGER" : return 5; break;
			case "GET_SITE_FOREMAN" : return 6; break; 
			case "GET_SITE_ENGINEER" : return 7; break; 
			case "GET_HEALTH_AND_SAFETY_REP" : return 8; break;
			case "GET_CONTRACT_ADMINISTRATOR" : return 9; break;
		}
	}

	/* Equipement List */
	
	public function equipmentList() 
	{
	    $this->db->select('id, equipment_name, equipment_image');
		$this->db->from('ref_equipment');
		return $this->db->get()->result_array();
	}

	/* Team Data */

	public function teamByDesignationList($designation, $search = null)
	{
		$this->db->select('T.id, T.name, T.email, T.phone, T.address, D.designation, T.image');
		$this->db->from('checkmate_team T');
		$this->db->join('ref_designation D', 'T.designation = D.id', 'left');
		$this->db->where('T.designation', $designation);
		if(!empty($search)) $this->db->where("T.name LIKE '%$search%'");
		return $this->db->get()->result_array();
	}

	/* Addign Team to Project */

	public function addTeamToProject($data)
	{
		$this->db->insert('checkmate_project_team', $data);
		return $this->db->insert_id();
	}


	/* Assign Equipment to Project */

	public function addEquipmentToProject($data)
	{
		$this->db->insert('checkmate_project_equipment', $data);
		return $this->db->insert_id();
	}

    public function getProjectEquipments($project_id, $offset=null, $limit=null)
    {
        $this->db->select('PE.id, E.equipment_name, E.equipment_image')->from('checkmate_project_equipment PE');
        $this->db->join('ref_equipment E', 'E.id = PE.equipment_id'); 
        $this->db->where('PE.project_id', $project_id);
        if(!empty($offset) || !empty($limit)) $this->db->limit($limit, $offset);
        return $this->db->get()->result_array();
    }

    public function getProjectEmployee($project_id, $offset=null, $limit=null)
    {
        $this->db->select('PT.id, CT.name');
        $this->db->from('checkmate_project_team PT');
        $this->db->join('checkmate_team CT', 'CT.id = PT.employee_id'); 
        $this->db->where('PT.project_id', $project_id);
        if(!empty($offset) || !empty($limit)) $this->db->limit($limit, $offset);
        return $this->db->get()->result_array();
    }

    public function save($data, $table) 
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function updateByCriteria($criteria, $table, $data)
    {
        $this->db->where($criteria);
        $this->db->update($table, $data);
        return $this->db->affected_rows();
    }

}

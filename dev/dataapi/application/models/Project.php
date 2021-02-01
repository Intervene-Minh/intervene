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
        $where  = '  ( project_status != 0 OR project_status is null )';
        $this->db->where($where);

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
        return true;
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

    
    public function getMilestoneListByProjectID($criteria, $table)
    {
        $milestoneDetailList = array();
        $data = array();
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where($criteria);
        $data['milestoneList'] = $this->db->get()->result();

        
        foreach ($data['milestoneList'] as $row){

            $criteriaTeamLeader = array('id'=>$row->teamlead_id);
            $this->db->select("*");
            $this->db->from('checkmate_user');
            $this->db->where($criteriaTeamLeader);
            $data['TeamLeader'] = $this->db->get()->result_array();
            foreach ($data['TeamLeader']  as $k => $m)
                {
                     $data['TeamLeader'][$k]['image'] = base_url('api/' . $data['TeamLeader'][$k]['image']);
                     $data['TeamLeader'][$k] =  $data['TeamLeader'][$k];
                    
                }
            $teamArray = explode(',', $row->employee_id);
            $this->db->select("*");
            $this->db->from('checkmate_user');
            $this->db->where_in('id', $teamArray);
            $data['TeamList'] = $this->db->get()->result_array();
            foreach ($data['TeamList']  as $k => $m)
                {
                     $data['TeamList'][$k]['image'] = base_url('api/' . $data['TeamList'][$k]['image']);
                     $data['TeamList'][$k] =  $data['TeamList'][$k];
                    
                }
            $equipmentArray = explode(',', $row->equipment_id);
            $this->db->select("*");
            $this->db->from('checkmate_equipment');
            $this->db->where_in('id', $equipmentArray);
            $data['EquipmentList'] = $this->db->get()->result_array();
            foreach ($data['EquipmentList']  as $k => $m)
                {
                     $data['EquipmentList'][$k]['equipment_image'] = base_url('api/' . $data['EquipmentList'][$k]['equipment_image']);
                     $data['EquipmentList'][$k] =  $data['EquipmentList'][$k];
                    
                }
            $array_merge=array("MilestoneName"=>$row->project_milestone_name,
                                "MilestoneCoordinates"=>$row->milestone_coordinates,
                                "TeamLeader"=>$data['TeamLeader'],
                               "TeamList"=>$data['TeamList'],
                               "EquipmentList"=>$data["EquipmentList"]
                        );
            
            $milestoneDetailList["milestoneList"][]= $array_merge;

            
        }
        return $milestoneDetailList;
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
            $this->db->from('checkmate_user');
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
    
    
    public function updateSubbyLocation($project_id,$User_id,$User_X,$User_y)
    {
         
        $query = "Insert into checkmate_project_location set project_id = '".$project_id."' , user_id = '".$User_id."' 
        , user_x = '".$User_X."',  user_y = '".$User_y."' ON DUPLICATE KEY UPDATE user_x = '".$User_X."',  user_y = '".$User_y."'";
        return $this->db->query($query);
    }
    
    // public function updateSubbyTime($project_id,$User_id,$inout)
    // {
         
    //      if($inout == 0 )
    //      {
    //         $query = "insert into checkmate_subby_timesheet set project_id = '".$project_id."' , user_id = '".$User_id."' , time_in = now() ";
    //         return $this->db->query($query);
    //      }
    //      else
    //      {
    //           $query = "update  checkmate_subby_timesheet set project_id = '".$project_id."' , user_id = '".$User_id."' , time_out = now() where time_in is not null ";
    //           return $this->db->query($query);
    //      }
    // }


    public function updateSubbyTime($project_id,$User_id,$inout,$entry_id=0)
    {
           // 0 for insert
         if($inout == 0 )
         {
            $query = "insert into checkmate_subby_timesheet set project_id = '".$project_id."' , user_id = '".$User_id."' , time_in = now() ";
            return $this->db->query($query);
         }
         else
         {   // last entry max id 
            $last_entry=" SELECT * FROM checkmate_subby_timesheet WHERE time_out IS NULL AND user_id=".$User_id." AND project_id=".$project_id." ORDER by time_in DESC LIMIT 1";
          $last_data=  $this->db->query($last_entry)->row();
          $last_entry_id=$last_data->id;
          //print_r($last_user_entry_id); die;

              // $query = "update  checkmate_subby_timesheet set project_id = '".$project_id."' , user_id = '".$User_id."' , time_out = now() where user_id='$User_id' AND time_out IS NULL AND time_in is not null ";

           $query = "UPDATE  checkmate_subby_timesheet SET time_out = now() where id='$last_entry_id' AND user_id='$User_id' AND time_out IS NULL AND time_in is not null ";


            // echo $query; die; 
              return $this->db->query($query);
         }
    }



    
    public function checkSubbyTime($project_id,$user_id,$entry_id=0)
    {
         $this->db->select('*');
         $this->db->from('checkmate_subby_timesheet');
         $this->db->where(['user_id'=> $user_id, 'project_id'=>$project_id]);   
         $this->db->where('time_out is null');  
         return $this->db->get()->result_array();

        // $this->db->select('*');
        //  $this->db->from('checkmate_subby_timesheet');
        //  $this->db->where(['id'=> $entry_id,'user_id'=> $user_id, 'project_id'=>$project_id]);   
        //  $this->db->where('time_out is null');  
        //  return $this->db->get()->result_array();
     }
    
    
     
    public function getSubbyLocation($project_id,$user_id,$all_location = 0,$latest_loc=0 )
    {
        if($all_location == 0&&$latest_loc==0)
        {
            $this->db->select('*');
            $this->db->from('checkmate_project_location');
            $this->db->where(['user_id'=> $user_id, 'project_id'=>$project_id]);   
        }else{
            
            if($latest_loc == 1) {
            $this->db->select('*');
                $this->db->from('checkmate_project_location');
                $this->db->where([ 'project_id'=>$project_id]);
                $this->db->order_by('id', 'desc');
                $this->db->limit(1);
            

                ///

            }else{
                $this->db->select('*');
                $this->db->from('checkmate_project_location');
                $this->db->where([ 'project_id'=>$project_id]);   
            }
        }

        return $this->db->get()->result_array();
    }
    
    //  public function getSubbyTimeSheet($project_id,$user_id=0,$all_location = 0 )
    // {
    //         $SQL_Query = "SELECT if(time_out is not null ,TIMEDIFF(now(),time_out),TIMEDIFF(now(),time_in) ) as lastactivity,
    //         if(time_out is not null , 'OUT' ,'IN' ) as lastactivity_name , project_id,user_id,time_in,
    //         time_out FROM `checkmate_subby_timesheet` where id in ( SELECT max(id) FROM `checkmate_subby_timesheet` 
    //         where project_id = ".$project_id." group by project_id,user_id)";

    //         return  $this->db->query($SQL_Query)->result_array();
    //         /*$this->db->select('*');
    //         $this->db->from('checkmate_subby_timesheet');
    //         $this->db->where(['user_id'=> $user_id, 'project_id'=>$project_id]);  */
           
    //         return $this->db->get()->result_array();
    // }
    //News//

      public function getSubbyTimeSheet($project_id,$user_id=0,$page_num=0)
      {
       $res= array();
       $num_row = $this->db->query("Select intime.id, intime.project_id, intime.user_id, intime.time_in as time ,'IN' as lastactivity_name from checkmate_subby_timesheet intime  UNION 
       Select intime.id, intime.project_id, intime.user_id, intime.time_out as time ,'OUT' as lastactivity_name from checkmate_subby_timesheet intime WHERE project_id =   ".$project_id." ORDER BY time DESC ")->num_rows();
       $rowsperpage = 20;
       $totalpages = ceil($num_row / $rowsperpage);
       $res['number_page'] = $totalpages;
       if (isset($page_num) && is_numeric($page_num)) {
            $currentpage = $page_num;
            } else {
            $currentpage = 1;
        }
        if ($currentpage > $totalpages) {
            $currentpage = $totalpages;
        }
        if ($currentpage < 1) {
            $currentpage = 1;
        }
        $offset = ($currentpage - 1) * $rowsperpage;
       $SQL_Query = "Select intime.id, intime.user_id, intime.time_in as time ,'IN' as lastactivity_name from checkmate_subby_timesheet intime  UNION 

  Select intime.id,intime.user_id, intime.time_out as time ,'OUT' as lastactivity_name from checkmate_subby_timesheet intime WHERE project_id =   ".$project_id." ORDER BY time DESC LIMIT ".$offset.", ".$rowsperpage."";

             if($user_id>0){
 $SQL_Query = "  Select intime.id, intime.user_id, intime.time_in as time ,'IN' as lastactivity_name from checkmate_subby_timesheet intime  UNION 

  Select intime.id, intime.user_id, intime.time_out as time ,'OUT' as lastactivity_name from checkmate_subby_timesheet intime WHERE project_id =   ".$project_id." AND user_id = ".$user_id ." ORDER BY time DESC LIMIT ".$offset.", ".$rowsperpage."" ;


             }
            $res['result'] =  $this->db->query($SQL_Query)->result_array();
            
           return $res;
            //  $array=array();
            // foreach ($results as $key => $line) {
            //     # code...
            //     $array[]['in_rows']=$line;
            //     unset($line['time_in']);
            //     $array[]['out_rows']=$line;
            // }
            // print_r($results); die; 
          

            ///////////////

            /*$this->db->select('*');
            $this->db->from('checkmate_subby_timesheet');
            $this->db->where(['user_id'=> $user_id, 'project_id'=>$project_id]);  */
           
          //  return $this->db->get()->result_array();
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
    public function contractAdministratorList()
    {
        $this->db->select('id,ca_name, ca_phone_no');
        $this->db->from('checkmate_project_contracts_administrator');

        return $this->db->get()->result_array();
    }
    public function healthSafetyRepresentativeList()
    {
        $this->db->select('id,rep_name, rep_phone_no');
        $this->db->from('checkmate_project_health_safty_representative');

        return $this->db->get()->result_array();
    }
public function milestoneListbyuserId($user_id)
    {
        
        $this->db->select('checkmate_project.id, checkmate_project.project_name, checkmate_project.project_address, checkmate_project.project_lat, checkmate_project.project_lng, checkmate_project.project_site_map ');
        $this->db->from('checkmate_project');

        $this->db->join('checkmate_project_resources', 'checkmate_project_resources.project_id = checkmate_project.id','inner');
        // SELECT pr.project_id,p.* FROM `checkmate_project_resources` pr LEFT JOIN checkmate_project p ON pr.project_id=p.id WHERE 1 AND pr.resources_id=33 
        //$this->db->where('user_id', $user_id);
           $this->db->where('checkmate_project_resources.resources_id', $user_id);

    //     $where  = ' ( checkmate_project.project_status != 0 OR checkmate_project.project_status is null )';

    //     $this->db->where($where);
                // $this->db->order_by('checkmate_project.created', 'desc');

                $response = array();
                $response['projects'] = array();



        $user_projects =  $this->db->get()->result();
         //print_r($user_projects); die;
                $project_id_arr = array();
                for($i=0; $i<count($user_projects); $i++){
                    
                    $project_id = $user_projects[$i]->id;
             $sql=" SELECT SUM(log.daily_expenses) as projsum FROM checkmate_project_logs log INNER JOIN checkmate_project_milestone m ON m.id=log.milestone_id WHERE 1 AND m.project_id=".$project_id; //43::loc=186
                  $proj_sum=$this->db->query($sql)->row();
                 //  echo '===';
                   $project_total_expense=(!empty($proj_sum->projsum))?$proj_sum->projsum:0;
                  //print_r($proj_sum->projsum); die; 



                    /////////////////////////////
                   
                    $this->db->select('cpm.project_milestone_name,cpm.teamlead_id, cpm.project_milestone_estimate, cpm.budget, cpm.id, cpm.milestone_coordinates, log.further_details, log.next_step, log.daily_expenses');
                    $this->db->from('checkmate_project_milestone cpm');
                    $where  = '( cpm.project_id = '.$project_id.' )';
										
                    $this->db->join('checkmate_project_logs log', 'cpm.id = log.milestone_id ','left');
										$this->db->like('cpm.employee_id', $user_id);
                    $this->db->where($where);
                    $project_milestones = $this->db->get()->result();
                   // print_r($project_milestones); die;
                     $proj_mile_sum=0;
                     foreach ($project_milestones as $key => $line) {
                     //echo   $line->daily_expenses; die;

                      if(!empty($line->daily_expenses))
                     $proj_mile_sum+=$line->daily_expenses;
                         # code...
                     }
                    //print_r($project_milestones);
                    if(count($project_milestones) > 0) {
                        $ctr=$i;
                        
												if(!in_array($project_id, $project_id_arr)) {
													$project_arr = array();
													$project_id_arr[] = $project_id;
													$project_arr['project_name'] = $user_projects[$i]->project_name;
													$project_arr['project_id'] = $project_id;
													$project_arr['project_address'] = $user_projects[$i]->project_address;
													$project_arr['project_lat'] = $user_projects[$i]->project_lat;
													$project_arr['project_lng'] = $user_projects[$i]->project_lng;
													$project_arr['project_site_map'] = $user_projects[$i]->project_site_map;

													///////////////
													//$response['projects'][$ctr]['milestones_sum'] =$proj_mile_sum;
												//  $response['projects'][$ctr]['project_total_expenses'] =$project_total_expense;
													$project_arr['milestones'] = $project_milestones;
													$response['projects'][] = $project_arr;
												}


                        ///
                       // $arrr['projects'][]=$response1;


                    }
                }
                ///
                //print_r($arrr);die;



//print_r($response); die;
        return $response;

    }
    public function getSiteContracter($project_id)
    {
        $this->db->select('CU.id, CU.guid_id, CU.name, CU.email_id, CU.location_id, CU.status, CU.ip_address, CU.created, CU.avatar, CU.phone_no, CU.address, CU.user_type, CU.created_at, CU.updated_at, CU.employee_name, CU.client_name, CU.designation, CU.user_title');
        $this->db->from('checkmate_project CP');
        $this->db->join('checkmate_user CU', 'CU.id = CP.site_contractor_id');
        $this->db->where_in('CP.id', $project_id);
        return $this->db->get()->result_array();
    }

    public function getClient($project_id)
    {
        $this->db->select('CU.id, CU.guid_id, CU.name, CU.email_id, CU.location_id, CU.status, CU.ip_address, CU.created, CU.avatar, CU.phone_no, CU.address, CU.user_type, CU.created_at, CU.updated_at, CU.employee_name, CU.client_name, CU.designation, CU.user_title');
        $this->db->from('checkmate_project CP');
        $this->db->join('checkmate_user CU', 'CU.id = CP.client_id');
        $this->db->where_in('CP.id', $project_id);
        return $this->db->get()->result_array();
    }
    
    public function gethealthandsafty_rep($project_id)
    {
        $this->db->select('CU.id, CU.guid_id, CU.name, CU.email_id, CU.location_id, CU.status, CU.ip_address, CU.created, CU.avatar, CU.phone_no, CU.address, CU.user_type, CU.created_at, CU.updated_at, CU.employee_name, CU.client_name, CU.designation, CU.user_title');
        $this->db->from('checkmate_project CP');
        $this->db->join('checkmate_user CU', 'CU.id = CP.project_healthandsafty_rep_id');
        $this->db->where_in('CP.id', $project_id);
        return $this->db->get()->result_array();
    }
    
     public function getworkinghrs($project_id)
    {
        $this->db->select('WH.*');
        $this->db->from('checkmate_project CP');
        $this->db->join('ref_working_hours WH', 'WH.id = CP.project_da_working_hrs');
        $this->db->where_in('CP.id', $project_id);
        return $this->db->get()->result_array();
    }

    public function getworkinghrsDetails($refID)
    {
        $this->db->select('*');
        $this->db->from('ref_working_hours');
        $this->db->where_in('id', $refID);
        $record = $this->db->get()->row_array();
        $record = (!empty($record)) ? $record : array();
        return $record;
    }
    
    

    public function getDirector($project_id)
    {
    $this->db->select('CU.id, CU.guid_id, CU.name, CU.email_id, CU.location_id, CU.status, CU.ip_address, CU.created, CU.avatar, CU.phone_no, CU.address, CU.user_type, CU.created_at, CU.updated_at, CU.employee_name, CU.client_name, CU.designation, CU.user_title');
       
        $this->db->from('checkmate_project CP');
        $this->db->join('checkmate_user CU', 'CU.id = CP.project_director_id');
        $this->db->where_in('CP.id', $project_id);
        return $this->db->get()->result_array();
    }

    public function getManager($project_id)
    {
        $this->db->select('CU.id, CU.guid_id, CU.name, CU.email_id, CU.location_id, CU.status, CU.ip_address, CU.created, CU.avatar, CU.phone_no, CU.address, CU.user_type, CU.created_at, CU.updated_at, CU.employee_name, CU.client_name, CU.designation, CU.user_title');
        $this->db->from('checkmate_project CP');
        $this->db->join('checkmate_user CU', 'CU.id = CP.project_manager_id');
        $this->db->where_in('CP.id', $project_id);
        return $this->db->get()->result_array();
    }

    public function getproject_site_manager($project_id)
    {
         $this->db->select('CU.id, CU.guid_id, CU.name, CU.email_id, CU.location_id, CU.status, CU.ip_address, CU.created, CU.avatar, CU.phone_no, CU.address, CU.user_type, CU.created_at, CU.updated_at, CU.employee_name, CU.client_name, CU.designation, CU.user_title');
        $this->db->from('checkmate_project CP');
        $this->db->join('checkmate_user CU', 'CU.id = CP.project_site_manager_id');
        $this->db->where_in('CP.id', $project_id);
        return $this->db->get()->result_array();
    }
    
    public function getproject_subbie_manager($project_id)
    {
         $this->db->select('CU.id, CU.guid_id, CU.name, CU.email_id, CU.location_id, CU.status, CU.ip_address, CU.created, CU.avatar, CU.phone_no, CU.address, CU.user_type, CU.created_at, CU.updated_at, CU.employee_name, CU.client_name, CU.designation, CU.user_title');
        $this->db->from('checkmate_project CP');
        $this->db->join('checkmate_user CU', 'CU.id = CP.project_manager_proxy_id');
        $this->db->where_in('CP.id', $project_id);
        return $this->db->get()->result_array();
    }
    
    public function getproject_subbie_site_manager($project_id)
    {
         $this->db->select('CU.id, CU.guid_id, CU.name, CU.email_id, CU.location_id, CU.status, CU.ip_address, CU.created, CU.avatar, CU.phone_no, CU.address, CU.user_type, CU.created_at, CU.updated_at, CU.employee_name, CU.client_name, CU.designation, CU.user_title');
        $this->db->from('checkmate_project CP');
        $this->db->join('checkmate_user CU', 'CU.id = CP.project_site_manager_proxy_id');
        $this->db->where_in('CP.id', $project_id);
        return $this->db->get()->result_array();
    }
    
    public function getproject_first_aider($project_id)
    {
         $this->db->select('CU.id, CU.guid_id, CU.name, CU.email_id, CU.location_id, CU.status, CU.ip_address, CU.created, CU.avatar, CU.phone_no, CU.address, CU.user_type, CU.created_at, CU.updated_at, CU.employee_name, CU.client_name, CU.designation, CU.user_title');
        $this->db->from('checkmate_project CP');
        $this->db->join('checkmate_user CU', 'CU.id = CP.project_first_aider_id');
        $this->db->where_in('CP.id', $project_id);
        return $this->db->get()->result_array();
    }
    
    public function getSiteManager($project_id)
    {
         $this->db->select('CU.id, CU.guid_id, CU.name, CU.email_id, CU.location_id, CU.status, CU.ip_address, CU.created, CU.avatar, CU.phone_no, CU.address, CU.user_type, CU.created_at, CU.updated_at, CU.employee_name, CU.client_name, CU.designation, CU.user_title');
        $this->db->from('checkmate_project CP');
        $this->db->join('checkmate_user CU', 'CU.id = CP.project_site_manager_id');
        $this->db->where_in('CP.id', $project_id);
        return $this->db->get()->result_array();
    }

    public function getForeMan($project_id)
    {
         $this->db->select('CU.id, CU.guid_id, CU.name, CU.email_id, CU.location_id, CU.status, CU.ip_address, CU.created, CU.avatar, CU.phone_no, CU.address, CU.user_type, CU.created_at, CU.updated_at, CU.employee_name, CU.client_name, CU.designation, CU.user_title');
        $this->db->from('checkmate_project CP');
        $this->db->join('checkmate_user CU', 'CU.id = CP.project_foreman_id');
        $this->db->where_in('CP.id', $project_id);
        return $this->db->get()->result_array();
    }

    public function getSiteEngineer($project_id)
    {
        $this->db->select('CU.id, CU.guid_id, CU.name, CU.email_id, CU.location_id, CU.status, CU.ip_address, CU.created, CU.avatar, CU.phone_no, CU.address, CU.user_type, CU.created_at, CU.updated_at, CU.employee_name, CU.client_name, CU.designation, CU.user_title');
        $this->db->from('checkmate_project CP');
        $this->db->join('checkmate_user CU', 'CU.id = CP.project_site_eng_id');
        $this->db->where_in('CP.id', $project_id);
        return $this->db->get()->result_array();
    }
    public function getContractAdministrator($project_id)
    {
        $this->db->select('CU.id, CU.guid_id, CU.name, CU.email_id, CU.location_id, CU.status, CU.ip_address, CU.created, CU.avatar, CU.phone_no, CU.address, CU.user_type, CU.created_at, CU.updated_at, CU.employee_name, CU.client_name, CU.designation, CU.user_title');
        $this->db->from('checkmate_project CP');
        $this->db->join('checkmate_user CU', 'CU.id = CP.project_contract_administrator_id');
        $this->db->where_in('CP.id', $project_id);
        return $this->db->get()->result_array();
    }
    
    public function getprojectemployee_new($project_id)
    {
        $this->db->select('CU.id, CU.guid_id, CU.name, CU.email_id, CU.location_id, CU.status, CU.ip_address, CU.created, CU.avatar, CU.phone_no, CU.address, CU.user_type, CU.created_at, CU.updated_at, CU.employee_name, CU.client_name, CU.designation, CU.user_title');
        $this->db->from('checkmate_user CU');
        $this->db->join('checkmate_project_resources  CR', 'CU.id = CR.resources_id');
        $this->db->where_in('CR.project_id', $project_id);
        $this->db->group_by('CR.resources_id');
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
        
        if(!empty($fields)) $this->db->select(implode(',', $fields));
        $this->db->from('checkmate_project');
        $this->db->where('user_id', $user_id);
        $where  = ' ( project_status != 0 OR project_status is null )';
        $this->db->where($where);
   
        if ($type == "RESULT_LATEST_PROJECT") {
            $this->db->order_by('created', 'desc');
            $this->db->limit(1);
            return $this->db->get()->result();
        }
        
        if ($type == "RESULT_ALL_PROJECT") {
            $this->db->order_by('created', 'desc');
            return $this->db->get()->result();
        }
            
        return $this->db->get()->result();
                

    }


    public function getProjectByUserAndId($user_id, $project_id, $fields = null)
    {
        if(!empty($fields)) $this->db->select(implode(',', $fields));
        $this->db->from('checkmate_project');
        $where  = '  ( project_status !=0 OR project_status is null )';
        $this->db->where($where);
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
        $this->db->from('checkmate_equipment');
        return $this->db->get()->result_array();
    }

    /* Team Data */

    public function teamByDesignationList($designation, $search = null)
    {
        $this->db->select('T.id, T.client_name, T.employee_name, T.name, T.email_id, T.phone_no, T.address,T.user_type,T.designation, T.avatar');
        $this->db->from('checkmate_user T');
        $this->db->join('ref_designation D', 'T.designation = D.id', 'left');
        $this->db->where('T.designation', $designation);
        if(!empty($search)) $this->db->where("T.employee_name LIKE '%$search%'");
        return $this->db->get()->result_array();
    }

    /* Addign Team to Project */

    public function addTeamToProject($data)
    {
        $this->db->insert('checkmate_project_team', $data);
        return $this->db->insert_id();
    }
    
    public function updateProjectlocation($project_id,$user_id,$user_x,$user_y)
    {
        $query = "Insert into checkmate_project_location set project_id = ".$project_id.",user_id =".$user_id." ,user_x = ".$user_x.",user_y =  ".$user_y." 
        ON DUPLICATE KEY UPDATE user_x = ".$user_x.",user_y =  ".$user_y;
        return $this->db->query($query);
    }
    
    public function getProjectlocation($project_id,$user_id,$all_location = 0)
    {
        if($all_location == '1' )
        {
            $this->db->from('checkmate_project_location');
            $this->db->where(['project_id'=>$project_id]);        
            return $this->db->get()->result();
        }
        else
        {
            $this->db->from('checkmate_project_location');
            $this->db->where(['user_id'=> $user_id, 'project_id'=>$project_id]);        
            return $this->db->get()->result();
        }
            
       
    }
    
    


    /* Assign Equipment to Project */

    public function addEquipmentToProject($data)
    {
        $this->db->insert('checkmate_project_equipment', $data);
        return $this->db->insert_id();
    }
    /* Assign People to Project */

    public function addResourceToProject($data)
    {
        $this->db->insert('checkmate_project_resources', $data);
        return $this->db->insert_id();
    }
    public function insertInvite($data)
    {
        $this->db->insert('checkmate_user_rabbit', $data);
        return $this->db->insert_id();
    }
    public function insertResources($data)
    {
        $this->db->insert('checkmate_resources', $data);
        return $this->db->insert_id();
    }
    public function getInviteCode($invite_code)
    {
        $this->db->select('PE.*')->from('checkmate_user_rabbit PE');
        $this->db->join('checkmate_user E' , 'E.id = PE.team_member_id'); 
        $this->db->where('PE.code', $invite_code);
        return $this->db->get()->row();
      
    }
    public function updateInviteCode($invite_code,$data)
    {
            $this->db->where('code', $invite_code);
            $this->db->update('checkmate_user_rabbit', $data);
        return $this->db->affected_rows();
    }
    public function updateResource($team_member_id, $data)
    {
        $this->db->where('id', $team_member_id);
        $this->db->update('checkmate_user', $data);
         $this->db->affected_rows();
        return $this->db->insert_id();
    }

    public function getOwnerName($userID)
    {
        $this->db->select('name')->from('checkmate_user')->where('id',$userID);
        $record = $this->db->get()->row_array();
        return $record['name'];
    }

    public function addTeamMember($data)
    {
        $this->db->insert('checkmate_user', $data);
        return $this->db->insert_id();
    }
    public function getProjectResources($project_id, $offset=null, $limit=null)
    {
        $this->db->select('PE.id, E.name, E.avatar')->from('checkmate_project_team PE');
        $this->db->join('checkmate_user E' , 'E.id = PE.resource_id'); 
        $this->db->where('PE.project_id', $project_id);
        if(!empty($offset) || !empty($limit)) $this->db->limit($limit, $offset);
        return $this->db->get()->result_array();
    }
    public function getProjectEquipments($project_id, $offset=null, $limit=null)
    {
        $this->db->select('E.id, E.equipment_name, E.equipment_image')->from('checkmate_project_equipment PE');
        $this->db->join('checkmate_equipment E', 'E.id = PE.equipment_id'); 
        $this->db->where('PE.project_id', $project_id);
        if(!empty($offset) || !empty($limit)) $this->db->limit($limit, $offset);
        return $this->db->get()->result_array();
    }

    public function getProjectEmployee($project_id, $offset=null, $limit=null)
    {
        $this->db->select('CT.id, CT.name, CT.avatar');
        $this->db->from('checkmate_project_team PT');
        $this->db->join('checkmate_user CT', 'CT.id = PT.employee_id'); 
        $this->db->where('PT.project_id', $project_id);
        
       // if(!empty($offset) || !empty($limit)) $this->db->limit($limit, $offset);
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

    public function milestoneUserList($project_id)
    {
        $this->db->select('*')->where('project_id', $project_id)->from('checkmate_project_milestone');
        return $this->db->get()->result_array();
    } 
    public function milestoneTeamUserList($user_id)
    {
        $this->db->select('*')->like('employee_id', $user_id)->from('checkmate_project_milestone');
        return $this->db->get()->result_array();
    }
    
     public function insertFirstAider($data)
    {
        $this->db->insert('checkmate_first_aider', $data);
        return $this->db->insert_id();
    }
    public function insertHealthSafty($data)
    {
        $this->db->insert('checkmate_health_safty', $data);
        return $this->db->insert_id();
    }
     public function addResource($data)
    {
        $this->db->insert('checkmate_project_resources', $data);
        return $this->db->insert_id();
    }
    public function listResources($offset=null, $limit=null)
    {
        $this->db->select('*')->from('checkmate_project_resources');
        if(!empty($offset) || !empty($limit)) $this->db->limit($limit, $offset);
        return $this->db->get()->result_array();
    }
    public function subTaskList($user_id=null, $project_id=null, $task_id=null) 
    {
        $this->db->select('*,RST.id as id');
        $this->db->from('ref_sub_task RST');
        $this->db->join('ref_task RT', 'RT.id = RST.task_id','left'); 

        $this->db->where('RST.project_id', $project_id);
        $this->db->where('RT.user_id', $user_id);

        $this->db->where('RST.task_id', $task_id); //echo $this->db->last_query();
        return $this->db->get()->result_array();
    }
    public function taskList($user_id=null, $project_id=null,$milestone_id) 
    {
        $this->db->select('*');
        $this->db->from('ref_task');
        $this->db->where('user_id', $user_id);
        $this->db->where('milestone_id', $milestone_id);
        $this->db->where('project_id', $project_id);
        return $this->db->get()->result_array();
    }
    public function updateMilestone($milestone_id,$data)
    {
            $this->db->where('id', $milestone_id);
            $this->db->update('checkmate_project_milestone', $data);
            return $this->db->affected_rows();
    }
    
    public function isExistMilestoneEmpId($milestone_id,$employee_id)
    {

        $sql = "SELECT *  FROM `checkmate_project_milestone` WHERE `id` = $milestone_id AND FIND_IN_SET($employee_id,employee_id)";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
                return $query->result_array();
              } else {
                return false;
        }


    }
    
    public function getMilestone($user_id,$id)
    {
        $this->db->select('PE.*')->from('checkmate_project_milestone PE');
        $this->db->where('PE.id', $id);
         $this->db->where('PE.employee_id', $user_id);
         $this->db->where( 'PE.status <> ' , '1');
        return $this->db->get()->row();
      
    }
    
    public function getMilestone_project($project_id,$id)
    {
        $this->db->select('PE.*')->from('checkmate_project_milestone PE');
        $this->db->where('PE.project_id', $project_id);
        $where  = ' ( PE.status != 0 OR PE.status is null )';
        $this->db->where(  $where);
        return $this->db->get()->row();
      
    }
    
    public function getMilestonebyproject($project_id)
    {
        $this->db->select('PE.*')->from('checkmate_project_milestone PE');
        $this->db->where('PE.project_id', $project_id);
        $where  = ' ( PE.status != 0 OR PE.status is null )';
        $this->db->where(  $where);
        return $this->db->get()->result_array();
      
    }
    
    public function deleteProject($project_id)
    {
        return $this->db->delete('checkmate_project', array('id' => $id));
    }
    
    public function milestoneListbyuser($user_id)
    {
        $this->db->select('cu.*') ;
        $this->db->from('checkmate_user cu') ;
        $this->db->where('cu.id',$user_id) ;
        $this->db->where('cu.user_type','0') ;
        $records = $this->db->get()->num_rows();
        
        $this->db->select('cpm.*');
        $this->db->from('checkmate_project_milestone cpm');
        // $this->db->join('checkmate_user cu','cu.id = cpm.teamlead_id','inner');

        if($records <= 0)
        {
            $where  = " cpm.teamlead_id like '%".$user_id."%' OR cpm.employee_id like '%".$user_id."%' ";
            $this->db->where($where);
        }
        $records = $this->db->get()->result_array();

        foreach ($records as $recordsKey => $recordsValue)
        {
            $records[$recordsKey]['teamLeadInfo'] = $this->getUserInfo($recordsValue['teamlead_id']);

            $teamInfo = array();

            $employees = (!empty($recordsValue['employee_id'])) ? explode(",", $recordsValue['employee_id']) : array();

            foreach ($employees as $employeesKey => $employeesValue)
            {
                $teamInfo[$employeesKey] = $this->getUserInfo($employeesValue);
            }

            $records[$recordsKey]['teamMemberInfo'] = $teamInfo;

            $equipmentInfo = array();

            $equipments = (!empty($recordsValue['equipment_id'])) ? explode(",", $recordsValue['equipment_id']) : array();

            foreach ($equipments as $equipmentsKey => $equipmentsValue)
            {
                $equipmentInfo[$equipmentsKey] = $this->getEquipmentInfo($equipmentsValue);
            }

            $records[$recordsKey]['equipmentInfo'] = $equipmentInfo;

        }

        return $records;

    }

    public function getUserInfo($userID)
    {
        $this->db->select('*');
        $this->db->from('checkmate_user');
        $this->db->where('id',$userID);
        $record = $this->db->get()->row_array();
        $record = (!empty($record)) ? $record : array();
        return $record;
    }

    public function getEquipmentInfo($eqipmentID)
    {
        $this->db->select('*');
        $this->db->from('checkmate_project_equipment');
        $this->db->where('id',$eqipmentID);
        $record = $this->db->get()->row_array();
        $record = (!empty($record)) ? $record : array();
        return $record;
    }
function get_rows_c1($table_name,$col1,$val1)
{ 
  $this->db->where($col1,$val1);
  $query = $this->db->get($table_name);
  return $query->result_array();
}

function get_rows_c2($table_name,$col1,$val1,$col2,$val2)
{ 
  $this->db->where($col1,$val1);
  $this->db->where($col2,$val2);
  $query = $this->db->get($table_name);
  return $query->result_array();
}
   
}

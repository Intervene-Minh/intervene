<?php 
if (!defined('BASEPATH')) {

    exit('No direct script access allowed');
}

    require_once APPPATH .'libraries/REST_Controller.php';
    error_reporting(1);

    class NewRowController extends REST_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Project');
        $this->load->model('Login');
        
    }


 public function CreateNewRowSessions_post(){


        $input=[];
        $default_board='Newrow';
        $system_warnings=[];   
        $Tutoring_client=$this->post('Tutoring_client_id');  
        $drhomework_ref_id=$this->post('drhomework_ref_id');  // ID - client side : TutrongMainID
        $input['Tutoring_client_id']     =$Tutoring_client;
        $input['drhomework_ref_id']     = $drhomework_ref_id;  //is_drhomework.
        $input['is_drhomework'] ='yes';
        $input['board_type'] =$default_board;
        $input['curr_active_board']=$default_board;  //'groupworld'; //Deafult board
        $input['type']='drhomework'; // Default
        $input['activity_start_time'] =$this->post('ses_start_time');
        $input['ses_start_time'] =$this->post('ses_start_time');
        $input['ses_end_time'] = $this->post('ses_end_time');  //'2019-09-04 00:00:00'; 
        $input['start_date'] =date('Y-m-d', strtotime($this->post('ses_start_time')) );
        $input['school_id'] =0; 
        $input['lesson_id']=9; 
        $input['quiz_id']=9;
        $input['teacher_id']=0; #NotUsedForDrhomework
        $input['grade_id']=$this->post('grade_id');
        $input['created_date']=$this->post('ses_start_time');
        $input['district_id']=$this->post('district_id');   
        # Add special_notes notes 
        $input['special_notes']=($this->post('special_notes'))?$this->post('special_notes'):'NA';
        //Add Session 
        $result = $this->db->insert("int_schools_x_sessions_log",$input);
        $LastSessionId=  $this->db->insert_id();
        #+add Session detail - info. 
        $arr_send=[];
        $arr_send['session_ref_id']=$LastSessionId; // Return ID from--intervne
        $arr_send['Tutoring_client_id']=$Tutoring_client;
        $arr_send['drhomework_ses_id']=$drhomework_ref_id;//API
        $arr_send['dr_parent_id']=$this->post('dr_parent_id'); # API
        $arr_send['session_stu_data']=$this->post('student_info');    // JSON strings.
        $arr_send['dr_grade_id']=$this->post('dr_grade_id'); 
        $arr_send['intervene_grade_id']=$this->post('grade_id'); 
        $SessionDetail = $this->db->insert("dr_tutoring_info",$arr_send);
        if($result==1)
        {
            $success='Tutoring Sessions Created! ';
            $res['status'] = true;
            $res['response']['success']=$success;
            $res['response']['tutorgigs_class_id']=$LastSessionId;
            $this->response($res);

        }
        else
        {

                $system_warnings[]='error while creating Tutoring session! ';
        }


        if(!empty($system_warnings))
        {
            $res['status'] = false;
            $res['response']['error']= implode(', ', $system_warnings) ;
            $this->response($res);

        }
}

/* Add New Room Details in tutor gig*/

public function CreateRoom_post(){




        $input=[];
        $input['newrow_room_id']     =   $this->post('newrow_room_id');
        $input['ses_tutoring_id']     =   $this->post('ses_tutoring_id');
        $input['tp_id']     =   $this->post('ses_tutoring_id');
        $input['name']                =   $this->post('name');
        $input['description']         =   $this->post('name');
        $result = $this->db->insert("newrow_rooms",$input);
        
        if($result==1)
        {
            
            $res['status'] = 'Room Add SuccessFully!';
            $this->response($res);
        }
        else
        {

                $res[]='error while add Room! ';
                $this->response($res);
        }
    }



}
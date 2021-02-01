<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Welcome extends CI_Controller {
	 public function __construct()
    {
        parent::__construct();
        $this->load->model('Rabbit');
          $this->load->model('Project');
    }
    public function activation()
    {
	    $type = $this->input->get('type');
	    $id = $this->input->get('id');
		switch(intval($type)){
			case 1:
			$result = $this->Project->getInviteCode($id); 
			if(!empty($result)){
				$update_user_data = array('status' => '1');
				$this->Rabbit->updateUserData($result->team_member_id, $update_user_data);
				 $update_user_data = array(
                'status' => 1,
                'updated_at' => getCurrDateTime(),
			);

			$this->Project->updateInviteCode($id,$update_user_data);
			echo  "Your account has been activated, please login to continue.";
			}
			break;
			case 2:
			$result = $this->Project->getUserDataByActiveToken($id);
			if(!empty($result)){
				$update_user_data = array('status' => '1');
				$this->Login->updateUserData($result->team_member_id, $update_user_data);
				 $update_user_data = array(
                'status' => 1,
                'updated_at' => getCurrDateTime(),
			);

			$this->Project->updateInviteCode($id,$update_user_data);
			echo  "Your account has been activated, please login to continue.";
			}
			
			break;
		}
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/Welcome.php */
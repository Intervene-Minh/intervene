<?php
include('config/connection.php'); 
session_start();ob_start();
// Loign
@extract($_POST);
@extract($_GET);

  if(!isset($_GET['view']) ){
 exit('page not found!');
  }
  


  if(isset($_GET['view']) ){
    $getid=$_GET['view'];
  // view=108
    // email password
    
    //print_r($_POST); die;  
        $user_name = $_POST['email'];
        $password = $_POST['password'];
        $role = $_POST['role'];
         // Only Selected Role as Admin 
        
       // if($role =="administrator"||$role =="teacher"){
           $email = stripslashes($user_name);
    // $password = stripslashes($password);
    $email = mysql_real_escape_string($email);
    // $password = mysql_real_escape_string($password);
    $md5password = md5($password);
    $role='teacher';
                
                
        if($role =="teacher"){

           $error = "Can Not login now";  //die; 
          //$query = mysql_query("SELECT * FROM gig_teachers WHERE password='$md5password' AND email='$email' LIMIT 1", $link);

           $query = mysql_query("SELECT * FROM gig_teachers WHERE id=".$getid);
            //$data= mysql_fetch_assoc($query);
            //print_r($data); die;

           $rows = mysql_num_rows($query);
           if ($rows == 1) {
               $data= mysql_fetch_assoc($query);
                if($data['status']==2){
                    $error='Your account suspended, Please contact service provider. !';
                   // suspended 
                }else{
                
      $lastest_login = date('Y-m-d H:i:s');
                     $_SESSION['ses_teacher_id']=$data['id']; // ses_teacher_id# login_id
                    $_SESSION['ses_curr_state_url']=($data['all_state']=='no')?$data['all_state_url']:'home.php';  // next default: application.php
                      $_SESSION['ses_total_pass']=$data['total_pass_quiz'];  // no
                   
                   $_SESSION['ses_access_website']=$data['all_state'];  //yes or No
      $_SESSION['login_user']=(!empty($data['f_name']))?$data['f_name']:$data['email']; // Initializing Session
      $_SESSION['login_mail']=$data['email'];
      $_SESSION['login_role']=1;
                        $error = "Login success..Tutor";
                       header("location:./dashboard/index.php");exit;
                }
      //$_SESSION['login_status']=$row['status'];   
              //1 
           }else $error= "Invalid Email&Password Combination ";


           ////Display messgae
           echo $error; die; 
          
        }
        
        
        
        
        
      
  }
  //else echo 'Page not found!'; die;

?>
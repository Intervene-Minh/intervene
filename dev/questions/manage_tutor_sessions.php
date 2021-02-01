<?php

/***
 * Schools > Slot by Admin
 * @ give no. of Tutor session to int Schools(Principle)
 * @ Slot ==>Tutor Session
 * 
 * 
 * **/
$error = '';
$author = 1;
$datetm = date('Y-m-d H:i:s');

include("header.php");
if ($_SESSION['login_role'] != 0) { //not admin
    header('Location: folder.php');
    exit;
}

//////////////////////
if(isset($_GET['assesment_id'])){
$qry = mysql_query('SELECT * FROM assessments WHERE id = ' . $_GET['assesment_id']);
$assesment_result = mysql_fetch_assoc($qry); 
$qry2 = mysql_query('SELECT * FROM assessments WHERE id = ' . $_GET['assesment_id']);
$assesment_det= mysql_fetch_assoc($qry2); 
 }


 
@extract($_POST);
/////////Update Assesment///////////
 // list of  Choose District: 
 // List of Choose ::  Schools:  Shows
 $assementArr=array(); // for search
 $is_serach=0;// no seach
 $today= date("Y-m-d H:i:s");
 
   $is_serach=1;
 
 
 // casee2
if (isset($_POST['get_submit'])) {
 // print_r($_POST); die; 
   // $adm_id=(!empty($_SESSION['login_id']))?$_SESSION['login_id']:1;
    $adm_id=$_SESSION['login_id'];
   $schools= mysql_fetch_assoc(mysql_query(" SELECT avaiable_slots FROM schools WHERE SchoolId='".$_GET['school']."' ")); 
      
      $avl_tut_ses=(($schools['avaiable_slots']>0))?$schools['avaiable_slots']:0;
      $avl_tut_ses=intval($avl_tut_ses)+intval($_POST[tot_slots]);
   // school_id tot_slots created_date created_by
    $qqq=" INSERT INTO int_slots_purchasehistory SET school_id='".$_GET['school']."',"
            . "created_date='$today',tot_slots='$_POST[tot_slots]',created_by='$adm_id' ";  
     $ff=mysql_query($qqq)or die(mysql_error());
      // add in Schools available Tut ses
     $q=" UPDATE schools SET avaiable_slots='".$avl_tut_ses."' WHERE SchoolId='".$_GET['school']."' ";
    $a=mysql_query($q); //schools tut sesson Updated
     
     //$error = 'Tut sesson added ... ';  
    $_POST['tot_slots']=1;
 } 
 //Interventions Add//


if (isset($_POST['get_submit_2'])) { // avaiable_interventions
    $adm_id=$_SESSION['login_id'];
   $schools= mysql_fetch_assoc(mysql_query(" SELECT avaiable_interventions FROM schools WHERE SchoolId='".$_GET['school']."' ")); 
      
    $avl_tut_ses=(($schools['avaiable_interventions']>0))?$schools['avaiable_interventions']:0;

      $avl_tut_ses=intval($avl_tut_ses)+intval($_POST[tot_intervention]);
   // school_id, tpye:: homework, Intervention
     $query=" INSERT INTO int_slots_purchasehistory SET type='intervention',school_id='".$_GET['school']."',"
            . "created_date='$today',tot_slots='$_POST[tot_intervention]',created_by='$adm_id' ";  // die;

     $success=mysql_query($query)or die(mysql_error());

      // add in Schools available Tut ses
     $update=" UPDATE schools SET avaiable_interventions='".$avl_tut_ses."' WHERE SchoolId='".$_GET['school']."' ";
    $a=mysql_query($update); //schools tut sesson Updated
     
     //$error = 'Tut sesson added ... ';  
    $_POST['tot_slots']=1;
 } 




 
 
 

//////////////////////////////
// $questions_list = array();
// if ($_GET['id'] > 0 && $_GET['action'] == 'edit') {
//     $qry = mysql_query('SELECT * FROM assessments WHERE id = ' . $_GET['id']);
//     $assesment_result = mysql_fetch_assoc($qry);
//     if ($_GET['cat'] > 0) {
//         unset($assesment_result['grade_id']);
//     }
//     $qn_query = mysql_query('SELECT qn.name, qn.id FROM questions qn '
//             . 'LEFT JOIN assessments_x_questions axq ON axq.qn_id = qn.id WHERE '
//             . 'axq.assesment_id = \'' . $_GET['id'] . '\' ');

//     while ($question = mysql_fetch_assoc($qn_query)) {
//         $questions_list[] = $question['id'];
//     }
// }

// if ($_SESSION['assess_id'] > 0) {
//     $qry = mysql_query('SELECT * FROM assessments WHERE id = ' . $_SESSION['assess_id']);
//     $assesment_result = mysql_fetch_assoc($qry);
//     $a_id = $_SESSION['assess_id'];
// }


////////////////
 $a_id=$_GET['assesment_id']; // Edit Assement
 // $a_id=
$district_level_res = mysql_query('SELECT entity_id FROM assessments_access WHERE assessment_id = \'' . $a_id . '\' AND access_level = "district" ');
$assessment_district = array();
while ($district = mysql_fetch_assoc($district_level_res)) {
    $assessment_district[] = $district['entity_id'];
}
if(count($_POST['master_school']) > 0) {
$assessment_school = $_POST['master_school'];
}
$district_qry = mysql_query('SELECT * from loc_district ORDER BY district_name ASC ');
?>
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">

<!-- JS -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {

        $('#district').chosen();

        $('#district').change(function () {
            district = $(this).val();

            $('#district_school').html('Loading ...');
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {district: district, action: 'get_multiple_schools', school_id: '<?php print implode(',', $assessment_school); ?>'},
                success: function (response) {
                    $('#district_school').html(response);
                    $('#d_school').chosen();
                },
                async: false
            });
        });
        $('#district').change();
    });
</script>
<div id="main" class="clear fullwidth">
    <div class="container">
        <div class="row">
            <div id="sidebar" class="col-md-4">
                <?php include("sidebar.php"); ?>
            </div>		<!-- /#sidebar -->
            <div id="content" class="col-md-8">
                
                <div id="single_question" class="content_wrap">
                    <div class="ct_heading clear">
                        <h3> <i class="fa fa-plus"> </i>
                          Add Tutor sessions Number</h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
                        <form name="form_passage" id="form_passage" method="post" action="" enctype="multipart/form-data">
                            
                            
                            
                            <div class="col-md-12">
                                <p title="Time Tutor session Number">
                                    <label for="">No. of Homework Help sessions</label>
      <input type="number"  style=" width:20%" name="tot_slots" class="required textbox" 
             value="<?=(isset($_POST['tot_slots']))?$_POST['tot_slots']:1;?>" min="1"  required />
      
      <input type="submit" name="get_submit" style=" margin-top: 10px;"
                  id="lesson_submit" class="form_button submit_button" value="Add Now" />
      
                                </p></div>

                                 <div class="col-md-12">
                                <p title="Time Tutor session Number">
                                    <label for="lesson_name">No. of Intervention sessions</label>
      <input type="number"  style=" width:20%" name="tot_intervention" class="required textbox" 
             value="<?=(isset($_POST['tot_intervention']))?$_POST['tot_intervention']:1;?>" min="1"  required />
      
      <input type="submit" name="get_submit_2" style=" margin-top: 10px;"
                  id="lesson_submit" class="form_button submit_button" value="Add Now" />
      
                                </p></div>

                           
                          
                            
                       
                            
                            
                            
                            

                        </form>
                        <div class="clearnone">&nbsp;</div>
                    </div>		<!-- /.ct_display -->
                </div> 
                <!--Search Pan--->
                
                 <div class="clearnone">&nbsp;</div> <br/>
                 
                <!-- List of Assesments---->
                
                <?php 
                $getid=$_GET['school']; // intSchols
           $schools= mysql_fetch_assoc(mysql_query(" SELECT * FROM schools WHERE SchoolId='".$getid."' ")); 
             //  if($schools['avaiable_slots']>0){   ///XXXX
           ?>
                
                
                <form id="form-manager" class="content_wrap" action=""  method="post">
                    <?php  
                  
                  /////////
                     $change=' ORDER BY created_by ASC ';
                   if(isset($_GET['school']))   
                     $change=' WHERE school_id='.$_GET['school'].' ORDER BY created_date DESC';
                       
                    
               $childs = mysql_query('SELECT * FROM int_slots_purchasehistory '.$change);         
                      $tot_records=mysql_num_rows($childs) ;
                //   Schools data .. 
                               
                      
                        ?> 
                   
                    
					<div class="ct_heading clear">
						<h3>(<?=$tot_records?>) Log of Tutor Sessions,
                                                    &nbsp;&nbsp;To School-
                                                    <strong>-<?=$schools['SchoolName']?></strong> </h3>
						<!-- <ul>
				
							
                                                        <li>
								<button id="delete-user" type="submit" name="delete-user"><span class="glyphicon glyphicon-trash"></span></button>
							</li>
						</ul>
 -->

					</div>		<!-- /.ct_heading -->
					<div class="clear">
                                            
                                           
                                            
				<table class="table-manager-user col-md-12">
								<colgroup>
									
									<col width="360">
									
									<col width="100">
                                                                        <col width="100">
									
									
								</colgroup>
								<tbody><tr>
									
									<th>Tot. sessions/School</th>
									<th>on Date</th>
                                                                        <th>Create By</th>
                                                                        
                                                                       
								</tr>
																		
                                     <?php 
                if( mysql_num_rows($childs) > 0 ) {
			
		                 $i = 1;
			while( $row= mysql_fetch_assoc($childs) ) {
                            
                           # record det
    $schools= mysql_fetch_assoc(mysql_query(" SELECT * FROM schools WHERE SchoolId='".$row['school_id']."' "));  
    $admin= mysql_fetch_assoc(mysql_query(" SELECT * FROM users WHERE id='".$row['created_by']."' "));
         $row['type']=($row['type']=="homework")?"Homework Help":"Intervention";


                            ?>       
                         <tr> 
                                <td>
                               <strong  class="text-success"><?=$row['tot_slots']."-&nbsp;Tutor sessions"?></strong>
                              
                               <?php //=$schools['SchoolName']?> <br/>
                          <strong>Type-</strong><?=$row['type']?>
                               </td>
                                                  

                                       <td>
                                   <?=$row['created_date']?>
                                     
                                   </td>

                                    <td> 
                                    <strong>By-</strong> 
                                    <?=$admin['first_name']?>
                                    </td>
                                                         
                                                                  
                                                                  
                                                         
                                                         
                                                                    
                                                                    
                                                                    
                                                                </tr>      
                                                                
                                                             
                                                              
                                    <?php
                        $i++;}
                                       }
                                                                
                                                                ?>
                                                                
                                                                </tbody></table>
												<div class="clearnone">&nbsp;</div>
					</div>		<!-- /.ct_display -->
                                        <input id="arr-user" name="arr-user" value="" type="hidden">
				</form>
                
                     <!----Assessment List--->
                
                
                
                
                
            </div>		<!-- /#content -->
            <div class="clearnone">&nbsp;</div>
        </div>
    </div>
</div>		<!-- /#header -->

<script type="text/javascript">
<?php 
  if(isset($_GET['ac']))$error="Assesment Saved Succesfully. !";

if ($error != '') echo "alert('{$error}')"; ?>
</script>
<style>
    .chosen-container-multi .chosen-choices li.search-field input[type="text"]{height:30px; }
</style>
<?php include("footer.php"); ?>

 


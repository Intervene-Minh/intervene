<?php
/*  process_roster.php ==>process_
 * Rosters list::lessonwise
 * //  print_r(array_unique($a));
  @Reults CSV by School
 * **/
//echo 'Result uplaoded';


$error = 'rosters_list';
$author = 1;
$datetm = date('Y-m-d H:i:s');

include("header.php");

// print_r($_SESSION); 
 
if ($_SESSION['login_role'] != 0) { //not admin
    header('Location: folder.php');
    exit;
}

$error = '';


//if (count($_SESSION['qn_list']) <= 0) {
 //   $error = 'Please select questions to create assesment!';
//}



 

/////////Update Assesment///////////
 // list of  Choose District: 
 // List of Choose ::  Schools:  Shows
 $assementArr=array(); // for search
 $is_serach=0;// no seach
 
 // 
 if(isset($_GET['del_id'])){

  
  // school_provider_1_tab
    $getid=$_GET['del_id'];
    $roster=mysql_fetch_assoc(mysql_query("SELECT * FROM school_provider_1_tab WHERE id=".$getid));
  if(unlink('uploads/results/'.$roster['filename'])){
  
    $del_roster=mysql_query(" DELETE FROM school_provider_1_tab WHERE id=".$getid);
  }

 // $del_roster=mysql_query(" DELETE FROM school_master_rosters WHERE id=".$getid);
    //unlink('uploads/rosters/'.$roster['filename']); 
   // echo 'file delter'; die; 

    // delete class and students 
   // $d1=mysql_query(" DELETE FROM students WHERE roster_id=".$getid);
    $get_ref_id=$getid;
    $Delete1=mysql_query(" DELETE FROM `teacher_x_assesments_x_students` WHERE ref_id='$get_ref_id' ");

  $Delete2=mysql_query(" DELETE FROM `students_x_assesments` WHERE ref_id='$get_ref_id' ");


    //$error = '';
    echo "<script>alert('Deleted');location.href='".$_SERVER['PHP_SELF']."';</script>";
 }
 
 
 
 
 

//////////////////////////////
$questions_list = array();
// if ($_GET['id'] > 0 && $_GET['action'] == 'edit') {
//     $qry = mysql_query('SELECT * FROM int_quiz WHERE id = ' . $_GET['id']);
//     $assesment_result = mysql_fetch_assoc($qry);
//     if ($_GET['cat'] > 0) {
//         unset($assesment_result['grade_id']);
//     }
//     $qn_query = mysql_query('SELECT qn.name, qn.id FROM questions qn '
//             . 'LEFT JOIN int_quiz_x_questions axq ON axq.qn_id = qn.id WHERE '
//             . 'axq.assesment_id = \'' . $_GET['id'] . '\' ');

//     while ($question = mysql_fetch_assoc($qn_query)) {
//         $questions_list[] = $question['id'];
//     }
// }






////////////////
 $a_id=$_GET['assesment_id']; // Edit Assement
 // $a_id=
$district_level_res = mysql_query('SELECT entity_id FROM int_quiz_access WHERE quiz_id= \'' . $a_id . '\' AND access_level = "district" ');
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
                
                <div id="single_question" class="content_wrap" style=" display: none;">
                    <div class="ct_heading clear">
                        <h3> <i class="fa fa-search"> </i>
                          Search Filter</h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
                        <form name="form_passage" id="form_passage" method="post" action="" enctype="multipart/form-data">
                            <h4 title="Edit Quiz here">Choose Search Fields:
                            
                            <?php  # var_dump($assementArr) ?>
                            
                            
                            </h4>
                            
                            
                            <div class="col-md-12">
                                <p>
                                    <label for="grade_level_name">Assessment Name</label>
      <input type="text"  style=" width: 100%" name="objective_name" class="required textbox" 
             value="<?=(isset($_POST['objective_name']))?$_POST['objective_name']:NULL;?>" />
                                </p></div>

                           
                            <div class="col-md-12">
                            <!-----add_question_wrap clear fullwidth---->
                            <div class="">
                                <p>
                                    <label for="grade_level_name">Choose District:</label><br />
                                    <select name="district[]" id="district" multiple="true">
                                        <?php while ($district = mysql_fetch_assoc($district_qry)) { ?>
                                   <option <?php if (in_array($district['id'], $_POST['district'])) { ?> selected="selected" <?php } ?> value="<?php print $district['id']; ?>"><?php print $district['district_name']; ?></option>

<?php } ?>
                                    </select>

                                </p>
                            </div> </div>
                            
                               <div class="col-md-12">
                            <div class="">
                                <div id="district_schools">

                                    <label for="grade_level_name">Choose Schools:</label>
                                    <div id="district_school" style=" width: 90%;">
                                        Select District to choose schools.
                                    </div>

                                </div>
                            </div> </div>
                            
                            
                            
                           
                            
                            <p style=" margin-top: 10px;text-align: center;">
           <input type="submit" name="qz_submit" style=" margin-top: 10px;"
                  id="lesson_submit" class="form_button submit_button" value="Search" />
                                <?php if ($_GET['id'] > 0) { ?>
                                    <input type="hidden" name="id" value="<?php print $_GET['id']; ?>" >
                                <?php } ?>
                                <?php if ($_SESSION['assess_id'] > 0) { ?>
                                    <input type="hidden" name="id" value="<?php print $_SESSION['assess_id']; ?>" >
<?php } ?>

                            </p>

                        </form>
                        <div class="clearnone">&nbsp;</div>
                    </div>		<!-- /.ct_display -->
                </div> 
                <!--Search Pan--->
                
                 <div class="clearnone">&nbsp;</div> <br/>
                 
                <!-- List of Assesments---->
                <?php  
                  
                   
                
                  if($is_serach==1){
                    // query
                  // echo  $dqq  ; 
                      
                      
                  $childs = mysql_query($dqq);       
                     }else{
                    //$childs = mysql_query('SELECT * FROM int_quiz ORDER BY created DESC');      
                     }




                     // $sql="SELECT s.SchoolName, r. * FROM school_provider_1_tab r INNER JOIN schools s ON r.school_id = s.SchoolId WHERE 1 ";

                     $sql="SELECT a.assesment_name,a.id as as_id,s.SchoolName, r. * FROM school_provider_1_tab r INNER JOIN schools s ON r.school_id = s.SchoolId 
Left join assessments a ON r.assessment_id=a.id
WHERE 1";


                     $childs= mysql_query($sql);

                     //
                     
                    //$childs = mysql_query('SELECT * FROM int_quiz ORDER BY created ASC');      
                          
                     $tot_records=mysql_num_rows($childs) ;
                                   
                                      
                                      
                                      
                                      ?> 
                
                
                
                <form id="form-manager" class="content_wrap" action="" method="post">
					<div class="ct_heading clear">
						<h3>Results(<?=$tot_records;?>)</h3>
						<ul>
				
							
                                                        <li>
								<button id="delete-user" type="submit" name="delete-user"><span class="fa fa-bolt"></span></button>
							</li>
						</ul>
					</div>		<!-- /.ct_heading -->
					<div class="clear">
                                            
                                           
                                            
				<table class="table-manager-user col-md-12">
								<colgroup>
									
									<col width="220">
									
									
                   <col width="240">
									
									<col width="100">
								</colgroup>
								<tbody><tr>
									
									<th>School</th>
									<th>Detail</th>
                                                                       
                 <th>Action/Status</th>
                                                                       
								</tr>
																		
                                                         <?php 
                                       if( mysql_num_rows($childs) > 0 ) {
			
			$i = 1;
			while( $row= mysql_fetch_assoc($childs) ) {
                            
                           # record det
          $data= mysql_fetch_assoc(mysql_query(" SELECT * FROM int_quiz WHERE id='".$row['id']."' "));
          $grade=mysql_fetch_assoc(mysql_query(" SELECT id,name FROM terms WHERE id='".$row['grade_id']."' "));  
                            //////////////////
                           $distList="All";    $schoolList="All";   

                           // http://intervene.srinfosystem.com/questions/uploads/sample-questions.csv
                           $url="process_results.php?id=".$row['id'];
                            ?>         <tr> 

                                                      <td>
                                       <span><strong class="text-primary">Assesment-</strong> 
                                       <?=$row['assesment_name']?> <br/></span>
                                       <strong class="text-info" style="font-size:18px">
                            <?=$row['SchoolName']?> </strong><br/>
                             <?=$row['filename']?> <br/>

                                <br/>
                         <a href="uploads/results/<?=$row['filename']?>"
                          class="btn btn-primary btn-sm">Download CSV</a>       
                                                      
                                                      </td>
                                                      
                                                      
                                                      
                                                      
                                                           <td>  

                                       <span><strong class="text-primary">Provider-</strong> <?=$row['provider_name']?> <br/></span>

                                       
                                         <span><strong class="text-primary">Grade-</strong> <?=$grade['name']?> <br/></span>
                                        
                                        <span><strong class="text-primary">Date-</strong> 
                                        <?=date('F d,Y',strtotime($row['created']))?> <br/></span>     
                                     

                  
                                                         </td>

                                                          <td>
                                                     <?=$row['status']?><br/>

                         <a   title="process Roster" href="<?=$url?>">
                                          <span class="glyphicon glyphicon-pencil"></span></a>
                                           &nbsp;&nbsp;
                                            <a   title="Delete Roster" href="result_uploaded.php?del_id=<?=$row['id']?>">
                                          <span class="glyphicon glyphicon-trash"></span></a>
                                          
                                       
                                               &nbsp;&nbsp;

                                                 </td>



                                                         
                                                                  


                                                                </tr>    <?php  $i++;} } ?>
                                  
                       
                                          
                                                                
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
  if(isset($_GET['ac']))$error="Quiz Saved Succesfully. !";

if ($error != '') echo "alert('{$error}')"; ?>
</script>
<style>
    .chosen-container-multi .chosen-choices li.search-field input[type="text"]{height:30px; }
</style>
<?php include("footer.php"); ?>

 



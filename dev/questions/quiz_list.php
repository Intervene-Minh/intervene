<?php
/*
 * Quiz list::lessonwise
 * //  print_r(array_unique($a));
 unset($_SESSION['list']);
        unset($_SESSION['qn_list']);
        unset($_SESSION['ses_quiz_grade']);
        unset($_SESSION['ses_quiz_objective']);
        unset($_SESSION['ses_quiz_lesson']);
 * **/



$error = '';
$author = 1;
 $assementArr=array(); // for search
 $is_serach=0;// no seach
$datetm = date('Y-m-d H:i:s');
include("header.php");
if ($_SESSION['login_role'] != 0) { //not admin
    header('Location: folder.php');
    exit;
}



///+add more process//
if(isset($_GET['quiz_id'])&&$_GET['quiz_id']>=1) {// $_GET['quiz_id'] > 0
    unset($_SESSION['list']);
    $questions = mysql_query('SELECT qn_id  FROM int_quiz_x_questions WHERE quiz_id = \''.$_GET['quiz_id'].'\' ORDER BY num ASC ');
    
    while($asses = mysql_fetch_assoc($questions)) {
        $_SESSION['list'][] = $asses['qn_id'];
    }
    $_SESSION['qz_assess_id'] = $_GET['quiz_id'] ;// Edit QuizID
/////// Set for question add more////
      $quiz_det= mysql_fetch_assoc(mysql_query("SELECT * FROM `int_quiz` WHERE id=".$_GET['quiz_id']));
     // print_r($quiz_det); die;
    $_SESSION['ses_quiz_grade']=$quiz_det['grade_id']; //  
    $_SESSION['ses_quiz_lesson']=$quiz_det['lesson_id'];
    $_SESSION['ses_quiz_objective']=$quiz_det['objective_id'];
    $_SESSION['is_addmore_ques']=1;
    // Re-direct to set >> Session
    header("Location:quiz_step3.php?ac=addmore&quiz_id=".$_GET['quiz_id']);exit;

}


///+add more process//


 //Search/////////////////////////////
if (isset($_POST['qz_submit'])) {
      $is_serach=1;  
     if (count($_POST['district']) > 0) {
       
       if(count($_POST['master_school'])>0)
       $ent_id_ls=array_merge($_POST['district'],$_POST['master_school']);
       else $ent_id_ls=$_POST['district'];
     }

      $ent_id_lsX=implode(",",$ent_id_ls); // entID   

      
  /** $dqq='Select a.* FROM int_quiz a LEFT JOIN int_quiz_access ac ON a.id = ac.assessment_id WHERE 
ac.entity_id IN ('.$ent_id_lsX.')
          AND a.objective_name LIKE "%'.$_POST['objective_name'].'%"  ';
   * 
   * ***/
       $dqq='Select DISTINCT(a.id) FROM int_quiz a LEFT JOIN int_quiz_access ac ON a.id = ac.assessment_id WHERE 
ac.entity_id IN ('.$ent_id_lsX.')
          AND a.objective_name LIKE "%'.$_POST['objective_name'].'%"  ';
     
   // cas 2
    $dqq=' SELECT a.* FROM int_quiz a WHERE 1 ';
    if(count($ent_id_ls)>0)
    $dqq=' Select DISTINCT(a.id) FROM int_quiz a LEFT JOIN int_quiz_access ac ON a.id = ac.assessment_id WHERE 
ac.entity_id IN ('.$ent_id_lsX.') ';
    
    if(isset($_POST['objective_name'])&&!empty($_POST['objective_name'])){
       $ff=' AND a.objective_name LIKE "%'.$_POST['objective_name'].'%"  ';
      $dqq.=$ff; 
    }
   
    //////:: query searhe     
     
     
     
 } 


//////////////////////////////
$questions_list = array();
if ($_GET['id'] > 0 && $_GET['action'] == 'edit') {
    $qry = mysql_query('SELECT * FROM int_quiz WHERE id = ' . $_GET['id']);
    $assesment_result = mysql_fetch_assoc($qry);
    if ($_GET['cat'] > 0) {
        unset($assesment_result['grade_id']);
    }
    $qn_query = mysql_query('SELECT qn.name, qn.id FROM questions qn '
            . 'LEFT JOIN int_quiz_x_questions axq ON axq.qn_id = qn.id WHERE '
            . 'axq.assesment_id = \'' . $_GET['id'] . '\' ');

    while ($question = mysql_fetch_assoc($qn_query)) {
        $questions_list[] = $question['id'];
    }
}

if ($_SESSION['assess_id'] > 0) {
    $qry = mysql_query('SELECT * FROM int_quiz WHERE id = ' . $_SESSION['assess_id']);
    $assesment_result = mysql_fetch_assoc($qry);
    $a_id = $_SESSION['assess_id'];
}


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



///Listing/////
if($is_serach==1){
         $childs = mysql_query($dqq); 
     }else{
                   // $childs = mysql_query('SELECT * FROM int_quiz ORDER BY created DESC');  
   $childs = mysql_query('SELECT q. *,ls.name, ls.id AS lsid FROM int_quiz q LEFT JOIN master_lessons ls ON q.lesson_id = ls.id WHERE q.lesson_id >0 ORDER BY q.created DESC ');

       }
        $tot_records=mysql_num_rows($childs) ;
                     
  
                
                  



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
                
                <div id="single_question" class="content_wrap" style="display: none;">
                    <div class="ct_heading clear">
                        <h3> <i class="fa fa-search"> </i>
                          Search Filter</h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
                        <form name="form_passage" id="form_passage" method="post" action="" enctype="multipart/form-data">
                            <h4 title="Edit Quiz here">Choose Search Fields: </h4>
                            
                            
                            
                            
                            <div class="col-md-12">
                                <p>
                                    <label for="grade_level_name">Name</label>
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

                <form id="form-manager" class="content_wrap" action="" method="post">
					<div class="ct_heading clear">
						<h3>Available Quiz(<?=$tot_records;?>)</h3>
						<ul>
            <li title="+Add Quiz"><a href="quiz_step1.php"><i class="fa fa-plus-circle"></i></a></li>
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
									
									<th>Objective</th>
									<th>Grade Detail</th>
                                                                       
                                                                        <th>Action</th>
                                                                       
								</tr>
																		
                                                         <?php 
                                       if( mysql_num_rows($childs) > 0 ) {
			
			$i = 1;
			while( $row= mysql_fetch_assoc($childs) ) {
                            
                           # record det
         // $data= mysql_fetch_assoc(mysql_query(" SELECT * FROM int_quiz WHERE id='".$row['id']."' "));  
                            //////////////////
                           $distList="All";    $schoolList="All";  


                           // if(!empty($data['access_level'])){ //  formainRec
                                //   	assessment_id access_level entity_id
                                # ORDER BY created ASC
                      //  $qq= mysql_query(" SELECT * FROM int_quiz_access WHERE quiz_id='".$row['id']."' "); 
                           // 3 TAB data ... 
                      //  $arr_dist=array(); $arr_schools=array();
                        //$districts=NULL;$schools=NULL; $x=NULL;
                           // }

                        
                           

             $tot_ques=mysql_num_rows(mysql_query("SELECT * FROM `int_quiz_x_questions` WHERE quiz_id=".$row['id']));    


                            ?>       
                                                                
                                                                <tr> 
                                                      <td>
                                 

                            <strong class="text-primary">
                                Lesson:</strong><?=$row['name']?> <br/>

                                <strong class="text-primary">
                                Objective:</strong><?=$row['objective_name']?><br/>
                                <strong class="text-primary">
                                Tot. Questions:</strong><?=$tot_ques?>
                                                     </td>
                                                          
                           
                                  
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                           <td>
                                                               
                                                               
                                             <strong class="text-primary"> <?=$row['grade_level_name']?> </strong> 
                                                           
                                                           
                                                           </td>
                                                         
                                                                  
                                                                    <td style="text-align: center;">
                                                                    <?php // edit_quiz.php?quiz_id=?>
                                     <!--  <a  >
                                          <span class="glyphicon glyphicon-pencil"></span></a>
                                           &nbsp;&nbsp; -->
                                          
                                          <a  title="+Add MOre Questions" href="quiz_list.php?quiz_id=<?=$row['id']?>">
                                              <span class="fa fa-plus-circle"></span></a> 
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
  if(isset($_GET['ac']))$error="Quiz Saved Succesfully. !";

if ($error != '') echo "alert('{$error}')"; ?>
</script>
<style>
    .chosen-container-multi .chosen-choices li.search-field input[type="text"]{height:30px; }
</style>
<?php include("footer.php"); ?>

 



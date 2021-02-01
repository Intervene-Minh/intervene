<?php
//  print_r(array_unique($a));

$error = '';
$author = 1;
$datetm = date('Y-m-d H:i:s');

include("header.php");
if ($_SESSION['login_role'] != 0) { //not admin
    header('Location: folder.php');
    exit;
}

$error = '';


//if (count($_SESSION['qn_list']) <= 0) {
 //   $error = 'Please select questions to create assesment!';
//}

if(isset($_GET['assesment_id'])){
$qry = mysql_query('SELECT * FROM assessments WHERE id = ' . $_GET['assesment_id']);
$assesment_result = mysql_fetch_assoc($qry); 
$qry2 = mysql_query('SELECT * FROM assessments WHERE id = ' . $_GET['assesment_id']);
$assesment_det= mysql_fetch_assoc($qry2); 
 }


 

/////////Update Assesment///////////
 // list of  Choose District: 
 // List of Choose ::  Schools:  Shows
 $assementArr=array(); // for search
 $is_serach=0;// no seach
 
 
 
 
 
 // Search Assesment
if (isset($_POST['assesment_submit'])) {
      $is_serach=1;$ent_id_ls=array();
 //var_dump($_PODST);
    //if(isset($_POST['district']))
       // $inf_text="districtArr found<br/>";
   // if(isset($_POST['master_school']))
      //  $inf_text.="master_schoolARR found<br/>";
     if (count($_POST['district']) > 0) {
       
       if(count($_POST['master_school'])>0)
       $ent_id_ls=array_merge($_POST['district'],$_POST['master_school']);
       else $ent_id_ls=$_POST['district'];
     }
    
   //////:: query searhe   
      $ent_id_lsX=implode(",",$ent_id_ls); // entID   
      
  /** $search_sql='Select a.* FROM assessments a LEFT JOIN assessments_access ac ON a.id = ac.assessment_id WHERE 
ac.entity_id IN ('.$ent_id_lsX.')
          AND a.assesment_name LIKE "%'.$_POST['assesment_name'].'%"  ';
   * 
   * ***/
       $search_sql='Select DISTINCT(a.id) FROM assessments a LEFT JOIN assessments_access ac ON a.id = ac.assessment_id WHERE 
ac.entity_id IN ('.$ent_id_lsX.')
          AND a.assesment_name LIKE "%'.$_POST['assesment_name'].'%"  ';
     
       
       
       //print_r($ent_id_ls); die;
   // cas 2
   // $search_sql=' SELECT a.* FROM assessments a WHERE 1 ';
       $search_sql=' SELECT DISTINCT(a.id) FROM assessments a WHERE 1 ';
       
    if(count($ent_id_ls)>0)
    $search_sql=' Select DISTINCT(a.id) FROM assessments a LEFT JOIN assessments_access ac ON a.id = ac.assessment_id WHERE 
ac.entity_id IN ('.$ent_id_lsX.') ';
    
    if(isset($_POST['assesment_name'])&&!empty($_POST['assesment_name'])){
       $ff=' AND a.assesment_name LIKE "%'.$_POST['assesment_name'].'%"  ';
      $search_sql.=$ff; 
    }
   
    //////created_by//// AND a.created_by >0
    // princ_created :filter
    if(isset($_POST['princ_created'])){
         $search_sql.=' AND a.created_by >0 '; 
        
    }
    
     
      //echo $search_sql; die; 
     
 } 

//  Search Assesment

 // get duplicate ID
 
 
 
 
 

//////////////////////////////
$questions_list = array();
if ($_GET['id'] > 0 && $_GET['action'] == 'edit') {
    $qry = mysql_query('SELECT * FROM assessments WHERE id = ' . $_GET['id']);
    $assesment_result = mysql_fetch_assoc($qry);
    if ($_GET['cat'] > 0) {
        unset($assesment_result['grade_id']);
    }
    $qn_query = mysql_query('SELECT qn.name, qn.id FROM questions qn '
            . 'LEFT JOIN assessments_x_questions axq ON axq.qn_id = qn.id WHERE '
            . 'axq.assesment_id = \'' . $_GET['id'] . '\' ');

    while ($question = mysql_fetch_assoc($qn_query)) {
        $questions_list[] = $question['id'];
    }
}

if ($_SESSION['assess_id'] > 0) {
    $qry = mysql_query('SELECT * FROM assessments WHERE id = ' . $_SESSION['assess_id']);
    $assesment_result = mysql_fetch_assoc($qry);
    $a_id = $_SESSION['assess_id'];
}


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
                        <h3> <i class="fa fa-search"> </i>
                          Search Filter</h3>
                    </div>		<!-- /.ct_heading -->
                    <div class="ct_display clear">
                        <form name="form_passage" id="form_passage" method="post" action="" enctype="multipart/form-data">
                            <h4 title="Edit Assessments here">Choose Search Fields:
                            
                            <?php  # var_dump($assementArr) ?>
                            
                            
                            </h4>
                            
                            
                            <div class="col-md-12">
                                <p>
                                    <label for="lesson_name">Assessment Name</label>
      <input type="text"  style=" width: 100%" name="assesment_name" class="required textbox" 
             value="<?=(isset($_POST['assesment_name']))?$_POST['assesment_name']:NULL;?>" />
                                </p></div>

                           
                            <div class="col-md-12">
                            <!-----add_question_wrap clear fullwidth---->
                            <div class="">
                                <p>
                                    <label for="lesson_name">Choose District:</label><br />
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

                                    <label for="lesson_name">Choose Schools:</label>
                                    <div id="district_school" style=" width: 90%;">
                                        Select District to choose schools.
                                    </div>

                                </div>
                            </div> </div>
                            <div class="col-md-12" style="margin-left:0px; margin-top: 11px;">
                            <p>
                                <input name="princ_created" id="" style="vertical-align: sub;" <?=(isset($_POST['princ_created']))?'checked':NULL?>  type="checkbox">
									<label for="question_public">Principal Created </label>
								</p>
                                                                </div>
                            
                            
                            
                           
                            
                            <p style=" margin-top: 10px;text-align: center;">
           <input type="submit" name="assesment_submit" style=" margin-top: 10px;"
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
                   
                  //echo  $search_sql  ; 
                     
                  $childs = mysql_query($search_sql);       
                     }else{
                    $childs = mysql_query('SELECT * FROM assessments ORDER BY id deSC');      
                     }
                     //
                          
                     $tot_records=mysql_num_rows($childs) ;
                                   
                                      
                                      
                                      
                                      ?> 
                
                
                
                <form id="form-manager" class="content_wrap" action="" method="post">
					<div class="ct_heading clear">
						<h3>All Assesments(<?=$tot_records;?>)</h3>


						


					</div>		<!-- /.ct_heading -->
					<div class="clear">
                                            
                                           
                                            
				<table class="table-manager-user col-md-12">
								<colgroup>
									
									<col width="220">
									
									<col width="100">
                                                                        <col width="100">
									<col width="100">
									<col width="140">
								</colgroup>
								<tbody><tr>
									
									<th>Assesments</th>
									<th>District</th>
                                                                        <th>Schools</th>
                                                                         <th>Principal created test</th>
                                                                        <th>Action</th>
                                                                       
								</tr>
																		
                                                         <?php 
                                       if( mysql_num_rows($childs) > 0 ) {
			
			$i = 1;
			while( $row= mysql_fetch_assoc($childs) ) {
                            
                           # record det
          $data= mysql_fetch_assoc(mysql_query(" SELECT * FROM assessments WHERE id='".$row['id']."' "));  
                            //////////////////
                           $distList="All";    $schoolList="All";                         
                            if(!empty($data['access_level'])){ //  formainRec
                                //   	assessment_id access_level entity_id
                                # ORDER BY created ASC
                        $qq= mysql_query(" SELECT * FROM assessments_access WHERE assessment_id='".$row['id']."' "); 
                           // 3 TAB data ... 
                        $arr_dist=array(); $arr_schools=array();
                        $districts=NULL;$schools=NULL; $x=NULL;
                        ///////////////////
                        while($line= mysql_fetch_assoc($qq) ) {
                         // dat of schools or Discrict
                            if($line['access_level']=="district"){
                    $det = mysql_fetch_assoc(mysql_query(" SELECT * FROM loc_district WHERE id='".$line['entity_id']."' "));
                       $x.=(!empty($x))?",<br/>".$det['district_name']:$det['district_name'];
                            }
                              
                            if($line['access_level']=="school"){
                    $det = mysql_fetch_assoc(mysql_query(" SELECT * FROM master_schools WHERE id='".$line['entity_id']."' "));
                         $schools.=(!empty($schools))?",".$det['school_name']:$det['school_name'];    
                    
                    
                            }
                            
                    
                        }// end while
                        
                        ////////////////////////
                        
                        
                            
                            }
                            ///////////////
                            $active_status=($data['status_active']=='yes')?'Active':'Inactive';
                            $active_status_class=($data['status_active']=='yes')?'btn btn-success btn-xs':'btn btn-danger btn-xs';


                            ?>       
                                                                
                                                                <tr> 
                                                   <td><strong><?=$data['assesment_name']?></strong></td>
                                                           <td><?=(!empty($x))?$x:"All"; ?></td>
                                                        
                                                          
                                                    <td>      <?=(!empty($schools))?$schools:"All"; ?></td>
                                                         
                                                         
                                                         
                                                                 
                                                         <td class="text-center"><strong><?=($data['created_by']>0)?'Yes':'No'?></strong></td>
                                                         
                                                         
                                                         <td class="text-center">
                                      <a   title="Edit" href="edit_assesment.php?assesment_id=<?=$row['id']?>">
                                          <span class="glyphicon glyphicon-pencil"></span></a>
                                           &nbsp;&nbsp;
                                          
                                          <a  title="+Add More Questions" href="assesment_step3.php?assesment_id=<?=$row['id']?>">
                                              <span class="fa fa-plus-circle"></span></a> &nbsp;&nbsp;
                                          
                                           <a  title="Duplicate" href="assesment_duplicate.php?assesment_id=<?=$row['id']?>">
                                          <span class="fa fa-copy"></span></a> 
                                                                        
                                                          <?php  //   Add|Delete|Duplicate ?>    
                                                          <br/>
                                                          <span title="Status" >
                                                            <span class="<?=$active_status_class?>" ><?=$active_status?></span> </span>          
                                                         
                                                       
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

 <!----
Select a.* FROM assessments a LEFT JOIN assessments_access ac ON a.id = ac.assessment_id WHERE 
(ac.entity_id IN (18,19,20,18) AND ac.access_level = 'district')
AND 
(ac.entity_id IN () AND ac.access_level = 'school')
AND a.title LIKE "%'..'%";



 
  $search_sql='Select a.* FROM assessments a LEFT JOIN assessments_access ac ON a.id = ac.assessment_id WHERE 
ac.entity_id IN ('.$ent_id_lsX.')
          AND a.assesment_name LIKE "%'.$_POST['assesment_name'].'%"  ';
 

//// 1 Nov Assesment
 
 ----->


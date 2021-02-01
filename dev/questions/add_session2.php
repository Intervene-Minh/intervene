<?php
$error = '';
$author = 1;
$datetm = date('Y-m-d H:i:s');
$error= 'Intervention Session create';


include("header.php");
if ($_SESSION['login_role'] != 0) { //not admin
    header('Location: folder.php');
    exit;
}

$error = '';


//if (count($_SESSION['qn_list']) <= 0) {
 //   $error = 'Please select questions to create assesment!';
//}
// assesment_submit
if(isset($_POST['assesment_submit'])){
  echo '<pre>'; print_r($_POST); die;

}


 if(isset($_GET['assesment_id'])){
$qry = mysql_query('SELECT * FROM assessments WHERE id = ' . $_GET['assesment_id']);
$assesment_result = mysql_fetch_assoc($qry); 
 }

/////////Update Assesment///////////

if ($_POST['assesment_submit']) {
/*    $qry = mysql_query('SELECT * FROM assessments WHERE id = ' . $_GET['assesment_id']);
$assesment_result = mysql_fetch_assoc($qry); */
    ///////
    if (count($_POST['district']) > 0 && count($_POST['master_school']) > 0) {
        $master_access_level = 'school';
    } else if (count($_POST['district']) > 0) {
        $master_access_level = 'district';
    } else {
        $master_access_level = 'ALL';
    }
    $up=mysql_query('UPDATE assessments SET assesment_name = \''.$_POST['assesment_name'].'\' , access_level = \'' . $master_access_level . '\'     WHERE id = \''.$_GET['assesment_id'].'\' ');
   // $error = '#Assesment has been updated successfully.';
    
    
    
  /////////////////////////////  
    
 $assesment_id=$_GET['assesment_id'];
   /// update Main Table
   $del=mysql_query('DELETE FROM assessments_access WHERE assessment_id  = \'' . $assesment_id . '\'  ');

//  Add NEW 1::           
   if (count($_POST['district']) > 0) {
                for ($k = 0; $k < count($_POST['district']); $k++) {
                    mysql_query('INSERT INTO assessments_access SET '
                            . 'assessment_id  = \'' . $assesment_id . '\' , '
                            . 'access_level = \'district\' , '
                            . 'entity_id = \'' . $_POST['district'][$k] . '\' ');
                }
                for ($k = 0; $k < count($_POST['master_school']); $k++) {
                    mysql_query('INSERT INTO assessments_access SET '
                            . 'assessment_id  = \'' . $assesment_id . '\' , '
                            . 'access_level = \'school\' , '
                            . 'entity_id = \'' . $_POST['master_school'][$k] . '\' ');
                }
            }

     $error = 'Assesment has been updated successfully.';
        //header('Location:edit_assesment.php?assesment_id='.$_GET['assesment_id']);
     header('Location:assesment_list.php?ac=1');
        exit;
   
}
//  End Update 




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

if ($_SESSION['assess_id'] > 0 && !$_GET['assesment_id']) {
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
$school_level_res = mysql_query('SELECT entity_id FROM assessments_access WHERE assessment_id = \'' . $a_id . '\' AND access_level = "school" ');
$assessment_school = array();
while ($school = mysql_fetch_assoc($school_level_res)) {
    $assessment_school[] = $school['entity_id'];
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
          var  district = $(this).val();
        // alert(district);
          //  $('#district_school').html('Loading ...');
          if(district){
            $.ajax({
                type: "POST",
                url: "intervene_ajax.php",
                data: 'district='+district,
                success: function (html) {
                    alert(district);
                    $('#district_school').html(html);
                    //$('#d_school').chosen();
                },
               // async: false
            });
          }
        });
       // $('#district').change();
    });

    
 $('#district_school').on('change',function(){
    
    var district_school = $(this).val();
    alert(district_school);
    if(district_school){
        $.ajax({
            type:'POST',
            url:'ajax.php',
            data:'district_school='+district_school,
            success:function(html){
                $('#grade').html(html);
            }
        }); 
    }
});

</script>
<div id="main" class="clear fullwidth">
    <div class="container">
        <div class="row">
            <div id="sidebar" class="col-md-4">
                <?php include("sidebar.php"); ?>
            </div>      <!-- /#sidebar -->
            <div id="content" class="col-md-8">
                <div id="single_question" class="content_wrap">
                    <div class="ct_heading clear">
                        <h3><i class="fa fa-plus-circle"></i>
                          Create Session</h3>
                    </div>      <!-- /.ct_heading -->
                    <div class="ct_display clear">
                        <form name="form_passage" id="form_passage" method="post" action="" enctype="multipart/form-data">
                            <h4 title="Create Session:">Create Session:</h4>

                            <div class="add_question_wrap clear fullwidth">
                              
                                <p>
                                   
                                                                             
                                   <select name="lesson" >
                                    <option value="6">xww</option>

          <option value="7">lession1</option>

          <option value="8">Analytics</option>
                                    </select>

                                    
                                </p>
                                
                            </div>




                            <div class="add_question_wrap clear fullwidth">
                            
                          
                           


                            <!-- <button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove" style="float:right;">X</button> -->
                           

                            <div class="row" id="add_more" >
                            <div class="col-md-6">
                             <label for="date">Date</label>
                            <input type="date"  placeholder="dd-mm-yyyy" 
                            style=" width: 100%" name="select_date[]" class="required textbox" value=""/>
                            </div>

                             <div class="col-md-6">
                              <p>
                                    <label for="lesson_name">Choose Lesson:</label>
                                    <select name="lesson[]" id="lesson" class="textbox">
                                    <option value="">Select Lesson</option>
                                        <?php $lesson_query =mysql_query("select * from master_lessons");
                                        while ($lesson = mysql_fetch_assoc($lesson_query)) { ?>
                                   <option value="<?php echo $lesson['id']; ?>"><?php echo $lesson['name']; ?></option>

                                      <?php } ?>
                                    </select>
                                </p>
                            </div>
                             <!-- Times -->
                             <div class="col-md-12">
                             <label for="date">Time</label>

                            <p>
                            <select class="col-md-4" name="hour[]" style="width:200px"  id="time" class="textbox">
                             <?php
                               $i = 1;
                               while ($i <= 12) {
                               $sel = (isset($_POST['hour']) && $i == $_POST['hour']) ? "selected" : NULL;
                             ?>
                             <option  <?= $sel ?>
                             value="<?= $i ?>" ><?= $i ?></option>                         
                             <?php
                               $i++; }
                             ?> 
                            </select>

                            <select  class="col-md-3" name="minnute[]"  class="textbox">
                            <?php
                              $k = 0;
                              while ($k <= 55) {
                              $sel = ($k == $_POST['minnute']) ? "selected" : NULL;
                              $kff = ($k > 5) ? $k : '0' . $k;
                             ?>                            
                            <option  <?= $sel ?> value="<?= $k ?>"><?= $kff ?></option> 
                              <?php $k = $k + 5;
                                } ?>
                                </select> 
                                    <?php
                                    $tArr = array('am', 'pm');
                                    $Type
                                    ?>
                                <select  class="col-md-2" name="hr_type[]"  class="textbox">
                                    <?php
                                    foreach ($tArr as $val) {
                                        $sel = ($val == $_POST['hr_type']) ? "selected" : NULL;
                                        ?>                         
                                        <option <?= $sel ?>   value="<?= $val ?>"><?= strtoupper($val) ?></option> 
                                    <?php } ?>

                                </select>
                                </p>
                             </div>




                            </div>

                           

                               



                                <!-- repeat_id Section -->


                                <!-- <div id="repeat_id"> -->
                                <div class="row"  id="repeat_id" >
                               
                                </div>
                             
                              



                             </div> 

                        <p class="align-right" style=" text-align: right;">
                              <a class="btn btn-primary btn-sm" name="add" id="add" ><i class="fa fa-plus-circle" >+Add More</i></a>
                              </p>
                               

                                <p >
           <input type="submit" name="assesment_submit" id="lesson_submit" class="form_button submit_button" value="Update" style="align:center;" />

                            </p>  


                        </form>
                        <div class="clearnone">&nbsp;</div>
                    </div>      <!-- /.ct_display -->
                </div>
            </div>      <!-- /#content -->
            <div class="clearnone">&nbsp;</div>
        </div>
    </div>
</div>      <!-- /#header -->

<script type="text/javascript">
<?php if ($error != '') echo "alert('{$error}')"; ?>
</script>

<script>  
 $(document).ready(function(){  
      var i=1;  
      $('#add').click(function(){  
           i++;  
           var text_ino=$('#add_more').html();
           var html = '';
         //var datatext='<div id="row'+i+'" >'+text_ino+'</div>';
           var datatext='<div title="parent" id="row'+i+'"><div class="col-md-10"  >'+text_ino+'</div><div  class="col-md-2"><button type="button" name="remove" id="'+i+'"  class="btn btn-danger btn_remove" style="float:right;">X</button></div></div>';

          $('#repeat_id').append(datatext);
      });  

      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+i+'').remove();  
           i--;
      });  
      $('#submit').click(function(){            
           $.ajax({  
                url:"name.php",  
                method:"POST",  
                data:$('#add_session').serialize(),  
                success:function(data)  
                {  
                     alert(data);  
                     $('#add_session')[0].reset();  
                }  
           });  
      });  
 });  
 </script>


<style>
    .chosen-container-multi .chosen-choices li.search-field input[type="text"]{height:30px; }
</style>
<?php include("footer.php"); ?>
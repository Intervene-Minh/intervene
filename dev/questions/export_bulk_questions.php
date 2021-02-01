<?php
/***
@ Export Grade QUestions

*/
require_once ('translate/vendor/autoload.php');
use \Statickidz\GoogleTranslate;


//echo 'Import bulk Question===============';


////////////////////////
$error	= '';
$author = 1;
$datetm = date('Y-m-d H:i:s');
$current_date = date('Y-m-d H:i:s');

include("header.php");
if($_SESSION['login_role'] !=0) { //not admin
	header('Location: folder.php');
	exit;
}

// Retrive question data
$quesId = (isset($_GET['question']) && is_numeric($_GET['question']) && $_GET['question'] > 0) ? $_GET['question'] : 0;

//if not admin but want to edit return index
require_once('inc/check-role.php');
$role = checkRole();
if($quesId>0 && $role!=0){
	header('Location: index.php');
	exit;
}





// upload////////////////////////
if( isset($_POST['upload']) ) { 

  print_r($_POST); die; 

 }


/* Process Submittion */


?>

<div id="main" class="clear fullwidth">
	<div class="container">
		<div class="row">
			<div id="sidebar" class="col-md-4">
				<?php include("sidebar.php"); ?>
			</div>		<!-- /#sidebar -->
			<div id="content" class="col-md-8">
				<div id="single_question" class="content_wrap">
					<div class="ct_heading clear">
						<h3><i class="fa fa-plus-circle"></i>Export Bulk Questions</h3>
					</div>		<!-- /.ct_heading -->
					<div class="ct_display clear">
						<form name="form_question"
						 id="form_question" method="get" action="export_file_1.php" enctype="multipart/form-data">

            <h4><?php if(is_array(($msg))&&count($msg)>0) echo implode('<br/>', $msg);
                                                                else echo 'Please Select Grade';  ?></h4>

                                                                                                           
							<div class="add_question_wrap clear fullwidth">
								
                                                               
                                                                
                                                                
                                                                
								
								<p>
									<div class="form-group">
                                <label for="school">Select a Grade</label>

                                <select required name="grade_id" class="required textbox" readonly="readonly" >
                                        <option value="">Select</option>
                                        <?php
                                        $grade_level_id = 0;
                                        if ($_GET['cat'] > 0) {
                                            $grade_level_id = $_GET['cat'];
                                        } else if ($assesment_result['grade_id'] > 0) {
                                            $grade_level_id = $assesment_result['grade_id'];
                                        }
                                        $foldersStop= mysql_query("SELECT * FROM `terms` WHERE `taxonomy` = 'category' AND `parent` = 0 AND `active` = 1");

                                        //Only Math Grades///// 

                                        $folders=mysql_query("SELECT * FROM terms WHERE taxonomy= 'category' AND parent= 0 AND active= 1 AND id IN(1)");

                                        //Only Math Grades///// 


                                        if (mysql_num_rows($folders) > 0) {
                                            while ($folder = mysql_fetch_assoc($folders)) {
                                                $selected = ($folder['id'] == $_GET['cat'] || $folder['id'] == $assesment_result['grade_id'] ) ? ' selected="selected"' : '';
                                                ///
                                               // echo '<option value="' . $folder['id'] . '"' . $selected . '>' . $folder['name'] . '</option>';


                          $subfolders = mysql_query("SELECT * FROM `terms` WHERE `taxonomy` = 'category' AND `parent` = {$folder['id']} AND `active` = 1 ORDER BY  name ");
                                                if (mysql_num_rows($subfolders) > 0) {
                                                    while ($subfolder = mysql_fetch_assoc($subfolders)) {
                                                        if ($_GET['cat'] <= 0 && $grade_level_id == 0) {
                                                            $grade_level_id = $subfolder['id'];

                                                            /// questionNum
                                                  //$quesTot=mysql_num_rows(mysql_query(" SELECT * FROM questions WHERE category='$grade_level_id' "));

                                                //  $queText='-('.$quesTot.' Questions)';
                                                            //

                                                        }
                                                        $selected = ($subfolder['id'] == $_GET['cat'] || $subfolder['id'] == $assesment_result['grade_id']) ? ' selected="selected"' : '';
                                                        echo '<option value="' . $subfolder['id'] . '" class="subfolder"' . $selected . '>' . $subfolder['name'] . '</option>';
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                              <!--   <select name="grade_id" id="lesson" class="textbox"> -->
                                   


                               

                        <small class="error text-danger" style="display: none;">This field is required!</small>
                            </div>
                                                                
                                                                
                                                                </p>
								
								
								
								
								
								
								
							</div>
							<p>
								


								<input type="submit" name="action" id="question_submit" class="form_button submit_button" value="Download" />

								<input type="reset" name="question_sreset" id="question_sreset" class="form_button reset_button" value="Reset" />

                                                                                                                
                                                        </p>
						</form>
						<div class="clearnone">&nbsp;</div>
					</div>		<!-- /.ct_display -->
				</div>
			</div>		<!-- /#content -->
			<div class="clearnone">&nbsp;</div>
		</div>
	</div>
</div>		<!-- /#header -->

<script type="text/javascript">
	<?php if( $error != '' ) echo "alert('{$error}')"; ?>
</script>

<?php include("footer.php"); ?>
<script type="text/javascript">
	$(document).ready(function(){
	    $("#trans").on("change",function(){
		   if($(this).is(":checked"))
		      $(this).val("1");
		    else
		      $(this).val("0");
		});
	});
</script>
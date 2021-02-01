<?php

    require_once 'includes/db_connect.php';
    
    $test = mysqli_query($connection, "SELECT * FROM tests WHERE ID = '".$_GET['test_id']."'");
    $isTest = $test->num_rows;

    
      $sql_grades=mysql_query("SELECT t. * , p.grade_level_id, p.permission FROM school_permissions p
                LEFT JOIN terms t ON p.grade_level_id = t.id where p.school_id = 114 ");
      
       if($isTest) {  $testRecord = mysqli_fetch_assoc($test);
       
      $subjects = array();
      if(!empty($testRecord['SubjectID']))
          $subjects = explode(":", $testRecord['SubjectID']);
      
     
    
     $select = '<select  name="subject[]" id="subject" class="form-control" multiple style="height:150px">';
                $select .= '<option value="">Select Subject</option>'; 
                while($schools = mysql_fetch_assoc($sql_grades)) 
                {
                    if(in_array($schools['id'], $subjects))
                       $select .= '<option value="'.$schools['id'].'" selected>'.$schools['name'].'</option>';
                    else
                      $select .= '<option value="'.$schools['id'].'" >'.$schools['name'].'</option>';
                }
                    $select .= '</select>';
         
   
?>

 <input type="hidden" id="test_id" name="test_id" value="<?php echo $_GET['test_id']; ?>">
                                                  	<div class="form-group">
								<label>Test Name</label>
                                                                <input type="text" class="form-control" placeholder="Test Name" id="tName" name="tName" required="" value="<?php echo $testRecord['Name'];?>">
							</div>	
 <div class="form-group">
								<label>Subject</label>
                                                                <?php echo $select;?>
							</div>
                                                       <div class="form-group">
								<label>Passing Percent(%)</label>
                                                                <input id="tMark" name="percent" type="number" min="1" placeholder="e.g-30%" class="form-control" required value="<?php echo $testRecord['PassingMark'];?>">
						       </div>
                                                       <div class="form-group">
								<label>Shown in Test List</label>
									<div class="radio-inline">
															<label class="radio">
                                                                                                                        <input type="radio" name="tIsActive" id="tIsActive-0" value="1" <?php if($testRecord['IsActive'] == 1) { echo 'checked="checked"'; }?> >
															<span></span>Active</label>
															<label class="radio">
                                                                                                                        <input type="radio" name="tIsActive" id="tIsActive-1" value="0" <?php if($testRecord['IsActive'] == 0) { echo 'checked="checked"';}?>>
															<span></span>Not Active</label>
															
														</div>
														
													</div>
    <?php } ?>
<script>
    
$("select").mousedown(function(e){
    e.preventDefault();
    
		var select = this;
    var scroll = select.scrollTop;
    
    e.target.selected = !e.target.selected;
    
    setTimeout(function(){select.scrollTop = scroll;}, 0);
    
    $(select).focus();
}).mousemove(function(e){e.preventDefault()});
        </script>
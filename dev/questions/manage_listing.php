<?php
/**
@ view popup detail


***/

echo 'Terms data..';

include("header.php");

$login_role = $_SESSION['login_role'];
if($login_role!=0 || !isGlobalAdmin()){
	header("location: index.php");
}
$current_date = date('Y-m-d H:i:s');// created_at



// echo $current_date.'======';
$self_url=$_SERVER['REQUEST_URI'];

$error='';
$id = $_SESSION['login_id'];

if(isset($_POST['delete-user'])){

	$arr = $_POST['arr-user'];

	if($arr!=""){
		$totlal_record= mysql_num_rows(mysql_query("SELECT * FROM terms WHERE  id IN($arr) OR parent IN($arr)"));

		 //echo $totlal_record,'=='; die; 
            

		   // parent and child Delete
		    $quwe=mysql_query(" DELETE FROM terms WHERE id IN($arr) OR parent IN($arr) ");

            //query =mysql_query("DELETE FROM terms WHERE id IN ($arr)" );
            $set_msg=$totlal_record.'-Record deleted.';

       
        echo "<script>alert('".$set_msg."');location.href='".$self_url."';</script>";

	}else{
     echo "<script>alert('Select Record..');location.href='".$self_url."';</script>";
 
	}
        
        
        ///
        
}



$schools = mysql_query("SELECT * FROM `schools` WHERE `status` = 1");
?>
<style type="text/css">
a.item_name.lg{
	font-size: 20px !important;
    font-weight: bold !important;
    color:#1b63a9!important;
}
</style>
<script>
	$(document).ready(function(){
		$('#delete-user').on('click',function(){
			var newLine = "\r\n"
			var msg = "Are you sure to remove these items?."
            msg += newLine;
            msg+="(All children will be also removed)";
            // confirm('Are you want to delete '+count+' ?');


			/////////////////
			var count = $('#form-manager .checkbox:checked').length;
			$('#arr-user').val("");
			$('#form-manager .checkbox:checked').each(function(){
				var val = $('#arr-user').val();
				var id = $(this).val();
				$('#arr-user').val(val+','+id);
			});
			var str = $('#arr-user').val();
			$('#arr-user').val(str.replace(/^\,/, ""));
			return confirm(msg);
		});
	});
</script>
<div id="main" class="clear fullwidth">
	<div class="container">
		<div class="row">
			<div id="sidebar" class="col-md-4">
				<?php include("sidebar.php"); ?>
			</div>		<!-- /#sidebar -->
			<div id="content" class="col-md-8">
				<!-- <div class="table-responsive">
					List of demo users<br/>
				</div> -->


				
                            <?php 

                            $title	= $select ? $select['name'] : 'List Objective';
                            $termId = (isset($_GET['taxonomy']) && is_numeric($_GET['taxonomy'])) ? $_GET['taxonomy'] : 0;

// Pagination
$per_page = ( isset($_GET['per_page']) && is_numeric($_GET['per_page']) && $_GET['per_page'] > 0 ) ? $_GET['per_page'] : 20;
$paged = ( isset($_GET['paged']) && is_numeric($_GET['paged']) && $_GET['paged'] > 0 ) ? $_GET['paged'] : 1;
$total = (int) ceil($count / $per_page);	# Total of pages
$start = (int) ($paged - 1) * $per_page;	# Start of records
$limit = " LIMIT $start , $per_page";		# Limit number of records will be appeared

$childs =("SELECT * FROM `terms` WHERE `taxonomy` = 'objective' AND `active` = 1 AND `parent` = " . $termId . $limit);
                            
									




                              // echo $query; 
                                                                        
                                                                        
                                                                        
									$results = mysql_query($childs);
									$tot_record=mysql_num_rows($results);

									 $record_label=(isset($_GET['taxonomy']))?'Objective':'Folder';
                                                                        
                                                                        
                            
                            ?>
                            
                            
				<form id="form-manager" class="content_wrap" action="" method="post">
					<div class="ct_heading clear">
						<h3><?=$record_label?>(<?=$tot_record?>)</h3>
						<ul>
							
                          <?php  if(isset($_GET['taxonomy'])){?> 
							<li>
								<button id="delete-user" type="submit" name="delete-user"><span class="glyphicon glyphicon-trash"></span></button>
							</li>
							<?php }else{?>
								<li>
							  <span onclick="alert('Please Select Objective Folder');" class="glyphicon glyphicon-trash"></span> 
							</li>

							<?php  }//}else{?>


						</ul>
					</div>		<!-- /.ct_heading -->
					<div class="clear">
						<?php
						if($error != '') {
							echo '<p class="error">'.$error.'</p>';
						} else {
						
                                              
                                                    
                                                    ?>
							<table style="margin-bottom:0px;" class="table table-manager-user col-md-12">
							
								<tr>
									<th width="7%">#</th>
									<th><?=$record_label?></th>
									

                                    
								</tr>
								<?php
								$folder_class=(isset($_GET['taxonomy']))?NULL:'lg';

                              if( mysql_num_rows($results) > 0 ) {
							while( $row = mysql_fetch_assoc($results) ) {
								$exp_sec=strtotime($row['expiry_date'])-strtotime($current_date);
								 // taxonomy=28
								// $edit_url="edit_objective.php?id=".$row['id']."&taxonomy=".$_GET['taxonomy'];
								$edit_url="edit_objective.php?id=".$row['id'];


					
											
								?>
										
                                                                
                                                                
                                             <tr>
											<td>
												<input type="checkbox" class="checkbox" value="<?php echo $row['id'];?>"/>
											</td>
                                                                                        
										<td>
										 <?php if(!isset($folder_class)){ // objective Edit?>

										 <a title="Edit objective" href="<?=$edit_url?>">
                                          <span class="glyphicon glyphicon-pencil"></span></a>
                                          <?php }?>

										 <a class="item_name <?=$folder_class?>" href="manage_objectives.php?taxonomy=<?=$row['id'];?>"> <?=$row['name'];?> </a>


										 <br/>

										 <p><?=  $row['description']; ?></p>


                                      

                                            </td>





                                            

                                                                             
                                                                                        
                                                                                        
										</tr>



								<?php
										}
									} else {
										echo '<div class="clear"><p>There is no item found!</p></div>';
									}
								?>
							</table>
						<?php } ?>
						<div class="clearnone">&nbsp;</div>
					</div>		<!-- /.ct_display -->
                                        <input type="hidden" id="arr-user" name="arr-user" value=""/>
				</form>

				<!-- pagination -->
				<?php if( mysql_num_rows($results) > 0&&isset($_GET['taxonomy']) ){
			   include("pagination.php"); } ?>


			</div>		
			<!-- /#content -->




			
			<div class="clearnone">&nbsp;</div>






		</div>
	</div>
</div>		<!-- /#header -->

<?php include("footer.php"); ?>
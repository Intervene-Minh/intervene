<?php
//
include("header.php");

$login_role = $_SESSION['login_role'];

//if($login_role!=0 || !isGlobalAdmin()){
//	header("location: index.php");
//}


$error='';
$id = $_SESSION['login_id'];

if(isset($_POST['delete-user'])){
	$arr = $_POST['arr-user'];
	if($arr!=""){
		//$query = mysql_query("DELETE FROM demo_users WHERE id IN ('$arr')", $link);
            
            //// Delete Role Table...
            $query = mysql_query("DELETE FROM demo_users WHERE id IN ($arr)", $link);
	}
        
        echo "<script>alert('#Record deleted..');location.href='manager_demo_user.php';</script>";
        ///
        
}



$schools = mysql_query("SELECT * FROM `schools` WHERE `status` = 1");
?>
<script>
	$(document).ready(function(){
		$('#delete-user').on('click',function(){
			var count = $('#form-manager .checkbox:checked').length;
			$('#arr-user').val("");
			$('#form-manager .checkbox:checked').each(function(){
				var val = $('#arr-user').val();
				var id = $(this).val();
				$('#arr-user').val(val+','+id);
			});
			var str = $('#arr-user').val();
			$('#arr-user').val(str.replace(/^\,/, ""));
			return confirm('Are you want to delete '+count+' user?');
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
				<div class="table-responsive">
					List of demo users<br/>
				</div>
				
                            <?php 
                            
									/////////////// Demo Users
                 $query = 'SELECT u. * , r.`name` FROM `demo_users` u LEFT JOIN `role` r ON u.`role` = r.`id`   ORDER BY u.date_registered DESC '.$limit;
                              /* Process Search */
                                                                        
                                                                        
                                                                        
									$results = mysql_query($query);
									$tot_record=mysql_num_rows($results);
                                                                        
                                                                        
                            
                            ?>
                            
                            
				<form id="form-manager" class="content_wrap" action="" method="post">
					<div class="ct_heading clear">
						<h3>Manager Demo Users(<?=$tot_record?>)</h3>
						<ul>
							<li><a href="#" class="edit-user"><span class="glyphicon glyphicon-pencil"></span></a></li>
							<li>
								<button id="delete-user" type="submit" name="delete-user"><span class="glyphicon glyphicon-trash"></span></button>
							</li>
						</ul>
					</div>		<!-- /.ct_heading -->
					<div class="clear">
						<?php
						if($error != '') {
							echo '<p class="error">'.$error.'</p>';
						} else {
						
                                              
                                                    
                                                    ?>
							<table class="table-manager-user col-md-12">
								<colgroup>
									<col width="30">
									<col width="220">
									<col width="80">
									<col width="80">
									<col width="120">
									<col width="55">
									<col width="100">
								</colgroup>
								<tr>
									<th>#</th>
									<th>Email</th>
									<th>First Name</th>
                                                                        <th>Last Name</th>
									<th>Expiry Date</th>
									
									<th>Role</th>
									<th>Date Created</th>
								</tr>
								<?php
                                                                if( mysql_num_rows($results) > 0 ) {
							while( $row = mysql_fetch_assoc($results) ) {
											
								?>
										
                                                                
                                                                
                                                                <tr>
											<td>
												<input type="checkbox" class="checkbox" value="<?php echo $row['id'];?>"/>
											</td>
                                                                                        
										<td>
                                                <a href="demo_user_profile.php?id=<?php echo $row['id']; ?>"><?php echo $row['email']; ?></a>
                                            </td>
                                            <td><?php echo $row['first_name']; ?></td>
                                            <td><?php echo $row['last_name']; ?></td>
                                            <td>
                                                <?php if ($row['expiry_date'] != NULL) : ?>
                                                <?php 
                                             $expiry_date_arr = explode('-', $row['expiry_date']);
                                             $date = strtotime($expiry_date_arr[2].'-'.$expiry_date_arr[1].'-'.$expiry_date_arr[0]); 
                                             echo date('M d Y', $date);
                                             
                                             ?>
                                                    
                                                <?php else : ?>
                                                    &nbsp;
            <?php endif; ?>
                                            </td>
                                            <td align="center">
            <?php echo $row['name']; ?>
                                            </td>
                                            <td>
                                                <?php
                                                $datetime = strtotime($row['date_registered']);
                                                if ($datetime == 0) {
                                                    echo "00h 00' 00/00/0000- ";
                                                } else {
                                                    echo date('H\h s\' - d/m/Y', $datetime);
                                                }
                                                ?>
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
			</div>		<!-- /#content -->
			<div class="clearnone">&nbsp;</div>
		</div>
	</div>
</div>		<!-- /#header -->

<?php include("footer.php"); ?>
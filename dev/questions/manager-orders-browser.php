<table class="table-manager-user col-md-12">
	
	<tr>
		<th>#</th>
		<th>Email</th>
		<th>School</th>
		<th>Amount</th>
		<th>Status</th>
		<th style="width:20%;">Tutor Sessions</th>
                <th   style="width:10%;"># Students</th>
		
                <th  style="width:20%;">Action</th>
	</tr>
	<?php 
        ////
	$results= mysql_query("SELECT p . * , s.`SchoolName`,s.`SchoolId`,s.`avaiable_slots`,s.`avaiable_interventions` FROM `purchases` p
	INNER JOIN `schools` s ON p.`schoolID` = s.`SchoolId` ORDER BY `created_at` DESC");
        
        $results99= mysql_query("SELECT p . * , s.`SchoolName`,s.`SchoolId` FROM `purchases` p
	INNER JOIN `schools` s ON p.`schoolID` = s.`SchoolId` ORDER BY `created_at` DESC");
        
	if( mysql_num_rows($results) > 0 ) {
		while( $row = mysql_fetch_assoc($results) ) {
                    
                    $student_count_res = mysql_fetch_assoc(mysql_query('SELECT COUNT(id) as total FROM students WHERE school_id = \''.$row['schoolID'].'\' '));
                    ////
                    
	?>
			<tr>
				<td class="text-center"><?php echo $row['id'];?>
                                    </td>
                                <td><a style="word-wrap: break-word;" href="?id=<?php echo $row['id']; ?>"><?php echo $row['email'];?></a></td>
				<td><?php echo $row['SchoolName'];?></td>
				<td class="text-center"><?php echo number_format($row['amount'], 2);?> (USD)</td>
				<td class="text-center"><?php echo $row['status'];?>
                                <br/>
                               
                                
                                </td>
				<td class="text-center"><?php echo $row['transId'];?>
                                    
                                   
                    <a href="manage_tutor_sessions.php?school=<?=$row['SchoolId']?>" 
                       class="btn btn-primary btn-sm">+Add Sessions</a> 
                         <br/>
                        <a  title="Avaialbe Homework Help Sessions" href="manage_tutor_sessions.php?school=<?=$row['SchoolId']?>" 
                        class="text-success">Homework Help(<?=$row['avaiable_slots'];?>)</a><br/>

                         <a  title="Avaialbe Intervention Sessions" href="manage_tutor_sessions.php?school=<?=$row['SchoolId']?>" 
                        class="text-info">Intervention(<?=$row['avaiable_interventions'];?>)</a>
                       
                                
                                </td>
                                <td class="text-center">
                                    (<?php print $student_count_res['total']; ?>)
                               <br/>     
                                 <?php echo date('M d, Y', strtotime($row['created_at']));?>   
                                    
                                </td>
                                
                                
	
				
                                
                                <td class="text-center">
					<?php //if($row['role'] == 0){ ?>
			<a onclick="confirmFunction()" href="?id=<?php echo $row['id']; ?>&ac=delete">Delete</a>|
		      <a href="manager-orders-new.php?id=<?php echo $row['id']; ?>&ac=edit">Edit</a>
					<?php //}?>
                                                
                     <a href="manager-orders.php?view=principal&id=<?=$row['SchoolId']?>" target="_blank" class="btn btn-info btn-sm">View principal</a>                   
                                                
                                                
				</td>
			</tr>
	<?php
		}
	} else {
		echo '<div class="clear"><p>There is no item found!</p></div>';
	}
	?>
</table>

<script>
function confirmFunction() {
    confirm("all related data will be removed and can not be restored!");
}
</script>
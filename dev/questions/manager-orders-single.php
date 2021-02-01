<?php
$states = array('AL'=>"Alabama",'AK'=>"Alaska",'AZ'=>"Arizona",'AR'=>"Arkansas",'CA'=>"California",'CO'=>"Colorado",'CT'=>"Connecticut"
,'DE'=>"Delaware",'DC'=>"District Of Columbia",'FL'=>"Florida",'GA'=>"Georgia",'HI'=>"Hawaii",'ID'=>"Idaho",'IL'=>"Illinois"
,'IN'=>"Indiana",'IA'=>"Iowa",'KS'=>"Kansas",'KY'=>"Kentucky",'LA'=>"Louisiana",'ME'=>"Maine",'MD'=>"Maryland"
,'MA'=>"Massachusetts",'MI'=>"Michigan",'MN'=>"Minnesota",'MS'=>"Mississippi",'MO'=>"Missouri",'MT'=>"Montana"
,'NE'=>"Nebraska",'NV'=>"Nevada",'NH'=>"New Hampshire",'NJ'=>"New Jersey",'NM'=>"New Mexico",'NY'=>"New York"
,'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma",'OR'=>"Oregon",'PA'=>"Pennsylvania"
,'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah"
,'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming");

$results = mysql_query("SELECT p. * , s.`SchoolName`
	FROM `purchases` p
	INNER JOIN `schools` s ON p.`schoolID` = s.`SchoolId`
	WHERE p.`id` = {$_GET['id']}");

if( mysql_num_rows($results) > 0 ) {
	$row = mysql_fetch_assoc($results);
	$payInfo = unserialize($row['payInfo']);
	
	$itemDetails = mysql_query("SELECT * FROM `terms` WHERE `id`
	IN (
		SELECT `termId`
		FROM `purchasemeta`
		WHERE `purchaseId` = {$_GET['id']}
	)
	AND `active` = 1
	ORDER BY `name` ASC");
?>
	<table class="table-manager-user col-md-12">
		<col width="40">
		<col width="160">
		<col width="200">
		<col width="70">
		<col width="80">
		<col width="100">
		<col width="70">
		<tr>
			<th>#</th>
			<th>Email</th>
			<th>School</th>
			<th>Amount</th>
			<th>Status</th>
			<th>Transaction</th>
			<th>Date</th>
		</tr>
		<tr>
			<td class="text-center"><?php echo $row['id'];?></td>
			<td><?php echo $row['email'];?></td>
			<td><?php echo $row['SchoolName'];?></td>
			<td class="text-center"><?php echo number_format($row['amount'], 2);?> (USD)</td>
			<td class="text-center"><?php echo $row['status'];?></td>
			<td class="text-center"><?php echo $row['transId'];?></td>
			<td class="text-center"><?php echo date('M d, Y', strtotime($row['created_at']));?></td>
		</tr>
	</table>
	
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<p>&nbsp;</p>
		<h3>Billing Details</h3>
	</div>
	
	<table class="table table-bordered">
		<col width="15%">
		<col width="35%">
		<col width="15%">
		<col width="35%">
		<tr>
			<th>First Name</th>
			<td><?php echo $payInfo['firstname']; ?></td>
			<th>Last Name</th>
			<td><?php echo $payInfo['lastname']; ?></td>
		</tr>
		<tr>
			<th>Address</th>
			<td><?php echo $payInfo['address']; ?></td>
			<th>Phone</th>
			<td><?php echo $payInfo['phone']; ?></td>
		</tr>
		<tr>
			<th>City</th>
			<td><?php echo $payInfo['city']; ?></td>
			<th>Zip Code</th>
			<td><?php echo $payInfo['zipcode']; ?></td>
		</tr>
		<tr>
			<th>State</th>
			<td><?php echo $states[$payInfo['state']]; ?></td>
			<th>Country</th>
			<td><?php echo $payInfo['country']; ?></td>
		</tr>
	</table>
	
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<p>&nbsp;</p>
		<h3>Item Details</h3>
		<?php
		if( mysql_num_rows($itemDetails) > 0 ) {
			while($item = mysql_fetch_array($itemDetails)) {
				echo "<p><a href='folder.php?taxonomy={$item['id']}' target='_blank'>{$item['name']}</a></p>";
			}
		}
		?>
	</div>
<?php
} else {
	echo '<div class="clear"><p>There is no item found!</p></div>';
}
?>
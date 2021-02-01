<?php
/****
@ Question Pagination 
echo  $_SERVER['QUERY_STRING'] ;  
    
 echo 'Pagenation ==========,<br/>';
 ?taxonomy=0&amp;paged=3&amp;per_page=20&amp;order_by=date_created&amp;order=DESC
//
****/
//echo  $_SERVER['QUERY_STRING'] ;  
    
 //echo 'Pagenation ==========,<br/>';


// Setup $array number of items per page
$numbers = array(10, 20, 50,100,200,500);


$taxonomy = '';
	$getTax = '';
/*check taxonomy if have taxonomy-> setup link*/
$taxonomy = '';
$getTax = '';
if(isset($_GET['taxonomy'])){
	$taxonomy = $_GET['taxonomy'];
	$getTax = 'taxonomy='.$taxonomy.'&';
}

// else{
// 	$taxonomy = '';
// 	$getTax = '';
// }
//////////If sapnish fileter exits ///////////////////////

 if(isset($_GET['lang_spanish'])&&!empty($_GET['lang_spanish'])){

   $getTax = 'lang_spanish='.$_GET['lang_spanish'].'&';

   // $getTax=$_SERVER['QUERY_STRING'];    //'taxonomy='.$taxonomy.'&';
 }


 ///Both Exitst ////
if(isset($_GET['taxonomy'])&& $_GET['taxonomy']>0&&isset($_GET['lang_spanish'])&&!empty($_GET['lang_spanish']) ){
	$getTax = 'taxonomy='.$taxonomy.'&lang_spanish='.$_GET['lang_spanish'].'&'; 
	//$getTax = 'lang_spanish='.$_GET['lang_spanish'].'&';

}


///////////////////////////
$url_order_by = isset($order_by) ? "&order_by=$order_by" : "";
$url_order = isset($order) ? "&order=$order" : "";

$url_id = (isset($_GET['id']) && is_numeric($_GET['id'])) ? 'id=' . $_GET['id'] . '&' : '';

$begin	= ($paged == 1		) ? 'javascript: void(0);' : '?'.$url_id.$getTax.'paged=1&per_page=' . $per_page . $url_order_by . $url_order;
$prev	= ($paged == 1		) ? 'javascript: void(0);' : '?'.$url_id.$getTax.'paged=' . ($paged - 1) . '&per_page=' . $per_page . $url_order_by . $url_order;
$next	= ($paged == $total	) ? 'javascript: void(0);' : '?'.$url_id.$getTax.'paged=' . ($paged + 1) . '&per_page=' . $per_page . $url_order_by . $url_order;
$end	= ($paged == $total	) ? 'javascript: void(0);' : '?'.$url_id.$getTax.'paged=' . $total . '&per_page=' . $per_page . $url_order_by . $url_order;
?>

<div class="pagination clear fullwidth">
            
         <?php  if(isset($_GET['lang_spanish'])&&!empty($_GET['lang_spanish'])){ 
         	$action_url="https://intervene.io/questions/questions.php?".$_SERVER['QUERY_STRING'];
         }


          ?>
         	 <form name="paginate" id="paginate" method="get" action=""   >
       
	     
	     


		<?php
			/*if have taxonomy -> add to $_GET --luvitas--*/
			if($taxonomy!=''){
				echo '<input type="hidden" name="taxonomy" value="'.$taxonomy.'">';
			}
			//////Field Spanish//
			if(isset($_GET['lang_spanish'])&&!empty($_GET['lang_spanish'])){
				$langVal=$_GET['lang_spanish'];

				echo '<input type="hidden" name="lang_spanish" value="'.$langVal.'">';
			}



			echo (isset($_GET['id']) && is_numeric($_GET['id'])) ? '<input type="hidden" name="id" value="'.$_GET['id'].'">' : '';
			
			echo isset($order_by) ? '<input type="hidden" name="order_by" value="'.$order_by.'">' : '';
			
			echo isset($order) ? '<input type="hidden" name="order" value="'.$order.'">' : '';
		?>
		<ul>
			<li><a href="<?php echo $begin; ?>"><img src="images/begin.png" /></a></li>
			<li><a href="<?php echo $prev; ?>"><img src="images/prev.png" /></a></li>
			<li><span>Page <?php echo $paged; ?> / <?php echo $total; ?></span></li>
			<li><a href="<?php echo $next; ?>"><img src="images/next.png" /></a></li>
			<li><a href="<?php echo $end; ?>"><img src="images/end.png" /></a></li>
		</ul>
		<span>Number of rows</span>
		<select name="per_page">
			<?php foreach( $numbers as $number ) {
				$selected = ($number == $per_page) ? ' selected="selected"' : '';
				echo "<option value='{$number}'{$selected}>{$number}</option>";
			} ?>
		</select>
	</form>
</div>

<script type="text/javascript">
$(document).ready(function() {
	$('form#paginate select[name=per_page]').on('change', function() {
		$('form#paginate').submit();
	});
});
</script>
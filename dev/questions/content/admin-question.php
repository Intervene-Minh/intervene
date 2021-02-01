<?php
/*
@ //  SELECT * FROM `questions` q WHERE category = 23 AND q.`passage`=0 ORDER BY `date_created` DESC  LIMIT 0 , 20

**/

// Setup title and where clause of query
$termId = (isset($_GET['taxonomy']) && is_numeric($_GET['taxonomy'])) ? $_GET['taxonomy'] : 0;
$select = mysql_fetch_assoc( mysql_query("SELECT * FROM `terms` WHERE `id` = {$termId} AND `active` = 1") );		# Return @boolean false if not found
if( $select ) {
	$title = $select['name'];
	$clause = ( $select['taxonomy'] == 'objective' )
			? " INNER JOIN `term_relationships` r ON q.`id` = r.`question_id` WHERE r.`objective_id` = {$termId}"
			: " WHERE {$select['taxonomy']} = {$termId}";
} else {
	$title = 'List Questions';
	$clause = '';
}

$passage_id = 0;
if(isset($_GET['passage']) && $_GET['passage']!="")$passage_id = $_GET['passage'];

if($clause==""){
	$passage_in = " WHERE q.`passage`=$passage_id"; 
}else{
	$passage_in = " AND q.`passage`=$passage_id";
}

// Pagination
if($passage_id > 0) {
	$per_page = ( isset($_GET['per_page']) && is_numeric($_GET['per_page']) && $_GET['per_page'] > 0 ) ? $_GET['per_page'] : 100;
}else{
$per_page = ( isset($_GET['per_page']) && is_numeric($_GET['per_page']) && $_GET['per_page'] > 0 ) ? $_GET['per_page'] : 20;
}
$paged = ( isset($_GET['paged']) && is_numeric($_GET['paged']) && $_GET['paged'] > 0 ) ? $_GET['paged'] : 1;
$query = mysql_query("SELECT `id` FROM `questions` q" . $clause . $passage_in);		# Count total of records
$count = (int) mysql_num_rows($query);		# Total of records
$total = (int) ceil($count / $per_page);	# Total of pages
$start = (int) ($paged - 1) * $per_page;	# Start of records
$limit = " LIMIT $start , $per_page";		# Limit number of records will be appeared

$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'date_created';
$order = isset($_GET['order']) ? $_GET['order'] : 'DESC';

// mysql_query
//  SELECT * FROM `questions` q WHERE category = 23 AND q.`passage`=0 ORDER BY `date_created` DESC  LIMIT 0 , 20

////////Test a Question for not spanish//////

$sql_ques=" SELECT id, question_spanish FROM `questions` WHERE `id` = '8237' ";
$Question=mysql_fetch_assoc(mysql_query($sql_ques)); ## Question>>''
# Empty paragraph//////////////<p>&nbsp;&nbsp;</p>/////////

$sql_ques=" SELECT id, question_spanish FROM `questions` WHERE `id` = '8233' ";
$Question=mysql_fetch_assoc(mysql_query($sql_ques));

// echo  'Question>>';
// var_dump($Question['question_spanish']); die;
 $sql_empty_para_questions="SELECT * FROM questions WHERE question_spanish= '<p>&nbsp;&nbsp;</p>' ";
  //echo 'Q==='  , mysql_num_rows(mysql_query($sql_empty_para_questions)); die; 


  
  $empty_paragraph='<p>&nbsp;&nbsp;</p>';

#######################################

 // spanish
 if(isset($_GET['lang_spanish'])&&$_GET['lang_spanish']=='spanish'){
 //  name_spanish question_spanish , answers_spanish  Not empty >> Q===304
 	

 	/////////////////////////
 	$sql_childs="SELECT * FROM `questions` q" . $clause . $passage_in;
 	$sql_childs.=" AND name_spanish!='' AND question_spanish!='' AND answers_spanish!='' AND question_spanish!='$empty_paragraph' ";
    $sql_childs.=" ORDER BY `" . $order_by . "` " . $order . " " . $limit;

 	$childs=mysql_query($sql_childs);

 	 //echo  '<pre>' , $sql_childs ;  
   // echo    mysql_num_rows($childs) ,'total==spanish'   ;    die; 

    
 
 }elseif(isset($_GET['lang_spanish'])&&$_GET['lang_spanish']=='not_spanish'){
   // any 1 is empty 
 	$sql_childs="SELECT * FROM `questions` q" . $clause . $passage_in;
 	 

 	$sql_childs.=" AND (question_spanish='$empty_paragraph' OR name_spanish='' OR question_spanish='' OR answers_spanish='' OR name_spanish IS NULL  OR question_spanish IS NULL  OR answers_spanish IS NULL  ) ";

 	//$sql_childs.=" ORDER BY `" . $order_by . "` " . $order . " " . $limit;
     $childs=mysql_query($sql_childs);


 	 //echo   mysql_num_rows($childs) ,'=total question' ,'not_spanish==='  ,'<pre>' , $sql_childs ;  die; 


 	


 }else{  //all mysql_query

 	$childs=mysql_query("SELECT * FROM `questions` q" . $clause . $passage_in . " ORDER BY `" . $order_by . "` " . $order . " " . $limit);
 	

 }



 // Old Query   





//////////////////////////

//$childsxxx = mysql_query("SELECT * FROM `questions` q WHERE 1 ORDER BY `id` DESC  LIMIT 0 , 20");

if($passage_id!=0){
	$result_passage = mysql_query("SELECT * FROM `passages` p WHERE `id` = $passage_id ORDER BY `date_created` DESC");
	$this_passage = mysql_fetch_assoc($result_passage);
	$title = $this_passage['title'];
}
//////////////////
$filter_option_arr=array('all'=>'All','not_spanish'=>'Not spanish','spanish'=>'Spanish');

?>
 


   <!-- search-users -->
   <div class="row">
   <form id="" method="GET"  >
   <input type="hidden" name="taxonomy" value="<?=(isset($_GET['taxonomy']))?$_GET['taxonomy']:0?>" >
    <div class="col-md-3"> 
   <select name="lang_spanish" class="form-control">
    <?php 
    foreach ($filter_option_arr as $key => $value) { 
    	//$select=''; // lang_spanish , selected
    	 $sel=(isset($_GET['lang_spanish'])&&$key==$_GET['lang_spanish'])?'selected':NULL;
    	?>
	<option <?=$sel?>  value="<?=$key?>"><?=$value?></option>
	<?php  }?>
								
	</select>
	</div>
	 <div class="col-md-2">


	 <button  type="submit"  class="btn" name="filter">Filter</button>
	 </div>
   </form> 
   </div>
   <br/>
    <!-- Select filter -->



<div id="folder_wrap" class="content_wrap">
    

    
	<div class="ct_heading clear">
		<h3><?php echo $title; ?>  &nbsp;&nbsp;&nbsp; <a style="color: #fff; text-decoration: underline;border: 1px solid;" 
		 href="language_issue_questions.php" class="btn" name="filter">language issue questions</a> </h3>
		<ul>
			<li><a href="single-question.php?action=new"><i class="fa fa-plus-circle"></i></a></li>
			<li><a href="#"><i class="fa fa-arrow-right"></i></a></li>
			<li><a href="javascript: void(0);" id="edit-question"><i class="fa fa-pencil-square-o"></i></a></li>
			<?php if( isGlobalAdmin() ) : ?>
				<li><a href="javascript: void(0);" class="remove_items" data-type="question"><i class="fa fa-trash"></i></a></li>
			<?php endif; ?>
		</ul>
	</div>		<!-- /.ct_heading -->
	
	<div class="ct_display no_padding clear">
		<?php
			if($passage_id!=0){
				echo '<div class="ct_display">';
				// echo '<h2>'.$this_passage['title'].'</h2>';
				echo $this_passage['content'];
				echo '</div>';
			}
		?>
		
		<div class="item-listing no_padding clear">
			<div class="col-md-1">&nbsp;</div>
			<div class="col-md-4 align-center">
				<h4>
					Question Name
					<a href="?<?php echo isset($_GET['taxonomy']) ? "taxonomy=" . $_GET['taxonomy'] . "&" : ""; ?>order_by=name&order=<?php echo ($order == 'DESC') ? 'ASC' : 'DESC'; ?>&per_page=<?php echo $per_page; ?>"><i class="fa fa-sort" aria-hidden="true"></i></a>
				</h4>
			</div>
			<div class="col-md-4 align-center">
				<h4>
					Spanish
					<a href="?<?php echo isset($_GET['taxonomy']) ? "taxonomy=" . $_GET['taxonomy'] . "&" : ""; ?>order_by=question_spanish&order=<?php echo ($order == 'DESC') ? 'ASC' : 'DESC'; ?>&per_page=<?php echo $per_page; ?>"><i class="fa fa-sort" aria-hidden="true"></i></a>
				</h4>
			</div>
			<div class="col-md-3 align-center">
				<h4>
					Publication
					<a href="?<?php echo isset($_GET['taxonomy']) ? "taxonomy=" . $_GET['taxonomy'] . "&" : ""; ?>order_by=public&order=<?php echo ($order == 'DESC') ? 'ASC' : 'DESC'; ?>&per_page=<?php echo $per_page; ?>"><i class="fa fa-sort" aria-hidden="true"></i></a>
				</h4>
			</div>
			<div class="clearnone fullwith">&nbsp;</div>
		</div>
		
            
            <ul class="ul-list">	
		<?php
		if( mysql_num_rows($childs) > 0 ) {
			$i = 1;
			while( $item = mysql_fetch_assoc($childs) ) {
				$line = ($i % 2 == 0) ? ' second' : '';
				if( $item['type'] == 1 ) {
					$echo = '<ul class="list-answers">';
					//lv-edit 04/05/2016
					$lv_answers = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $item['answers']);
					$answers = unserialize($lv_answers);
					//end
					foreach( $answers as $key => $answer ) {
						//lv-edit-2
						$answer['answer'] = str_replace('\"','"',$answer['answer']);
						//end
						$width = ( isset($answer['width']) && $answer['width'] != "" ) ? " width='" . $answer['width'] . "'" : "";
						$height = ( isset($answer['height']) && $answer['height'] != "" ) ? " height='" . $answer['height'] . "'" : "";
						$echo .= ($key % 2 == 1) ? '<li class="col-right">' : '<li>';
						$echo .= $answer['corect'] ? '<i class="fa fa-check fa-check-square-o"></i>' : '';
						$echo .= '<p>' . $answer['answer'] . '</p>';
						$echo .= ($answer['image'] != '') ? '<p><img src="' . $answer['image'] . '"' . $width . $height . ' /></p>' : '';
						if( $answer['explain'] != '' ) {
							$explain = mysql_fetch_assoc(mysql_query("SELECT `name` FROM `distrators` WHERE `id` = " . $answer['explain']));
							$echo .= $explain ? '<p><i>' . $explain['name'] . '</i></p>' : '';
						}
						$echo .= ($key % 2 == 1) ? '</li><div class="clearnone">&nbsp;</div>' : '</li>';
					}
					$echo .= '</ul>';
				} else {
					$echo = $item['answers'];
				}
		?>
        <!--	<div class="item-listing clear<?php echo $line; ?>" >ques-text-->
                <li title="Question" style="border:1px solid #f4f4f4;">
                             <div class="item-listing">
            
					<div class="col-md-1">
						<input type="checkbox" name="questions[]" class="edit_items" value="<?php echo $item['id']; ?>" title="Edit Question (<?php echo $item['name']; ?>)" />
					</div>
					<div class="col-md-9">
					<a style="color: red;" href="questions.php?quid=<?=$item['id']?>">Duplicate question</a>

						<a href="javascript: void(0);" class="toggle-detail"><?php echo $item['name']; ?></a>


						<div class="item-detail clear fullwith">
							<?php echo $item['question']; ?>
							<?php echo $echo; ?>
							<div class="clearnone">&nbsp;</div>
						</div><div class="ques-button">
						<button class="add-to-list" name="add_to_list" value="<?php echo $item['id'];?>">Choose Me<i class="fa fa-heart"></i></button>			
					</div>
					</div>
					<div class="col-md-2 align-center">
						<i class="fa fa-<?php echo $item['public'] ? 'check' : 'minus'; ?>" aria-hidden="true"></i>
						
					</div>
					
					<div class="clearnone fullwith">&nbsp;</div>
				</div>
                </li>
		<?php
				$i++;
			}
		} else {
			echo '<div class="item-listing clear"><p>There is no item found!</p></div>';
		}
		?>
            </ul>
		<div class="clearnone">&nbsp;</div>
	</div>		<!-- /.ct_display -->
</div>		<!-- /#folder_wrap -->
<script type="text/javascript">
		$(document).ready(function(){
			var $count =0;
			var $timehidden;
			$('.add-to-list').on('click',function(){ 
				
				var item = $(this).parents('li').first()
				$count++;
				
				/*store id to list*/
				var $id = $(this).val();
                               // alert($id);exit;
				$.ajax({
					type	: 'POST',
					url		: 'inc/ajax-add_to_list.php',
					data	: {
						'add_to_list':$id,
						'is_passage':<?php echo $passage_id;?>
					},
					dataType: 'json',
					success	: function(response) {
						if(response.check){
							item.slideUp(500);
							// var is_unlimited = response.is_unlimited;
							var count = response.count;
							// var remaining = response.remaining;
							
							// if(is_unlimited){
								// remaining = ' Unlimited';
							// }else{
								// if(remaining <0){
									// if(remaining=='-1')$('.alert-q-remaining').show();
									// remaining = 0;
								// }
							// }
							
							$('.list-notification>.text>.number').text(count);
							// $('.list-notification>.text>.remaining').text(remaining);
							$('.list-fixed').show();
							clearTimeout($timehidden);
							$timehidden = setTimeout(function() {
								$('.list-fixed').hide(500);
							}, 10000);
							
						}else{
							alert("Can't add this question");
						}
					}
				});
				
				
			});
			$('.list-notification').on('click',function(){
				$(this).parents('.list-fixed').first().hide(500);
			});
			$('.alert-q-remaining .fa.fa-times').on('click',function(){
				$(this).parents('.alert-q-remaining').first().hide(500);
			});
                        
                        
                        
                      ////////////////submit_error////////////////////  
                        
			
		});
	</script>
	<div class="list-fixed">
	<div class="list-notification">
		<i class="fa fa-times"></i>
		<div class="text">A problem has been added (<span class="number">0</span> problems total)</div>
	</div>
</div>
<?php if( mysql_num_rows($childs) > 0 ) include("pagination_ques.php");    // pagination_ques.php //   pagination_ques

    ?>
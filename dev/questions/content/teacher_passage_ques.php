<?php
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


// Public Questions ::Teacher User
  //$public_question = ($_SESSION['login_role'] == 1) ? " AND `q`.`public` = 1 " : "";// School assessment includes
$public_question = ($_SESSION['login_role'] == 1) ? " AND `q`.`public` = 1 AND `q`.`smart_prep` = 1 " : "";// SmartPrep==1& School assessment includes




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
// Publice==1&smart==1 Question for teacher User
$childs =mysql_query("SELECT * FROM `questions` q" . $clause . $passage_in .$public_question. " ORDER BY `" . $order_by . "` " . $order . " " . $limit);
// OLD //$childs =mysql_query("SELECT * FROM `questions` q" . $clause . $passage_in . " ORDER BY `" . $order_by . "` " . $order . " " . $limit);
//echo '<pre>', $childs; die;

//$childsxxx = mysql_query("SELECT * FROM `questions` q WHERE 1 ORDER BY `id` DESC  LIMIT 0 , 20");

if($passage_id!=0){
	$result_passage = mysql_query("SELECT * FROM `passages` p WHERE `id` = $passage_id ORDER BY `date_created` DESC");
	$this_passage = mysql_fetch_assoc($result_passage);
	$title = $this_passage['title'];
}
?>

<div id="folder_wrap" class="content_wrap">
	<div class="ct_heading clear">
		<h3><?php echo $title; ?></h3>
		<!-- <ul>
			<li><a href="#"><i class="fa fa-question"></i></a></li>
			
		</ul> -->
	</div>		<!-- /.ct_heading -->
	
	<div class="ct_display no_padding clear">
		<?php   // Ques>0,
			if($passage_id!=0&&mysql_num_rows($childs) >0){
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
        <!--	<div class="item-listing clear<?php// echo $line; ?>" >ques-text-->
                <li title="Question" style="border:1px solid #f4f4f4; list-style: none;">
                             <div class="item-listing">
            
                <!--	<div class="col-md-1"></div>-->
						
					
                                 
					<div class="col-md-10">
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
						&nbsp;<button title="ID:<?=$item['id']; ?>" type="button" 
						class="btn btn-primary btn-xs">ID</button>
						<!-- <span class="text-info">ID:&nbsp;<?php // echo $item['id']; ?></span> -->
						
					</div>
					
					<div class="clearnone fullwith">&nbsp;</div>
				</div>
                </li>
		<?php
				$i++;
			}
		} else {
                    $taxonomy_id=$_GET['taxonomy'];
			echo '<div class="item-listing clear"><p>There is no item found!. -<a class="text-danger" href="folder.php?taxonomy='.$taxonomy_id.'">Go, Back</a></p></div>';
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
                               // alert($id);exit;  smart_add_to_list.php # ajax-add_to_list.php
				$.ajax({
					type	: 'POST',
					url		: 'inc/smart_add_to_list.php',
					data	: {
						'add_to_list':$id,
						'is_passage':<?php echo $passage_id;?>
					},
					dataType: 'json',
					success	: function(response) {
                                            console.log("TEST::"+response.check);
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
<?php if( mysql_num_rows($childs) > 0 ) include("pagination.php"); ?>
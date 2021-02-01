<?php
	die();
	include('connection.php');
	session_start();
	ob_start();
	function curPageName() {
	 return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
	}

	if( !(isset($_SESSION['login_user'])&&isset($_SESSION['login_id'])&&isset($_SESSION['login_role'])) ) {
		if(!(curPageName()=='login.php'||curPageName()=='signup.php')){
			header('Location: login.php');
			exit;
		}
	}else{
		if(curPageName()=='login.php'||curPageName()=='signup.php'){
			header("location: index.php");
			exit;
		}
	}
	require_once('check-status.php');
	$status = checkStatus();
	if($status==0){
		header("Location: ../arrange.php");
		die();
	}
	
	//check question remaining
// if(isset($_SESSION['list'])){
	// require_once('update-user-question.php');
	// if(!is_unlimited($_SESSION['login_id']) && getQuestionsRemaining($_SESSION['login_id']) < count($_SESSION['list'])){
		// header("location: ../arrange.php");//not enough question remaining
		// exit;
	// }
// }
	
	
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

if(isset($_SESSION['list'])){
	#if !isset $_SESSION['list'] no query it.
	if($clause==""){
		$in = " WHERE q.`id` IN ( '" . implode($_SESSION['list'], "', '") . "' )"; 
	}else{
		$in = " AND q.`id` IN ( '" . implode($_SESSION['list'], "', '") . "' )";
	}
	$orderby = " ORDER BY FIELD(q.`id`,'". implode($_SESSION['list'], "', '")."')";
}else{
	if($clause==""){
		$in = " WHERE q.`id` IN ( '' )"; 
	}else{
		$in = " AND q.`id` IN ( '' )";
	}
	$orderby ="";
}

$innerjoin =" INNER JOIN `terms` t ON (q.`category` = t.`id` or t.`id` = (
	select `term_relationships`.`objective_id` from `term_relationships` where `term_relationships`.`question_id` = q.`id` LIMIT 1
))";
$groupby = ' GROUP BY q.`id`';

$childs = mysql_query("SELECT q.*, GROUP_CONCAT(t.`name` SEPARATOR '|') AS terms FROM `questions` q " . $innerjoin ." AND t.`taxonomy`='objective'" . $clause . $in . $groupby. $orderby);
// echo "SELECT q.*, GROUP_CONCAT(t.`name` SEPARATOR '|') AS terms FROM `questions` q " . $innerjoin ." AND t.`taxonomy`='objective'" . $clause . $in . $groupby. $orderby;

$lang = '';
if(isset($_GET['lang']) && $_GET['lang'] == 'spanish') $lang = '_spanish';

// while( $item = mysql_fetch_assoc($childs) ) {
	// $lv_answers = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $item['answers'.$lang]);
	// $answers = unserialize($lv_answers);
	// echo '<pre>';
	// print_r($answers);
	// echo '</pre>';
// }
// exit();
?>
<link type="text/css" href="../css/print.css" rel="stylesheet" />
<link type="text/css" href="../css/font-awesome.min.css" rel="stylesheet" />
<link type="text/css" href="../css/bootstrap.min.css" rel="stylesheet" />
<?php
$array = array();
$bulet = array(0 => 'A', 1 => 'B', 2 => 'C', 3 => 'D');

$header = '
	<htmlpageheader name="main">
		<div class="ct_heading clear">
			<p align="left" style="float:left;width:50%;">Name: ___________________________</p>
			<p align="right"style="float:right;width:50%;text-align:right;text-indent:30px;">ID: ___________________________</p>
		</div>
	</htmlpageheader>';
$content = '<div id="list-document" class="content_wrap">'.$header.'
	<sethtmlpageheader name="main" value="on" show-this-page="1" />
	<sethtmlpageheader name="main" value="off" show-this-page="1" />
	<div id="instructions-example" class="ct_display clear">
		<div class="underline">
			<strong>Instructions:</strong>
		</div>
		<div>
			<div class="instructions-example-item"><strong>1. Administer quiz</strong></div>
			<div class="instructions-example-item"><strong>2. Pass out Student Answer key. Ask students to write their answers in the "Answer" column and mark/circle their corresponding distractor in the next column.
				</strong><div>
					<div><strong>Example:</strong></div>
					<img src="../images/example.png">
				</div>
			</div>
			<div class="instructions-example-item"><strong>3. Ask student to answer the question below:</strong>
				<div><strong>Which distractor, if any, did you choose the most? Process - Wrong operation. If they chose two the most, then ask them to write both.
				</strong></div>
			</div>
			<div class="instructions-example-item"><strong>4. Teachers can sort and group the students based on answer to #3.</strong></div>
			<div class="instructions-example-item"><strong>5. Use the small group schedule below and mini lesson resources for Process – Wrong Operation from our website to teach/reteach.</strong>
				<div>
					<img src="../images/example2.png">
				</div>
			</div>
		</div>
		<pagebreak />
	';
		
		
		
		
			if( mysql_num_rows($childs) > 0 ) {
				$i = 1;
				while( $item = mysql_fetch_assoc($childs) ) {
					if( $item['type'] == 1 ) {
						$echo = '<table class="list-answer">';
						$j = 0;
						//lv-edit 04/05/2016
						$lv_answers = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $item['answers'.$lang]);
						$answers = unserialize($lv_answers);
						// $answers = unserialize($item['answers']);
						//end
						
						$distractors = array();
						foreach( $answers as $key => $answer ) {
							$converted = strtr($answer['answer'], array_flip(get_html_translation_table(HTML_ENTITIES, ENT_QUOTES)));
							$clear = strip_tags($converted);
							$result = trim($clear, chr(0xC2) . chr(0xA0));
							$result = trim($result);
							//lv-edit-2
							$answer['answer'] = str_replace('\"','"',$answer['answer']);
							//end
							$width = ( isset($answer['width']) && $answer['width'] != "" ) ? " width='" . $answer['width'] . "'" : "";
							$height = ( isset($answer['height']) && $answer['height'] != "" ) ? " height='" . $answer['height'] . "'" : "";
							$echo .= '<tr>';
							$echo .= '<td style="width:20px;">' . $bulet[$j] . '.</td>';
							$echo .= ( $result == "" ) ? "<td >" : '<td >' . trim($answer['answer']);
							$echo .= ($answer['image'] != '') ? '<p><img src="../' . $answer['image'] . '"' . $width . $height . ' /></p></td>' : '</td>';
							$echo .= '</tr>';
							$j++;
							
							if($answer['corect']) {
								$distractors[] = "&nbsp;";
								$corect = $bulet[$key];
							} else {
								$getDist = mysql_query("SELECT `name` FROM `distrators` WHERE `id` = {$answer['explain']}");
								$explain = mysql_fetch_assoc($getDist);
								$distractors[] = $explain["name"];
							}
						}
						$echo .= '</table>';
					} else {
						$echo = "";
						$corect = trim($item['answers'.$lang]);
						$distractors = array("&nbsp;", "&nbsp;", "&nbsp;", "&nbsp;");
					}
					$content .= '<table class="tab-question">
						<tr>
							<td valign="top" style="width: 30px;">' . $i . '.</td>
							<td valign="top">' . $item['question'.$lang] . $echo . '</td>
						</tr>
					</table>';
					$tmp_term = explode("|", $item['terms']);
					$tmp = array(
						'question'		=> $i,
						'objective'		=> $tmp_term[0],
						'answer'		=> $corect,
						'distractors'	=> $distractors
					);
					array_push($array,$tmp);
					$i++;
				}
				//student test
				$content .='<pagebreak />
				<table id="tab-answer">
					<thead>
						<tr>
							<th>Question</th>
							<th>Objective</th>
							<th>Answer</th>
							<th>Distractor</th>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td style="padding: 0;">
								<table style="width: 100%; height: 100%;">
									<tr>
										<td style="width: 25%; height: 100%; border: 0; text-align: center;">A</td>
										<td style="width: 25%; height: 100%; border: 0; text-align: center;">B</td>
										<td style="width: 25%; height: 100%; border: 0; text-align: center;">C</td>
										<td style="width: 25%; height: 100%; border: 0; text-align: center;">D</td>
									</tr>
								</table>
							</td>
						</tr>
					</thead>
					<tbody>';
				foreach($array as $key => $value){
					$explains = "";
					foreach( $value["distractors"] as $k => $ditractor ) {
						$border = ( $k == 3 ) ? "" : "border-right: 1px solid #000000;";
						$explains .= "<td style='width: 25%; height: 100%; border: 0;'>$ditractor</td>";
					}
					$content .='
						<tr>
							<td align="center">'.$value['question'].'</td>
							<td>'.$value['objective'].'</td>
							<td>&nbsp;</td>
							<td style="padding: 0;">
								<table style="width: 100%; height: 100%;">
									<tr>' . $explains . '</tr>
								</table>
							</td>
						</tr>';
				}
				$content .='</tbody></table>';
				$content .='<div id="text-after-question"><strong>Which Distractor, if any, did you choose the most?  ___________________<strong></div>';
				
				
				
				//teach answer key
				$content .='<pagebreak />';
				
				$content .='<div id="teach-anskey" class="underline"><strong>Teacher Answer Key<strong></div>';
				
				$content .='
				<table id="tab-answer">
					<thead>
						<tr>
							<th>Question</th>
							<th>Objective</th>
							<th>Answer</th>
						</tr>
					</thead>
					<tbody>';
				foreach($array as $key => $value){
					$explains = "";
					foreach( $value["distractors"] as $k => $ditractor ) {
						$border = ( $k == 3 ) ? "" : "border-right: 1px solid #000000;";
						$explains .= "<td style='width: 25%; height: 100%; border: 0;'>$ditractor</td>";
					}
					$content .='
						<tr>
							<td align="center">'.$value['question'].'</td>
							<td>'.$value['objective'].'</td>
							<td>'.$value['answer'].'</td>
						</tr>';
				}
				$content .='</tbody></table>';
				
				
				$content .='<pagebreak />';
				$content .='<table id="small-group">
					<thead>
						<tr>
							<th colspan="2" style="border-right:none;">
								<div style="font-size:22px; margin-bottom:15px;">Small Group Schedule</div>
							</th>
							<th colspan="2" style="border-left:none;">
								<div>Week of ____________________</div>
							</th>
						</tr>
						<tr>
							<th style="height:75px;line-height:30px;vertical-align:top;">Mini lesson:&nbsp;</th>
							<th style="height:75px;line-height:30px;vertical-align:top;">Mini lesson:&nbsp;</th>
							<th style="height:75px;line-height:30px;vertical-align:top;">Mini lesson:&nbsp;</th>
							<th style="height:75px;line-height:30px;vertical-align:top;">Mini lesson:&nbsp;</th>
						</tr>
					</thead>
					<tbody>';
					for($i=0;$i<16;$i++){
						$content .='
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>';
					}
				$content .='
					</tbody>
				</table>
				';
			} else {
				$content .= '<div class="item-listing clear"><p>There is no item found!</p></div>';
			}
	$content .='
	</div>
</div>';
?>
<?php
	$content = str_replace('&nbsp;',' ',$content);
	
	// echo $content;
	// die();
	/*echo '<pre>';
	print_r($array);
	echo '</pre>';
	 */
	// if(isset($_POST['print'])&&isset($_POST['print-content'])){
		
		ob_clean();
		header('Content-type: application/pdf; charset=utf-8');
		header('Content-Disposition: inline; filename=""');
		header('Content-Transfer-Encoding: binary');
		header('Accept-Ranges: bytes');
		
		$content = str_replace('<strong>','<strong style="font-weight:bold;">',$content);
		$content = str_replace('<b>','<strong style="font-weight:bold;">',$content);
		$content = str_replace('<p><img style="display: block; margin-left: auto; margin-right: auto;"', '<p style="text-align:center;"><img style="display: block; margin-left: auto; margin-right: auto;"', $content);
		
		
		$html = $content;
		include('../mpdf/mpdf.php'); // including mpdf.php
		
		$mpdf=new mPDF('c','A4','','',25,25,27,25,16,13); 
		$mpdf->SetHTMLFooter('<p style="text-align: center">www.lonestaar.com</p>');
		$mpdf->SetDisplayMode('fullpage');
		$mpdf->shrink_tables_to_fit=1;
		$mpdf->list_indent_first_level = 0; 
		$stylesheet  = '';
		$stylesheet .= file_get_contents('../css/print.css');
		$stylesheet .= file_get_contents('../css/font-awesome.min.css');
		$stylesheet .= file_get_contents('../css/bootstrap.min.css');
		$mpdf->WriteHTML($stylesheet, 1);
		// $mpdf->SetHTMLHeader($header,'',TRUE);
		$mpdf->WriteHTML($html);
		$mpdf->Output('Question.pdf','D');
		

		
		//updateAfterPrint($_SESSION['login_id'],count($_SESSION['list']));
		ob_end_flush();
		
	// }
?>
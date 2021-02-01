<?php
	include('connection.php');
        
	session_start();
	ob_start();
	function curPageName() {
	 return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
	}
	
	// Query current logged in user email
	$query_email = mysql_query("SELECT `email` FROM `demo_users` WHERE `id` = {$_SESSION['demo_user_id']}");
	$user_email = mysql_fetch_assoc($query_email)['email'];

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
/* 
$header = '
	<htmlpageheader name="main">
		<div class="ct_heading clear">
			<p align="left" style="float:left;width:50%;">Name: ___________________________</p>
			<p align="right"style="float:right;width:50%;text-align:right;text-indent:30px;">ID: ___________________________</p>
		</div>
	</htmlpageheader>';
 */
$header = '
	<htmlpageheader name="main">
		<div class="ct_heading clear">
			<div class="underline">
				<h3 style="text-align:center;">Student Gap Analysis Testing</h3>
			</div>
		</div>
	</htmlpageheader>';
$content = '<div id="list-document" class="content_wrap">'.$header.'
	<sethtmlpageheader name="main" value="on" show-this-page="1" />
	<sethtmlpageheader name="main" value="off" show-this-page="1" />
	<div id="instructions-example" class="ct_display clear">';

$instruction = '<div class="underline">
			<h3 style="text-align:center;">Instructions</h3>
		</div>
		<div>
			<div class="instructions-example-item"><strong>1. Administer quiz</strong></div>
			<div class="instructions-example-item"><strong>2. Record Answers:</strong> Write answers in “Answer” column and mark/circle their corresponding distractor in the next column.
				<div>
					<div>Example:</div>
					<img src="../images/example1.png" />
				</div>
			</div>
			<div class="instructions-example-item"><strong>3. Analyze Student Gaps:</strong> Which distractor, if any, did student choose the most?
				<div>Example: Process - Wrong operation. If student chose two the most, then ask them to write both</div>
			</div>
			<div class="instructions-example-item"><strong>4. Group Students:</strong> Sort and group the students based on distractor.</div>
			<div class="instructions-example-item"><strong>5. Target Intervention:</strong> Break students into small groups based on distractor.
				<div><i>P2G can provide additional targeted concept questions and tutorials/ interventions to teach, reinforce, and verify student mastery of identified concept gaps.</i></div>
			</div>
			<div class="instructions-example-item">How to use our Small Group Schedule to target intervention:
			<div>
				<img src="../images/example2.png">
			</div>
		</div>
		<pagebreak />';

		// Include section if current logged in user is test@lesstestprep.com
		// $content .= ($user_email == 'test@lesstestprep.com') ? $instruction : '';
		if($_GET['for'] != 'teacher') {
			$content .= $instruction;
		}


			if( mysql_num_rows($childs) > 0 ) {
				$i = 1;
				while( $item = mysql_fetch_assoc($childs) ) {
					if( $item['type'] == 1 ) {
						$echo = '<table class="list-answer">';
						$j = 0;
						// lv-edit 04/05/2016
						$lv_answers = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $item['answers'.$lang]);
						$answers = unserialize($lv_answers);
						// $answers = unserialize($item['answers']);
						// end
						
						// Check multiple images
						$multiple = ($answers[0]['image'] != '' && $answers[1]['image'] != '' && $answers[2]['image'] != '' && $answers[3]['image'] != '') ? true : false;
						
						$distractors = array();
						$answerTable = array();
						
						foreach( $answers as $key => $answer ) {
							$converted = strtr($answer['answer'], array_flip(get_html_translation_table(HTML_ENTITIES, ENT_QUOTES)));
							$clear = strip_tags($converted);
							$result = trim($clear, chr(0xC2) . chr(0xA0));
							$result = trim($result);
							// lv-edit-2
							$answer['answer'] = str_replace('\"','"',$answer['answer']);
							// end
							$width = ( isset($answer['width']) && $answer['width'] != "" ) ? " width='" . $answer['width'] . "'" : "";
							$height = ( isset($answer['height']) && $answer['height'] != "" ) ? " height='" . $answer['height'] . "'" : "";
							
							$String = '<td style="width:20px;">' . $bulet[$j] . '.</td>';
							$String .= ( $result == '' ) ? '<td>' : '<td>' . trim($answer['answer']);
							$String .= ($answer['image'] != '') ? '<p><img src="../' . $answer['image'] . '"' . $width . $height . ' /></p></td>' : '</td>';
							
							$answerTable[] = $String;
							
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
						$echo .= $multiple
							? '<tr>' . $answerTable[0] . $answerTable[2] . '</tr><tr>' . $answerTable[1] . $answerTable[3] . '</tr>'
							: '<tr>' . $answerTable[0] . '</tr><tr>' . $answerTable[1] . '</tr><tr>' . $answerTable[2] . '</tr><tr>' . $answerTable[3] . '</tr>';
							
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
				
				// Include section if current logged in user is test@lesstestprep.com
				// if( $user_email == 'test@lesstestprep.com' ) {
					// student test
					if($_GET['for'] != 'teacher') {
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
					}
				// }
				
				
				// teach answer key
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
				
				if($_GET['for'] != 'teacher') {
				// Include section if current logged in user is test@lesstestprep.com
				// if( $user_email == 'test@lesstestprep.com' ) {
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
				}
					$content .='</tbody></table>';
				// }
			} else {
				$content .= '<div class="item-listing clear"><p>There is no item found!</p></div>';
			}
	$content .='
	</div>
</div>';
?>
<?php
	$content = str_replace('<p>&nbsp;</p>','<br />',$content);
	$content = str_replace('&nbsp;',' ',$content);
	/* 
	echo $content;
	die();
	
	echo '<pre>';
	print_r($array);
	echo '</pre>';
	exit;
	 */
	// if(isset($_POST['print'])&&isset($_POST['print-content'])){
		unset($_SESSION['list']);
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
		$mpdf->SetHTMLFooter('<div class="mpdf-footer">Pathways 2 Greatness, LLC &copy; '.date("Y").'</div>');
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
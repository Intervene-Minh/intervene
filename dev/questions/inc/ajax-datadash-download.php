<?php
include('connection.php');
session_start();
ob_start();

function curPageName()
{
    return substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1);
}

if (!(isset($_SESSION['login_user']) && isset($_SESSION['login_id']) && isset($_SESSION['login_role']))) {
    if (!(curPageName() == 'login.php' || curPageName() == 'signup.php')) {
        header('Location: login.php');
        exit;
    }
} else {
    if (curPageName() == 'login.php' || curPageName() == 'signup.php') {
        header("location: index.php");
        exit;
    }
}
require_once('check-status.php');
$status = checkStatus();
if ($status == 0) {
    header("Location: ../save&print.php");
    die();
}

// Query current logged in user email
$query_email = mysql_query("SELECT `email` FROM `users` WHERE `id` = {$_SESSION['login_id']}");
$user_email = mysql_fetch_assoc($query_email)['email'];
$array = array();
$qn_ctr = 0;
$bulet = array(0 => 'A', 1 => 'B', 2 => 'C', 3 => 'D');
$header = '
	<htmlpageheader name="main">
		<div class="ct_heading clear">
			<div class="underline">
				<h3 style="text-align:center;">Smart Prep <sup>TM</sup> Misconception & Distractor Testing</h3>
			</div>
		</div>
	</htmlpageheader>';


$content = '<div id="list-document" class="content_wrap">' . $header . '
	<sethtmlpageheader name="main" value="on" show-this-page="1" />
	<sethtmlpageheader name="main" value="off" show-this-page="1" />
	<div class="ct_display clear">';
if (count($_SESSION['list']) > 0) {
    for ($l = 0; $l < count($_SESSION['list']); $l++) {
        $qn_ctr = $qn_ctr + 1;
        $temp_list = array();
        $temp_list[] = $_SESSION['list'][$l];
        $termId = (isset($_GET['taxonomy']) && is_numeric($_GET['taxonomy'])) ? $_GET['taxonomy'] : 0;

        $select = mysql_fetch_assoc(mysql_query("SELECT * FROM `terms` WHERE `id` = {$termId} AND `active` = 1"));
        if ($select) {
            $title = $select['name'];
            $clause = ($select['taxonomy'] == 'objective') ? " INNER JOIN `term_relationships` r ON q.`id` = r.`question_id` WHERE r.`objective_id` = {$termId}" : " WHERE {$select['taxonomy']} = {$termId}";
        } else {
            $title = 'List Questions';
            $clause = '';
        }

        if (isset($_SESSION['list'])) {
            #if !isset $_SESSION['list'] no query it.
            if ($clause == "") {
                $in = " WHERE q.`id` IN ( '" . implode($temp_list, "', '") . "' )";
            } else {
                $in = " AND q.`id` IN ( '" . implode($temp_list, "', '") . "' )";
            }
            $orderby = " ORDER BY FIELD(q.`id`,'" . implode($temp_list, "', '") . "')";
        } else {
            if ($clause == "") {
                $in = " WHERE q.`id` IN ( '' )";
            } else {
                $in = " AND q.`id` IN ( '' )";
            }
            $orderby = "";
        }

        $innerjoin = " INNER JOIN `terms` t ON (q.`category` = t.`id` or t.`id` = (
        select `term_relationships`.`objective_id` from `term_relationships` where `term_relationships`.`question_id` = q.`id` LIMIT 1
        ))";
        $groupby = ' GROUP BY q.`id`';

        $passage_in_list = mysql_query("SELECT DISTINCT `q`.`passage`,p.*  FROM `questions` q INNER JOIN `passages` p ON `q`.`passage` = `p`.`id` " . $clause . $in . $orderby);

        $lang = '';
        if (isset($_GET['lang']) && $_GET['lang'] == 'spanish') {
            $lang = '_spanish';
        }
        ?>
        <link type="text/css" href="../css/print.css" rel="stylesheet"/>
        <link type="text/css" href="../css/font-awesome.min.css" rel="stylesheet"/>
        <link type="text/css" href="../css/bootstrap.min.css" rel="stylesheet"/>
        <?php

        $numResults = mysql_num_rows($passage_in_list);
        $counter = 1;
        $lastrow = 0;
        $i = 1;

        if (mysql_num_rows($passage_in_list) > 0) {
            $content .= '<div id="passage-list" class="save-print-list">';
            while ($item_passage = mysql_fetch_assoc($passage_in_list)) {
                $lastrow++;
                $content .= '<div>';
                // $content .= '<div class="passage-title">'.($counter+1).'. '.$item_passage['title'].'</div>';
                $content .= '<div class="passage-content">' . $item_passage['content' . $lang] . '</div>';

                $str = "SELECT  q.*, GROUP_CONCAT(t.`name` SEPARATOR '|') AS terms FROM `questions` q " . $innerjoin . " AND t.`taxonomy`='objective'" . $clause . $in . ' AND `q`.`passage`= ' . $item_passage['id'] . $groupby . $orderby;
                $childs = mysql_query($str);
                if (mysql_num_rows($childs) > 0) {
                    while ($item = mysql_fetch_assoc($childs)) {
                        if ($item['type'] == 1) {
                            $echo = '<table class="list-answer">';
                            $j = 0;
                            // lv-edit 04/05/2016
                            $lv_answers = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $item['answers' . $lang]);
                            $answers = unserialize($lv_answers);
                            $multiple = ($answers[0]['image'] != '' && $answers[1]['image'] != '' && $answers[2]['image'] != '' && $answers[3]['image'] != '') ? true : false;
                            $number = 0;
                            $distractors = array();
                            foreach ($answers as $key => $answer) {
                                // lv-edit-2
                                $answer['answer'] = str_replace('\"', '"', $answer['answer']);
                                // end
                                $width = (isset($answer['width']) && $answer['width'] != "") ? " width='" . $answer['width'] . "'" : "";
                                $height = (isset($answer['height']) && $answer['height'] != "") ? " height='" . $answer['height'] . "'" : "";

                                if (!$multiple || ($multiple && ($number % 2 == 0))) {
                                    $echo .= '<tr>';
                                }

                                $echo .= '<td style="width:20px; vertical-align:top;">' . $bulet[$j] . '.</td>';
                                $echo .= '<td>' . trim($answer['answer']);
                                $echo .= ($answer['image'] != '') ? '<p><img src="../' . $answer['image'] . '"' . $width . $height . ' /></p></td>' : '</td>';

                                if (!$multiple || ($multiple && ($number % 2 == 0))) {
                                    $echo .= '<tr>';
                                }

                                $j++;

                                if ($answer['corect']) {
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
                            $corect = trim($item['answers' . $lang]);
                        }
                        $content .= '<table class="tab-question">
							<tr>
								<td valign="top" style="width:30px;">' . $qn_ctr . '.</td>
								<td valign="top">' . $item['question' . $lang] . $echo . '</td>
							</tr>
						</table>';
                        // print_r($item['terms']);
                        $tmp_term = explode("|", $item['terms']);
                        $tmp = array(
                            'question' => $qn_ctr,
                            'objective' => $tmp_term[0],
                            'answer' => $corect,
                            'distractors' => $distractors
                        );
                        array_push($array, $tmp);
                    }
                    $content .= '<pagebreak />';
                }
            }
        }
    }
    $content .= '
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
    foreach ($array as $key => $value) {
        $explains = "";
        foreach ($value["distractors"] as $k => $ditractor) {
            $border = ($k == 3) ? "" : "border-right: 1px solid #000000;";
            $explains .= "<td style='width: 25%; height: 100%; border: 0;'>$ditractor</td>";
        }
        $content .= '
									<tr>
										<td align="center">' . $value['question'] . '</td>
										<td>' . $value['objective'] . '</td>
										<td>&nbsp;</td>
										<td style="padding: 0;">
											<table style="width: 100%; height: 100%;">
												<tr>' . $explains . '</tr>
											</table>
										</td>
									</tr>';
    }
    $content .= '</tbody></table>';

    $content .= '<div id="text-after-question"><strong>Which Distractor, if any, did you choose the most?  ___________________<strong></div>';
// teach answer key
    $content .= '<pagebreak />';

    $content .= '<div id="teach-anskey" class="underline"><strong>Teacher Answer Key<strong></div>';

    $content .= '
						<table id="tab-answer">
							<thead>
								<tr>
									<th>Question</th>
									<th>Objective</th>
									<th>Answer</th>
								</tr>
							</thead>
							<tbody>';
    foreach ($array as $key => $value) {
        $explains = "";
        foreach ($value["distractors"] as $k => $ditractor) {
            $border = ($k == 3) ? "" : "border-right: 1px solid #000000;";
            $explains .= "<td style='width: 25%; height: 100%; border: 0;'>$ditractor</td>";
        }
        $content .= '
								<tr>
									<td align="center">' . $value['question'] . '</td>
									<td>' . $value['objective'] . '</td>
									<td>' . $value['answer'] . '</td>
								</tr>';
    }
    $content .= '</tbody></table>';


    // Include section if current logged in user is test@lesstestprep.com
    // if( $user_email == 'test@lesstestprep.com' && $lastrow == $numResults ) {
    $content .= '<pagebreak />';
    $content .= '<table id="small-group">
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
    for ($j = 0; $j < 16; $j++) {
        $content .= '
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>';
        $content .= '</tbody></table>';
    }
} else {
    $content .= '<div class="item-listing clear"><p>There is no item found!</p></div>';
}
$content .= '</div></div></div>
</div>';
?>
<?php
$content = str_replace('&nbsp;', ' ', $content);
ob_clean();
header('Content-type: application/pdf; charset=utf-8');
header('Content-Disposition: inline; filename=""');
header('Content-Transfer-Encoding: binary');
header('Accept-Ranges: bytes');
$content = str_replace('<strong>', '<strong style="font-weight:bold;">', $content);
$content = str_replace('<b>', '<strong style="font-weight:bold;">', $content);
$content = str_replace('<p><img style="display: block; margin-left: auto; margin-right: auto;"', '<p style="text-align:center;"><img style="display: block; margin-left: auto; margin-right: auto;"', $content);

// $content = str_replace('</strong>','</strong>',$content);
$html = $content;
include('../mpdf/mpdf.php'); // including mpdf.php

$mpdf = new mPDF('c', 'A4', '', '', 25, 25, 27, 25, 16, 13);
$mpdf->SetHTMLFooter('<div class="mpdf-footer">Intervene (c) ' . date("Y") . '</div>');
$mpdf->SetDisplayMode('fullpage');
$mpdf->shrink_tables_to_fit = 1;
$mpdf->list_indent_first_level = 0;
$stylesheet = '';
$stylesheet .= file_get_contents('../css/print.css');
$stylesheet .= file_get_contents('../css/font-awesome.min.css');
$stylesheet .= file_get_contents('../css/bootstrap.min.css');
$mpdf->WriteHTML($stylesheet, 1);
// $mpdf->SetHTMLHeader($header,'',TRUE);
$mpdf->WriteHTML($html);
$mpdf->Output('Question.pdf', 'D');
ob_end_flush();
// }
?>
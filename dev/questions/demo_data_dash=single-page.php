<?php
@session_start();
include('inc/connection.php');
set_time_limit(600);
ini_set("memory_limit", "256M");

//
$timeo_start = microtime(true);
//==============================================================
include("mpdf/mpdf.php");
ini_set('display_errors', 0);
$user_id = $_SESSION['demo_user_id'];
//$query = mysql_query("SELECT school FROM users WHERE id=" . $user_id);
//$rows = mysql_num_rows($query);
//if ($rows == 1) {
//    $row = mysql_fetch_assoc($query);
//    $school_id = $row['school'];
//}
$school_id = $_SESSION['school_id'];
global $base_url;
$base_url = "http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER["REQUEST_URI"] . '?') . '/';


if ($_GET['assesment']) {
///////**********SCORE SUMMARY*********/////////

    $score_res = mysql_query('SELECT SUM( corrected ) AS correct, count( qn_id ) AS total, ((SUM( corrected ) / count( qn_id )) *100) AS percentage, student_id
FROM demo_students_x_assesments
WHERE assessment_id =\'' . $_GET['assesment'] . '\' AND class_id = \''.$_GET['cid'].'\'  
GROUP BY student_id');
    $score_percentage_group = array();
    $total_students = mysql_num_rows($score_res);
    while ($res = mysql_fetch_assoc($score_res)) {
        if ($res['percentage'] < 50) {
            $score_percentage_group['<50'] += 1;
        } elseif ($res['percentage'] >= 50 && $res['percentage'] < 60) {
            $score_percentage_group['51-59'] += 1;
        } elseif ($res['percentage'] >= 60 && $res['percentage'] < 70) {
            $score_percentage_group['61-69'] += 1;
        } else {
            $score_percentage_group['70-100'] += 1;
        }
    }
    $all_student = $total_students; 
    $score_summary_res = array();
    $score_summary_res['<50'] = round(($score_percentage_group['<50'] / $total_students) * 100);
    $score_summary_res['51-59'] = round(($score_percentage_group['51-59'] / $total_students) * 100);
    $score_summary_res['61-69'] = round(($score_percentage_group['61-69'] / $total_students) * 100);
    $score_summary_res['70-100'] = round(($score_percentage_group['70-100'] / $total_students) * 100);

    /// REDINESS SUMMARY

    $readiness_res = mysql_query('SELECT t.id, t.objective_type, t.short_code, t.obj_short, t.name FROM terms t '
            . 'LEFT JOIN term_relationships rel ON rel.objective_id = t.id '
            . 'LEFT JOIN assessments_x_questions a_x_s ON a_x_s.qn_id = rel.question_id '
            . 'WHERE a_x_s.assesment_id = \'' . $_GET['assesment'] . '\'  AND t.objective_type = "R" GROUP BY t.id ');

    $readiness_percentage_group = array();
    $readiness_percentage_group_result_bar = array();
    while ($readiness_result = mysql_fetch_assoc($readiness_res)) {

        $readinesss_score_res = mysql_query('SELECT SUM( s_x_s.corrected ) AS correct, count( s_x_s.qn_id ) AS total, ((SUM( s_x_s.corrected ) / count( s_x_s.qn_id )) *100) AS percentage, student_id
FROM demo_students_x_assesments s_x_s 
LEFT JOIN term_relationships rel ON rel.question_id = s_x_s.qn_id 
AND rel.objective_id = \'' . $readiness_result['id'] . '\'  
WHERE s_x_s.assessment_id =\'' . $_GET['assesment'] . '\'  AND s_x_s.class_id = \''.$_GET['cid'].'\'  AND  rel.question_id = s_x_s.qn_id 
GROUP BY s_x_s.student_id');

        $total_students = mysql_num_rows($readinesss_score_res);
        $percentage_total = 0;
        while ($res = mysql_fetch_assoc($readinesss_score_res)) {
            $percentage_total += $res['percentage'];
        }
        //echo $readiness_result['id'].'=='.$total_students.'=='.$percentage_total.'<br/>';

        $readiness_percentage_group_result_bar[$readiness_result['id']]['objective_type'] = $readiness_result['objective_type'];
        $readiness_percentage_group_result_bar[$readiness_result['id']]['short_code'] = $readiness_result['short_code'];
        $readiness_percentage_group_result_bar[$readiness_result['id']]['obj_short'] = $readiness_result['obj_short'];
        $readiness_percentage_group_result_bar[$readiness_result['id']]['name'] = $readiness_result['name'];
        $readiness_percentage_group_result_bar[$readiness_result['id']]['result']['value'] = round($percentage_total / $total_students);
        if (round($percentage_total / $total_students) < 50) {
            $color = 'red';
        } else if (round($percentage_total / $total_students) >= 50 && round($percentage_total / $total_students) < 60) {
            $color = 'orange';
        } else if (round($percentage_total / $total_students) >= 60 && round($percentage_total / $total_students) < 70) {
            $color = 'yellow';
        } else {
            $color = 'green';
        }
        
        $readiness_percentage_group_result_bar[$readiness_result['id']]['result']['color'] = $color;
    }
    
    
    
//die;
    ///
    /// REDINESS SUMMARY
    $reteach_total = 0;
    $less_50 = 0;
    $more_50 = 0;

    $whole_group_summary = array();
    $small_group_summary = array();
    $students = array();
    $readiness_res = mysql_query('SELECT t.id, t.objective_type, t.short_code, t.obj_short, t.name FROM terms t '
            . 'LEFT JOIN term_relationships rel ON rel.objective_id = t.id '
            . 'LEFT JOIN assessments_x_questions a_x_s ON a_x_s.qn_id = rel.question_id '
            . 'WHERE a_x_s.assesment_id = \'' . $_GET['assesment'] . '\'  AND ( t.objective_type = "R" OR t.objective_type = "S" ) GROUP BY t.id ');
    $readiness_percentage_group = array();
    $readiness_percentage_group_result = array();

    while ($readiness_result = mysql_fetch_assoc($readiness_res)) {

        $readinesss_score_res = mysql_query('SELECT SUM( s_x_s.corrected ) AS correct, count( s_x_s.qn_id ) AS total, ((SUM( s_x_s.corrected ) / count( s_x_s.qn_id )) *100) AS percentage, student_id
FROM demo_students_x_assesments s_x_s 
LEFT JOIN term_relationships rel ON rel.question_id = s_x_s.qn_id 
AND rel.objective_id = \'' . $readiness_result['id'] . '\'  
WHERE s_x_s.assessment_id =\'' . $_GET['assesment'] . '\' AND s_x_s.class_id = \''.$_GET['cid'].'\'   AND  rel.question_id = s_x_s.qn_id 
GROUP BY s_x_s.student_id');

        $total_students = mysql_num_rows($readinesss_score_res);
        $percentage_total = 0;
        while ($res = mysql_fetch_assoc($readinesss_score_res)) {
            $percentage_total += $res['percentage'];
        }
        //echo $readiness_result['id'].'=='.$total_students.'=='.$percentage_total.'<br/>';


        if (round($percentage_total / $total_students) < 50) {
            $less_50 = $less_50 + 1;
            $whole_group_summary[$readiness_result['id']]['objective_type'] = $readiness_result['objective_type'];
            $whole_group_summary[$readiness_result['id']]['short_code'] = $readiness_result['short_code'];
            $whole_group_summary[$readiness_result['id']]['obj_short'] = $readiness_result['obj_short'];
            $whole_group_summary[$readiness_result['id']]['name'] = $readiness_result['name'];
            $whole_group_summary[$readiness_result['id']]['result']['value'] = round($percentage_total / $total_students);
        } else {
            $more_50 = $more_50 + 1;
            $small_group_summary[$readiness_result['id']]['objective_type'] = $readiness_result['objective_type'];
            $small_group_summary[$readiness_result['id']]['short_code'] = $readiness_result['short_code'];
            $small_group_summary[$readiness_result['id']]['obj_short'] = $readiness_result['obj_short'];
            $small_group_summary[$readiness_result['id']]['name'] = $readiness_result['name'];
            $small_group_summary[$readiness_result['id']]['result']['value'] = round($percentage_total / $total_students);
        }
    }
    
    $whole_50_retech_avg = (($less_50 / ($less_50 + $more_50)) * 100);
    $small_50_retech_avg = (($more_50 / ($less_50 + $more_50)) * 100);
    $total_mis = 0;
    $res = mysql_query('SELECT count(DISTINCT s_x_a.student_id) as cnt, s_x_a.distractor, d.name FROM demo_students_x_assesments s_x_a '
            . 'INNER JOIN distrators d ON s_x_a.distractor = d.id '
            . 'WHERE s_x_a.corrected = 0 AND s_x_a.distractor > 0 '
            . 'AND s_x_a.assessment_id = \'' . $_GET['assesment'] . '\' AND s_x_a.class_id = \''.$_GET['cid'].'\'  ' 
            . 'GROUP BY  s_x_a.distractor ORDER BY count(DISTINCT s_x_a.student_id) DESC LIMIT 0,5  ');
    $misconception_result = array();

    while ($mis = mysql_fetch_assoc($res)) {

        $total_mis += $mis['cnt'];
            $misconception_result[$mis['distractor']]['count'] = $mis['cnt'];
        $misconception_result[$mis['distractor']]['name'] = $mis['name'];
        
        $mis_num = mysql_query('SELECT s_x_a.num, t.obj_short FROM demo_students_x_assesments s_x_a '
                . 'INNER JOIN distrators d ON s_x_a.distractor = d.id '
                . 'INNER JOIN term_relationships rel ON rel.question_id = s_x_a.qn_id '
                . 'INNER JOIN terms t ON t.id = rel.objective_id '
                . 'WHERE s_x_a.corrected = 0 AND s_x_a.distractor > 0 AND t.id = rel.objective_id '
                . 'AND s_x_a.assessment_id = \'' . $_GET['assesment'] . '\' AND s_x_a.class_id = \''.$_GET['cid'].'\'  AND s_x_a.distractor = \'' . $mis['distractor'] . '\'  ');

        $numb = array();
        $obj_short = array();
                
        while ($mis_res = mysql_fetch_assoc($mis_num)) {
            if(!in_array($mis_res['num'], $numb)) {
            $numb[] = $mis_res['num'];
            }
             if(!in_array($mis_res['obj_short'], $obj_short)) {
            $obj_short[] = $mis_res['obj_short'];
             }
        }
        $misconception_result[$mis['distractor']]['number'] = implode(', ', $numb);
        $misconception_result[$mis['distractor']]['obj_short'] = implode(', ', $obj_short);
    }
}
?>


<html>
    <head>
        <title>## Greatness</title>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load('current', {'packages': ['corechart']});
            google.charts.setOnLoadCallback(drawChart);
            google.charts.setOnLoadCallback(drawReteachChart);

            function drawChart() {

                var data = google.visualization.arrayToDataTable([
                    ['Task', 'Hours per Day'],
                    ['<50', <?php print $score_summary_res['<50']; ?>],
                    ['50-59', <?php print $score_summary_res['51-59']; ?>],
                    ['60-69', <?php print $score_summary_res['61-69']; ?>],
                    ['70-100', <?php print $score_summary_res['70-100']; ?>],
                ]);

                var options = {
                    width: 280,
                    height: 200,
                    title: '',
					pieSliceTextStyle: {
            color: 'black',
          },
                    slices: {
                        0: {color: 'red'},
                        1: {color: 'orange'},
                        2: {color: 'yellow'},
                        3: {color: 'green'}
                    },
                    chartArea: {
                        left: '3%',
                        top: '5%',
                        width: '100%',
                    }
                };
                var chart_div = document.getElementById('piechart');

                var chart = new google.visualization.PieChart(document.getElementById('piechart'));
                google.visualization.events.addListener(chart, 'ready', function () {
                    chart_div.innerHTML = '<img src="' + chart.getImageURI() + '">';
                    console.log(chart_div.innerHTML);
                });

                chart.draw(data, options);
            }
            function drawReteachChart() {

                var data = google.visualization.arrayToDataTable([
                    ['Task', 'Hours per Day'],
                    ['Whole Group Reteach', <?php print $whole_50_retech_avg; ?>],
                    ['Small Group Reteach', <?php print $small_50_retech_avg; ?>],
                ]);

                var options = {
                    width: 250,
                    height: 150,
                    title: '',
                    slices: {
                        0: {color: 'Blue'},
                        1: {color: 'Red'},
                    },
                    chartArea: {
                        left: '3%',
                        top: '5%',
                        width: '90%'
                    }
                };
                var chart_div = document.getElementById('retechpiechart');

                var chart = new google.visualization.PieChart(document.getElementById('retechpiechart'));
                google.visualization.events.addListener(chart, 'ready', function () {
                    chart_div.innerHTML = '<img src="' + chart.getImageURI() + '">';
                    console.log(chart_div.innerHTML);
                });

                chart.draw(data, options);
            }
        </script>
<?php if (count($readiness_percentage_group_result_bar) > 0) { ?>
        <script type="text/javascript">
            google.charts.load("current", {packages: ['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ["Element", "Density", {role: "style"}],
<?php
if (count($readiness_percentage_group_result_bar) > 0) {
    foreach ($readiness_percentage_group_result_bar as $key => $value) {
        ?>
                            ["<?php print $value['short_code'].'\n'.$value['obj_short'] ?>", <?php print $value['result']['value']; ?>, '<?php print $value['result']['color']; ?>'],
        <?php
    }
}
?>

                ]);

                var view = new google.visualization.DataView(data);
                view.setColumns([0, 1,
                    {calc: "stringify",
                        sourceColumn: 1,
                        type: "string",
                        role: "annotation"},
                    2]);

                var options = {
                    title: "     ",
                    width: 650,
                    height: 400,
                    bar: {groupWidth: "95%"},
                    legend: {position: "none"},
                    chartArea: {
                        left: '3%',
                        top: '5%',
                        width: '100%',height:'75%'
                    }
                };
                var chart_div = document.getElementById('columnchart_values');

                var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
                // Wait for the chart to finish drawing before calling the getImageURI() method.
                google.visualization.events.addListener(chart, 'ready', function () {
                    chart_div.innerHTML = '<img src="' + chart.getImageURI() + '">';
                    console.log(chart_div.innerHTML);
                });

                chart.draw(view, options);
            }
        </script>
<?php }else { $message = 'There were no readiness objectives selected on this assessment.'; ?>
<?php } ?>
        <style>


            table { page-break-inside:auto; }
            td    { border:1px solid lightgray; }
            tr    { page-break-inside:auto; }
            table, th, td {

                margin: 0px;
                padding: 0px;
                border: .23em solid black;
                border-collapse: collapse;
            }
            @media print {
                .print_row{display:none;}
                table {
                    
                    border: solid #000 !important;
                    border-width: 1px !important;
                }
                th, td {
                    border: solid #000 !important;
                    border-width: 1px !important;
                }
            }
        </style>
    </head>
    <?php
    $html = '
        <body>
        <table padding="0" width="100%" border="1">
            <tr class="print_row"><td style="border:0px;height:50px;" colspan="2" align="right"><a href="#" onclick="window.print();" style="border:0px; background: #006600;text-decoration: none;color: #fff; margin: 10px; padding: 5px;">Print</a></td></tr>
            <tr>
                <td valign="top" align="center" style="border:0px;">
                <h4>READINESS SUMMARY</h4>
                    <div id="columnchart_values" >'.$message.'</div>
                    
                </td>
                <td style="border:0px;" align="center" ><h4>SCORE SUMMARY</h4><div id="piechart" ></div></td>
                 
            </tr>
            
            <tr>
                <td colspan="2"> <table style="border:0px !important;"  width="100%"><tr>
                    <td valign="top" >
                    <table style="border:0px !important; width:100%;" >
                        <tr>
                            <td style="text-align:center;border-top:0px !important;border-bottom:0px !important;"  colspan="2" valign="top" >RETEACH SUMMARY</td>
                        </tr>
                        <tr>
                            <td style="width:250px;"><div id="retechpiechart" ></div></td>
                            <td valign="top"  style="border-bottom:0px;  ">
                                <table width="100%" style="border:0px;width:280px;  "   >
                                    <tr>
                                        <td style="border:0px !important;  width:100%; text-align:center;" colspan="2">Whole Group Reteach</td>
                                    </tr>
                                    ';

    if (count($whole_group_summary) > 0) {
        // print_r($whole_group_summary); die;
        foreach ($whole_group_summary as $key => $value) {
            if ($value['obj_short'] || $value['short_code']) {
                $html .= '<tr><td  style="border-left:0px !important;font-size: 12px;" >' . $value['obj_short'] . '</td>
<td  style="border-left:0px !important;border-right:0px !important;font-size: 12px;">' . $value['short_code'] . '</td></tr>';
            }
        }
    }
    $html .= '
                                    
                                </table>
                            </td>

                        </tr>
                    </table>
                </td>
                <td valign="top"  style="border:0px !important;border-bottom: 0px;width: 800px;" colspan="2">
                    <table style="border:0px !important; width:100%;" >
                        <tr>
                            <td colspan="4" align="center" style="border:0px !important" >MISCONCEPTIONS</td>
                        </tr>';

    if (count($misconception_result) > 0) {
        $html .= '
                            <tr>
                                <td style="border-left:0px !important;font-size: 12px;">Question #</td>
                                <td style="border-left:0px !important;font-size: 12px;">Objective(s)</td>
                                <td style="border-left:0px !important; font-size: 12px;width:30px;">%</td>
                                <td style="border-left:0px !important; font-size: 12px; border-right:0px !important;">Misconception</td>
                            </tr>';
        foreach ($misconception_result as $k => $v) {
            $html .= '
                                <tr>
                                    <td style="border-left:0px !important; font-size: 12px;" >' . $v['number'] . '</td>
                                    <td  style="border-left:0px !important; font-size: 12px;">' . $v['obj_short'] . '</td>
                                    <td  style="border-left:0px !important; font-size: 12px;">' . round(($v['count'] / $all_student ) * 100) . '</td>
                                    <td  style="border-left:0px !important; border-right:0px !important;font-size: 12px;">' . $v['name'] . '</td>
                                </tr>';
        }
    }

    $html .= '
                    </table>

                </td>
            </tr>
            
             </table>

                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">SMALL GROUPS</td>
            </tr>
            <tr>
                <td colspan="2" style="border:0px !important;"><table style="border:0px !important;"><tr>
               ';
    foreach ($small_group_summary as $k => $v) {
        $html .= '

                <td style="border-bottom: 0px;" valign="top" colspan="2">
                    <table style="border-left: 0px;border-right: 0px;border-top: 0px; ">
                        <tr><td  align="center" width="300" style="border-left:0px !important;font-size: 12px;height:20px; border-top: 0px;border-right: 0px;">' . $v['short_code'] . '</td></tr>
                         <tr>   <td  align="center" style="border-left:0px !important;font-size: 12px;border-right: 0px;border-top:0px;height:20px;">' . $v['obj_short'] . '</td></tr>
                          <tr>  <td  align="center" style="border-left:0px !important;font-size: 12px;border-right: 0px;height:20px;">' . $v['objective_type'] . '</td></tr>';

        $less_50_stu = mysql_query('SELECT SUM( s_x_s.corrected ) AS correct, count( s_x_s.qn_id ) AS total, 
            ((SUM( s_x_s.corrected ) / count( s_x_s.qn_id )) *100) AS percentage, student_id
FROM students_x_assesments s_x_s 
LEFT JOIN term_relationships rel ON rel.question_id = s_x_s.qn_id 
AND rel.objective_id = \'' . $k . '\'  
WHERE s_x_s.assessment_id =\'' . $_GET['assesment'] . '\' AND s_x_s.class_id = \''.$_GET['cid'].'\' AND  rel.question_id = s_x_s.qn_id 
GROUP BY s_x_s.student_id HAVING percentage < 50 ');
        while ($students = mysql_fetch_assoc($less_50_stu)) {
            $student_name = mysql_fetch_assoc(mysql_query('SELECT CONCAT(first_name, " ", middle_name, " ", last_name) as student_name FROM students WHERE id = \'' . $students['student_id'] . '\' '));
            $html .= ' <tr>  <td  align="center" style="border-left:0px !important;border-right: 0px;height:20px;font-size: 12px;">' . $student_name['student_name'] . '</td> </tr>';
        }

        $html .= '  
                    </table>
                </td>';
    }
    $html .= ' 
                        </tr></table></td>
            </tr>
        </table>

    </body>
</html>';
    $_SESSION['print_data_dash'] = $html;
    print $html;
//$mpdf=new mPDF('c','A4','','',32,25,27,25,16,13); 
//
//    $mpdf->SetDisplayMode('fullpage');
//    $mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list
//
//    // LOAD a stylesheet
//    $stylesheet = file_get_contents('mpdfstyletables.css');
//    $mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text
//    $mpdf->WriteHTML($html);
//    $mpdf->Output('student_list.pdf','D'); 
    


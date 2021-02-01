<?php
//  function wrk , get days 
// date diffrence 
function date_range($first, $last, $step = '+1 day', $output_format = 'd/m/Y' ) {

    $dates = array();
    $current = strtotime($first);
    $last = strtotime($last);

    while( $current <= $last ) {

        $dates[] = date($output_format, $current);
        $current = strtotime($step, $current);
    }

    return $dates;
}
 
//$fd=date_range($first="2018-01-01", $last="2018-01-10", $step = '+4 day', $output_format = 'Y-m-d' );
//  last date of array item would be end date,
//  O/p
//print_r($fd);
// Week days arr//////////////
//$arr_day=array("Mon","Wed","Fri");# selected daysArr# >1
$arr_day=(isset($_POST['week_day'])&&count($_POST['week_day'])>0)?$_POST['week_day']:NULL;
function date_range_week($first, $last, $step = '+1 day', $output_format = 'd/m/Y' ) {
    global $arr_day; // Selected days
    $dates = array();
    $current = strtotime($first);
    $last = strtotime($last);

    while( $current <= $last ) {

       $get_date = date($output_format, $current); 
       $dayName= date('D', strtotime($get_date));
       //  e.g- Mon,Wed date
       if (in_array($dayName, $arr_day)){
       //$dates[] = date($output_format, $current)."-".$dayName;  
       $dates[] = date($output_format, $current);
      }
       
       // $dates[] = date($output_format, $current);
        $current = strtotime($step, $current);
        
        
        
    }

    return $dates;
}
 
//$arr_dats=date_range_week($first="2018-03-11", $last="2018-03-25", $step = '+1 day', $output_format = 'Y-m-d' );
//   all date 


?>
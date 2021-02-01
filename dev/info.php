<?php
echo '=============='; //  die; 
// $str='[{"xPos":50,"yPos":23.771790808240887,"indexPos":1},{"xPos":50,"yPos":23.771790808240887,"indexPos":2}]';

// $json_arr=json_decode($str);
// //print_r($json_arr); die; 


// $arrayName = array( array('xPos'=>50,'yPos'=>23.771790808241,'indexPos'=>2  ),
// 	array('xPos'=>90,'yPos'=>25.771790808241,'indexPos'=>5  )
//   );

// $json=json_encode($arrayName);
// echo $json; die;

// print_r($arrayName); die; 
//

/////////////////////////////
 phpinfo(); die; 
echo  $val1 = date("Y-m-d H:i:s"); #currTime

$start_date = new DateTime('2007-09-01 04:10:58');
$since_start = $start_date->diff(new DateTime('2012-09-11 10:25:00'));
print_r($since_start); die; 
echo $since_start->days.' days total<br>';
echo $since_start->y.' years<br>';
echo $since_start->m.' months<br>';
echo $since_start->d.' days<br>';
echo $since_start->h.' hours<br>';
echo $since_start->i.' minutes<br>';
echo $since_start->s.' seconds<br>';

///////////////////////
// phpinfo();
 die; 


?>
   
<?php
ini_set('max_execution_time', '300');


$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => "https://api.clever.com/v2.1/schools",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => [
    "Accept: application/json",
    "Authorization: Bearer 49591426e7a08b660a1c7865b11d7a8a2813ca03"
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
 // echo $response;


  $d=json_decode($response,true);


}




echo '<pre>';
print_r($d);
echo '<pre>';
/*


                                



//print_r($d);


$r=array('school_number', 'name', 'state_id', 'district', 'principalName','principalEmail','Address','City','State','Zip','sis_id','low_grade','high_grade','phone','id');
$row=[];
 header('Content-Type: text/csv; charset=utf-8');  
      header('Content-Disposition: attachment; filename=SchoolList.csv');  
      $output = fopen("php://output", "w");  
      fputcsv($output, $r); 

      foreach ($d as $k => $ar) {
        foreach ($ar as $key => $v) {
          $row=array( 
            $v['data']['school_number'],
            $v['data']['name'],
            $v['data']['state_id'],
            $v['data']['district'],
            $v['data']['principal']['name'],
            $v['data']['principal']['email'],
            $v['data']['location']['address'],
            $v['data']['location']['city'],
            $v['data']['location']['state'],
            $v['data']['location']['zip'],
            $v['data']['sis_id'],
            $v['data']['low_grade'],
            $v['data']['high_grade'],
            $v['data']['phone'],
            $v['data']['id']);
          fputcsv($output, $row);  

}
 
}
fclose($output); 






//Teacher Script



$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => "https://api.clever.com/v2.1/teachers",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => [
    "Accept: application/json",
    "Authorization: Bearer 49591426e7a08b660a1c7865b11d7a8a2813ca03"
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {

   $d=json_decode($response,true);


}


$r=array('teacher_number', 'state_id', 'sis_id', 'district', 'email','title','firstName','lastName','middleName','school','id');

header('Content-Type: text/csv; charset=utf-8');  
header('Content-Disposition: attachment; filename=TeacherFInalList.csv');  
$output = fopen("php://output", "w");  
fputcsv($output, $r); 

foreach ($d as $k => $ar) {
        foreach ($ar as $key => $v) {



          $row=array( 
                      $v['data']['teacher_number'],
                      $v['data']['state_id'],
                      $v['data']['sis_id'],
                      $v['data']['district'],
                      $v['data']['email'],
                      $v['data']['title'],
                      $v['data']['name']['first'],
                      $v['data']['name']['last'],
                      $v['data']['name']['middle'],
                      $v['data']['school'],
                      $v['data']['id']
                    );
        fputcsv($output, $row);  
        }
      }



fclose($output); 

print_r($row);

//Student 



$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => "https://api.clever.com/v2.1/teachers/5b515c60491ebd0082141d32/students",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => [
    "Accept: application/json",
    "Authorization: Bearer 49591426e7a08b660a1c7865b11d7a8a2813ca03"
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {



  $d=json_decode($response,true);

}


$r=array('gender', 'student_number', 'district', 'graduation_year', 'location','state','zip','Address','city','grade','firstName','lastName',
  'middleName',
'email',
'state_id',
'school',
'sis_id',
'dob',
'id'

);

header('Content-Type: text/csv; charset=utf-8');  
header('Content-Disposition: attachment; filename=StudetnFInalList.csv');  
$output = fopen("php://output", "w");  
fputcsv($output, $r); 

foreach ($d as $k => $ar) {
        foreach ($ar as $key => $v) {





          $row=array( 
      $v['data']['gender'],
      $v['data']['student_number'],
      $v['data']['district'],
      $v['data']['graduation_year'],
      $v['data']['location']['lon'] ,
      $v['data']['location']['state'], 
      $v['data']['location']['zip'],
      $v['data']['location']['address'], 
      $v['data']['location']['city'],
      $v['data']['grade'],
      $v['data']['name']['first'],
      $v['data']['name']['last'],
      $v['data']['name']['middle'],
      $v['data']['email'],
      $v['data']['state_id'],
      $v['data']['school'],
      $v['data']['sis_id'],
      $v['data']['dob'],
      $v['data']['id']

                    );
        fputcsv($output, $row);  
        }
      }



fclose($output); 

print_r($row);



$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => "https://api.clever.com/v2.1/sections",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => [
    "Accept: application/json",
    "Authorization: Bearer 49591426e7a08b660a1c7865b11d7a8a2813ca03"
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  $d=json_decode($response,true);
}

//print_r($d);


$r=array('subject', 'sis_id', 'teacher', 'studentsList', 'name','period','grade','school','section_number','district','id');

header('Content-Type: text/csv; charset=utf-8');  
header('Content-Disposition: attachment; filename=ClassListFinal.csv');  
$output = fopen("php://output", "w");  
fputcsv($output, $r); 


foreach ($d as $k => $ar) {
        foreach ($ar as $key => $v) {
         $row=array( 

              $v['data']['subject'],
              $v['data']['sis_id'],
              $v['data']['teacher'],
              implode(',',$v['data']['students']),
              $v['data']['name'],
              $v['data']['period'], 
              $v['data']['grade'],
              $v['data']['school'],
              $v['data']['section_number'],
              $v['data']['district'],
              $v['data']['id']);

 fputcsv($output, $row);  
              }
            }

           fclose($output); 
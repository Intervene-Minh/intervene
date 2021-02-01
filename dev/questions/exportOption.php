<?php
/**
@ 19 colmn in CSV
@ Test Grade: 2634, ==9 questiions
@ : 2635=== 7
@ WordWrap: Column
@ New WordWrap Testing .
**/


$error = '';
$author = 1;
$datetm = date('Y-m-d H:i:s');

include('inc/connection.php'); 
session_start();
  ob_start();
        
        
$created = date('Y-m-d H:i:s');

$user_id = $_SESSION['login_id'];
$query = mysql_query("SELECT school FROM users WHERE id=" . $user_id);
$rows = mysql_num_rows($query);
if ($rows == 1) {
    $row = mysql_fetch_assoc($query);
    $school_id = $row['school'];
}




  // echo 'xxxTTS============'; die; 


///  download CSV////////////
 if(isset($_GET['grade_id'])){

$getid =$_GET['grade_id']; //
   
// $file_csv_name='Bulk_questions';# name by GradeNameCsv
 $file_csv_name='Bulk_questions_grade_'.$getid; // Grade

    $sql="SELECT * FROM questions WHERE category=".$getid ; 
    //$sql.=" WHERE category= '2634' ";
    $sql.=" ORDER BY id DESC ";

    //echo $sql; die; 
   
   // Question to CSV  
    $res_questions=mysql_query($sql);




   //prepare question data : 19 column
    $csv_arr=array();
   while ($row = mysql_fetch_assoc($res_questions)){

    ////////questionObjectiveL//////////
    $questionId=$row['id'];
    $res_objectives=mysql_query("SELECT objective_id FROM  term_relationships WHERE question_id=".$questionId);
     $objArr=array();
      $str_objectives='';  //default

      if(mysql_num_rows($res_objectives)>0){
    while ( $line= mysql_fetch_assoc($res_objectives))
      $objArr[]=$line['objective_id'];
    
    
      if(is_array($objArr)&&count($objArr)>0)
       $str_objectives=implode(":",$objArr);

      }

      //echo $str_objectives  , '==='.$questionId ;

       

    ///////////////

      if(!empty($row['answers']))
        $ans_arr=unserialize($row['answers']);
       //  answer still not save 
      if(!is_array($ans_arr))
        continue;

        //print_r($ans_arr);
          
          $ans_numbr=NULL; // 1-4
         for ($i=0; $i <count($ans_arr); $i++) { 
            if($ans_arr[$i]['corect']==1)
              $ans_numbr=($i+1);

            //$ans_arr[$i]['explain']=($ans_arr[$i]['explain'])?$ans_arr[$i]['explain']:0;
             if(!empty($ans_arr[$i]['image'])){
              $ans_arr[$i]['image']=basename($ans_arr[$i]['image']).PHP_EOL;
             }else{ // no image
              $ans_arr[$i]['image']='';

             }
         }



         

         $first_dist=$ans_arr[0]['explain'];
        // echo $ans_numbr.'===first_dist'.$first_dist;
    
         $row['public']=($row['public'])?$row['public']:0;
       //print_r($row); die;   

         // stripslashes
     $item=array('passage'=>$row['passage'], 
                'name_of_question'=>$row['name'],
                 'actual_ques_details'=>stripslashes($row['question']),
                 'category'=>$row['category'],  // Folder id

                 'objective_ids'=>$str_objectives, //'77:78:79:80',  //term field: ids

                 'opn_A'=>$ans_arr[0]['answer'],
                 'opn_B'=>$ans_arr[1]['answer'],
                 'opn_C'=>$ans_arr[2]['answer'],
                 'opn_D'=>$ans_arr[3]['answer'],

                 'Image_A'=>$ans_arr[0]['image'],
                 'Image_B'=>$ans_arr[1]['image'],
                 'Image_C'=>$ans_arr[2]['image'],
                 'Image_D'=>$ans_arr[3]['image'],
                 'Corrected'=>$ans_numbr, //'1-4',
                 'Distractor_A'=>$ans_arr[0]['explain'],
                 'Distractor_B'=>$ans_arr[1]['explain'],
                 'Distractor_C'=>$ans_arr[2]['explain'],
                 'Distractor_D'=>$ans_arr[3]['explain'],

                 'Public'=>$row['public']
                     );

       // echo count($item).'==total column';// 19==total column

       $itemValues=array_values($item);


     $csv_arr[]=$itemValues; unset($item); unset($itemValues);


    }

      print_r($csv_arr); die; 


      

 //prepare question data : 19 column


    ////CSV data ///
    

      
  $data_arr=array(); 
  //header('Content-Type: application/csv');       
// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.$file_csv_name.'.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');
 
 $question_column=array('Passage ID', 
  'Name of Question',
  'Actual Question Detail',
  'Subject folder ID (Organize Questions)',
  'Objective ID (Objective Field)',
  'A',
  'B',
  'C',
  'D',
  'Image A',
  'Image B',
  'Image C',
  'Image D',
  'Corrected(1-4)',
  'Distractor A',
  'Distractor B',
  'Distractor C',
  'Distractor D',
  //'Public (1 or 0)',
  'Public (1 or 0)');



// output the column headings
 fputcsv($output, $question_column);
// fputcsv($output, array('First Name', 'Middle Name', 'Last Name','Username'));


// loop over the rows, outputting them
 foreach ($csv_arr as $key => $row) {
   # code...
 
  // Process Question rows 
   //print_r($row); die; 
  #######################
  fputcsv($output, $row);


}


 }else exit('Page not found. !');
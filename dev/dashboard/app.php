<?php
$dir = "../training_docs/";

// Open a directory, and read its contents
if (is_dir($dir)){
  if ($dh = opendir($dir)){
     $file_arr=array();
    while (($file = readdir($dh)) !== false){

    if ($file == '.' || $file == '..') {
        continue;
    }else{
       //echo "filename:" . $file . "<br>";
      $file_arr[]=$file;
    }
       ////
     
    }
    closedir($dh);
  }
}


 print_r($file_arr);
?>


<button class="btn btn-default" data-toggle="modal"  data-target=".bs-example-modal-lg">Large modal</button> </center>

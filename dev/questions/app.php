<?php
$dir = "../training_docs/";

// Open a directory, and read its contents
if (is_dir($dir)){
  if ($dh = opendir($dir)){
    while (($file = readdir($dh)) !== false){

    if ($file == '.' || $file == '..') {
        continue;
    }
       ////
      echo "filename:" . $file . "<br>";
    }
    closedir($dh);
  }
}
?>


<button class="btn btn-default" data-toggle="modal"  data-target=".bs-example-modal-lg">Large modal</button> </center>

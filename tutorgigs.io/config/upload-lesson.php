<?php
function upload_lesson($fileupload,$path){
	$new_path = '';
	
	$year_folder = $path . date("Y");
	$month_folder= $year_folder . '/' . date("m");
	// Create folder if not exist
	!file_exists($year_folder) && mkdir($year_folder , 0777);
	!file_exists($month_folder) && mkdir($month_folder, 0777);
	
	$new_path = $month_folder.'/';
	// echo $new_path;
	// die();
	
	$allowedExts = array("pdf");
	$temp = explode(".", $fileupload["name"]);
	$extension = end($temp);
	
	if (($fileupload["type"] == "application/pdf")
	&& ($fileupload["size"] < 1000000)
	&& in_array($extension, $allowedExts))
	{
		if ($fileupload["error"] > 0){
			// echo "Return Code: " . $fileupload["error"] . "<br>";
			return ''; /* if error return 0*/
		}else{

				
			$newfilename = $new_path .date("d-H-i-s-"). $fileupload["name"];
			move_uploaded_file($fileupload["tmp_name"], $newfilename); 
			return $newfilename;
			/* if file uploaded and removed return 1*/
		}
	}else{
		return '';
	}
	
}
?>
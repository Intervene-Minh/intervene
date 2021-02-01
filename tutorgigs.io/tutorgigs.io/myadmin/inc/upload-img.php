<?php
/*
	if(isset($_POST['path'])&&isset($_POST['filename'])){
		$filename = $_POST['filename'];
		$path = $_POST['path'].$filename.'/';
		if (!is_dir($path)) {
			mkdir($path, 0777);
		}
		$fileupload = $_FILES['avatar'];
		$allowedExts = array("gif", "jpeg", "jpg", "png");
		$temp = explode(".", $fileupload["name"]);
		$extension = end($temp);
		if ((($fileupload["type"] == "image/gif")
			|| ($fileupload["type"] == "image/jpeg")
			|| ($fileupload["type"] == "image/jpg")
			|| ($fileupload["type"] == "image/pjpeg")
			|| ($fileupload["type"] == "image/x-png")
			|| ($fileupload["type"] == "image/png"))
		&& ($fileupload["size"] < 1000000)
		&& in_array($extension, $allowedExts))
		{
			if ($fileupload["error"] > 0){
				echo "Return Code: " . $fileupload["error"] . "<br>";
			}
			else{
				if (file_exists($path . $fileupload["name"])){
					echo $fileupload["name"] . " already exists. ";
				}
				else{
				/* $newfilename = $filename. '.' . $extension; */
				/* move_uploaded_file($fileupload["tmp_name"], $path . $newfilename); */
				/* echo "Stored in: " . $path . $fileupload["name"]; */
				
				/*move_uploaded_file($fileupload["tmp_name"], $path . $fileupload["name"]);
				
				}
			}
		}
		else
		{
		echo "Invalid file";
		}
	}
*/
	function upload_img($fileupload,$path,$filename,$empty){
		if (!is_dir($path)) {
			mkdir($path, 0777);
		}
		
		$allowedExts = array("gif", "jpeg", "jpg", "png");
		$temp = explode(".", $fileupload["name"]);
		$extension = end($temp);
		if ((($fileupload["type"] == "image/gif")
			|| ($fileupload["type"] == "image/jpeg")
			|| ($fileupload["type"] == "image/jpg")
			|| ($fileupload["type"] == "image/pjpeg")
			|| ($fileupload["type"] == "image/x-png")
			|| ($fileupload["type"] == "image/png"))
		&& ($fileupload["size"] < 1000000)
		&& in_array($extension, $allowedExts))
		{
			if ($fileupload["error"] > 0){
				echo "Return Code: " . $fileupload["error"] . "<br>";
				return '0'; /* if error return 0*/
			}
			else{
				if($empty){
					$dir_contents = scandir($path);
					foreach ($dir_contents as $file) {
						if($file !== '.' && $file !== '..'){
							unlink($path.$file); 
						}
					}
				}
				if (file_exists($path . $fileupload["name"])){
					echo $fileupload["name"] . " already exists. ";
					return '2';
					/* if file already exists return 2*/
				}
				else{
					
				$newfilename = $filename. '.' . $extension;
				move_uploaded_file($fileupload["tmp_name"], $path . $newfilename); /* rename $filename.$extension*/
				
				/* move_uploaded_file($fileupload["tmp_name"], $path . $fileupload["name"]); */
				
				return '1';
				/* if file uploaded and removed return 1*/
				}
			}
		}else{
			return '0'; /* if error return 0*/
		}
		
	}
?>
<!--<form method="post" action="" enctype="multipart/form-data">
    <input type="file" name="avatar" />
    <input type="submit" value="Upload" />
</form>-->
<?php

$arrIMG=[];
if(isset($_POST['submit'])){
    // Include the database configuration file

    
    // File upload configuration
    $targetDir = "uploads/tutorDoc/";
    $allowTypes = array('pdf');
    
    $statusMsg = $errorMsg = $insertValuesSQL = $errorUpload = $errorUploadType = '';
    if(!empty(array_filter($_FILES['files']['name']))){
        foreach($_FILES['files']['name'] as $key=>$val){
            // File upload path
            $fileName = basename($_FILES['files']['name'][$key]);
            $targetFilePath = $targetDir . $fileName;
            
            // Check whether file type is valid
            $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
            if(in_array($fileType, $allowTypes)){
                // Upload file to server
                if(move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)){
                    // Image db insert sql
                   $arrIMG[] = $fileName;
                }else{
                    $errorUpload .= $_FILES['files']['name'][$key].', ';
                };
            }else{
                $errorUploadType .= $_FILES['files']['name'][$key].', ';
            }
        }
        
        if(!empty($arrIMG)){
           
         $insertValuesSQL = implode($arrIMG, ',');
            
        }
    }else{
        $statusMsg = 'Please select a file to upload.';
    }
    
    // Display status message
    echo $statusMsg;
}
?>

<form id="form-profile" action="" method="POST" enctype="multipart/form-data" class="profile-wrap col-md-12">


<div class="right col-md-6">
<label for="phone">Upload Document:</label>

<input name="files[]" multiple type="file" class="form-control" multiple accept=".pdf">

</div> 



</div>
<div class="clear">&nbsp;</div>
<input   type="submit" id="profile-submit" class="button-submit" name="submit" value="save">
</form>
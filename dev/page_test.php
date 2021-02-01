<?php

//$string = "The text you want to filter goes here. http://google.com, https://www.youtube.com/watch?v=K_m7NEDMrV0,https://instagram.com/hellow/";
//
//$string.=" this other  https://intervene.io/questions/uploads/pic/imgq415.png other iamge as https://intervene.io/questions/uploads/pic/abc.png below ";
//preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $string, $match);
//
//echo "<pre>";
//print_r($match[0]); 
//
//echo "</pre>";

//$url="http://google.com/image.jpg";
$url="https://intervene.io/questions/uploads/pic/abc.png";

//$url=" is ,sdff fd https://intervene.io/questions/uploads/pic/abc.png this ,sdff  https://intervene.io/questions/uploads/pic/abc.png fds";

function isImage( $url ){
  $pos = strrpos( $url, ".");
    if ($pos === false)
      return false;
    $ext = strtolower(trim(substr( $url, $pos)));
    $imgExts = array(".gif", ".jpg", ".jpeg", ".png", ".tiff", ".tif"); // this is far from complete but that's always going to be the case...
    if ( in_array($ext, $imgExts) )
      return true;
return false;
}

$test=isImage($url);
//var_dump($test) ; die;

if($test){
  $pattern = '/((?:[\w\d]+\:\/\/)?(?:[\w\-\d]+\.)+[\w\-\d]+(?:\/[\w\-\d]+)*(?:\/|\.[\w\-\d]+)?(?:\?[\w\-\d]+\=[\w\-\d]+\&?)?(?:\#[\w\-\d]*)?)/';
  //$replace = '<a href="$1">$1</a>';
 $replace = '<img alt="Intervene" src="$1">';
  $msg = preg_replace( $pattern , $replace , $url );
  echo  stripslashes( utf8_encode( $msg ) );  // return image if url/file.png png,jpg
}

?>
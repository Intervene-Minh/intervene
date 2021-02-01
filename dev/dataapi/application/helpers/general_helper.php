<?php
function stripData($data)
{
	$tags_to_strip = Array('">','"<','<"','<script>');
	foreach ($tags_to_strip as $tag)
	{
		$data= str_replace($tag,'',$data);
	}
	return trim($data);
}

function getCurrDateTime($date='',$formate=''){
	
	if(empty($date)){
		$date = date('Y-m-d H:i:s');
	}else{
		$date = $date;
	}
	
	if(empty($formate)){
		$formate = 'Y-m-d H:i:s';
	}else{
		$formate = $formate;
	}
	$datetime = date($formate, strtotime($date));
	return $datetime;
}

function getNewAccessToken($length = 50) {
    return substr(base64encode(time().generateRandomString($length)),0,100);
}

function generateRandomString($length = 10, $mode="sln") {
	$characters = "";
	if(strpos($mode,"s")!==false){$characters.="abcdefghijklmnopqrstuvwxyz";}
	if(strpos($mode,"l")!==false){$characters.="ABCDEFGHIJKLMNOPQRSTUVWXYZ";}
	if(strpos($mode,"n")!==false){$characters.="0123456789";}

	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
	$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

function base64encode($input) {
	$input = json_encode(array('t'=>time(),'s'=>$input));
	$default = "ABCDEFQRSTUVWXYZabcdefghiuvwxyz012789+/=";
	$custom  = "ZYXWVUTSRQFEDCBAzyxwvuihgfedcba987210+/_";
	return strtr(base64_encode($input), $default, $custom);
}

function base64decode($input) {
	$default = "ABCDEFQRSTUVWXYZabcdefghiuvwxyz012789+/=";
	$custom  = "ZYXWVUTSRQFEDCBAzyxwvuihgfedcba987210+/_";
	$return = base64_decode(strtr($input, $custom, $default));
	$return_arr = json_decode($return,true);
	return $return_arr['s'];
}

function convertDate($date='', $formate='y-m-d'){
	if(empty($date)){
		$date = date('y-m-d');
	}else{
		$date = $date;
	}
	
	$r_date = date($formate,strtotime($date));
	return $r_date;
}

function base64_to_file($target_path, $encoded_string_file)
{
	$string_exploded = explode(';base64,', $encoded_string_file);

	if(!empty($string_exploded) && count($string_exploded) >= 1)
	{
		$extension = mime2ext($string_exploded[0]);
		$decoded_file = base64_decode($string_exploded[1]); // decode the file

		$file = $target_path . uniqid() .'.'. $extension;
		
		file_put_contents($file, $decoded_file); // save
		return $file;
	}	
	return false;
	
}

function mime2ext($mime)
{	
	$extensions = explode('/',$mime);
	return $extensions[1];
}

function now()
{
	$today = new DateTime();
	return $today->format('Y-m-d H:i:s');
}

function dump($data)
{
	echo "<pre>";
	print_r($data);
	echo "</pre>";
}

function dd($data)
{
	echo "<pre>";
	print_r($data);
	echo "</pre>";
	die;
}

?>
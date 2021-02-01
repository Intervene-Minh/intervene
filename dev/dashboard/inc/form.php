<?php
include('connection.php');

$action = $_POST['action'];

switch( $action ) {
	case 'search_object':
		search_object();
		break;
}

/* Search Objective */
function search_object() {
	$suggest = array();
	$keyword = $_POST['keyword'];
	$question= $_POST['question'];
	$objects = array_unique( (array) $_POST['objects'] );
	$inlcude = implode(', ', $objects);
	$results = mysql_query("
		SELECT DISTINCT `id`, `name`
		FROM `terms` t
		LEFT JOIN `term_relationships` r ON t.`id` = r.`objective_id`
		WHERE t.`name` LIKE '%{$keyword}%'
		AND t.`taxonomy` = 'objective'
		AND t.`active` = 1
		AND ( r.`question_id` IS NULL OR r.`question_id` != {$question} )
		OR t.`id` IN ( {$inlcude} )
	");
	$arr1 = array();
	$arr2 = array();
	if( mysql_num_rows($results) > 0 ) {
		while( $item = mysql_fetch_assoc($results) ) {
			if( in_array($item['id'], $objects) ) {
				$arr1[] = '<label><input type="checkbox" name="suggest[]" class="suggest" value="' . $item['id'] . '" checked="checked" /> ' . $item['name'] . '</label>';
			} else {
				$arr2[] = '<label><input type="checkbox" name="suggest[]" class="suggest" value="' . $item['id'] . '" /> ' . $item['name'] . '</label>';
			}
		}
	} else {
		echo 'No objective found!';
	}
	
	$suggest = array_merge($arr1, $arr2);
	
	echo implode(', ', $suggest);
	die();
}
?>
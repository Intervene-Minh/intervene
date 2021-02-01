<?php
include('connection.php');
session_start();
ob_start();
$action = $_POST['action'];

switch( $action ) {
	case 'query_folder':
		query_folder();
		break;
	case 'query_objective':
		query_objective();
		break;
	case 'query_distrator':
		query_distrator();
		break;
	case 'remove_items':
		remove_items();
		break;
	case 'remove_premium_option':
		remove_premium_option();
		break;
	default:
		echo json_encode(array('msg'=>'Have not action!'));
		die();
		break;
}

/* Folder */
function query_folder() {
	// Retrive data
	$hidId = is_numeric($_POST['hidId']) ? $_POST['hidId'] : 0;
	$value = $_POST['value'];
	$level = $_POST['level'];
	
	// Init @array $return
	$return = array('stt' => true, 'sql' => '', 'msg' => '');
	
	// Check if name is already exist
	$count = mysql_num_rows( mysql_query("SELECT `id` FROM `terms` WHERE `taxonomy` = 'category' AND `id` != {$hidId} AND `name` = '{$value}' AND `active` = 1") );
	if( $count > 0 ) {
		$return['stt'] = false;
		$return['msg'] = 'This folder is already exist!';
	} else {
		if( $hidId > 0 ) {	# Update
			$return['sql'] = 'update';
			// Check item exist?
			$check = mysql_num_rows( mysql_query("SELECT `id` FROM `terms` WHERE `taxonomy` = 'category' AND `id` = {$hidId} AND `active` = 1") );
			if( $check > 0 ) {
				// Do update. Return @bool: true on success | false on error
				$update = mysql_query("UPDATE `terms` SET `name` = '{$value}', `parent` = {$level} WHERE `taxonomy` = 'category' AND `id` = {$hidId} AND `active` = 1 LIMIT 1");
				if( $update ) {
					$return['stt'] = true;
					$return['msg'] = 'Successfully!';
				} else {
					$return['stt'] = false;
					$return['msg'] = 'Can not update folder. Please try again later!';
				}
			} else {	# Not found
				$return['stt'] = false;
				$return['msg'] = 'This folder is not found!';
			}
		} else {		# Insert
			$return['sql'] = 'insert';
			// Do Insert
			$insert = mysql_query("INSERT INTO `terms` ( `name` , `taxonomy` , `parent` ) VALUES ( '{$value}', 'category', '{$level}' )");
			$return['msg'] = ($insert) ? 'Successfully!' : 'Can not add new folder. Please try again later!';
		}
	}
	
	echo json_encode($return);
	die();
}

/* Objective */
function query_objective() {
	// Retrive data
	$hidId = is_numeric($_POST['hidId']) ? $_POST['hidId'] : 0;
	$value = $_POST['value'];
	$descr = $_POST['descr'];
	$level = $_POST['level'];
	
	// Init @array $return
	$return = array('stt' => true, 'msg' => '');
	
	// Check if name is already exist
	$count = mysql_num_rows( mysql_query("SELECT `id` FROM `terms` WHERE `taxonomy` = 'objective' AND `id` != {$hidId} AND `name` = '{$value}' AND `active` = 1") );
	if( $count > 0 ) {
		$return['stt'] = false;
		$return['msg'] = 'This objective is already exist!';
	} else {
		if( $hidId > 0 ) {	# Update
			$return['sql'] = 'update';
			// Check item exist?
			$check = mysql_num_rows( mysql_query("SELECT `id` FROM `terms` WHERE `taxonomy` = 'objective' AND `id` = {$hidId} AND `active` = 1") );
			if( $check > 0 ) {
				// Do update. Return @bool: true on success | false on error
				$update = mysql_query("UPDATE `terms` SET `name` = '{$value}', `description` = '{$descr}', `parent` = {$level} WHERE `taxonomy` = 'objective' AND `id` = {$hidId} AND `active` = 1 LIMIT 1");
				if( $update ) {
					$return['stt'] = true;
					$return['msg'] = 'Successfully!';
				} else {
					$return['stt'] = false;
					$return['msg'] = 'Can not update objective. Please try again later!';
				}
			} else {	# Not found
				$return['stt'] = false;
				$return['msg'] = 'This objective is not found!';
			}
		} else {		# Insert
			$return['sql'] = 'insert';
			// Do Insert
			$insert = mysql_query("INSERT INTO `terms` (`name` , `taxonomy` , `description` , `parent`) VALUES ('{$value}', 'objective', '{$descr}', '{$level}')");
			$return['msg'] = ($insert) ? 'Successfully!' : 'Can not add new objective. Please try again later!';
		}
	}
	
	echo json_encode($return);
	die();
}

/* Distrator */
function query_distrator() {
	// Retrive data
	$hidId = is_numeric($_POST['hidId']) ? $_POST['hidId'] : 0;
	$value = $_POST['value'];
	
	// Init @array $return
	$return = array('stt' => true, 'sql' => '', 'msg' => '');
	
	// Check if name is already exist
	$count = mysql_num_rows( mysql_query("SELECT `id` FROM `distrators` WHERE `id` != {$hidId} AND `name` = '{$value}'") );
	if( $count > 0 ) {
		$return['stt'] = false;
		$return['msg'] = 'This distrator is already exist!';
	} else {
		if( $hidId > 0 ) {	# Update
			$return['sql'] = 'update';
			// Check item exist?
			$check = mysql_num_rows( mysql_query("SELECT `id` FROM `distrators` WHERE `id` = {$hidId}") );
			if( $check > 0 ) {
				// Do update. Return @bool: true on success | false on error
				$update = mysql_query("UPDATE `distrators` SET `name` = '{$value}' WHERE `id` = {$hidId} LIMIT 1");
				if( $update ) {
					$return['stt'] = true;
					$return['msg'] = 'Successfully!';
				} else {
					$return['stt'] = false;
					$return['msg'] = 'Can not update distrator. Please try again later!';
				}
			} else {	# Not found
				$return['stt'] = false;
				$return['msg'] = 'This distrator is not found!';
			}
		} else {		# Insert
			$return['sql'] = 'insert';
			// Do Insert
			$insert = mysql_query("INSERT INTO `distrators` ( `name` ) VALUES ( '{$value}' )");
			$return['msg'] = ($insert) ? 'Successfully!' : 'Can not add new distrator. Please try again later!';
		}
	}
	
	echo json_encode($return);
	die();
}

/* Delete */
function remove_items() {
	// Retrive data
	$type	= $_POST['type'];
	$items	= $_POST['items'];
	
	// Init @array $return
	$return = array('stt' => true, 'sql' => '', 'msg' => '');
	
	// Retrieve subitems if working with terms
	if( $type == 'category' || $type == 'objective' ) {
		// Init @array to store subitems
		$subitems = array();
		// Get children if exist
		foreach( $items as $item ) {
			$children = mysql_query("SELECT `id` FROM `terms` WHERE `taxonomy` = '{$type}' AND `active` = 1 AND `parent` = {$item}");
			if( mysql_num_rows($children) > 0 ) {
				while( $child = mysql_fetch_assoc($children) ) {
					if( !in_array($child['id'], $subitems) )
						$subitems[] = $child['id'];
				}
			}
		}
		// Implode list ids into a string, separated by comma
		$ids = implode(', ', array_merge($items, $subitems));
		$query = "UPDATE `terms` SET `active` = '0' WHERE `taxonomy` = '{$type}' AND `id` IN ( {$ids} )";
	} elseif( $type == 'distrator' ) {
		$ids = implode(', ', $items);
		$query = "DELETE FROM `distrators` WHERE `id` IN ( {$ids} )";
	} elseif( $type == 'question' ) {
		$ids = implode(', ', $items);
		$query = "DELETE FROM `questions` WHERE `id` IN ( {$ids} )";
	} elseif( $type == 'passage' ) {
		$ids = implode(', ', $items);
		$query = "DELETE FROM `passages` WHERE `id` IN ( {$ids} )";
	} elseif( $type == 'lesson' ) {
		$ids = implode(', ', $items);
		$query = "DELETE FROM `lessons` WHERE `id` IN ( {$ids} )";
	}
	
	$remove = mysql_query($query);		# Return @boolean true|false
	if( $remove ) {
		$return['stt'] = true;
		$return['msg'] = 'Successfully!';
	} else {
		$return['stt'] = false;
		$return['msg'] = 'Can not remove these items. Please try again later!';
	}
	
	echo json_encode($return);
	die();
}


function remove_premium_option(){
	$id		= $_POST['id'];
	
	// Init @array $return
	$return = array('stt' => true, 'msg' => '');
	// Check
	$current_user_role = $_SESSION['login_role'];
	
	if( $current_user_role == 0 ) {
		$delete = mysql_query("DELETE FROM `option_premium` WHERE `id` = {$id}");
		$return['msg'] = ($delete) ? 'Successfully!' : 'Can not delete item. Please try again later!';
	} else {
		$return['stt'] = false;
		$return['msg'] = "You can't delete this item";
	}
	
	echo json_encode($return);
	die();
}
?>
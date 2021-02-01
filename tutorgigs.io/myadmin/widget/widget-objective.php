<h4 class="widget-title"><i class="fa fa-life-ring"></i>Objectives</h4>
<div  class="widget-content">
	<?php if($role==0):?>
	<p class="add_new">
		<i class="fa fa-plus-circle"></i>
		<a href="javascript: void(0);" class="popup_form" data-target="#objective_dialog" title="New Objective">Add Objective</a>
	</p>
	<?php endif;?>
	
	<p class="list"><i class="fa fa-th-list"></i><a href="objective.php">List Objectives</a></p>
	<?php
		$objectives = mysql_query("SELECT * FROM `terms` WHERE `taxonomy` = 'objective' AND `active` = 1 AND `parent` = 0");
		if( mysql_num_rows($objectives) > 0 ) {
			echo '<ul class="listing">';
			while( $object = mysql_fetch_assoc($objectives) ) {
				$counter = mysql_num_rows(mysql_query("SELECT * FROM `term_relationships` WHERE `objective_id` = {$object['id']}"));
				$subitem = '';
				$current = false;
				$subcats = mysql_query("SELECT * FROM `terms` WHERE `taxonomy` = 'objective' AND `active` = 1 AND `parent` = " . $object['id']);
				if( mysql_num_rows($subcats) > 0 ) {
					$subitem .= '<ul class="subitem">';
					while( $item = mysql_fetch_assoc($subcats) ) {
						if($item['id'] == $taxonomy) {
							$current = true;
							$classes = 'sub-active';
						} else {$classes = '';}
						$subitem .= '<li class="' . $classes . '">';
						$subitem .= '<a href="objective.php?taxonomy=' . $item['id'] . '">' . $item['name'] . '</a>';
						// Count number of questions
						$quesNum  = mysql_num_rows(mysql_query("SELECT * FROM `term_relationships` WHERE `objective_id` = {$item['id']}"));
						$subitem .= ($quesNum > 0) ? '<span class="badge">' . $quesNum . '</span>' : '';
						$counter += $quesNum;
						$subitem .= '</li>';
					}
					$subitem .= '</ul>';
				}
				echo ($current || $object['id'] == $taxonomy)
					?((mysql_num_rows($subcats) > 0) ? '<li class="active has-child">' : '<li class="active">')
					: '<li>';
				echo '<i class="fa fa-angle-right"></i><i class="fa fa-angle-down"></i>';
				echo '<a href="objective.php?taxonomy=' . $object['id'] . '">' . $object['name'] . '</a>';
				echo ($counter > 0) ? '<span class="badge">' . $counter . '</span>' : '';
				echo $subitem . '</li>';
			}
			echo '</ul>';
		}
	?>
</div>
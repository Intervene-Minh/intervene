<h4 class="widget-title"><i class="fa fa-folder"></i>Folders</h4>
<div  class="widget-content">
	
	<?php if($role<10):?>
	<p class="add_new">
		<i class="fa fa-plus-circle"></i>
		<a href="javascript: void(0);" class="popup_form" data-target="#folder_dialog" title="New Folder">Add New</a>
	</p>
	<?php endif;?>
	<p class="list"><i class="fa fa-th-list"></i><a href="folder.php">List Folders</a></p>
	<?php
		$categories = mysql_query("SELECT * FROM `terms` WHERE `taxonomy` = 'category' AND `active` = 1 AND `parent` = 0 ORDER BY `name` ASC");
		if( mysql_num_rows($categories) > 0 ) {
			echo '<ul class="listing">';
			while( $category = mysql_fetch_assoc($categories) ) {
				$counter = mysql_num_rows(mysql_query("SELECT `id` FROM `questions` WHERE `category` = {$category['id']}"));
				$subitem = '';
				$current = false;
				$subcats = mysql_query("SELECT * FROM `terms` WHERE `taxonomy` = 'category' AND `active` = 1 AND `parent` = {$category['id']} ORDER BY `name` ASC");
				if( mysql_num_rows($subcats) > 0 ) {
					$subitem .= '<ul class="subitem">';
					while( $item = mysql_fetch_assoc($subcats) ) {
						if($item['id'] == $taxonomy) {
							$current = true;
							$classes = 'sub-active';
						} else {
							$classes = '';
						}
						$subitem .= '<li class="' . $classes . '">';
						$subitem .= '<i class="fa fa-folder"></i>';
						$subitem .= '<a href="folder.php?taxonomy=' . $item['id'] . '">' . $item['name'] . '</a>';
						// Count number of questions
						$quesNum  = mysql_num_rows(mysql_query("SELECT `id` FROM `questions` WHERE `category` = {$item['id']}"));
						$subitem .= ($quesNum > 0) ? '<span class="badge">' . $quesNum . '</span>' : '';
						$counter += $quesNum;
						$subitem .= '</li>';
					}
					$subitem .= '</ul>';
				}
				echo ($current || $category['id'] == $taxonomy)
					?((mysql_num_rows($subcats) > 0) ? '<li class="active has-child">' : '<li class="active">')
					: '<li>';
				echo '<i class="fa fa-angle-right"></i><i class="fa fa-angle-down"></i><i class="fa fa-folder"></i>';
				echo '<a href="folder.php?taxonomy=' . $category['id'] . '">' . $category['name'] . '</a>';
				echo ($counter > 0) ? '<span class="badge">' . $counter . '</span>' : '';
				echo $subitem . '</li>';
			}
			echo '</ul>';
		}
	?>
</div>
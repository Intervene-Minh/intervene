<!-- Sidebar -->

<?php $taxonomy = ( isset($_GET['taxonomy']) && is_numeric($_GET['taxonomy']) && $_GET['taxonomy'] > 0 ) ? $_GET['taxonomy'] : 0; ?>

<div id="folder" class="widget clear fullwith">
	<h4 class="widget-title"><i class="fa fa-folder"></i>Folder</h4>
	<div  class="widget-content">
		<p class="add_new">
			<i class="fa fa-plus-circle"></i>
			<a href="javascript: void(0);" class="popup_form" data-target="#folder_dialog" title="New Folder">Add New</a>
		</p>
		<p class="list"><i class="fa fa-th-list"></i><a href="folder.php">List</a></p>
		<?php
			$categories = mysql_query("SELECT * FROM `terms` WHERE `taxonomy` = 'category' AND `active` = 1 AND `parent` = 0");
			if( mysql_num_rows($categories) > 0 ) {
				echo '<ul class="listing">';
				while( $category = mysql_fetch_assoc($categories) ) {
					$counter = mysql_num_rows(mysql_query("SELECT `id` FROM `questions` WHERE `category` = {$category['id']}"));
					$subitem = '';
					$current = false;
					$subcats = mysql_query("SELECT * FROM `terms` WHERE `taxonomy` = 'category' AND `active` = 1 AND `parent` = {$category['id']}");
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
</div>		<!-- /#folder -->

<div id="questions" class="widget clear fullwith">
	<h4 class="widget-title"><i class="fa fa-question-circle"></i></span>Questions</h4>
	<div  class="widget-content">
		<p class="add_new">
			<i class="fa fa-plus-circle"></i>
			<a href="single-question.php?action=new">Add New</a>
		</p>
		<p class="list"><i class="fa fa-th-list"></i><a href="questions.php">Last Questions</a></p>
	</div>
</div>		<!-- /#questions -->

<div id="distrator" class="widget clear fullwith">
	<h4 class="widget-title"><i class="fa fa-bolt"></i></span>Distrator</h4>
	<div  class="widget-content">
		<p class="add_new">
			<i class="fa fa-plus-circle"></i>
			<a href="javascript: void(0);" class="popup_form" data-target="#distrator_dialog" title="New Distrator">Add Distrator</a>
		</p>
		<p class="list"><i class="fa fa-th-list"></i><a href="distrator.php">List Distrator</a></p>
	</div>
</div>		<!-- /#distrator -->

<div id="objective" class="widget clear fullwith">
	<h4 class="widget-title"><i class="fa fa-life-ring"></i>Objective</h4>
	<div  class="widget-content">
		<p class="add_new">
			<i class="fa fa-plus-circle"></i>
			<a href="javascript: void(0);" class="popup_form" data-target="#objective_dialog" title="New Objective">Add Objective</a>
		</p>
		<p class="list"><i class="fa fa-th-list"></i><a href="objective.php">List Objective</a></p>
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
</div>		<!-- /#objective -->

<div id="manager_user" class="widget clear fullwith">
	<h4 class="widget-title"><i class="fa fa-users"></i><a href="manager-user.php">Manager User</a></h4>
</div>		<!-- /#manager_user -->

<div id="manager_user" class="widget clear fullwith">
	<h4 class="widget-title"><i class="fa fa-user"></i>Profile</h4>
	<div  class="widget-content">
		<p class="add_new"><i class="fa fa-pencil-square-o"></i><a href="profile.php">Edit Profile</a></p>
		<p class="list"><i class="fa fa-sign-out"></i><a href="logout.php">Logout</a></p>
	</div>
</div>		<!-- /#manager_user -->
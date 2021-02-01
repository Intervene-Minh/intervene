	<div id="footer" class="clear fullwidth">
		SR I<div class="container">
			<div class="row">
				<p>Copyright &copy; <?php echo date('Y'); ?> Lonestaar Prep, LLC. All rights reserved.</p>
                <ul class="menu-footer">
                    <li class="item-menu item-menu-id-1"><a href="privacy-policy.php">Privacy policy</a></li>
                    <li class="item-menu item-menu-id-2"><a href="terms-and-conditions.php">Terms and conditions</a></li>
                </ul><!-- end menu-footer -->
                <p class="p-socical">
                    <a href="#"class="gg" >gg</a>
                    <a href="#" class="tt" >tt</a>
                    <a href="#" class="fb" >fb</a>
                </p>
			</div>
		</div>
	</div>		<!-- /#footer -->

</div>		<!-- /#wrapper -->

<!-- Form Add/Edit Folder -->
<div id="folder_dialog" class="form_dialog">
	<form name="folder_form" id="folder_form" class="form_data" method="post" action="">
		<div class="form_wrap clear fullwith">
			<p>
				<label>Folder Name:</label><input type="text" name="folder_name" id="folder_name" class="field_data textfield" value="" />
			</p>
			<p>
				<label>With:</label>
				<label class="sub-label"><input type="radio" name="folder_level" value="0" class="level_field" />New Foder Level 1</label>
			</p>
			<?php
			$categories = mysql_query("SELECT * FROM `terms` WHERE `taxonomy` = 'category' AND `parent` = 0 AND `active` = 1");
			if( mysql_num_rows($categories) > 0 ) {
			?>
				<p>
					<label>&nbsp;</label>
					<label class="sub-label"><input type="radio" name="folder_level" value="1" class="level_field" />Subfolders</label>
					<select name="child_of_folder" id="child_of_folder" class="child_of">
						<option value=""></option>
						<?php
						while( $category = mysql_fetch_assoc($categories) ) {
							echo '<option value="' . $category['id'] . '">' . $category['name'] . '</option>';
						}
						?>
					</select>
				</p>
			<?php } ?>
		</div>
		<div class="button_wrap clear fullwith">
			<input type="hidden" name="hidden_id" class="hidden_id" value="" />
			<input type="submit" name="submit_folder" id="submit_folder" class="form_button submit_button" value="Create" />
			<input type="reset" name="reset_folder" id="reset_folder" class="form_button reset_button" value="Cancel" />
		</div>
	<form>
</div>

<!-- Form Add/Edit Objective -->
<div id="objective_dialog" class="form_dialog">
	<form name="objective_form" id="objective_form" class="form_data" method="post" action="">
		<div class="form_wrap clear fullwith">
			<p>
				<label>Objective Name:</label><input type="text" name="objective_name" id="objective_name" class="field_data textfield" value="" />
			</p>
			<p>
				<label>Description:</label><textarea name="objective_desc" id="objective_desc" class="field_data textfield field_desc"></textarea>
			</p>
			<p>
				<label>With:</label>
				<label class="sub-label"><input type="radio" name="objective_level" value="0" class="level_field" />New Objective Level 1</label>
			</p>
			<?php
			$objects = mysql_query("SELECT * FROM `terms` WHERE `taxonomy` = 'objective' AND `parent` = 0 AND `active` = 1");
			if( mysql_num_rows($objects) > 0 ) {
			?>
				<p>
					<label>&nbsp;</label>
					<label class="sub-label"><input type="radio" name="objective_level" value="1" class="level_field" />SubObjective</label>
					<select name="child_of_object" id="child_of_object" class="child_of">
						<option value=""></option>
						<?php
						while( $object = mysql_fetch_assoc($objects) ) {
							echo '<option value="' . $object['id'] . '">' . $object['name'] . '</option>';
						}
						?>
					</select>
				</p>
			<?php } ?>
		</div>
		<div class="button_wrap clear fullwith">
			<input type="hidden" name="hidden_id" class="hidden_id" value="" />
			<input type="submit" name="submit_objective" id="submit_objective" class="form_button submit_button" value="Create" />
			<input type="reset" name="reset_objective" id="reset_objective" class="form_button reset_button" value="Cancel" />
		</div>
	<form>
</div>

<!-- Form Add/Edit Distrator -->
<div id="distrator_dialog" class="form_dialog">
	<div class="clear fullwidth">
		<form name="distrator_form" id="distrator_form" class="form_data" method="post" action="">
			<div class="form_wrap clear fullwith">
				<label for="distrator_field">Distractor Name:</label>
				<input type="text" name="distrator_field" id="distrator_field" class="field_data textfield" value="" />
			</div>
			<div class="button_wrap clear fullwith">
				<input type="hidden" name="hidden_id" class="hidden_id" value="" />
				<input type="submit" name="submit_distrator" id="submit_distrator" class="form_button submit_button" value="Create" />
				<input type="reset" name="reset_distrator" id="reset_distrator" class="form_button reset_button" value="Cancel" />
			</div>
		</form>
	</div>
</div>

<script type="text/javascript" src="questions/js/functions.js"></script>
<script type="text/javascript" src="questions/js/form.js"></script>

</body>
</html>
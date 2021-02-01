<form method="get" action="search.php" id="search-form">
	<input type="text" name="search" id="search" value="<?php echo (isset($_GET['search'])) ? $_GET['search'] : ''; ?>" placeholder="What are you looking for?" />
	<button type="submit"><i class="fa fa-search"></i></button>
</form>
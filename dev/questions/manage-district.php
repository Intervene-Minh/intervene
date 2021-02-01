<?php
include("header.php");

$login_role = $_SESSION['login_role'];
if ($login_role != 0 || !isGlobalAdmin()) {
    header("location: index.php");
}

$error = '';
$id = $_SESSION['login_id'];


?>
<?php
// Pagination
$per_page = ( isset($_GET['per_page']) && is_numeric($_GET['per_page']) && $_GET['per_page'] > 0 ) ? $_GET['per_page'] : 20;
$paged = ( isset($_GET['paged']) && is_numeric($_GET['paged']) && $_GET['paged'] > 0 ) ? $_GET['paged'] : 1;
$query = mysql_query('SELECT id FROM loc_district ');		# Count total of records
$count = (int) mysql_num_rows($query);		# Total of records
$total = (int) ceil($count / $per_page);	# Total of pages
$start = (int) ($paged - 1) * $per_page;	# Start of records
$limit = " LIMIT $start , $per_page";		# Limit number of records will be appeared
$childs = $results = mysql_query('SELECT * FROM loc_district ORDER BY district_name ASC  '.$limit);
?>
<div id="main" class="clear fullwidth">
    <div class="container">
        <div class="row">
            <div id="sidebar" class="col-md-4">
                <?php include("sidebar.php"); ?>
            </div>		<!-- /#sidebar -->
            <div id="content" class="col-md-8">
                <form id="search-users" method="GET" action="">
                        <table class="table">
                            <tr>
                                <td><label>District:</label></td>
                    <td><input type="submit" name="search" value="Search" class="button" /></td>
                            </tr>

                        </table>
                    </form>            
                <div id="single_question" class="content_wrap">
					<div class="ct_heading clear">
						<h3>Manage Districts</h3><ul>
                                                    <li><a href="edit_district.php"><i class="fa fa-plus-circle"></i></a></li>
			
		</ul>
					</div>
                    
                <div class="table-responsive">
                    
                    <table class="table-manager-user col-md-12">
                        <tr>
                           
                            <td>District</td>
                            <td>State</td>
                            <td>Action</td>
                        </tr>
                        <?php 
                               if (mysql_num_rows($results) > 0) {
                                   $i=1;
                                   while ($row = mysql_fetch_assoc($results)) { 
                                       ?>
                        <tr>
                            <td><?php print $row['district_name']; ?></td>
                            <td>Texas</td>
                            <td><a href="edit_district.php?id=<?php print $row['id']; ?>">Edit</a></td>
                        </tr>
                        <?php
                                   }
                               }
                               ?>
                    </table>
                </div>
                </div>		<!-- /#content -->
                <div class="clearnone">&nbsp;</div>
            </div>
        </div>
    </div>		<!-- /#header -->
<?php if( mysql_num_rows($childs) > 0 ) include("pagination.php"); ?>
    <?php include("footer.php"); ?>
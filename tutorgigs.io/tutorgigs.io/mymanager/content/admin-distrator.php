<?php
if (!isset($_SESSION['login_role']) || $_SESSION['login_role'] != 0) {
    header('Location: index.php');
    exit;
}
$search_cond = '';
if($_GET['dis']) {
    $search_cond = ' WHERE  d.name LIKE "%'.addslashes($_GET['dis']).'%" ';
}
// Pagination
$per_page = ( isset($_GET['per_page']) && is_numeric($_GET['per_page']) && $_GET['per_page'] > 0 ) ? $_GET['per_page'] : 20;
$paged = ( isset($_GET['paged']) && is_numeric($_GET['paged']) && $_GET['paged'] > 0 ) ? $_GET['paged'] : 1;
$query = mysql_query("SELECT d.id FROM `distrators` d LEFT JOIN lessons l ON d.id = l.distrator_id".$search_cond);  # Count total of records
$count = (int) mysql_num_rows($query);  # Total of records
$total = (int) ceil($count / $per_page); # Total of pages
$start = (int) ($paged - 1) * $per_page; # Start of records
$limit = " LIMIT $start , $per_page";  # Limit number of records will be appeared

$childs = mysql_query("SELECT d.*, l.id as lesson_id, l.name as lesson_name FROM `distrators` d LEFT JOIN lessons l ON d.id = l.distrator_id " .$search_cond. $limit);
?>
<div id="distrator_wrap" class="content_wrap">
    <div class="table-responsive">
        <form id="search-users" method="GET" action="">
            <table class="table">
                <tr>
                    <td><label>Search:</label></td>
                    <td><input type="text" name="dis" class="form-control" placeholder="Distractor" value="<?php echo (isset($_GET['dis'])) ? $_GET['dis'] : ''; ?>" /></td>

                    <td><input type="submit" name="action" class="btn" value="Search" /></td>
                </tr>
            </table>
        </form>
    </div>
    <div class="ct_heading clear">
        <h3>List Distrator</h3>
        <ul>
            <li><a href="javascript: void(0);" class="popup_form" data-target="#distrator_dialog" title="New Distrator"><i class="fa fa-plus-circle"></i></a></li>
            <li><a href="javascript: void(0);" class="popup_edit" data-target="#distrator_dialog"><i class="fa fa-pencil-square-o"></i></a></li>
            <?php if (isGlobalAdmin()) : ?>
                <li><a href="javascript: void(0);" class="remove_items" data-type="distrator"><i class="fa fa-trash"></i></a></li>
            <?php endif; ?>
        </ul>
    </div>		<!-- /.ct_heading -->

    <div class="ct_display no_padding clear">
        <?php
        if (mysql_num_rows($childs) > 0) {
            $i = 1;
            while ($item = mysql_fetch_assoc($childs)) {
                $line = ($i % 2 == 0) ? ' second' : '';
                ?>
                <div class="item-listing clear<?php echo $line; ?>">
                    <input type="checkbox" class="edit_items" value="<?php echo $item['id']; ?>" title="Edit Distrator (<?php echo $item['name']; ?>)" />

                    <a href="javascript: void(0);" class="item_name"><?php echo $item['name']; ?></a> <br /><?php if ($item['lesson_id'] > 0) {
            print "Link to Defination: " . $item['lesson_name'];
        } ?> 
                </div>
                <?php
                $i++;
            }
        } else {
            echo '<div class="item-listing clear"><p>There is no item found!</p></div>';
        }
        ?>
        <div class="clearnone">&nbsp;</div>
    </div>		<!-- /.ct_display -->
</div>		<!-- /#distrator_wrap -->

<?php
if (mysql_num_rows($childs) > 0)
    include("pagination.php");?>
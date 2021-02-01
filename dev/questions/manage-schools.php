<?php
include("header.php");

$login_role = $_SESSION['login_role'];
if ($login_role != 0 || !isGlobalAdmin()) {
    header("location: index.php");
}

$error = '';
$id = $_SESSION['login_id'];

if (isset($_POST['delete-user'])) {
    $arr = $_POST['arr-user'];
    if ($arr != "") {
        $query = mysql_query("DELETE FROM users WHERE id IN ('$arr')", $link);
    }
}

$schools = mysql_query("SELECT * FROM `schools` WHERE `status` = 1");
?>
<script>
    $(document).ready(function () {
        $('#delete-user').on('click', function () {
            var count = $('#form-manager .checkbox:checked').length;
            $('#arr-user').val("");
            $('#form-manager .checkbox:checked').each(function () {
                var val = $('#arr-user').val();
                var id = $(this).val();
                $('#arr-user').val(val + ',' + id);
            });
            var str = $('#arr-user').val();
            $('#arr-user').val(str.replace(/^\,/, ""));
            return confirm('Are you want to delete ' + count + ' user?');
        });
    });
</script>
<?php
if (isset($_POST['upload'])) {
    $ctr = 0;
    $file_name = $_FILES['school_list']['name'];
    //echo $file_name; die;
    $cwd = getcwd();
    $uploads_dir = $cwd . '/uploads/school_csv';
    $tmp_name = $_FILES["school_list"]["tmp_name"];
    $name = $school_id . '_' . $user_id . '_' . basename($_FILES["school_list"]["name"]);
    move_uploaded_file($tmp_name, "$uploads_dir/$name");
    $row = 1;

    if (($handle = fopen($uploads_dir . '/' . $name, "r")) !== FALSE) {
        $d = 0;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

            if ($d != 0) {
                $district_name = $data[1];
                $school_name = $data[2];
                $result = mysql_fetch_assoc(mysql_query('SELECT * FROM loc_district WHERE district_name = "' . $district_name . '" '));
                $district_id = $result['id'];
                $state_id = $result['state_id'];
                mysql_query('INSERT INTO master_schools SET '
                        . 'state_id = \'' . $state_id . '\' , '
                        . 'district_id = \'' . $district_id . '\' , '
                        . 'school_name = \'' . $school_name . '\' ');
            }
            $d = $d + 1;
        }
    }
}
// Pagination
$per_page = ( isset($_GET['per_page']) && is_numeric($_GET['per_page']) && $_GET['per_page'] > 0 ) ? $_GET['per_page'] : 20;
$paged = ( isset($_GET['paged']) && is_numeric($_GET['paged']) && $_GET['paged'] > 0 ) ? $_GET['paged'] : 1;

if($_GET['school_name']) {
    $cond = ' AND ms.school_name LIKE "%'.addslashes($_GET['school_name']).'%" ';
}

if($_GET['district'] > 0) {
    $cond .= ' AND ms.district_id = \''.$_GET['district'].'\' ';
}


$query = mysql_query('SELECT ms.id,  ms.school_name, dis.district_name, st.state_name FROM master_schools ms '
        . 'LEFT JOIN loc_district dis ON ms.district_id = dis.id  '
        . 'LEFT JOIN loc_state st ON ms.state_id = st.id  WHERE 1 '.$cond.' ORDER BY ms.school_name ASC  ');  # Count total of records
$count = (int) mysql_num_rows($query);  # Total of records
$total = (int) ceil($count / $per_page); # Total of pages
$start = (int) ($paged - 1) * $per_page; # Start of records
$limit = " LIMIT $start , $per_page";  # Limit number of records will be appeared
$childs = $results = mysql_query('SELECT ms.id,  ms.school_name, dis.district_name, st.state_name FROM master_schools ms '
        . 'LEFT JOIN loc_district dis ON ms.district_id = dis.id  '
        . 'LEFT JOIN loc_state st ON ms.state_id = st.id WHERE 1 '.$cond.' ORDER BY ms.school_name ASC  ' . $limit);
$district_qry = mysql_query('SELECT * from loc_district ORDER BY district_name ASC ');
?>
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">

<!-- JS -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>

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
                                <td><label>Search:</label></td>
                                <td><input type="text" name="school_name" class="form-control" placeholder="School Name" value="<?php echo (isset($_GET['school_name'])) ? $_GET['school_name'] : ''; ?>" /></td>
                                <td>
                                    <select name="district" id="district">
                                        <option value="0">Choose District</option>
                                <?php while ($district = mysql_fetch_assoc($district_qry)) { ?>
                                                                        <option <?php if ($_GET['district'] == $district['id']) { ?> selected="selected" <?php } ?> value="<?php print $district['id']; ?>"><?php print $district['district_name']; ?></option>

                                <?php } ?>
                                </select>
                                </td>
                                <td><input type="submit" name="action" class="btn" value="Search" /></td>
                            </tr>
                        </table>
                    </form>
                <div id="single_question" class="content_wrap">
                    <div class="ct_heading clear">
                        <h3>Manage Schools</h3><ul>
                            <li><a href="edit_school.php"><i class="fa fa-plus-circle"></i></a></li>

                        </ul>
                    </div>
                    <div class="table-responsive">

                        <!--                    <form id="search-users" method="POST" action="" enctype="multipart/form-data">
                                                <table class="table">
                                                    <tr>
                                                        <td><label>Choose File: </label><input type="file" name="school_list" /></td>
                                                        <td><input type="submit" name="upload" value="upload" class="button" /></td>
                                                    </tr>
                        
                                                </table>
                                            </form>-->
                        <table class="table-manager-user col-md-12">
                            <tr>
                                <td>School Name</td>
                                <td>District</td>
                                <td>State</td>
                                <td>Action</td>
                            </tr>
                            <?php
                            if (mysql_num_rows($results) > 0) {
                                $i = 1;
                                while ($row = mysql_fetch_assoc($results)) {
                                    ?>
                                    <tr>
                                        <td><?php print $row['school_name']; ?></td>
                                        <td><?php print $row['district_name']; ?></td>
                                        <td><?php print $row['state_name']; ?></td>
                                        <td><a href="edit_school.php?sid=<?php print $row['id']; ?>">Edit</a></td>
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
<script type="text/javascript">
<?php if ($error) { ?>
        alert('<?php print $error; ?>');
<?php } ?>
    $(document).ready(function () {
        $('#district').chosen();
    });
    </script>
   
<?php if (mysql_num_rows($childs) > 0) include("pagination.php"); ?>
<?php include("footer.php"); ?>
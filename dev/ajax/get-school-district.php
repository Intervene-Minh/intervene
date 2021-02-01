<?php
include('../questions/inc/connection.php');

//echo 'Test optons';  die; 

session_start();
$id = $_SESSION['demo_user_id'];
$sql = mysql_query("SELECT `school_id` FROM `demo_users` WHERE `id` = $id ");
if (mysql_num_rows($sql) > 0) {
    if (mysql_num_rows($sql) == 1) {
        $school_id = mysql_fetch_assoc($sql);
    } else {
        $error = 'Error';
    }
}
$district = $_POST['district']; // mysql_query
$schools =mysql_query("SELECT * FROM `master_schools` WHERE `district_id` = $district");

$query =  ("SELECT ms.school_name, school.Schoolid as id FROM `master_schools` ms "
                . "INNER JOIN schools school ON school.master_school_id = ms.id "
                . "WHERE  ms.district_id =  ".$district);

 //echo $schools , '<br/>' ,$query; 

//die; 

////////////////////////

$respond = "";
if( mysql_num_rows($schools) > 0 ) {
	$respond.='<select name="school" id="school" class="form-control ">';
	while($row = mysql_fetch_assoc($schools)) {
            $selected = ($school_id['school_id'] == $row['id']) ? ' selected' : '';
		$respond .= "<option value='{$row['id']}' {$selected}>{$row['school_name']}</option>";
	}

	$respond.='<option value="other">Other</option> </select>';
    $respond.=' <small class="error text-danger">Please enter your school!</small>';
}

echo $respond;
?>
<script>
 //$=jQuery;
$(document).ready(function(){
 
$('#school').on('change', function(){
            var school_val = $(this).val(); 
             var school_html='';
            // alert(school_val);

             if(school_val=='other'){
                $('#oth-school').show();
            }else{ $('#oth-school').hide();}

            

        });

});
</script>

<?php
die();
?>
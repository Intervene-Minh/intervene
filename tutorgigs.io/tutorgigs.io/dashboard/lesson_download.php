<?php
/****
Lesson list
**/
 @extract($_GET) ;
@extract($_POST) ;
include("header.php");

  $curr_time= date("Y-m-d H:i:s"); #currTime
$login_role = $_SESSION['login_role'];
$page_name="Jobs Board List";

// action
if(!isset($_SESSION['ses_teacher_id'])){
    header('Location:logout.php');exit;
}


if(isset($_SESSION['ses_access_website'])&&$_SESSION['ses_access_website']=="no"){
  header("Location:".$tutor_regiser_page);exit;
}



$error='';
$id = $_SESSION['ses_teacher_id'];
// submit_reject
// 


//$schools = mysql_query("SELECT * FROM `schools` WHERE `status` = 1");


//////lesson listing////
$result_lessons= mysql_query(" SELECT l. * , t.id AS objid, t.name as objname , t.short_code, t.obj_short
FROM `master_lessons` l
LEFT JOIN terms t ON l.objective_id = t.id
WHERE t.taxonomy = 'objective'
ORDER BY t.obj_short ASC  ");

?>
<script>

///////////

	$(document).ready(function(){
		$('#delete-user').on('click',function(){
			var count = $('#form-manager .checkbox:checked').length;
			$('#arr-user').val("");
			$('#form-manager .checkbox:checked').each(function(){
				var val = $('#arr-user').val();
				var id = $(this).val();
				$('#arr-user').val(val+','+id);
			});
			var str = $('#arr-user').val();
			$('#arr-user').val(str.replace(/^\,/, ""));
			return confirm('Are you want to delete '+count+' user?');
		});
	});
        
  /////////////////      
      function sent_form(path, params, method) {
    method = method || "post"; // Set method to post by default if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);
	 form.setAttribute("target", "_blank");

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
         }
    }

    document.body.appendChild(form);
    form.submit();
}

///
$(document).ready(function() {
   $('#setdate').change(function() {
     var parentForm = $(this).closest("form");
     if (parentForm && parentForm.length > 0)
       parentForm.submit();
   });
});
</script>
 <style>
    #myInput {
    background-image: url('/css/searchicon.png'); /* Add a search icon to input */
    background-position: 10px 12px; /* Position the search icon */
    background-repeat: no-repeat; /* Do not repeat the icon image */
    width: 100%; /* Full-width */
    font-size: 16px; /* Increase font-size */
    padding: 12px 20px 12px 40px; /* Add some padding */
    border: 1px solid #ddd; /* Add a grey border */
    margin-bottom: 12px; /* Add some space below the input */
}

#myUL {
    /* Remove default list styling */
    list-style-type: none;
    padding: 0;
    margin: 0;
}

#myUL li a {
    border: 1px solid #ddd; /* Add a border to all links */
    margin-top: -1px; /* Prevent double borders */
    background-color: #f6f6f6; /* Grey background color */
    padding: 12px; /* Add some padding */
    text-decoration: none; /* Remove default text underline */
    font-size: 18px; /* Increase the font-size */
    color: black; /* Add a black text color */
    display: block; /* Make it into a block element to fill the whole list */
}

#myUL li a:hover:not(.header) {
    background-color: #eee; /* Add a hover effect to all links, except for headers */
}
#myUL li{  margin:5px;
  }

 </style>
<div id="main" class="clear fullwidth">
	<div class="container">
		<div class="row">
			<div id="sidebar" class="col-md-4">
				<?php include("sidebar.php"); ?>
			</div>		<!-- /#sidebar -->
			<div id="content" class="col-md-8">
      <nav class="navbar navbar-light bg-light static-top">

     

    </nav>
 <h1 align="center">Download Lessons</h1>
 <h4>Search for TEKS.  Example: 4.4(C)</h4>
 <h4>Just click the TEKS and a lesson will download automatically</h4>
<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for TEKS here">
<ul id="myUL">
   <?php 
 // $result_lessons
   if(mysql_num_rows($result_lessons)>0){
    while ($row=mysql_fetch_assoc($result_lessons)) {
      # code... /dashboard/lessons/math/texas/gr3/3.2A.pdf
      if(!empty($row['file_name']))
    $downlodad_link="https://intervene.io/questions/uploads/lesson/".$row['file_name'];
    else $downlodad_link="#";
     $text=$row['name'];
    if(!empty($row['obj_short'])){
      $text=$row['obj_short'].'-'.$row['name'];
    }



   ?>
<li title="click to Download"><a href="<?=$downlodad_link?>"><?=$text?></a></li>

  <?php  }
   } ?>






</ul>

 


 <script>
function myFunction() {
    // Declare variables
    var input, filter, ul, li, a, i;
    input = document.getElementById('myInput');
    filter = input.value.toUpperCase();
    ul = document.getElementById("myUL");
    li = ul.getElementsByTagName('li');

    // Loop through all list items, and hide those who don't match the search query
    for (i = 0; i < li.length; i++) {
        a = li[i].getElementsByTagName("a")[0];
        if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
        }
    }
}
</script>
				</div>		
      <!-- /#content -->

			<div class="clearnone">&nbsp;</div>
		</div>
	</div>
</div>		<!-- /#header -->

<script type="text/javascript">
<?php 
  

if ($error != '') echo "alert('{$error}')"; ?>
</script>
   
<?php include("footer.php"); ?>
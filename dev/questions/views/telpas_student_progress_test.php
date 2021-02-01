<?php
include("header.php");

$Permission = _CheckTelpassPermission();
if($Permission==0){

  header("location:https://intervene.io/questions/folder.php");
  exit;
}

$login_teacher_id=$_SESSION['login_id'];
/////////////////////////////////////
$user_id = $_SESSION['login_id'];

include_once("MoodleWebServices/get-category-list.php");?>
<style>
table, th, td {border: 1px solid black;}
td{padding: 5px;}
</style>

<div id="main" class="clear fullwidth">
   <div class="container">
      <div class="row">
         <div id="sidebar" class="col-md-4">
            <?php include("sidebar.php"); ?>
         </div>
         <!-- /#sidebar -->
         <div id="content" class="col-md-8">
            <div id="single_question" class="content_wrap">
               <div class="ct_heading clear">
                  <h3><i class="fa fa-plus-circle"></i>Teacher Reports on Student Progress</h3>
               </div>
               <!-- /.ct_heading -->
               <div class="ct_display clear">
                 
                  <div class="col-md-6">
                    <div class="form-group">
                        <label>Assignment Category</label>
                        <select class="form-control" id="CatName">
                            <option vaue="0">Select Category</option>
                            <?php 
                            foreach ($responCategory as  $value) {?>
                                <option value="<?php echo $value['id'];?>"> <?php echo $value['name']?></option>
                                <?php } ?>
                            </select>
                        </div>
                  </div>

                      <div class="col-md-6">
                    <div class="form-group courseList" style="display: none">
                    <label>Assignment</label>
                    <select class="form-control" id="courseName">
                    <option vaue="0">Select Assignment</option>
                    </select>
                    </div>
                  </div>

 <div class="col-md-12" style="text-align: center;display: none" id="loaderImg">
                  <img src="https://intervene.io/questions/wait.gif" alt="gif" style="height: 50px" align="center">
              </div>


   <!-- Telpas Student Report -->
   <?php 
    $category_arr=  array("Reading", "Listening","Speaking","Writing" );

   ?>
 <div class="col-md-12 tbl" >

 <table width="100%" class="table" style="background: #fff; padding: 10px;border-collapse: collapse; border:none;">
  <thead class="thead-primary">
   <tr ><th>  Student Name</th>
       <!-- Course-items -->
      <th>  Reading</th>
      <th>  Listening</th>
      <th>  Speaking</th>
      <th>  Writing</th></tr>
</thead>
<!--  Rows    -->  
   <tbody id="dataRow99"></tbody>

</table>
 </div>
        <!-- Telpas Student Report -->
                  <div class="clearnone">&nbsp;</div>
               </div>
               <!-- /.ct_display -->
            </div>
         </div>
         <!-- /#content -->
         <div class="clearnone">&nbsp;</div>
      </div>
   </div>
</div>
<script type="text/javascript">
	function openModal(str){
  var StuID=str;
  $.ajax({

        url:"get_audio_modal.php",
        type:"POST",
        data:{InereveneStuID:StuID},
        success:function(data)
        {

              $('#myModal').modal('show');
              $('.DataList').html(data);
        }
});
}
  function _giveGrade(stu,qusID) {
 //alert(stu);
  //alert(qusID);
}
</script>
<style>
table, th, td {
    border: 1px solid #fff !important;
}
.table thead tr th {
    vertical-align: bottom;
    border-bottom: 2px solid #fff !important;
  background-color:#1b64a9;
  color: #fff;
}
.table tbody tr td{
  background-color: #e9ebf4;
}
.table tbody tr:nth-child(odd) td{
  background-color: #cfd5e8;
}
.DataList{    
    list-style: none;
    text-align: center;
}
.modal-dialog {
    max-width: 900px;
	width: 900px;
}
.w-100{
	width:100%;
	font-weight:bold;
	display:inline-block;
}
audio{
    display: inline-block;
    vertical-align: baseline;
    width: 100%;
}
.br-2{
	border-bottom:1px solid #ddd;
	padding: 20px 0px;
	border-left: 1px solid #ddd;
}
</style>
<!-- /#header -->
<?php include("footer.php"); ?>
<script>
$('#CatName').change(function()
{

    var catID=$(this).val();

    if(catID > 0)
    {
           $('#loaderImg').show();
    $.ajax({

        url:"MoodleWebServices/get_courseListByField_test.php",
        type:"POST",
        data:{category:catID},
        success:function(data)
        {
            console.log(data);
        $('#loaderImg').hide();
            $('#dataRow99').html(data);

        }
});
}
else{


   $('.courseList').hide();
}
});

</script>
 <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Audio List</h4>
        </div>
        <div class="modal-body">
		  <div class="row">
		       <div class="col-md-3">
			        <p class="w-100">
					   Directions for user:<br>
					   1 = Beginner;<br>
					   2 = Intermediate;<br>
					   3 = Advanced;<br>
					   4 = Advanced High</p>
					<p class="w-100">Calculation:<br> Points earned/Point Possible
					</p>
			   </div>
         <div class="col-md-9">
          <form>
          <div class="DataList"></div>
          <div class="row br-2 text-center">
            <div class="checkbox">
							<label><input type="checkbox"> Remember me</label>
						  </div>
						 <button type="submit" class="btn btn-default" style="padding: 6px 39px;">Submit</button>
                    </div>  
                    </form>					
			   </div>
		  </div>
        </div>
      </div>
    </div>
  </div>
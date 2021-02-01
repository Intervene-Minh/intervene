<?php
   $datetm = date('Y-m-d H:i:s');
   $msg='';
   extract($_REQUEST);
   include("header.php");
   if($_SESSION['login_role'] !=0) { //not admin
      header('Location: folder.php');
      exit;
   } 
function _gradeName($id){

   $graeName='NA';
   if($id > 0){

         $str="SELECT `id`,`name` FROM `terms` WHERE id=$id";
         $qr=mysql_query($str);
         $res = mysql_fetch_assoc($qr);
         $graeName= $res['name']; 
   }
     
   return $graeName;                        
}
if(isset($submitButton)){

   if($id>0){
      $str="UPDATE `classlink_grade_x_terms` SET `intervene_grade_id`=$grad_interven_id WHERE id=".$id;
         mysql_query($str);
         $msg="<p class='alert alert-success'>Grade Update Successfully. <a class='text text-info' href='class_link_grade.php'> back to grade list page</a></p>";
  }

}
?>  
<style type="text/css"> .chosen-single{height: 31px!important}</style>                                                            
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
                  <h3><i class="fa fa-eidt"></i>Class Link Grade Edit</h3>
               </div>
               <!-- /.ct_heading -->
               <div class="ct_display clear">
                  <?php  
                     $str=" SELECT * FROM `classlink_grade_x_terms` WHERE active =1 && id=".$id;
                     $gradeArr = mysql_query($str);
                        $num = mysql_num_rows($gradeArr);

                        if($num > 0){
                     $row = mysql_fetch_assoc($gradeArr);

                     $inID=$row['intervene_grade_id'];?>
                  <form name="form_question" id="form_question" method="post" action="" enctype="multipart/form-data">
                     <div class="col-md-12"><?php echo $msg;?></div>
                     <div class="col-md-3">
                        <label>Class Link Grade Name</label>
                        <input type="text" readonly="readonly" class="form-control" value="<?php echo $row['name']?>">
                     </div>
                     <div class="col-md-3"> <label>Intervene Grade Name</label>
                        <input type="text" readonly="readonly" class="form-control" value="<?php echo _gradeName($inID)?>">
                     </div>
                     <div class="col-md-6"> <label>Intervene Grade List</label>
                        <select class="form-control" name="grad_interven_id" id="grad_interven_id" required="required">
                           <option value=""> </option> 
                        <?php
                           $gradeInArr = mysql_query("SELECT * FROM `terms` WHERE  `active` = 1");
                           if( mysql_num_rows($gradeInArr) > 0 ) {
                              while( $row = mysql_fetch_assoc($gradeInArr) ) {
                                 $selected = ($row['id'] == $inID) ? ' selected="selected"' : '';
                                 echo '<option value="' . $row['id'] . '"' . $selected . '>' . $row['name'] . '</option>';
                              }
                           }
                           ?>
                        </select>
                     </div>
                     <div class="col-md-12" style="padding: 20px"></div>
                     <div class="col-md-12" style="text-align: center;padding: 26px;">
                        <input type="submit" name="submitButton" class="form_button submit_button btn" value="Submit">
                        <a class='form_button submit_button btn' href='class_link_grade.php'>Cancel</a>
                     </div>
                  </form>
               <?php }else{

                  echo "<p class='alert alert-info'>Please Change Status First. <a class='text text-danger' href='class_link_grade.php'> back to grade list page</a></p>";
               }?>
               </div>
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
<!-- /#header -->
<!-- CSS -->
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">

<!-- JS -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>
<script type="text/javascript">
   
   $("#grad_interven_id").chosen();
</script>
<script type="text/javascript" src="js/functions.js"></script>
<script type="text/javascript" src="js/form.js"></script>

</body>
</html>
<?php
   $datetm = date('Y-m-d H:i:s');
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

$limit = 10;  
if (isset($_GET["page"])) {
   $page  = $_GET["page"]; 
   } 
   else{ 
   $page=1;
   };  
$start_from = ($page-1) * $limit;
$strQuery="SELECT sc.SchoolName,trm.* FROM `classlink_grade_x_terms` as trm  INNER JOIN `schools` AS sc On sc.sourced_id= trm.school_source_id";
   ?>                                                               
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
                  <h3><i class="fa fa-list"></i>Class Link Grade List</h3>
               </div>
               <!-- /.ct_heading -->
               <div class="ct_display clear">
                  <table class="table">
                     <tr>
                        <th>Slno</th>
                        <th>Class Link Grade Name</th>
                        <th>Class Link School Name</th>
                        <th>Intervene Grade Name</th>
                        <th>Action</th>
                     </tr>
                  <?php  $str= "$strQuery ORDER BY trm.name ASC LIMIT $start_from, $limit";
                      $gradeArr = mysql_query($str);
                              if( mysql_num_rows($gradeArr) > 0 ) {
                                 $i=1;
                                 while( $row = mysql_fetch_assoc($gradeArr) ) {
                                    $staus= ($row['active']==1?'text-success':'text-danger');
                                    $title= ($row['active']==1?'Archive This':'Undo This');
                                     $stausVal= ($row['active']==1? 0 : 1 );
                                  ?>
                                    <tr>
                                       <td><?php echo $i;?></td>
                                       <td><?php echo $row['name']?></td>
                                       <td><?php echo $row['SchoolName']?></td>
                                       <td align="center"><?php echo  _gradeName($row['intervene_grade_id'])?></td>
                                       <td align="center">
                                          <?php if($row['active'] > 0){?>
                                          <a href="class_link_grade_update.php?id=<?php echo $row['id']?>" title="Edit This"><i class="fa fa-edit"></i></a>
                                       <?php } ?>
                                          <a onclick="archive('<?php echo $row['id']?>','<?php echo $stausVal ?>')" title="<?php echo $title?>"
                                           href="javascript:" class="text <?php echo $staus?>"><i class="fa fa-archive"></i></a>
                                       </td>
                                    </tr>
                                 <?php $i++;} }?>
                                 </table>
                              </div>
                              <div class="col-md-12 text-right">
                                 <?php 
                           $result_db = mysql_query($strQuery); 
                           $total_records = mysql_num_rows($result_db);  
                           $total_pages = ceil($total_records / $limit); 
                           /* echo  $total_pages; */
                  
                           $pagLink = "<ul class='pagination'>";  
                           for ($i=1; $i<=$total_pages; $i++) {
                              if($page==$i){

                                 $class='active';
                              }
                              else{
                                          $class='';
                              }
                              $pagLink .= "<li class='page-item ".$class." '><a class='page-link' href='class_link_grade.php?page=".$i."'>".$i."</a></li>"; 
                           }
                           echo $pagLink . "</ul>"; ?>
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

<script type="text/javascript">
   
   function archive(id,status){

      var x= confirm("Are you sure you want change status?");
      if(x==true)
      {
          $.ajax({
                     url:'ajax_chage_status.php',
                     data:{
                            'id':id,
                            'status':status
                           },
                      type: 'POST',
                     success:function(d)
                     {
                        location.reload();
                        }

                  });

      }
      return false;
   }
</script>
<script type="text/javascript" src="js/functions.js"></script>
<script type="text/javascript" src="js/form.js"></script>

</body>
</html>
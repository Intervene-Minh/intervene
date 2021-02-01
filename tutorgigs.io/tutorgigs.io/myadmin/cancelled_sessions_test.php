<?php
@extract($_REQUEST);
///////////////////////////
include("header.php");
$error='';
$queryFilter=" ";
$login_role = $_SESSION['login_role'];
$page_name="Cancelled Sessions History";
$id = $_SESSION['login_id'];
?>
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>
<style type="text/css">
  .bs-example{margin: 20px;}
  .nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover {cursor: default;background-color: transparent;border: 1px solid #ddd;border-bottom-color: #fff;font-size: 16px;color:#1b64a9;}
  .nav-tabs { border-bottom: 1px solid #ddd;}
  .tab-content{padding:20px 10px;margin-top:0px;border:1px solid #ddd;border-top-color: transparent;float: left;}
  .nav>li>a {position: relative;display: block;padding: 12px 20px;font-size: 16px;}
  .resolve_session{color: #fff;background-color: #d9534f;padding: 7px 22px;border: none;}
  .resolve_session:hover{background-color: #d9534f!important;color: #fff!important;}
  .resolve_session:focus{background-color: #d9534f!important;color: #fff!important;}
  
</style>
<div id="main" class="clear fullwidth">
   <div class="container">
      <div class="row">
         <div id="sidebar" class="col-md-4">
            <?php include("sidebar.php"); ?>
         </div>
         <!-- /#sidebar -->
         <?php
         if(isset($resolve_session)){

            $str="UPDATE `sessioncancelnotification` SET ReadStatus=1 WHERE  `ID` IN(".$arruser.")";
            mysql_query($str);
            header('location:cancelled_sessions.php');
        }

          /*filter*/
         if(isset($_GET['action'])&&$_GET['action']=="Filter")
          {
            if($filter=='all'){
              header('location:cancelled_sessions.php');

            }

            else{
            $queryFilter = " AND ReadStatus=".$filter;
          }

          }
            $sql="SELECT sn.*,ins.* FROM `sessioncancelnotification` AS sn LEFT JOIN `int_schools_x_sessions_log` AS ins ON sn.SessionID=ins.id
            WHERE 1 ".$queryFilter." ORDER BY sn.ID DESC";

            $results = mysql_query($sql);
            $tot_record=mysql_num_rows($results);
          //table-responsive
            ?>
         <div id="content" class="col-md-8">
            <div class="bs-example">
               <div class="tab-content-off">
                  <div id="sectionA9" class="tab-pane">
                  </div>
                  <div id="sectionB" class="tab-pane fade in active">
                     <form id="search-users" method="GET" action="">
                      <div class="col-md-12" style="padding: 10px">
                      <div class="col-md-4">
                        <select name="filter" id="districtqzz" style="color: red;" class="form-control">
                          <option value="all" <?php if(empty($filter)){echo 'selected';}?>>All</option>
                          <option value="0"   <?php if(isset($filter) && $filter==0){echo 'selected';}?>>Unresolved</option>
                          <option value="1"   <?php if(isset($filter) && $filter==1){echo 'selected';}?>>Resolved</option>
                        </select>
                      </div>
                      <div class="col-md-4">
                       <input name="action" class="btn btn-default" value="Filter" type="submit">  
                       </div> 
                       </div> 
                     </form>
                     <form id="form-manager" class="content_wrap" action="" method="post">
                        <div class="ct_heading clear">
                           <h3><?php echo $page_name?>(<?php echo  $tot_record; ?>)</h3>
                           <ul>
                              <li>             
                                 <button id="resolve_session"
                                    type="submit" name="resolve_session" class="resolve_session btn btn-default">Resolved Selected</button>
                              </li>
                           </ul>
                        </div>
                        <div class="clear">
                           <table class="table-manager-user col-md-12">
                              <tbody>
                                 <tr>
                                    <th style="width: 10%">
                                       <input class="" id="checkAll" type="checkbox">       
                                    </th>
                                    <th>Date/Time</th>
                                    <th>Details</th>
                                 </tr>
                              </tbody>
                              <?php
                                 if( mysql_num_rows($results) > 0 ) {
                                  while( $row = mysql_fetch_assoc($results) ) {
                                  $str="SELECT * FROM `gig_teachers` WHERE  `id`='".$row['TutorID']."'";
                                  $r=mysql_query($str);
                                  $Tutor= mysql_fetch_assoc($r);
                                  ?>
                              <tr>
                                 <td>
                                    <input type="checkbox" class="checkbox" value="<?php echo $row['ID'];?>"/>
                                 </td>
                                 <td>
                                    <span>
                                    <?php  echo date_format(date_create($row['ses_start_time']), 'F d,Y');?><br>     
                                    </span>  
                                    <span class="btn btn-success btn-xs" title="Start Time">
                                    <?php echo date_format(date_create($row['ses_start_time']), 'h:i a');?></span> 
                                    <br/>
                                 </td>
                                 <td >
                                    <strong class="text-primary">
                                    Tutor:</strong><?php echo $Tutor['f_name']." ".$Tutor['lname'];?><br/>      
                                    <strong class="text-primary">Session id:</strong><?=$row['id']?><br/>
                                    <?php if($row['ReadStatus']==1){?>
                                    <span class="btn btn-success btn-xs" title="Resolved">Resolved</span>
                                  <?php }else{?>
                                    <span class="btn btn-danger btn-xs" title="Unresolved">Unresolved</span>
                                    <?php } //else{?>

                                  

                                    <br/>
                                 </td>
                              </tr>
                              <?php
                                 }
                                 } else {
                                 echo '<div class="clear"><p>There is no item found!</p></div>';
                                 }
                                 ?>
                           </table>
                        </div>
                        <input type="hidden" id="arruser" name="arruser" value=""/>
                     </form>
                  </div>
               </div>
            </div>
         </div>
         <!-- /#content -->
         <div class="clearnone">&nbsp;</div>
      </div>
   </div>
</div>
<!-- /#header -->
<script>
 $(document).ready(function(){

$("#checkAll").click(function(){

  $('input:checkbox').not(this).prop('checked', this.checked);
    }); 
          ////////////////  
   $('#resolve_session').on('click',function(){
    var count = $('#form-manager .checkbox:checked').length;
    $('#arruser').val("");
    $('#form-manager .checkbox:checked').each(function(){
    var val = $('#arruser').val();
    var id = $(this).val();
    $('#arruser').val(val+','+id);
    });
    var str = $('#arruser').val();
    $('#arruser').val(str.replace(/^\,/, ""));
    return confirm('Are you sure?');
 
   });
});
</script>
<?php include("footer.php"); ?>
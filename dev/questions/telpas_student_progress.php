<?php

include("header.php");
$Permission = _CheckTelpassPermission();
if($Permission==0)
{

header("location:https://intervene.io/questions/folder.php");
exit;
}

$login_teacher_id=$_SESSION['login_id'];

/////////////////////////////////////
$user_id = $_SESSION['login_id'];
include_once("MoodleWebServices/get-category-list.php");

?>


  <style>
  table, th, td {border: 1px solid black;}
  td{padding: 5px;}
  table, th, td {border: 1px solid #fff !important;}
  .table thead tr th {vertical-align: bottom;border-bottom: 2px solid #fff !important;background-color:#1b64a9;color: #fff;}
  .table tbody tr td{background-color: #e9ebf4;}
  .table tbody tr:nth-child(odd) td{background-color: #cfd5e8;}
  .DataList{ list-style: none;text-align: center;}
  .modal-dialog {max-width: 900px; width: 900px;}
  .w-100{width:100%;font-weight:bold;display:inline-block;}
  audio{display: inline-block;vertical-align: baseline;width: 100%;}
  .br-2{border-bottom:1px solid #ddd;padding: 20px 0px;border-left: 1px solid #ddd;}
  .desImg{max-width: 100%;height: auto;}
  .toggleBtn{background: #fff;color: #000;border-color: #000;font-weight: 800;border: 2px solid;}
  #loaderImg{text-align: center;display: none}
  #loaderImg img{height: 50px}
  .tableCls{background: #fff; padding: 10px;border-collapse: collapse; border:none;display: none;}
  .bgcolor{background-color: yellow!important;}
  </style>
<div id="main" class="clear fullwidth">
   <div class="container">
      <div class="row">
         <div id="sidebar" class="col-md-4"><?php include("sidebar.php"); ?>
         </div>
         <!-- /#sidebar -->
         <div id="content" class="col-md-8">
            <div id="single_question" class="content_wrap">
               <div class="ct_heading clear">
                  <h3><i class="fa fa-plus-circle"></i>Teacher Reports on Student Progress</h3>
               </div>
               <!-- /.ct_heading -->
               <div class="ct_display clear"><div class="col-md-6">
                    <div class="form-group">
                      <?php
                      array_multisort(array_map(function($element) {
                         return $element['sortorder'];
                       }, $responCategory), SORT_ASC, $responCategory);?>
                        <label>Assignment Category</label>
                        <select class="form-control" id="CatName">
                            <option vaue="0">Select Category</option>
                            <?php 
                             foreach ($responCategory as  $rowCat) {
                            if($rowCat['visible']==1){
                              ?>
                                <option value="<?php echo $rowCat['id'];?>"> <?php echo $rowCat['name']?></option>
                                <?php }  }?>
                            }
                            </select>
                        </div>
                  </div>
                  <div class="col-md-6"></div>
                  <div class="col-md-12" id="loaderImg">
                  <img src="https://intervene.io/questions/wait.gif" alt="gif"align="center">
              </div>
              <!-- Telpas Student Report -->
              <div class="col-md-12 tbl" >
                <table width="100%" class="table tableCls">
                  <thead class="thead-primary">
                    <tr ><th>  Student Name</th><th>  Reading</th><th>  Listening</th><th>  Speaking</th><th>  Writing</th></tr></thead>
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
<!-- /#header -->
<?php include("footer.php"); ?>
<?php require_once("modal_box_js.php"); ?>
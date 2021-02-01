<?php
// unset($_SESSION['msg_info']); 
 if(isset($_SESSION['msg_info'])){
?>
 <div class="alert alert-info"> <a href=""
      class="close" data-dismiss="alert" aria-label="close">×</a>
                 <?=$_SESSION['msg_info']; ?>
                </div>
<?php  unset($_SESSION['msg_info']);}  ?>

  <?php 

   if(isset($_SESSION['msg_success'])){
  ?>
   <div class="alert alert-success"> <a href=""
      class="close" data-dismiss="alert" aria-label="close">×</a>
                  <?=$_SESSION['msg_success']; ?>
                </div>
                <?php    unset($_SESSION['msg_success']); 
                }  ?>
<style>
p.page-title{
	font-size: 19px;
    font-weight: bold;
    background-color: aliceblue;
    border-bottom: 1px solid;
    font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
}
.list-item{
  background:white;
  padding:10px;
    width:100%;
  display:inline-block;
  text-align:left;
}
.nav-tabs{
  margin:40px 0px 0px;  
}
.tab-content{
  background:#ddd;
  padding:20px 20px;
  margin:10px 0px 0px;
}
.nav-tabs>li>a {
  padding:10px 30px;
  font-size:20px;
}
.dropdown {
  position: relative;
  display: inline-block; 
}
.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f1f1f1;
  min-width: 300px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
  margin:0px 0px 0px;
  padding:20px; 
}

.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

.dropdown-content a:hover {background-color: #ddd;}

.dropdown:hover .dropdown-content {display: block;}

a.dropbtn{cursor: pointer !important;}
.nav-tabs>li>a:hover {
    border-color: #eee #eee #ddd;
	border-bottom-color: transparent;
}
.nav>li{
	margin-bottom:-1px;
}
.nav>li>a:focus, .nav>li>a:hover {
    text-decoration: none;
	background-color: #fff;	
}
</style>
<?php 
 /***
 @ upcoming_sessions.php : Stop
@ start_session.php :: all upcoming
 **/
// start_session.php :: all upcoming
 // echo '==pending quiz=='.get_count_incomplete_quiz($num=1);
 // echo '==get_completed_assessments=='.get_completed_assessments($num=1);
  // get_count_pending_assessments
 // assesment result  : completed_quiz  completed_assessments
 // get_completed_quiz
//echo '==get_completed_quiz=='.get_completed_quiz($num=1);

$count_arr=array();
$count_arr['pending_quiz']=get_count_incomplete_quiz($num=1);
$count_arr['pending_assessments']=get_count_pending_assessments($num=1);
// upcoming_sessions.php
$count_arr['upcoming_sessions']=0;
   $count_arr['completed_quiz']=$result_opn1=get_completed_quiz($num=1);
		$count_arr['completed_assessments']=$result_opn2=get_completed_assessments($num=1);
  //print_r($count_arr);
?>
<ul class="nav nav-tabs ">
					<li class="dropdown">
					   <a class="dropbtn" href="pending_assessments.php" >Tests</a>
					   <?php  //if($count_arr['pending_quiz']>0||$count_arr['pending_assessments']>0){?>
					    <?php if($count_arr['pending_assessments']>0){?>
						<div class="dropdown-content tab-content-2">
						
						   <div class="row">
								<div class="col-md-12">
									<div class="list-item">
									   <?php //if($count_arr['pending_quiz']>0):?>
										<!-- <p><a href="incomplete_quiz.php">Incomplete Exit Quiz</a></p> -->
										<?php// endif;?>

										 <?php if($count_arr['pending_assessments']>0):?>

										<p><a href="pending_assessments.php">Assigned Data Dash Tests</a></p>
										<?php endif;?>


										

										<!-- <p><a href="start_session.php">Recent Session</a></p> -->
										
									</div>
								   
								</div>
								
								


						   </div>
						</div> <?php } //  ?>
					</li>


					<li class="dropdown">
					    <a class="dropbtn" href="student_pendings.php">Tutoring</a>
						<div class="dropdown-content tab-content-2">
						   <div class="row">
								<div class="col-md-12">
									<div class="list-item">

										<p><a href="student_pendings.php">Upcoming Tutorial session</a></p>
										 
									</div>
								   
								</div>

								
								 


						   </div>
						</div>
					</li>


					<li class="dropdown">
					    <a class="dropbtn" href="incomplete_quiz.php">Quiz</a>
					    <?php  if($count_arr['pending_quiz']>0){?>
						<div class="dropdown-content tab-content-2">
						   <div class="row">
								<div class="col-md-12">
									<div class="list-item">
									 <?php  if($count_arr['pending_quiz']>0): ?>
									<p><a href="incomplete_quiz.php">Incomplete Exit Quiz</a></p>
									<?php endif;?>
								

									</div>
								   
								</div>
								
								 


						   </div>
						</div>
						<?php   }//endif;?>

					</li>

					<li class="dropdown">
					    <a class="dropbtn" href="completed_quiz.php">Results</a>
					    <?php if($count_arr['completed_quiz']>0||$count_arr['completed_assessments']>0){?>
						<div class="dropdown-content tab-content-2">
						   <div class="row">
								<div class="col-md-12">
									<div class="list-item">

									<?php   if($count_arr['completed_quiz']>0):?>

										<p><a href="completed_quiz.php">Quiz Results</a></p>
										<?php  endif;?>

										 <?php if($count_arr['completed_assessments']>0):?>
										<p><a href="completed_assessments.php">Data Dash Results</a></p>
										<?php endif;?>
									</div>
								   
								</div>
						   </div>
						</div>  <?php  }//endif;?>
					</li>
					<?php // telpas_practice.php
					// telpas_practice_login.php
					if(_CheckTelpassPermissionStudent()==1){ ?>
					<li class="">
					   <a class="dropbtn" href="telpas_practice.php" >TELPAS Pro-Practice</a>
					</li>
					<?php
					// Test Quiz Week practice 
					 $default_quiz_course_id=8;
					 $telps_quiz_start_url="telpas_start_quiz_iframe.php?cid=".$default_quiz_course_id;

					?>
					


				<?php } ?>
				</ul>

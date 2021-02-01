<?php
echo 'Start==';
echo  $start_date = date('Y-m-d h:i:s');

include("student_header.php");
if (!$_SESSION['student_id']) {
    header('Location: login.php');
    exit;
}


?>
<style>
.list-item{
	background:white;
	padding:10px;
    width:100%;
	display:inline-block;
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
  min-width: 900px;
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

</style>
<div id="home main" class="clear fullwidth tab-pane fade in active">
    <div class="container">
        <div class="row">
            <div class="align-center col-md-12">
                
                <div style=" width:auto;" title="">
				<ul class="nav nav-tabs ">
					<li class="active dropdown">
					   <a class="dropbtn">Home</a>
						<div class="dropdown-content tab-content-2">
						   <div class="row">
								<div class="col-md-3 col-sm-3">
									<div class="list-item">
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p> 
									</div>
								   
								</div>
								
								 <div class="col-md-3 col-sm-3">
									<div class="list-item">
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p> 
									</div> 
								</div>
								
								 <div class="col-md-3 col-sm-3">
									<div class="list-item">
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p> 
									</div> 
								</div>
								
								 <div class="col-md-3 col-sm-3">
									 <div class="list-item">
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p> 
									</div>
								</div>
						   </div>
						</div>
					</li>
					<li class="dropdown">
					    <a class="dropbtn">Menu 1</a>
						<div class="dropdown-content tab-content-2">
						   <div class="row">
								<div class="col-md-3 col-sm-3">
									<div class="list-item">
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p> 
									</div>
								   
								</div>
								
								 <div class="col-md-3 col-sm-3">
									<div class="list-item">
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p> 
									</div> 
								</div>
								
								 <div class="col-md-3 col-sm-3">
									<div class="list-item">
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p> 
									</div> 
								</div>
								
								 <div class="col-md-3 col-sm-3">
									 <div class="list-item">
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p> 
									</div>
								</div>
						   </div>
						</div>
					</li>
					<li class="dropdown">
					    <a class="dropbtn">Menu 2</a>
						<div class="dropdown-content tab-content-2">
						   <div class="row">
								<div class="col-md-3 col-sm-3">
									<div class="list-item">
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p> 
									</div>
								   
								</div>
								
								 <div class="col-md-3 col-sm-3">
									<div class="list-item">
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p> 
									</div> 
								</div>
								
								 <div class="col-md-3 col-sm-3">
									<div class="list-item">
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p> 
									</div> 
								</div>
								
								 <div class="col-md-3 col-sm-3">
									 <div class="list-item">
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p> 
									</div>
								</div>
						   </div>
						</div>
					</li>
					<li class="dropdown">
					    <a class="dropbtn">Menu 3</a>
						<div class="dropdown-content tab-content-2">
						   <div class="row">
								<div class="col-md-3 col-sm-3">
									<div class="list-item">
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p> 
									</div>
								   
								</div>
								
								 <div class="col-md-3 col-sm-3">
									<div class="list-item">
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p> 
									</div> 
								</div>
								
								 <div class="col-md-3 col-sm-3">
									<div class="list-item">
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p> 
									</div> 
								</div>
								
								 <div class="col-md-3 col-sm-3">
									 <div class="list-item">
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p>
										<p><a href="">Ut enim ad minim veniam</a></p> 
									</div>
								</div>
						   </div>
						</div>
					</li>
				</ul>

				
                
                      </div>    



            </div>
        </div>
    </div>
</div>
<?php //include("footer.php"); ?>
<?php ob_flush();
 //print_r($_SESSION);
 ?>


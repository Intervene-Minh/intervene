<?php require_once('header.php');?>
<?php
if( isset($_POST['submit']) ) {
	$to = $_POST['submit'];
	$body  = "Intervene contact information: \r\n";
	$body .= "Name: {$_POST['name']} {$_POST['lastname']} \r\n";
	$body .= "Email: {$_POST['email']} \r\n";
	$body .= "School name: {$_POST['subject']} \r\n";
	$body .= "Phone: {$_POST['phone']} \r\n";
	
	$mail = mail( $to, 'Intervene request demo', $body);
	
	if( $mail )
		header("Location: pricing.php?msg=true");
	else
		header("Location: pricing.php?msg=false");
}
?>
	<div class="jumbotron jumbotron-fluid">
		<div class="container">
			<h2 class="display-4">Our Pricing</h2>
			<p class="lead">We currently have three products to support your school or district's intervention.</p>
		</div>
	</div>
<div class="container" style="margin-bottom:20px;">
	<div class="col-sm-4 col-lg-4">
		<div>
			<h4>Demo</h4>
			<span>Free</span>
		</div>
		<ul>
			<li> Access our Printable Database </li>
			<li> Aligned to State Standards </li>
			<li> Access our Student Assessment Portal </li>
			<li> Find Student Misconceptions</li>
			<li> Create a Small Group Action Plan</li>
			<li> Demo Access to 2 grade levels</li>
		</ul>
	<a href="access-demo.php" class="btn btn-primary btn-plan-select">Access Demo</a>

    
        </div>
        <div style="z-index:55;" class="col-sm-4 col-lg-4">
          <div>
            <h4>Student Gap Analysis Tool</h4>
            <font color="red">Contact Us for Pricing - <span class="label label-warning">Sale</span></font></span>
          </div>
          <ul>
        <li> Premium Access to our Printable Database</li>
        <li> Unlimited Downloads </li>
        <li> Aligned to State Standards </li>
        <li> Find Student Misconceptions</li>
        <li> Create a Small Group Action Plan</li>
        <li> Per subject, per grade level pricing</li>
        </ul>
            <a href="https://www.getdrip.com/forms/496556018/submissions/new" class="btn btn-primary btn-plan-select"><i class="icon-white icon-ok"></i>Purchase</a>
          
        </div>
        <div class="col-sm-4 col-lg-4">
          <div>
            <h4>Online Tutoring</h4>
            <span>Contact Us for Pricing</span>
          </div>
          <ul>
        <li> Scheduled Online Tutoring </li>
        <li> Whiteboard, Chat, Audio, or Video </li>
        <li> Data-driven Lessons </li>
        <li> Differentiated Learning Plans</li>
        <li> Detailed Feedback from Tutor</li>
        <li> Historical Student File Creation</li>
              </ul>
		</div>
<?php require_once('footer.php');?>
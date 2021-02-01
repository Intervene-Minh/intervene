<div id="Questions" class="widget clear fullwith">
     <h4 class="widget-title"><i class="fa fa-question-circle" aria-hidden="true"></i>

	<a href="#" class="menu_heading">Questions</a></h4>


</div>

<div  class="Questions_widget widget-content">
	<p class="add_new" >
		<i class="fa fa-plus-circle"></i>
		<a href="single-question.php?action=new">Add New</a>
	</p>
	<p class="list"><i class="fa fa-th-list"></i><a href="questions.php">Last Questions</a></p>
        <p class="list"><i class="fa fa-plus-circle"></i><a href="bulk-upload.php">Bulk Upload Questions</a></p>
        <p class="list"><i class="fa fa-plus-circle"></i><a href="export_bulk_questions.php">Export Bulk Questions</a></p>
       
	
</div>

<?php if(isset($_SESSION['ses_subdmin'])&&$_SESSION['ses_subdmin']=='no'){ ?>
<div id="Assesments" class="widget clear fullwith">
     <h4 class="widget-title"><i class="fa fa-question-circle" aria-hidden="true"></i>

	<a href="#" class="menu_heading">Assesments</a></h4>


</div>


<div  class="Assesments_widget widget-content">
	
        <p class="add_new">
		<i class="fa fa-plus-circle"></i>
		<a href="assesment_step1.php">Add Assesment</a>
	</p>
        
         <p class="add_new">
	<i class="fa fa-file-text-o"></i><a href="assesment_step3.php">Arrange</a></p>
        <i class="fa fa-th-list"></i><a href="assesment_list.php">List Assesments</a>
        
	
</div>  <?php } ?>
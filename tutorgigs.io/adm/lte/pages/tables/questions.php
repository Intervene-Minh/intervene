<?php  
	require("config.php");
    if(empty($_SESSION['user']) || (int)$_SESSION['user']['Security'] < 90) 
    {
        header("Location: /adm/index.php");
        die("Redirecting to index.php"); 
    }
	
		// db connection
		include 'dbtutor.ini';
		
		// Connect to tutor database
		$connection = mysqli_connect($tServer, $tUser, $tPass, $tDB) or 
			die('Connection Error: ' . mysqli_connect_error() . ' User:'. $tUser);
		mysqli_select_db($connection, "$tDB");
		
		// Tally up totals
		//$result = mysqli_query($connection, "SELECT * FROM questions WHERE ID=".$_POST['testID']);
		//$totalTests = $result->num_rows;
		//$result = mysqli_query($connection, "SELECT * FROM tests WHERE IsActive=1");
		//$totalActive = $result->num_rows;
		//$result = mysqli_query($connection, "SELECT * FROM tests WHERE IsActive=0");
		//$totalNotActive = $result->num_rows;
		if ( empty($_GET['testID']) ) { 
			$_GET['testID'] = $_SESSION['qTestID'];	
		}
		
		$questionList = mysqli_query($connection, "SELECT * from questions WHERE TestID=".$_GET['testID']);
		$totalQuestions = $questionList->num_rows;
		
		$questionTypes = mysqli_query($connection, "SELECT * FROM questionTypes");
		
				
		
		$result = mysqli_query($connection, "SELECT Name FROM tests WHERE ID=".$_GET['testID']." LIMIT 1");
		$testRow = mysqli_fetch_array($result);
		$testName = $testRow['Name'];
		$testID =  $_GET['testID'];
		
		$answerLetter = array("A", "B", "C", "D", "E");
?> 


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Questions | P2G Admin</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        
        <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
        <!-- bootstrap 3.0.2 -->
        <link href="../../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="../../css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="../../css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- DATA TABLES -->
        <link href="../../css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="../../css/AdminLTE.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        <style>
		.radio-grid li {
			display: block;
			float: left;
			width: 50%;
		}	
		</style>
    </head>
    <body class="skin-black">
        <!-- header logo: style can be found in header.less -->
        <header class="header">
            <a href="../../index.php" class="logo">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                <img src="/img/logo80x120.png" width="40" height="60"> Admin
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-right">
                    <ul class="nav navbar-nav">
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-user"></i>
                                <span><?php echo htmlentities($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8'); ?> <i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header bg-light-blue">
                                   
                                </li>
                                <!-- Menu Body -->
                                <li class="user-body">
                                    
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    
                                    <div class="pull-right">
                                        <a href="/adm/logout.php" class="btn btn-default btn-flat">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="left-side sidebar-offcanvas">                
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <div class="user-panel">
                        
                    </div>
                    
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <? include("menu.htm"); ?>
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">                
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Questions
                        <small>management</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="#">Test Engine</a></li>
                        <li class="active">Questions</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header">                                    
                                    <!-- Rep Report -->
                                    <ul class="nav navbar-nav">
                                        
                                        <li class="dropdown messages-menu">                                                                                      
                                        </li><!-- /.rep report -->                                       
                                    </ul>  
                                    
                                    
                                </div><!-- /.box-header --> 
                                
                                <div class="navbar-form">
                                	<div class="btn-toolbar" role="toolbar"> 
                                    <div class="btn-group"> 
                                        <button class="btn btn-default navbar-btn" onClick="window.location='tests.php'">
                                                <span class="glyphicon glyphicon-chevron-left" ></span> Back to Tests</button>
                                    </div>                          
                                    <div class="btn-group">                                    	
                                        <button class="btn btn-primary navbar-btn" data-toggle="modal" data-target="#myModal">
                                            <span class="glyphicon glyphicon-plus-sign" ></span> Add New Question</button>
                                        <!-- <button class="btn btn-danger navbar-btn" onClick="#">
                                            <span class="glyphicon glyphicon-trash" ></span> Remove a Question</button>-->
                                    </div>
                                    <div class="btn-group"> 
                                        <button class="btn btn-warning navbar-btn" onClick="#">
                                            <span class="glyphicon glyphicon-pencil" ></span> View Tutors Tested</button>
                                    </div>
                                    </div>
                                </div>
                                
                                <div id="msgBox" style="display:none">
                                        <h4>I am an info callout!</h4>
                                        <p>Follow the steps to continue</p>
                                </div>
                                                                
                                <div class="box box-solid">
                                <div class="box-header">
                                    <h3 class="box-title"><strong><? echo $testName ?></strong> Questions 
                                                <span class="label label-success"><? echo $totalQuestions ?></span></h3>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                    <div class="box-group" id="accordion">
                                        <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                                        <?php	
												$qCount = 1;
                                                while ( $qRecord = mysqli_fetch_array($questionList) ) {
													// row color
                                                    $rowColor = (string)$qRecord['IsActive'] == 1 ? 'success' : 'danger';													
                                        ?>
                                        <div class="panel box box-<? echo $rowColor?>">
                                            <div class="box-header">
                                                <h3 class="box-title" id="t<?php echo $qRecord['ID'] ?>">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $qRecord['ID'] ?>">
                                                       <strong>Question <?php echo $qCount++ ?>.</strong>                                                       
                                                       <br/>
                                                       <blockquote id="theQ<?php echo $qRecord['ID'] ?>"><?php echo $qRecord['Question'] ?></blockquote>
                                                    </a>                                                    
                                                </h3>
                                                  <button class="btn btn-danger pull-right" onclick="deleteQuestion(<? echo $qRecord['ID'] ?>);">Delete Question</button>
                                                	<button 
                                                    	id='newFeature' 
                                                        data-toggle="tooltip" 
                                                    	data-placement='left' 
                                                    	data-content='The "Edit Question" button is now working!' 
                                                    	data-original-title='P2G Admin'
                                                        data-trigger"hover"
                                                    	type="button" 
                                                    	class="btn btn-default pull-right" 
                                                        onClick="qePopulate( <? echo $qRecord['ID'] ?>);"
                                                        title="Edit this Question"
                                                    >
                                                        <span class="glyphicon glyphicon-edit"></span> 
                                                        Edit Question
                                                    </button>
                                                  
                                            </div>
                                            <div id="collapse<?php echo $qRecord['ID'] ?>" class="panel-collapse collapse">
                                                <div class="box-body">
                                                	<table class="table table-responsive table-hover">
                                                    <tbody>
                                                    
                                                    <?php 
														$options = explode(",", $qRecord['OptionIDs']);
														$correctLetter = '';
														$correctAnswer = '';
														
														// Looop through Options
														for ($j=0; $j < count($options); $j++ ) {
															$query = "SELECT * FROM answers WHERE ID=".$options[$j];
															$result = mysqli_query($connection, $query);
															
															$qOption = mysqli_fetch_array($result);
															// Find Correct Answeer
															if ($options[$j] == $qRecord['AnswerID']) {
																$correctLetter = $answerLetter[$j];
																$correctAnswer = $qOption['Answer'];
															} 
															// Begin table row of Answer Options/Choices
															echo '<tr style="text-align:bottom;">';
															// Radio Button
															echo '<td>';
															// Option Letter/Number
																echo ' <strong>'.$answerLetter[$j].'.</strong>&nbsp;&nbsp;';															
																echo '<input type="radio" name="answer'.$qCount.'" value="'.$options[$j].'"> ';																
															echo '</td>';	
															// The Answer Choice														
															echo '<td>';
																echo '<span id="theO'.$qOption['ID'].'">'. $qOption['Answer'] . '</span>';
																echo '
																<button 
																	id="newFeature" 
																	data-toggle="tooltip" 
																	data-placement="left"
																	data-content="Edit this Answer option" 
																	data-original-title="P2G Admin"
																	data-trigger"hover"
																	type="button" 
																	class="btn btn-default pull-right" 
																	onClick="oePopulate( ' . $qOption['ID'] . ');"
																	title="Edit Answer Optionxx"
																>
																<span class="glyphicon glyphicon-edit"></span> 																	
																</button>
																';
															echo '</td>';															
															echo '</tr>';															
														}
														
														// Print Answer - after loop through options
														echo '<tr><td colspan="2">';
															echo '<h4>The Correct Answer is '.$correctLetter.'. '.$correctAnswer.'</h4>';																
														echo '</td</tr>';															
																											
													?>
                                                    
                                                    </tbody>
                                                    </table>                                                    
                                                    <br/>                                                    
                                                </div>
                                            </div>
                                        </div> 
                                        <?php } ?>                                                                           
                                    </div>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                            </div><!-- /.box -->
                        </div>
                    </div>

                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->

		<!-- Button trigger modal -->
<!--        <button class="btn btn-primary btn-lg center-block" data-toggle="modal" data-target="#myModal">pop test</button> -->
        <!-- Add Question Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria- labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                         <h4 class="modal-title" id="myModalLabel">Add Question for <strong><? echo $testName; ?></strong></h4>

                    </div>
                    <div class="modal-body">
                    
                    	<div class="container" style="max-width:500px">
                              <form id="qForm" name="qForm" role="form" action="addQuestion.php" method="post">
                               <script type="text/javascript">
							   function checkOptions() {
								   //alert( $("#tSubjectID").val() > 0 );
									if ( $("#qNumOptions").val() > 0) {
										$("#qSubmit").removeAttr("disabled"); 
										
										// Iterate Options inputs
										var optionInputs = '';
										
										for (j = 0; j < $("#qNumOptions").val(); j++) {
											optionInputs+= '<label class="control-label" for="qOption'+(j+1)+'">Option '+ (j+1) + '</label>';
											optionInputs+= '<div class="controls">';
											//optionInputs+= '<input id="qOption'+(j+1)+'" name="qOption'+(j+1)+'" type="text" ';
											optionInputs+= '<textarea id="qOption'+(j+1)+'" name="qOption'+(j+1)+'" type="text" ';											
											//optionInputs+=	'placeholder="" ';
											//optionInputs+=	'class="form-control" required>';
											optionInputs+=	'class="form-control" required> </textarea>';															
											optionInputs+= '</div><br/>';
										}
										
										// Populate div with inputs
										$("#qOptionList").html( optionInputs ) ;
										$("#qOptionList").show();
										
										// CKEditor replace those inputs
										for (j = 0; j < $("#qNumOptions").val(); j++) {											
											CKEDITOR.replace('qOption' + (j+1),{} );
											//CKEDITOR.add;		
										}
										
										// Iterate Correct Answer list
										var correctAnswer = '';
											correctAnswer+='<label class="control-label" for="qCorrectAnswer">Correct Answer</label>';
											correctAnswer+= '<select class="form-control" id="qCorrectAnswer" name="qCorrectAnswer" onBlur="alert( $("#qCorrectAnswer").val() )">';
										for (k = 0; k < $("#qNumOptions").val(); k++) {
											correctAnswer+= '<option value="'+(k+1)+'">Option'+(k+1)+'</option>';	
										}
											correctAnswer+= '</select>';
											
										$("#qAnswer").html( correctAnswer ) ;
										$("#qAnswer").show();
										
										
									} else {
										$("#qSubmit").attr("disabled", "disabled");
										$("#qOptionList").hide();
										$("#qOptionList").html( '' ) ;
										
										$("#qAnswer").hide();
										$("#qAnswer").html( '' ) ;
									}
								}
								function checkType() {
									// if multiple choice									
									if ( $("#qQuestionType").val() == '1' ) {
										$("#qNumOptionsGroup").show();
										$("#qNumOptionsGroup").val(0);
										checkOptions();
										$("#qOptionList").show();										
										$("#qSubmit").attr("disabled", "disabled");
									}  
									// true or false
									if ( $("#qQuestionType").val() == '2' ) {
										//alert($("#qTestType").val());
										$("#qNumOptionsGroup").hide();
										$("#qOptionList").hide();
										$("#qOptionList").html( '' ) ;
										$("#qAnswer").hide();
										$("#qAnswer").html( '' ) ;
										$("#qSubmit").removeAttr("disabled");
										
										// Iterate Correct Answer list
										var correctAnswer = '';
											correctAnswer+='<label class="control-label" for="qCorrectAnswer">Correct Answer</label>';
											correctAnswer+= '<select class="form-control" id="qCorrectAnswer" name="qCorrectAnswer">';										
											correctAnswer+= '<option value="1">True</option>';	
											correctAnswer+= '<option value="2">False</option>';										
											correctAnswer+= '</select>';
											
										$("#qAnswer").html( correctAnswer ) ;
										$("#qAnswer").show();
									}  
									// yes or no
									if ( $("#qQuestionType").val() == '3' ) {
										//alert($("#qTestType").val());
										$("#qNumOptionsGroup").hide();
										$("#qOptionList").hide();
										$("#qOptionList").html( '' ) ;
										$("#qAnswer").hide();
										$("#qAnswer").html( '' ) ;
										$("#qSubmit").removeAttr("disabled");
										
										// Iterate Correct Answer list
										var correctAnswer = '';
											correctAnswer+='<label class="control-label" for="qCorrectAnswer">Correct Answer</label>';
											correctAnswer+= '<select class="form-control" id="qCorrectAnswer" name="qCorrectAnswer">';										
											correctAnswer+= '<option value="1">Yes</option>';	
											correctAnswer+= '<option value="2">No</option>';										
											correctAnswer+= '</select>';
											
										$("#qAnswer").html( correctAnswer ) ;
										$("#qAnswer").show();
									}
									
								}
							   </script>
                                <!-- Text input-->
                                <div class="control-group">
                                  <label class="control-label" for="qQuestion">Question</label>
                                  <div class="controls">
                                 	 <textarea id="qQuestion" name="qQuestion" rows="10" cols="80">
                                            This is my Question to be replaced with CKEditor.
                                     </textarea>
                                    <!--<input id="qQuestion" name="qQuestion" type="text" placeholder="Enter the Question here" class="form-control" required>-->
                                  </div>
                                </div>
                                <br/>    
                                 <!-- Multiple Radios -->
                                <div class="control-group">
                                  <label class="control-label" for="qQuestionType">Question Type</label>
                                  <div class="controls">
                                  <select class="form-control" id="qQuestionType" name="qQuestionType" onBlur="checkType()" onChange="checkType()">
                                  	<?php while ($type = mysqli_fetch_array($questionTypes)) { ?>
                                    <option value="<? echo $type['ID']?>"><? echo $type['Name'] ?></option>                                     		
									<?php } ?>     
                                    </select>                                    
                                  </div>                                                                 
                                </div>  
                                <br/>
                                <!-- Text input-->
                                <div id="qNumOptionsGroup" class="control-group">
                                  <label class="control-label" for="qNumOptions">Number of Choices/Options</label>
                                  <div class="controls">
                                    <select class="form-control" id="qNumOptions" name="qNumOptions" autofocus onBlur="checkOptions()" onChange="checkOptions()">
                                        <option value="0" selected>[Select the Number of Options]</option>
                                        <option value="1">1 Option</option>
                                        <option value="2">2 Options</option>
                                        <option value="3">3 Options</option>
                                        <option value="4">4 Options</option>
                                        <option value="5">5 Options</option>
                                     </select>                                    
                                  </div>
                                 
                                </div>
                                <br/>
                                <div id="qOptionList" class="control-group">
                                </div>
                                <br/>
                                <div id="qAnswer" class="control-group">
                                </div>
                                <br/>       
                                <!-- Multiple Radios -->
                                <div class="control-group">
                                  <label class="control-label" for="qIsActive">Shown in Test</label>
                                  
                                    <label class="radio" for="qIsActive-0">
                                      <input type="radio" name="qIsActive" id="qIsActive-0" value="1" checked="checked">
                                      Active
                                    </label>
                                    <label class="radio" for="qIsActive-1">
                                      <input type="radio" name="qIsActive" id="qIsActive-1" value="0">
                                      Not Active
                                    </label>                                  
                                </div>
                                <input type="hidden" id="qCreator" name="qCreator" value="<?php echo $_SESSION['user']['id']; ?>">
                                <input type="hidden" id="qTestID" name="qTestID" value="<?php echo $testID; ?>">
                                <!-- Button (Double) -->
                                <div class="control-group">
                                  <label class="control-label" for="qSubmit"></label>
                                  <div class="controls">
                                    <button id="qSubmit" name="qSubmit" class="btn btn-success" disabled="disabled">Submit</button>
                                    <button id="qCancel" name="qCancel" class="btn btn-warning pull-right" type="reset">Reset</button>
                                  </div>
                                </div>
                                
                            </form>
                       </div>
                       
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        
         <!-- Edit Question Modal -->
        <div class="modal fade" id="myEditQuestionModal" tabindex="-1" role="dialog" aria- labelledby="myEditQuestionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                         <h4 class="modal-title" id="myEditQuestionModalLabel">Edit Question for <strong><? echo $testName; ?></strong></h4>

                    </div>
                    <div class="modal-body">
                    
                    	<div class="container" style="max-width:500px">
                              <form id="qeForm" name="qeForm" role="form" action="editQuestion.php" method="post">
                               <script type="text/javascript">
							   
							   </script>
                                <!-- Text input-->
                                <div class="control-group">
                                  <label class="control-label" for="qeQuestion">Question</label>
                                  <div class="controls">
                                 	 <textarea class="qeEditor" id="qeQuestion" name="qeQuestion" rows="10" style="width:80%"></textarea>
                                    <!--<input id="qQuestion" name="qQuestion" type="text" placeholder="Enter the Question here" class="form-control" required>-->
                                  </div>
                                </div>
                                <br/>    
                                
                                <input type="hidden" id="qeCreator" name="qeCreator" value="<?php echo $_SESSION['user']['id']; ?>">
                                <input type="hidden" id="qeTestID" name="qeTestID" value="<?php echo $testID; ?>">
                                 <input type="hidden" id="qeID" name="qeID" value="">
                                <!-- Button (Double) -->
                                <div class="control-group">
                                  <label class="control-label" for="qeSubmit"></label>
                                  <div class="controls">
                                    <button id="qeSubmit" name="qeSubmit" class="btn btn-success">Submit</button>
                                    <button id="qeCancel" name="qeCancel" class="btn btn-warning pull-right" type="reset">Reset</button>
                                  </div>
                                </div>
                                
                            </form>
                       </div>
                       
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        
        <!-- Edit Option Answer Modal -->
        <div class="modal fade" id="myEditOptionModal" tabindex="-1" role="dialog" aria- labelledby="myEditOptionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                         <h4 class="modal-title" id="myEditOptionModalLabel">Edit Answer Option for <strong><? echo $testName; ?></strong></h4>

                    </div>
                    <div class="modal-body">
                    
                    	<div class="container" style="max-width:500px">
                              <form id="oeForm" name="oeForm" role="form" action="editOption.php" method="post">
                               <script type="text/javascript">
							   
							   </script>
                                <!-- Text input-->
                                <div class="control-group">
                                  <label class="control-label" for="oeOption">Option</label>
                                  <div class="controls">
                                 	 <textarea class="oeOption" id="oeOption" name="oeOption" rows="10" style="width:80%"></textarea>
                                    <!--<input id="qQuestion" name="qQuestion" type="text" placeholder="Enter the Question here" class="form-control" required>-->
                                  </div>
                                </div>
                                <br/>    
                                
                                <input type="hidden" id="oeCreator" name="oeCreator" value="<?php echo $_SESSION['user']['id']; ?>">
                                <input type="hidden" id="oeTestID" name="oeTestID" value="<?php echo $testID; ?>">
                                 <input type="hidden" id="oeID" name="oeID" value="">
                                <!-- Button (Double) -->
                                <div class="control-group">
                                  <label class="control-label" for="oeSubmit"></label>
                                  <div class="controls">
                                    <button id="oeSubmit" name="oeSubmit" class="btn btn-success">Submit</button>
                                    <button id="oeCancel" name="oeCancel" class="btn btn-warning pull-right" type="reset">Reset</button>
                                  </div>
                                </div>
                                
                            </form>
                       </div>
                       
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->


        <!-- jQuery 2.0.2 
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>-->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
        
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
        <!-- Bootstrap -->
        <script src="../../js/bootstrap.min.js" type="text/javascript"></script>
        <!-- DATA TABES SCRIPT 
        <script src="../../js/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
        <script src="../../js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>-->
        <!-- AdminLTE App -->
        <script src="../../js/AdminLTE/app.js" type="text/javascript"></script>
		<script src="../../js/dialog-patch.js"></script>
        <!-- CK Editor -->
        <script src="../../js/plugins/ckeditor/ckeditor.js" type="text/javascript"></script>
        <script type="text/javascript" src="../../js/plugins/ckeditor/adapters/jquery.js"></script>
        
		<script type="text/javascript">
        $(document).on('focusin', function(e) {
			if ($(event.target).closest("#myModal").length) {
				e.stopImmediatePropagation();
			}
		});
		
		$(function() {
                // Replace the <textarea id="editor1"> with a CKEditor
                // instance, using default configuration.
                CKEDITOR.replace('qQuestion');
				CKEDITOR.replace('qeQuestion');
				CKEDITOR.replace('oeOption');
                
				//bootstrap WYSIHTML5 - text editor
                //$(".textarea").wysihtml5();
            });
		
        function deleteQuestion ( questionID ) {
            window.location = "delQuestion.php?tID=<?php echo $_REQUEST['testID']?>&qID=" + questionID ;
        }
        
        function toggleQuestionActive( questionID ) {
            window.location = "adminfQuestions.php?action=toggleQuestion&questionID=" + questionID ;
        }
		
		$.widget( "ui.dialog", $.ui.dialog, {
		 /*! jQuery UI - v1.10.2 - 2013-12-12
		  *  http://bugs.jqueryui.com/ticket/9087#comment:27 - bugfix
		  *  http://bugs.jqueryui.com/ticket/4727#comment:23 - bugfix
		  *  allowInteraction fix to accommodate windowed editors
		  */
		  _allowInteraction: function( event ) {
			if ( this._super( event ) ) {
			  return true;
			}
		
			// address interaction issues with general iframes with the dialog
			if ( event.target.ownerDocument != this.document[ 0 ] ) {
			  return true;
			}
		
			// address interaction issues with dialog window
			if ( $( event.target ).closest( ".cke_dialog" ).length ) {
			  return true;
			}
		
			// address interaction issues with iframe based drop downs in IE
			if ( $( event.target ).closest( ".cke" ).length ) {
			  return true;
			}
		  },
		 /*! jQuery UI - v1.10.2 - 2013-10-28
		  *  http://dev.ckeditor.com/ticket/10269 - bugfix
		  *  moveToTop fix to accommodate windowed editors
		  */
		  _moveToTop: function ( event, silent ) {
			if ( !event || !this.options.modal ) {
			  this._super( event, silent );
			}
		  }
		});	
			
		$('#myEditQuestionModal').on('shown.bs.modal', function () {
			//alert('hi');
			
		});
		
		// Question Edit populate form
		function qePopulate( qid) {	
			//alert($('#theQ'+qid).html());	
			
			CKEDITOR.instances.qeQuestion.setData($('#theQ'+qid).html());
			$('#qeID').val(qid);
			
			$('#myEditQuestionModal').modal('toggle');						
		}


		// Option/Answer Edit populate form
		function oePopulate( aid) {	
            console.log('Test:='+aid);
			//alert($('#theQ'+aid).html());	
			CKEDITOR.instances.oeOption.focus();
			CKEDITOR.instances.oeOption.setData($('#theO'+aid).html());
			$('#oeID').val(aid);
			
			$('#myEditOptionModal').modal('toggle');						
		}
		
		
        </script>
        

    </body>
</html>
<?php
    ///////////////////////
    //include("student_header.php");
    /*if (!$_SESSION['student_id']) {
        header('Location: login.php');
        exit;
    }*/
    /////////////////////
    include("student_inc.php");
    include("ses_live_inc.php");# Tutor sesion Live 
    $page_name='MicSoundInet Test';
?>
<!doctype html>
<html ng-app="acousticLevel">
    <head>
        <meta charset="utf-8" /> 
        <title>Intervene Audio Test</title>
        <script src="msitest/angular.min.js"></script>
        <script src="msitest/utils.js"></script>
        <script src="msitest/app.js"></script>
        <script src="msitest/volume-analyser.js"></script>
        <script src="msitest/volume-meter/volume-meter.js"></script>
        <script src="msitest/levelController.js"></script>
        <link rel="stylesheet" type="text/css" href="msitest/style.css"/>
        <link href='https://fonts.googleapis.com/css?family=Cousine:700' rel='stylesheet' type='text/css'> 
    </head>
    <div id="home main" class="clear fullwidth tab-pane fade in active">
        <div class="container">
            <div>
                <button id="btnStart">Click to begin</button>
            </div>
            <div class="page" ng-controller="levelController">
                <div id="overlay" ng-style="{height:(100*(1-level))+'%'}" >
                    Microphone level test
                </div>                
                <div id="sensitivity" ng-style="{opacity: opacity, transition: 'opacity '+(1-opacity)+'s'}">
                    Adjust microphone sensitivity using the up and down arrows or vertical swipe (left to reset) 
                    <br/><br/> 
                    Current sensitivity: {{sensitivity}} 
                </div>
        
                <div id="base">
                    <div id="number">
                        {{logLevel}}
                    </div>	
                </div>
            </div>
        </div>        
    </div>
</html>
<?php //include("footer.php"); ?>
<?php ob_flush();
 //print_r($_SESSION);
 ?>


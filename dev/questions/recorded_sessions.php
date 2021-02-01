<?php
    require_once("student_header.php");
    if (!$_SESSION['student_id']) {
        header('Location: login.php');        
    }
    require_once("student_inc.php");
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<div id="home main" class="clear fullwidth tab-pane fade in active">
    <div class="container">      
        <div class="row">
            <div class="align-center col-md-12" style="margin-top:10px; margin-bottom:20px;">
                <div style=" width:auto;" title="">
                <?php require_once("nav_students_2.tpl.php"); ?>
                </div>
            </div>            
        </div>
        <div class="row">
            <div class="align-center col-md-12">
                <div class="hero-unit">
                    <h1>Intervene Recorded Tutoring Sessions</h1>
                    <p>
                        <a class="btn-large">
                            Did you miss a tutoring session?  No worries, we have it recorded below.   
                        </a>
                    </p>
                </div>
                <div class="container">
                    <div id="accordion">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    8th Grade Math
                                    </button>
                                </h5>
                            </div>

                            <div id="collapseOne" class="collapse in show" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="card-body">
                                    <a href="https://vimeo.com/414851830/4b89799f98" target="_blank">Surface Area - Lesson 1</a><br>
                                    <a href="https://vimeo.com/414851387/3fe8e741cc" target="_blank">Surface Area - Lesson 2</a><br>
                                    <a href="https://vimeo.com/414852351/de018ecd6d" target="_blank">Volume of a Cylinder - Lesson 1</a><br>
                                    <a href="https://vimeo.com/414850913/97933edb8e" target="_blank">Pythagorean Theorem - Lesson 1</a><br>
                                    <a href="https://vimeo.com/414852758/e719145ac6" target="_blank">STAAR Review - 1</a><br>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Algebra 1
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                <div class="card-body">
                                    <a href="https://vimeo.com/414859094/58eadc2b63" target="_blank">Rates - Lesson 1</a><br>
                                    <a href="https://vimeo.com/414859570/5c317187ed" target="_blank">Rates - Lesson 2</a><br>
                                    <a href="https://vimeo.com/414860224/9c07ce93f3" target="_blank">Linear Equations - Lesson 1</a><br>
                                    <a href="https://vimeo.com/414860899/646f16c313" target="_blank">Quadratic Equations - Lesson 1</a><br>
                                    <a href="https://vimeo.com/414861430/3079634c6b" target="_blank">Quadratic Equations - Lesson 2</a><br>
                                    <a href="https://vimeo.com/414861956/37d2501834" target="_blank">Quadratic Equations - Lesson 3</a><br>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingThree">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    7th Grade Reading
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                                <div class="card-body">
                                    <a href="https://vimeo.com/414868001/6161ec7077" target="_blank">Inferencing - Lesson 1</a><br>
                                    <a href="https://vimeo.com/414868211/f16a4f55ea" target="_blank">Paraphrasing - Lesson 1</a><br>
                                    <a href="https://vimeo.com/414868718/81ac9b98f5" target="_blank">Plot Elements - Lesson 1</a><br>
                                
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingFour">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    English 1
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                                <div class="card-body">                                
                                    <a href="https://vimeo.com/410906121/711f56f93a" target="_blank">Context Clues - Lesson 1</a>                                 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready((e) => {
        $('.active').toggleClass('active');
    })
</script>

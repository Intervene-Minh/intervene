<?php
/**
 * @author GallerySoft.info
 * @copyright 2016
 */

include("header.php");
?>

<div id="main" class="clear fullwidth p-full-width">
            <section id="p-sec-1" class="p-sec p-sec-1">
            	<div class="container">
            		<div class="row">
                        <div class="p-text-sec">
                            <h3>Free STAAR Math Questions</h3>
                            <p>Just choose the questions you <span class="glyphicon glyphicon-heart" style="color:red" aria-hidden="true"></span>
                            <p>and press print</p>
                        </div>
                        <a href="#" class="btn-slide-down" id="slide-down">bg</a>
            		</div>
            	</div>
            </section><!-- end. p-sec-1 -->
            
			<div class="clearnone">&nbsp;</div>
            <section id="p-sec-2" class="p-sec p-sec-2">
			   <div class="container">
                             <div class="row">
                                <div class="p-text-sec">
                                   <h3>Why should I use this site?</h3>
                                </div>
                                   <div class="col-sm-4">
                      
                                
                                   <img class="displayed" alt="...">
                                   <div class="caption" style="text-align:center">
				      <h3>It's Free</h3>
				     <p>Schools pay an average of 100 dollars per student for test prep in Texas.  Why spend so much just for questions and answers?  Try our site, and tell your friends.  It's free!</p>
				     <p><a href="#" class="btn btn-primary" role="button">Learn More</a> 
                                  </div>
                                  </div>
                           
                         <div class="col-sm-4">
                      
                                
                                   <img class="displayed" alt="...">
                                   <div class="caption" style="text-align:center">
				      <h3>Aligned Questions</h3>
				     <p>Texas is one of the few states that has not adopted the Common Core curriculum.  Our state standards are very specific, and we do our best to align our questions directly to what kids need to know.</p>
				     <p><a href="#" class="btn btn-primary" text-align:"center" role="button">TEKS We Cover</a> </p>
                                  </div>
                                  </div>
                         <div class="col-sm-4">
                      
                                
                                   <img class="displayed" alt="...">
                                   <div class="caption" style="text-align:center">
				      <h3>A Better Analysis</h3>
				     <p>We have a new feature planned that will change the way you teach your students.  No more over-testing.  We will give you specific ideas to help you plan for tomorrow.</p>
				     <p><a href="#" class="btn btn-primary" role="button">Extra Resources</a> </p>
                                  </div>
                                  </div>
                            
                        </div>
                    </div>
             
            </section><!-- end. p-sec-2 -->
		<div class="clearnone">&nbsp;</div>
            <section id="p-sec-3" class="p-sec p-sec-3">
                <div class="container">
                    <div class="row">
                        <div class="p-text-sec">
                            <h3>Our assessment questions are free.</h3>
                            <p>Is it hard to find aligned questions?</p>
                            <p>Are you running out of test prep items?</p>
                            <p>Do you love free resources?</p>
                        </div>
                        <p class="sec-sign-up"><a href="questions/signup.php">Sign up now!</a></p>
                    </div>
                </div>
            </section><!-- end. p-sec-3 -->	
            <section id="p-sec-4" class="p-sec p-sec-4">
                <div class="container">
                    <div class="row">
                        <div class="p-text-sec">
                            <h3>Testimonials By User</h3>
                        </div>
                        <div class="p-slider p-slider-desktop">
                            <ul class="bxslider"> 
                            <?php for($i=1; $i<=3 ; $i++) {?>
                                <li>
                                    <div class="col-slide col-silde-1">
                                        <div class="slider-text">
                                            <span class="top-style"></span>
                                            <p>Great job! I love how it looks like STAAR, and I appreciate how you broke each problem down by readiness or supporting student expectation and the answer. Well done!</p>
                                            <span class="bottom-style"></span>
                                        </div>
                                        <div class="writer">
                                            <p class="ava-writer"><img src="questions/images/ava-df.png" /></p>
                                            <div class="info">
                                                <p>Julie W.</p>
                                                <span>TX, USA</span>
                                            </div>
                                            <small></small>
                                        </div>
                                    </div>
                                    <div class="col-slide col-silde-2">
                                        <div class="slider-text">
                                            <span class="top-style"></span>
                                            <p>This site is so easy to use! I printed an assessment right before my tutoring session.  It was very convenient.</p>
                                            <span class="bottom-style"></span>
                                        </div>
                                        <div class="writer">
                                            <p class="ava-writer"><img src="questions/images/ava-df.png" /></p>
                                            <div class="info">
                                                <p>C. Krenz</p>
                                                <span>Houston, TX</span>
                                            </div>
                                            <small></small>
                                        </div>
                                    </div>
                                </li>
                            <?php } ?>
                            </ul>
                        </div><!-- end p-slider desktop-->
                        
                        
                        <div class="p-slider p-slider-mobile">
                            <ul class="bxslider"> 
                            <?php for($i=1; $i<=6 ; $i++) {?>
                                <li>
                                    <div class="col-slide col-silde-1">
                                        <div class="slider-text">
                                            <span class="top-style"></span>
                                            <p>Thank you so much for this product. This was exactly what I needed. I loved that this resembled the questions on the STAAR</p>
                                            <span class="bottom-style"></span>
                                        </div>
                                        <div class="writer">
                                            <p class="ava-writer"><img src="questions/images/ava-df.png" /></p>
                                            <div class="info">
                                                <p>Ms. Gab</p>
                                                <span>Texas, USA</span>
                                            </div>
                                            <small></small>
                                        </div>
                                    </div>
                                </li>
                            <?php } ?>
                            </ul>
                        </div><!-- end p-slider mobile-->
                    </div>
                </div>
            </section><!-- end. p-sec-3 -->
</div>		<!-- /#header -->
<?php include("footer.php"); ?>
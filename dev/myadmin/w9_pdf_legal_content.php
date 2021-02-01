<?php
include('inc/connection.php'); 
include('inc/public_inc.php'); 

 if (isset($_GET['tid'])) {
    	$getid=$_GET['tid'];
    }

 $tdata=$tutor_det=mysql_fetch_assoc(mysql_query("SELECT * FROM `gig_teachers` WHERE id=".$getid));

  $get_state_arr=unserialize($tutor_det['signup_state']);
   //  print_r($get_state_arr);

  //////Profile data////


 $data2=mysql_fetch_assoc(mysql_query("SELECT * FROM `tutor_profiles` WHERE tutorid=".$getid));

$edit=unserialize($data2['profile_1']);
 $tutor_full_name=ucwords($tutor_det['f_name']).' '.$tutor_det['lname'];
  $form_3_arr=unserialize($data2['legal_form3_data']);
?>
<html>
    <head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
</head>
    <style>
									    .page-container{
											width:100%;
											display:inline-block;
											border:1px solid #fbfbfb;
											padding:20px;
											margin:0px 0px 20px;
										}
										.legal-text-box{
											display:inline-block;
											width:100%;
											height:400px;
											overflow:auto;
											padding:20px;
										}
										.page-number{
											border-bottom:1px solid #ccc;
											margin-bottom:20px;
										}
										.row {
											margin-right: 0px;
											margin-left: 0px;
										}
										h3{
											margin-top:0px;
										}
										table{border: 1px solid #ddd;}
										.requester-left{
											border-right:1px solid #000;
											width:100%;
											display:inline-block;
											padding:0px 20px;
											min-height: 117px;
										}
										.requester-left span{
											font-size:40px;
											font-weight:700;
										}
										.requester-right{
											border-left:1px solid #000;
											width:100%;
											display:inline-block;
											padding:0px 20px;
											min-height: 117px;
										}
										.tax-form{
											border-top: 1px solid #000;
											width:100%;
											display:inline-block;
										}
										
										.tax-form-form{
											width:100%;
											border-left:1px solid #000;
											display:inline-block;
											padding:20px 0px 0px;
										}
										.form-control-2{
											width:100%;
											background-color:#fff;
											border-bottom:1px solid #000 !important;
											box-shadow:none;
											color:#000;
											border:none;
											padding:0px 10px;
										}
										.form-inline{
											margin-top:7px;
										}
										.form-control-3{
											background-color:#fff;
											border-bottom:1px solid #000 !important;
											box-shadow:none;
											color:#000;
											border:none;
											padding:0px 20px;
										}
										.inline-vvv{
											background-color:#fff;
											border-bottom:1px solid #000 !important;
											box-shadow:none;
											color:#000;
											border:none;
											margin-left:6px;
											margin-bottom:10px;
										}
										.checkbox-inline, .radio-inline{
											margin-right:10px;
											font-size: 12px;
											margin-top:2px;
											padding: 0px 0px 0px 30px;
										}
										.checkbox-inline + .checkbox-inline, .radio-inline + .radio-inline{
											margin-left:0px;
											font-size: 12px;
											margin-top:2px
										}
										.tax-right{
											border-left:1px solid #000;
											width:100%;
											display:inline-block;
											padding:0px 0px;
										}
										label {
											padding: 5px 10px;
										}
										.form-group {
											margin-bottom: 0px;
										}
										.stepss{
											width:100%;
											display:inline-block;
											border-bottom:1px solid #000;
											font-size:16px;
											color:#000;
											margin: 0px 0px 10px;
                                            padding: 10px 0px;
										}
										.stepss span{
											background:#000;
											padding:10px;
											color:#fff;
											margin-right:20px;
										}
										.sign-box{
											border-top:1px solid #000;
											border-bottom:1px solid #000;
											width:100%;
											display:inline-block;
											padding:0px 0px;
										}
										.sign-text{
											color:#000;
											border-right:1px solid #000;
											font-size:30px;
										}
										.sms-verification-simple{
											  position: relative;
											  width: 300px;
											  margin: 10px 0; 
											}

											.sms-verification-simple input{
											  border: none;
											  box-shadow: none;
											  outline: none;
											  font-size: 25px;
											  width: 300px;
											  height: 50px;
											  letter-spacing: 15.3px;
											  padding: 0 15px;
											  -webkit-appearance: none;
											  -moz-appearance:    none;
											  appearance:         none;
											  font-weight: 320;
											  background: transparent;
											  color: #000;
											  overflow: hidden;
											  
											}

											.sms-verification-simple input:focus, .sms-verification-simple input:active, .sms-verification-simple input:visited{
											  outline: none!important;
											  border: none!important;
											  box-shadow: none!important;
											  
											}

											.sms-verification-simple span{
											  position: absolute;
											  bottom: 0;
											  left: 10px;
											  width: 23px;
											  height: 2px;
											  background-color: #000;
											  border-radius: 30px;
											}
											.sms-verification-simple span:nth-child(1){
											  left: 0px;
											}
											.sms-verification-simple span:nth-child(2){
											  left: 40px;
											}
											.sms-verification-simple span:nth-child(3){
											  left: 70px;
											}
											.sms-verification-simple span:nth-child(4){
											  left: 100px;
											}
											.sms-verification-simple span:nth-child(5){
											  left: 130px;
											}
											.sms-verification-simple span:nth-child(6){
											  left: 160px;
											}
											.sms-verification-simple span:nth-child(7){
											  left: 190px;
											}
											.sms-verification-simple span:nth-child(8){
											  left: 220px;
											}
											.sms-verification-simple span:nth-child(9){
											  left: 250px;
											}
									</style>
    <body>
<div id="content" class="col-md-12">
			<div class="col-md-4" ><img src="images/logo.png"></div>
            <div class="col-md-4">Tutor Application Details</div>
            
            <br> <br>	
				<div class="page-content" style="background-image: url('images/wizard-v3.jpg')">
					
                                         
                        
                  
                                    
                                  							


									 									<section>
									        <div class="page-container">
												 <h3 class="text-center">Privacy Terms</h3>
															  <a href="http://www.tutorgigs.io/" target="_blank">www.tutorgigs.io</a>
															  <p>Tutorgigs</p>
															  <p>EFFECTIVE: JAN 01, 2019 </p>
															  <p>This Privacy Policy governs the manner in which Intervene, LLC (“Tutorgigs�?) collects,
																uses, maintains and discloses information collected from users (each, a “User�?) of its
																www.tutorgigs.io Site and any derivative or affiliated Sites on which this Privacy Policy is
																posted (collectively, the “Site�?). Please note that this Privacy Policy covers Tutorgigs’
																practices regarding information collected from its Site.</p>
																<h3>The Information Tutorgigs Collects</h3>
																<p> Like most website operators, Tutorgigs collects non-personally-identifying information of
																	the sort that web browsers and servers typically make available, such as the browser
																	type, language preference, referring site, and the date and time of each visitor request.
																	Our purpose in collecting non-personally identifying information is to better understand
																	how our visitors use our Site.</p>
																<p> Additionally, certain visitors to Tutorgigs’ Site choose to interact with us in ways that
																	require us to gather personally-identifying information. The amount and type of
																	information that we gather depends on the nature of the interaction. Those who engage
																	in transactions with us online – by purchasing packages of tutoring hours – are asked to
																	provide additional information, such as the personal and financial information required
																	to process those transactions. Tutorgigs also collects potentially personally-identifying
																	information like Internet Protocol (IP) addresses. We do not use such information to
																	identify our visitors, however, and do not disclose such information, other than under the
																	same circumstances that we use and disclose personally-identifying information, as
																	described below. A User browsing the Site without registering an account or
																	affirmatively providing personally identifiable information to Tutorgigs does so
																	anonymously.</p>
																<p> Tutorgigs may collect personally identifiable information from customers in a variety of
																	ways. Personally identifiable information may include (i) contact data (such as a
																	customer’s name, mailing and e-mail addresses); (ii) financial data (such as a
																	customer’s credit card number); and (iii) demographic data (such as a customer’s zip
																	code, age and income). A User may also provide personally identifiable information
																	about others, such as emergency contact information for notification in the event a
																	situation requires it. If you communicate with Tutorgigs by e-mail; post messages to
																	forums; make telephone or cell phone calls, communicate through electronic devices or
																	other interactive devices; complete online forms, surveys or contest entries; or
																	communicate with Tutorgigs by any means, any information provided in such
																	communication may be collected by Tutorgigs. Tutorgigs may also collect information 
																	about how customers use our Site, for example, by tracking the number of unique views
																	received by the pages of the Site, or the domains from which customers originate.</p>
                                                                <h3>Information Sharing</h3>
																<p>It is Tutorgigs’ policy to respect your privacy regarding any information we may collect
																through the Site, e-mail, phone, or any other communications we have with you. We
																treat the confidential information you share with us, such as students’ academic
																performance and any financial information, with the utmost respect. Tutorgigs will not
																disclose student records with third parties under any circumstance, except as
																specifically set forth in this Privacy Policy or as required by applicable law.</p>
																<p>We may, however, share a student’s information with the tutor(s) who may or will be
																providing tutoring to the student so that they may evaluate the engagement and
																personalize and tailor the tutoring to the student. In each case, we collect such
																information only insofar as is necessary or appropriate to fulfill the purpose of the
																visitor’s interaction with Tutorgigs. We do not disclose personally-identifying information
																other than as described below in the section “How Tutorgigs Uses Information�?. Visitors
																can always refuse to supply personally-identifying information, with the caveat that it
																may prevent them from engaging in certain Site-related activities.</p>
                                                                <h3>Tracking Technologies</h3> 
                                                                <p>Tutorgigs and our analytics and marketing providers use technologies such as cookies,
																beacons, tags, and scripts, to analyze trends, administer the Site, track users’
																movements around the Site, and to gather demographic information about our User
																base as a whole. We may receive reports based on the use of these technologies by
																these companies on an individual and aggregated basis.</p>
																<p>We use cookies for [our shopping cart, to remember users’ settings (e.g. language
																preference), for authentication]. Each User can control the use of cookies at the
																individual browser level. If you reject cookies, you may still use our Site, but your ability
																to use some features or areas of our Site may be limited.</p>
																<p>As is true of most websites, we gather certain information automatically and store it in
																log files. This information may include Internet protocol (IP) addresses, browser type,
																internet service provider (ISP), referring/exit pages, operating system, date/time stamp,
																and/or clickstream data. We may combine this automatically collected log information
																with other information we collect about you. We do this to improve services we offer you
																and to improve marketing, analytics, or site functionality.</p>
																<h3>LSOs</h3>
																<p>Third Parties, with whom we partner to provide certain features on our Site or to display
																advertising based upon your Web browsing activity, use LSOs such as HTML 5 to 
																collect and store information. Various browsers may offer their own management tools
																for removing HTML5 LSOs.</p>
																<h3>How Tutorgigs Uses Information</h3>
																<p>Tutorgigs may use personally identifiable information collected through the Site for the
																specific purposes for which the information was collected; to contact Users regarding
																Tutorgigs' live-learning platform; to communicate to Users regarding products and
																services offered through Tutorgigs, its parent, subsidiaries, and trusted affiliates; and
																otherwise to enhance Users’ experience with Tutorgigs. Out of respect for your privacy,
																you may choose to stop receiving these marketing emails by following the unsubscribe
																instructions included in these emails, accessing the email preferences in your account
																settings page, or you can contact us at <a href="mailto:learn@p2g.org" target="_blank">support@tutorgigs.io</a>. Tutorgigs may also use
																information collected through the Site for research regarding the effectiveness of the
																Site and the business planning, marketing, advertising and sales efforts of Tutorgigs, its
																parent, subsidiaries, and trusted affiliates.</p>
																<p>Tutorgigs may disclose personally identifiable information collected from Users to its
																parent, subsidiaries and other related companies, trusted affiliates, independent
																contractors and business partners who will use the information for the purposes outlined
																above, as necessary to provide the services offered by or through Tutorgigs and to
																provide the Site itself, and for the specific purposes for which the information was
																collected. These companies are not permitted to use your personally identifiable
																information in any other way.</p>
																<p>Tutorgigs may also disclose aggregate, anonymous data based on information collected
																from Users to investors and potential partners. Finally, Tutorgigs may disclose or
																transfer personally identifiable information collected from Users in connection with or in
																contemplation of a sale of its assets or business or a merger, consolidation, or other
																reorganization of its business.</p>
																<h3>Correcting, Updating, Accessing or Removing Personal Information</h3>
																<p>To the extent applicable, if a User’s personally identifiable information changes (such as
																a User’s zip code), or if a User no longer desires to receive non-account specific
																information from Tutorgigs, Tutorgigs will endeavor to provide a way to correct, update,
																delete, and/or remove that User’s previously-provided personal data. This can be done
																by emailing a request to Tutorgigs at support@tutorgigs.io. Additionally, you may
																request access to the personally identifiable information as collected by Tutorgigs by
																sending a request to Tutorgigs as set forth above. We will respond to your request
																within a reasonable timeframe. We will retain your information for as long as your
																account is active or as needed to provide you services. We will retain and use your 
																information as necessary to comply with our legal obligations, resolve disputes, and
																enforce our agreements.</p>
																<h3>Legal Disclaimer</h3>
																<p>Tutorgigs may disclose personally identifiable information at the request of law
																enforcement or governmental agencies or in response to subpoenas, court orders, or
																other legal process, to establish, protect, or exercise Tutorgigs’ legal or other rights or to
																defend against a legal claim or as otherwise required or allowed by law. Tutorgigs may
																disclose personally identifiable information in order to protect the rights, property, or
																safety of a User or any other person. Tutorgigs may disclose personally identifiable
																information to investigate or prevent a violation by User of any contractual or other
																relationship with Tutorgigs or the perpetration of any illegal or harmful activity.</p>
																<h3>Security of Information</h3>
																<p>Tutorgigs discloses potentially personally-identifying and personally-identifying
																information only to those of its employees, contractors, and affiliated organizations that
																(i) need to know that information in order to process transactions on our behalf or to
																provide tutoring services, and (ii) that have agreed not to disclose it to others. We take
																all measures reasonably necessary to protect against the unauthorized access, use,
																alteration, or destruction of potentially personally-identifying and personally-identifying
																information.</p>
																<p>Information about each User that is maintained on Tutorgigs’ systems is protected using
																industry standard security measures. We follow generally accepted standards to protect
																the personal information submitted to us, both during transmission and once it is
																received. If you have any questions about the security of your personal information, you
																can contact us at support@tutorgigs.io. However, no security measures are perfect or
																impenetrable, and Tutorgigs cannot guarantee that the information submitted to,
																maintained on, or transmitted from its systems will be completely secure. Tutorgigs is
																not responsible for the circumvention of any privacy settings or security measures
																relating to the Site by any User or third parties.</p>
																<p>Your security is a priority and we strive to provide a safe and secure environment for our
																customers; however, please note that we cannot guarantee that the information
																submitted to, maintained on, or transmitted from our systems will be completely secure.
																If you do not feel comfortable sending your financial/credit card over the Internet,
																contact us and we will be happy to fill your order over the phone.</p>
																<h3>Links to 3rd Party Sites</h3>
																<p>Please note that the Site may contain links to other Sites. These linked sites may not be
																operated or controlled by Tutorgigs. Tutorgigs is not responsible for the privacy 
																practices of these or any other Sites, and you access these Sites entirely at your own
																risk. Tutorgigs recommends that you review the privacy practices of any other Sites that
																you choose to visit.</p>
																<p>Tutorgigs is based, and this Site is hosted, in the United States of America. If User is
																from from the European Union or other regions of the world with laws governing data
																collection and use that may differ from U.S. law and User is visiting, accessing, or
																otherwise using the Site, please note that any personally identifiable information that
																User provides to Tutorgigs will be transferred to the United States. Any such personally
																identifiable information provided will be processed and stored in the United States by
																Tutorgigs or a service provider acting on its behalf. By providing personally identifiable
																information to Tutorgigs, User hereby specifically and expressly consents to such
																transfer and processing and the uses and disclosures set forth herein.</p>
																<h3>Social Media Widgets</h3>
																<p>Our Site includes Social Media Features, such as the Facebook Like button and
																Widgets, such as the Share this button or interactive mini-programs that run on our Site.
																These Features may collect your IP address, which page you are visiting on our Site,
																and may set a cookie to enable the Feature to function properly. Social Media Features
																and Widgets are either hosted by a third party or hosted directly on our Site. Your
																interactions with these Features are governed by the privacy policy of the company
																providing it.</p>
																<h3>Customer Testimonials / Comments / Reviews</h3>
																<p>We post customer testimonials/comments/reviews on our Site which may contain
																personally identifiable information. We do obtain the customer's consent via email or
																other means prior to posting the testimonial authorizing us to post their name along with
																their testimonial. To request removal of your personal information from
																testimonials/comments/reviews please contact us at <a href="mailto:learn@p2g.org" target="_blank">support@tutorgigs.io</a>.</p>
																<h3>Blogs</h3>
																<p>Anytime you post on our blog please be aware that you are posting using a third party
																application, and we have no access or control over this information.
																To request removal of your personal information from our blog, you can either log into
																the third party application and remove your comment or you can contact the appropriate
																third party application. Your interaction with these features is governed by the privacy
																policy of the company providing it.</p>
																<h3>Tutor Profiles/Bios</h3>
																<p>If you apply as a tutor, the profile you create on our Site will be publicly accessible
																unless otherwise indicated. You may change the privacy settings of your profile and
																make it non-public through your account portal.</p>
																<h3>Import Contacts</h3>
																<p>You can import contacts from your Outlook or other email account address book to
																invite them to become members or Users of our Site. We collect the username and
																password for the email account from which you wish to import your contacts, and will
																only use your personal information for that purpose.<p>
																<h3>Children’s Privacy</h3>
																<p>Tutorgigs products are specifically designed to function without third party access to a
																child’s personal information. Our Site does not collect or retain any personal information
																from children except as needed to facilitate tutoring for that student, who may be a
																minor. Tutorgigs is committed to complying fully with the Children’s Online Privacy
																Protection Act of 1998 ("COPPA"). This Site assures the privacy of collected information
																in accordance with the Tutorgigs' Privacy Policy.<p>
																<p>Tutorgigs is committed to safeguarding the information each User entrusts to Tutorgigs
																and believes that every User should know how it utilizes the information collected from
																each User. Other than free information and materials that do not require any
																registrations or identifying information, the Site is not directed at children under 13 years
																of age, and Tutorgigs does not knowingly collect personally identifiable information from
																children under 13 years of age online. For purposes of using the Site as a platform to
																connect with tutors, Parents may supply information such as name or age of their
																children in order for Tutorgigs to assist facilitate the connection between student and
																tutor. Tutorgigs may store this information in a secure digital format. Parents or
																guardians of children under 13 years of age may review or have deleted any personal
																information previously provided by the child, parent, or guardian that is ultimately
																collected by Tutorgigs, if any, to connect the child with a tutor. If you seek to review or
																delete such information, contact Tutorgigs at the below phone number or email address
																indicating what you would like to have done and Tutorgigs will contact you for purposes
																of taking the requested action, including but not limited to refusing to permit the
																information further collection or use.<p>
																<h3>Your California Privacy Rights</h3>
																<p>California Civil Code Section 1798.83 permits a User that resides in California to
																request certain information regarding Tutorgigs’ disclosures of personally identifiable
																information to third parties for such third parties’ direct marketing purposes. If you are a
																California resident and would like to make such a request, please email Tutorgigs at
																<a href="mailto:learn@p2g.org" target="_blank">support@tutorgigs.io</a></p>
																<h3>Changes to this Policy</h3>
																<p>We may update this privacy policy to reflect changes to our information practices. If we
																make any material changes we will notify you by email (sent to the e-mail address
																specified in your account) or by means of a notice on this Site prior to the change 
																becoming effective. We encourage you to periodically review this page for the latest
																information on our privacy practices.</p>
																<h3>Contacting Tutorgigs</h3>
																<p>If you have any questions or comments about this Privacy Policy, you may contact
																Tutorgigs via any of the following methods:</p>
																<p>Tutorgigs<br>
																6666 Harwin Dr. Suite 260C<br>
																Houston TX 77036<br>
																(855) 34-LEARN<br>
																Email: <a href="mailto:learn@p2g.org" target="_blank">support@tutorgigs.io</a></p>
													 </div>

											

									</section>
																		<!--Content:Section-->

				</div>
			</div>
        </BODY>
        </html>
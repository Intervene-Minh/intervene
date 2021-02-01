<?php

/*
	P2G - June 2014
	Author: Kevin Pryce
	The great P2G Class
*/
require_once(__DIR__.'/../config.php');
require_once('SiteRules.class.php');
require_once('Category.class.php');
require_once('Subject.class.php');

require_once('class.Student.php');
require_once('class.Tutor.php');
require_once('class.StudentProfile.php');
require_once('class.StudentPaymentInfo.php');
require_once('class.TutorPaymentInfo.php');
require_once('class.TutorProfile.php');
require_once('class.TutorSession.php');
require_once('class.LogSite.php');

require_once('class.phpmailer.php');


class P2G {
	
// ---------------------------------------	
// ------------ Variables ----------------
	
	private static $username = "intervenedevUser"; 
	private static $password = 'Te$btu$#4f56#'; 
	private static $host = "localhost"; 
	private static $dbname = "ptwogorg_main";
	
	private static $logVisitorCreator = "P2G";
	private static $logVisitorType = "Visit";
	private static $logVisitorMessage = "Visitor to the home page - unknown user";
	
	private static $conn;
	
	//private static $connection;
	
	public $pathSiteRoot ;
	public $zipCodeLength = 5;
	
	public $SiteRules;
	public $LogSite;
	
	public $Category;
	public $Subject;
	public $Student;
	public $Tutor;
	public $StudentProfile;
	public $TutorProfile;
	
	public $connection;
	

// ---------------------------------------

	// - Constructor
	function P2G() {
		
		// Initialize all sub classes
		$this->SiteRules = new SiteRules();
                
		$this->LogSite = new LogSite();

		
                include(dirname(__FILE__)."/../db_config.php");


                self::$host = $host;
                self::$password = $password;
                self::$username = $username;
                self::$dbname = $dbname;

		
		$this->connection = mysqli_connect(self::$host, self::$username, self::$password, self::$dbname) or
			die('Connection Error: ' . mysqli_connect_error() . ' User:'. $username);
			mysqli_select_db($connection, "$tDB");	
			
			$this->pathSiteRoot="//".$site_name;
		self::$conn = $this->connection;	
	}
	
// ---------------------------------------	
	
	static function getFriends( $id ) {
		
		$connection = mysqli_connect(self::$host, self::$username, self::$password, self::$dbname) or
			die('Connection Error: ' . mysqli_connect_error() . ' User:'. $username);
			mysqli_select_db($connection, "$tDB");
			
		// Mailbox ID will give us the buddie's id
		$query = "
			SELECT * FROM 
				messages
			WHERE
				(SenderID=$id 
			OR
				RecipientID=$id) 
			AND
				!(MailBoxID=$id)
			AND
				!(MailBoxID=0)
			GROUP BY 
				MailBoxID, ID
		";
		$rsl = mysqli_query($connection, $query) or die (mysqli_error($connection));
					
		$count = mysqli_num_rows($rsl);
		
		return $rsl;
	}
	
	static function getStudentFriends( $id ) {
		
		$connection = mysqli_connect(self::$host, self::$username, self::$password, self::$dbname) or
			die('Connection Error: ' . mysqli_connect_error() . ' User:'. $username);
			mysqli_select_db($connection, "$tDB");
			
		// Mailbox ID will give us the buddie's id
		$query = "
			SELECT * FROM 
				messages
			WHERE
				(SenderID=$id 
			OR
				RecipientID=$id) 
			AND
				!(MailBoxID=$id)
			GROUP BY 
				MailBoxID, ID
		";
		$rsl = mysqli_query($connection, $query) or die (mysqli_error($connection));
					
		$count = mysqli_num_rows($rsl);
		
		return $rsl;
	}
	
	static function getTutorFriends( $id ) {
		
		$connection = mysqli_connect(self::$host, self::$username, self::$password, self::$dbname) or
			die('Connection Error: ' . mysqli_connect_error() . ' User:'. $username);
			mysqli_select_db($connection, "$tDB");
			
		// Mailbox ID will give us the buddie's id
		$query = "
			SELECT * FROM 
				messages
			WHERE
				(SenderID=$id 
			OR
				RecipientID=$id) 
			AND
				!(MailBoxID=$id)
			GROUP BY 
				MailBoxID, ID
		";
		$rsl = mysqli_query($connection, $query) or die (mysqli_error($connection));
					
		$count = mysqli_num_rows($rsl);
		
		return $rsl;
	}
	
	/*
		-----------	
	*/
	static function getStudentMailRecipients ( $id ) {
		$connection = mysqli_connect(self::$host, self::$username, self::$password, self::$dbname) or
			die('Connection Error: ' . mysqli_connect_error() . ' User:'. $username);
			mysqli_select_db($connection, "$tDB");
			
		// 
		$query = "
			SELECT * FROM 
				messages
			WHERE
				RecipientID = $id			
			GROUP BY
				SenderID, ID
		";
		$rsl = mysqli_query($connection, $query) or die (mysqli_error($connection));
					
		$count = mysqli_num_rows($rsl);
		
		return $rsl;
	}
	
	/*
		-------------	
	*/
	static function getStudentSessionTutorList ( $id ) {
		$connection = mysqli_connect(self::$host, self::$username, self::$password, self::$dbname) or
			die('Connection Error: ' . mysqli_connect_error() . ' User:'. $username);
			mysqli_select_db($connection, "$tDB");
			
		// Mailbox ID will give us the buddie's id
		$query = "
			SELECT * FROM
				messages
			WHERE(
				(RecipientID=$id AND MailBoxID !=$id )
				OR
				(RecipientID=$id AND MailBoxID=$id)  
			) 			
			GROUP BY
				SenderID, ID
		";
		$rsl = mysqli_query($connection, $query) or die (mysqli_error($connection));
					
		$count = mysqli_num_rows($rsl);
		
		return $rsl;
	}
	
	/*
		-----------	
	*/
	static function getTutorMailRecipients ( $id ) {
		$connection = mysqli_connect(self::$host, self::$username, self::$password, self::$dbname) or
			die('Connection Error: ' . mysqli_connect_error() . ' User:'. $username);
			mysqli_select_db($connection, "$tDB");
			
		// 
		$query = "
			SELECT * FROM 
				messages
			WHERE
				RecipientID=$id	
			GROUP BY
				SenderID, ID
		";
		$rsl = mysqli_query($connection, $query) or die (mysqli_error($connection));
					
		$count = mysqli_num_rows($rsl);
		
		return $rsl;
	}
	
	/*
		-------------	
	*/
	static function getTutorSessionStudentList ( $id ) {
		$connection = mysqli_connect(self::$host, self::$username, self::$password, self::$dbname) or
			die('Connection Error: ' . mysqli_connect_error() . ' User:'. $username);
			mysqli_select_db($connection, "$tDB");
			
		// Mailbox ID will give us the buddie's id
		$query = "
			SELECT * FROM
				messages
			WHERE(
				RecipientID=$id			  
			) 
			AND 
				MailBoxID!=$id
			GROUP BY
				MailBoxID, ID
		";
		$rsl = mysqli_query($connection, $query) or die (mysqli_error($connection));
					
		$count = mysqli_num_rows($rsl);
		
		return $rsl;
	}
	
		
	static function getStudentProfileCompletion($studentID) {
	
		$student = new Student();
		$studentProfile = new StudentProfile();
		
		$student->select($studentID);
		$studentProfile->select($studentID);
		
		// Load Student Payment Info
		$hasPaymentInfo = false;
		$pmtInfo = new StudentPaymentInfo();
		$pmtInfo->selectByStudentID($studentID);
		if ($pmtInfo->getStudentID() == $studentID) {
			$hasPaymentInfo = true;
		}
		
		// -- STUDENT PROFILE COMPLETION %s --
		$points = 100;
		$point_weight = 10;
		
		if ( $student->HasVerifiedEmail == 0 ) {
			//echo '-'.$point_weight. '% Verify your email address'.'<br>';
			$points = $points - $point_weight;
		}
		/*
		if ( empty( $studentProfile->Picture ) ) {
			echo '-'.$point_weight. '% Upload a Profile Photo'.'<br>';
			$points = $points - $point_weight;
		}
		*/
		if ( !$hasPaymentInfo ) {
			//echo '-'.$point_weight.'% Update Payment Information'.'<br>';
			$points = $points - $point_weight;
		}
		/*
		if ( $studentProfile->BackgroundCheck == 0 ){
			echo '-'.$point_weight. '% No Background Check'.'<br>';
			$points = $points - $point_weight;
		}	
		*/	
		if ( empty($student->ZipCode) || empty($studentProfile->ZipCode) || 
			 strlen($student->ZipCode) < $zipCodeLength) {
			//echo '-'.$point_weight. '% Enter a valid ZipCode'.'<br>';
			$points = $points - $point_weight;
		}							
		if ( empty( $student->Gender ) ){
			//echo '-'.$point_weight. '% Select a Gender'.'<br>';
			$points = $points - $point_weight;
		}
		if ( empty( $studentProfile->Over18 ) || $studentProfile->Over18 == 0 ){
			//echo '-'.$point_weight. 'Complete Age Info'.'<br>';
			$points = $points - $point_weight;
		}
		/*
		if ( empty( $studentProfile->Headline) ){
			echo '-'.$point_weight. '% Edit your Profile Headline'.'<br>';
			$points = $points - $point_weight;
		}
		if ( empty( $studentProfile->Description ) ){
			echo '-'.$point_weight. '% Edit your Profile Description'.'<br>';
			$points = $points - $point_weight;
		}
		*/
		/*
		if ( empty( $student->College) ){
			echo '-'.$point_weight. '% Edit your College'.'<br>';
			$points = $points - $point_weight;
		}
		if ( empty( $student->Major ) ){
			echo '-'.$point_weight. '% Edit College Major'.'<br>';
			$points = $points - $point_weight;
		}
		*/	
		return $points;
	}
	
	/* 
	----------------------------------------
	------------- COUNTERS -----------------	
	----------------------------------------
	*/
	static function getNumAdmins() {
                include(dirname(__FILE__)."/../db_config.php");


                self::$host = $host;
                self::$password = $password;
                self::$username = $username;
                self::$dbname = $dbname;
		$connection = mysqli_connect(self::$host, self::$username, self::$password, self::$dbname) or
			die('Connection Error: ' . mysqli_connect_error() . ' User:'. $username);
			mysqli_select_db($connection, "$tDB");
			
		// Mailbox ID will give us the buddie's id
		$query = "
			SELECT * FROM 
				users			
		";
		$rsl = mysqli_query($connection, $query) or die (mysqli_error($connection));
					
		$count = mysqli_num_rows($rsl);
		
		return $count;
	}
	
	static function getNumTutors() {
		$connection = mysqli_connect(self::$host, self::$username, self::$password, self::$dbname) or
			die('Connection Error: ' . mysqli_connect_error() . ' User:'. $username);
			mysqli_select_db($connection, "$tDB");
			
		// Mailbox ID will give us the buddie's id
		$query = "
			SELECT * FROM 
				tutors			
		";
		$rsl = mysqli_query($connection, $query) or die (mysqli_error($connection));
					
		$count = mysqli_num_rows($rsl);
		
		return $count;
	}
	
	static function getNumTutorProfiles() {
		$connection = mysqli_connect(self::$host, self::$username, self::$password, self::$dbname) or
			die('Connection Error: ' . mysqli_connect_error() . ' User:'. $username);
			mysqli_select_db($connection, "$tDB");
			
		// Mailbox ID will give us the buddie's id
		$query = "
			SELECT * FROM 
				tutorProfiles			
		";
		$rsl = mysqli_query($connection, $query) or die (mysqli_error($connection));
					
		$count = mysqli_num_rows($rsl);
		
		return $count;
	}
	
	static function getNumStudents() {
		$connection = mysqli_connect(self::$host, self::$username, self::$password, self::$dbname) or
			die('Connection Error: ' . mysqli_connect_error() . ' User:'. $username);
			mysqli_select_db($connection, "$tDB");
			
		// Mailbox ID will give us the buddie's id
		$query = "
			SELECT * FROM 
				students			
		";
		$rsl = mysqli_query($connection, $query) or die (mysqli_error($connection));
					
		$count = mysqli_num_rows($rsl);
		
		return $count;
	}
	
	static function getNumJobPosts() {
		$connection = mysqli_connect(self::$host, self::$username, self::$password, self::$dbname) or
			die('Connection Error: ' . mysqli_connect_error() . ' User:'. $username);
			mysqli_select_db($connection, "$tDB");
			
		// Mailbox ID will give us the buddie's id
		$query = "
			SELECT * FROM 
				jobPosts			
		";
		$rsl = mysqli_query($connection, $query) or die (mysqli_error($connection));
					
		$count = mysqli_num_rows($rsl);
		
		return $count;
	}
	static function getNumJobPostsForStudent($studentID) {
		$connection = mysqli_connect(self::$host, self::$username, self::$password, self::$dbname) or
			die('Connection Error: ' . mysqli_connect_error() . ' User:'. $username);
			mysqli_select_db($connection, "$tDB");
			
		// Mailbox ID will give us the buddie's id
		$query = "
			SELECT * FROM 
				jobPosts	
			WHERE StudentID = $studentID		
		";
		$rsl = mysqli_query($connection, $query) or die (mysqli_error($connection));
					
		$count = mysqli_num_rows($rsl);
		
		return $count;
	}

	static function TutorAppliedToJobPosts($tutorID, $jobPostID) {
		$connection = mysqli_connect(self::$host, self::$username, self::$password, self::$dbname) or
			die('Connection Error: ' . mysqli_connect_error() . ' User:'. $username);
			mysqli_select_db($connection, "$tDB");
			
		// Mailbox ID will give us the buddie's id
		$query = "
			SELECT * FROM 
				jobApplications	
			WHERE JobPostID = $jobPostID AND TutorID = $tutorID		
		";
		$rsl = mysqli_query($connection, $query) or die (mysqli_error($connection));
					
		$count = mysqli_num_rows($rsl);
		if ($count > 0)
			return true;
		else
			return false;
	}

	static function getNumTutorSessions() {
		$connection = mysqli_connect(self::$host, self::$username, self::$password, self::$dbname) or
			die('Connection Error: ' . mysqli_connect_error() . ' User:'. $username);
			mysqli_select_db($connection, "$tDB");
			
		// Mailbox ID will give us the buddie's id
		$query = "
			SELECT * FROM 
				tutorSessions			
		";
		$rsl = mysqli_query($connection, $query) or die (mysqli_error($connection));
					
		$count = mysqli_num_rows($rsl);
		
		return $count;
	}
	static function getNumSubjects() {
		$connection = mysqli_connect(self::$host, self::$username, self::$password, self::$dbname) or
			die('Connection Error: ' . mysqli_connect_error() . ' User:'. $username);
			mysqli_select_db($connection, "$tDB");
			
		// Mailbox ID will give us the buddie's id
		$query = "
			SELECT * FROM 
				subjects			
		";
		$rsl = mysqli_query($connection, $query) or die (mysqli_error($connection));
					
		$count = mysqli_num_rows($rsl);
		
		return $count;
	}
	static function getNumTests() {
		$connection = mysqli_connect(self::$host, self::$username, self::$password, self::$dbname) or
			die('Connection Error: ' . mysqli_connect_error() . ' User:'. $username);
			mysqli_select_db($connection, "$tDB");
			
		// Mailbox ID will give us the buddie's id
		$query = "
			SELECT * FROM 
				tests			
		";
		$rsl = mysqli_query($connection, $query) or die (mysqli_error($connection));
					
		$count = mysqli_num_rows($rsl);
		
		return $count;
	}
	
	static function getNumPaymentRequests() {
		$connection = mysqli_connect(self::$host, self::$username, self::$password, self::$dbname) or
			die('Connection Error: ' . mysqli_connect_error() . ' User:'. $username);
			mysqli_select_db($connection, "$tDB");
			
		// Mailbox ID will give us the buddie's id
		$query = "
			SELECT * FROM 
				PaymentRequests			
		";
		$rsl = mysqli_query($connection, $query) or die (mysqli_error($connection));
					
		$count = mysqli_num_rows($rsl);
		
		return $count;
	}
	
	static function getNumTutorSessionFeedback() {
		$connection = mysqli_connect(self::$host, self::$username, self::$password, self::$dbname) or
			die('Connection Error: ' . mysqli_connect_error() . ' User:'. $username);
			mysqli_select_db($connection, "$tDB");
			
		// Mailbox ID will give us the buddie's id
		$query = "
			SELECT * FROM 
				tutorSessionFeedback			
		";
		$rsl = mysqli_query($connection, $query) or die (mysqli_error($connection));
					
		$count = mysqli_num_rows($rsl);
		
		return $count;
	}
	
		
// ---------------------------------------	
	
	
// ---------------------------------------


	// - Returns a JSON object
	static function jsonOut( $inData ) {
		
		
	}	

 // ------------- Utility -----------------
 // ---------------------------------------
 
 	// - Sanitizes string output
 	static function PrintString($string) {
		if (empty($string))
			return "";
		else
			return $string;		
	}
	
	// - Sanitizes MONEY strings & output
 	static function PrintMoneyString($string) {
		if (empty($string))
			return "0.00";
		else
			return number_format($string, 2);		
	}
 	
 
 	// - Checks if Visitor has been logged within the past interval time period. returns bool
 	static function HasRecentlyBeenLogged($ip) {
		$connection = mysqli_connect(self::$host, self::$username, self::$password, self::$dbname) or
			die('Connection Error: ' . mysqli_connect_error() . ' User:'. $username);
			mysqli_select_db($connection, "$tDB");
		
			$rule = new SiteRules();
		// Get Last Log for IP addr
		$query = "
			SELECT * FROM logSite
			WHERE 
			CreatedOn > (DATE_ADD(NOW(),INTERVAL 1 HOUR) - INTERVAL ".$rule->getLogInterval()." MINUTE)
			AND
			IPAddress='".trim($ip)."'
			ORDER BY ID DESC LIMIT 1
		";
		//$rsl = $database->Query($query);
		$rsl = mysqli_query($connection, $query) or die (mysqli_error($connection));
		//echo ('result: '.var_dump($rsl));				
		$count = mysqli_num_rows($rsl);
		$row = mysqli_fetch_array($rsl);
		
		//echo ('count:'.(int)$count.'log int:' );
		if ( (int)$count == 1)  {
			//echo ('count was 1');
			return true;
		}  
		if ( (int)$count == 0) { 
			// no recently logged
			//echo ('count was 0');
			return false;
		}		
		//echo ('neither case');
		// just in case
		return false;
	}
 	
	// - Add a Log Message
	static function Log($strType, $strMessage, $strCreatedBy) {
		
		$log = new LogSite();
		return $log->Create($strType, $strMessage, $strCreatedBy);
		
	}
	
	// - Add a Log Message
	static function LogVisitor() {
		
		$log = new LogSite();
		return $log->Create(self::$logVisitorType, self::$logVisitorMessage, self::$logVisitorCreator);
		
	}
	
	static function sendP2GEmail($subject, $email, $body) {
		$mail = new PHPMailer ();
		$mail->From = "tutoring@p2g.org";
		$mail->FromName = "P2G Admin";
		$mail->Subject = $subject;
		$mail->addAddress ( $email );
		$mail->isHTML ( true );
		$mail->Body = $body;
		$mail->AltBody = $body;
		
		if (! $mail->send ()) {
			
			return false;
		} else {
			return true;
		}
	}
		
	
 	// - Auto magically Loads a Class ... how to use?
	function __autoload($Name)
	{
		$seperators = array('.', '-', '', '_');
		$namingConventions = array(
			'class[SEP]'.$Name,
			$Name.'[SEP]class',
			$Name,
		);
		$includePath = array(
			'/includes/',
			'/home6/ptwogorg/public_html/classes'
		);
		foreach ($includePath as $path)
			foreach ($seperators as $sep)
				foreach ($namingConventions as $convention)
					if (is_file($file = $_SERVER['DOCUMENT_ROOT'].$path.str_replace('[SEP]', $sep, $convention).'.php'))
						include_once ($file);
	}
	
// ---------------------------------------	
// ---------------------------------------

}
?>

<?

	require_once("class.TutorProfile.php");
	
	$tutorPro = new TutorProfile(100);
	//$tutorPro->select(100);
	
	$tutorPro->Headline = 'Kevin Pryce Tutor plus + ' . $tutorPro->Headline;
	echo ('Headline: ' . $tutorPro->Headline . '<br/>');
	echo ('Description:' . $tutorPro->Description . '<br/>');

	$tutorPro->update($tutorPro->TutorID);
	echo ('Profile Updated! ' . $tutorPro->TutorID . '<br/>');
	
?>
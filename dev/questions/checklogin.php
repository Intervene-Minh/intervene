<?php

	$student_id = mysql_query("SELECT * FROM `students` WHERE `id` = $uID AND `status` = 1 ");

	if(mysql_num_rows($student_id) >0 ) {

		$students = mysql_fetch_assoc($student_id);

		$_SESSION['student_id'] = $students['id'];

		$studi= $students['id'];

		$_SESSION['student_name'] = $students['first_name'];

		$_SESSION['last_name'] = $students['last_name'];

		$str="SELECT tch.teacher_id FROM `students_x_class` as stu INNER JOIN `class_x_teachers` AS tch ON stu.class_id=tch.class_id WHERE stu.student_id=$studi";

		$teachD= mysql_fetch_assoc(mysql_query($str));
		$_SESSION['teacher_id'] = $teachD['teacher_id'];

		$_SESSION['schools_id'] = $students['school_id'];
	} 

?>
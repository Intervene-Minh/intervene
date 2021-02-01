<?php
	
	$config = array(
    'login' => array(
	array(
	'field' => 'email_id',
	'label' => 'Email',
	'rules' => 'trim|required|valid_email'
	),
	array(
	'field' => 'password',
	'label' => 'Password',
	'rules' => 'trim|required'
	),
	
	
	
    ),
    
	
	'registration' => array(
	array(
	'field' => 'User_Name',
	'label' => 'Name',
	'rules' => 'trim|required'
	),
	array(
	'field' => 'User_Password',
	'label' => 'Password',
	'rules' => 'trim|required'
	),
	array(
	'field' => 'Email_id',
	'label' => 'Email',
	'rules' => 'trim|required|valid_email'
	),
	array(
	'field' => 'User_Mobile',
	'label' => 'Mobile',
	'rules' => 'trim|required|regex_match[/^[0-9]{10}$/]'
	)
	
	
    ),
	);
	

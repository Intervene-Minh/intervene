<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$config['useragent'] = 'CodeIgniter';
$config['protocol'] = 'smtp';
//$config['mailpath'] = '/usr/sbin/sendmail';
$config['smtp_host'] = 'ssl://mail.subbieapp.com';
$config['smtp_user'] = 'api@subbieapp.com';
$config['smtp_pass'] = '4t324t3q45542354';
$config['smtp_port'] = 465; 
$config['smtp_timeout'] = 5;
$config['wordwrap'] = TRUE;
$config['wrapchars'] = 76;
$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['validate'] = FALSE;
$config['priority'] = 3;
$config['crlf'] = "\r\n";
$config['newline'] = "\r\n";
$config['bcc_batch_mode'] = FALSE;
$config['bcc_batch_size'] = 200;

/*
$this->load->library('email'); // Note: no $config param needed
$this->email->from('YOUREMAILHERE@gmail.com', 'YOUREMAILHERE@gmail.com');
$this->email->to('SOMEEMAILHERE@gmail.com');
$this->email->subject('Test email from CI and Gmail');
$this->email->message('This is a test.');
$this->email->send();
*/
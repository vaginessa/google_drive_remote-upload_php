<?php
/*
// Your are free to modify and distribute it as long as below three lines are there 
//in the file. 
//@author Sachin Khokhar
//@website http://savedrive.faltutech.club
*/
//session settings
session_start();
$time = $_SERVER['REQUEST_TIME'];
$timeout_duration = 3599;
if (isset($_SESSION['LAST_ACTIVITY']) && ($time - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
  session_unset();     
  session_destroy();
session_start(); 
}

//************************************************************
// Configuration file
//************************************************************
$site_url="";    // change it your own
$site_title="";
$site_description="";
$footer_small_logo="";
$max_file_size_allowed="";   // to tell your users the allowed size
// site setting ends
$redirect_uri_code_get=$site_url.'/code.php';
$redirect_uri_save_email=$site_url.'/saveemail.php';
$scope="https://www.googleapis.com/auth/drive https://www.googleapis.com/auth/userinfo.email";
$access_type="offline";
$project_id="phrasal-bivouac-133723";
$token_uri="https://accounts.google.com/o/oauth2/token?";
$auth_provider_x509_cert_url="https://www.googleapis.com/oauth2/v1/certs";
//$client_secret="MQYDeR9DcC3e3pEzrvf2WxCt";

//**************************************************
//Database settings
$host='';
$db='';
$user='';
$pass='';





//-----------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------
//End of file
//******************************************************************************************************************
//*******************************************************************************************************************



?>

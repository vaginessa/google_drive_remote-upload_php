<?php
/**********************************
// Your are free to modify and distribute it as long as below three lines are there 
//in the file. 
//@author Sachin Khokhar
//@website http://savedrive.faltutech.club
*****************************************/
/*********************************
save email to database so that you can know who have used your service

redirects to index for uploading
*****************************************/
require ('config.php');
require __DIR__ .'/vendor/autoload.php';

	
//***********************************************
//parsing json and saving refresh token
//***********************************************
$conn = new mysqli($host,$user,$pass,$db);

if ($conn->connect_error){
	echo 'error establishing database connection'. $conn->error;
}
else{
$token = json_decode($_SESSION['access_token']);

//********************
//start to get the user email
//
//***********************************/
	$client = new Google_Client();
$client->setAuthConfigFile('client_secrets.json');
$client->setAccessToken($_SESSION['access_token']);
 
//***************************************/


   
 $googlePlus = new Google_Service_Plus($client);
$userProfile = $googlePlus->people->get('me');
$emails = $userProfile->getEmails();
$foundemail=$emails[0]->value;
//***********************************************************
//end of getting email
//*******************************************

	$_SESSION['email']=$foundemail;
	
	$time=date('d\/M\/Y');
	
	$saveToken = "INSERT INTO drive"."(email,time)". "VALUES"."('$foundemail','$time')"; // Saving the email, time in database. 	
		$conn->query($saveToken);
       mysqli_close($conn);


		
$after_token_saved='/index.php';
header('Location: ' . filter_var($after_token_saved, FILTER_SANITIZE_URL));
			

}

?>
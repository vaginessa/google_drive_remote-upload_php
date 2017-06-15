
<?php
/**********************************
// Your are free to modify and distribute it as long as below three lines are there 
//in the file. 
//@author Sachin Khokhar
//@website http://savedrive.faltutech.club
*****************************************/
/*************************
It refreshes a token and 
sends to http referer in url parameter 'purl' or if no referer defined then to index
**************************/
require ('config.php');
require __DIR__ .'/vendor/autoload.php';

if (!isset($_SESSION['email'])){
	die('You are not logged in <a href="/">Home</a>');
}

/*

********************************************************************************************************************
                                                      refresh access token area
//*******************************************************************************************************************

*/


$conn=new mysqli($host,$user,$pass,$db);
$email=$_SESSION['email'];

$sql="SELECT * FROM `drive` WHERE `email` = '$email'";
$fields=mysqli_fetch_assoc($conn->query($sql));
 $refreshToken=$fields['refreshtoken'];

$client = new Google_Client();
$client->setAuthConfigFile('client_secrets.json');

if(Null==$_SESSION['access_token']) goto gettoken;

$client->setAccessToken($_SESSION['access_token']);


$checktoken=$client->isAccessTokenExpired($_SESSION['access_token']);

 if ($checktoken==True){
	
	gettoken:
	
	$client->refreshToken($refreshToken);
		$_SESSION['access_token'] = $client->getAccessToken();
		$client->verifyIdToken();
		if (Null==$_GET['purl'])goto newurl;
		$url=$_GET['purl'];
		header('Location: ' . filter_var($url, FILTER_SANITIZE_URL));
	newurl:
	$url1='/';
	header('Location: ' . filter_var($url1, FILTER_SANITIZE_URL));
	}
	else {
		echo 'Token is not expired:-)';
	}
		


//****************************************************refesh token Area Ends**************************************************/







?>



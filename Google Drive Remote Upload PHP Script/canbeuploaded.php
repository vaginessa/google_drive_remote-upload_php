<?php
/*
// Your are free to modify and distribute it as long as below three lines are there 
//in the file. 
//@author Sachin Khokhar
//@website http://savedrive.faltutech.club
*/
// It just makes sure that file can be uploaded to the google drive
require ('config.php');
require __DIR__ .'/vendor/autoload.php';

$client = new Google_Client();
$client->setAuthConfigFile('client_secrets.json');
$client->setAccessToken($_SESSION['access_token']);

$drive=new Google_Service_Drive($client);
$about=$drive->about->get();   // get all the about information of google drive

//get remaining storage in google drive
$remaining=($about['quotaBytesTotal'])-($about['quotaBytesUsedAggregate']);




$url=$_POST['url'];
$head=get_headers($url,TRUE);
$header=array_change_key_case($head);
$remote_file_size=$header['content-length'];
$basenamee=pathinfo($url)['extension'];

if($head==false){                    // checks if url exists
	$url2='/index.php?status=badurl';
header('Location: ' . filter_var($url2, FILTER_SANITIZE_URL));
}
elseif($basenamee==NULL){                                       // checks if file have extension
	$url2='/index.php?status=noextension';
header('Location: ' . filter_var($url2, FILTER_SANITIZE_URL));
}
elseif($remote_file_size>2147483648){                            // change the file size which is allowed to be uploaded
	$url2='/index.php?status=largefile';
header('Location: ' . filter_var($url2, FILTER_SANITIZE_URL));
}
elseif ($remaining<$remote_file_size){                         // checks if drive has enough storage
	
	$url2='/index.php?status=driveisoutofstorage';
header('Location: ' . filter_var($url2, FILTER_SANITIZE_URL));
}
else{                                                            // if passed all of them than download and upload file
	$url1='/remote_upload.php?url='.base64_encode($url);
header('Location: ' . filter_var($url1, FILTER_SANITIZE_URL));
}


?>

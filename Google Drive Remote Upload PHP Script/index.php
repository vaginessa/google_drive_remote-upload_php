<?php
/**********************************
// Your are free to modify and distribute it as long as below three lines are there 
//in the file. 
//@author Sachin Khokhar
//@website http://savedrive.faltutech.club
*****************************************/
error_reporting(0);
require('config.php');
include 'khokharfunctions.php';

require __DIR__ .'/vendor/autoload.php';

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="discription" content="<?php echo $site_description; ?>">
<meta name="keywords" content="remote url upload to google drive,google drive remote url upload">
<link rel="stylesheet" type="text/css" href="style.css"/>
    <meta name="author" content="">
    <link rel="shortcut icon" href="assets/ico/favicon.png">

    <title><?php echo $site_title; ?></title>

    <link href="assets/css/hover_pack.css" rel="stylesheet">

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="assets/css/main.css" rel="stylesheet">
    <link href="assets/css/colors/color-74c9be.css" rel="stylesheet">    
    <link href="assets/css/animations.css" rel="stylesheet">
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">
    
    
    <!-- JavaScripts needed at the beginning
    ================================================== 

   
    
    
    <!-- Main Jquery & Hover Effects. Should load first -->
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="assets/js/hover_pack.js"></script>
    

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
<div id="headerwrap">
    	<div class="container">
			<div class="row centered">
				<div class="col-lg-8 col-lg-offset-2 mt">
					<h1 class="animation slideDown">SaveDrive.FaltuTech.Club    </h1>				
				</div>
				
			

<?php
if (!isset($_SESSION['email']))
{

echo   '<p class="mt"><button type="button" class="btn btn-cta btn-xs">File Size Limit : '.$max_file_size_allowed .'</button><br><button type="button" class="btn btn-cta btn-lg"><a href="/authorize.php">Click Here To Authorize</a></button></p>';
	
}
else{
	if($_SESSION['access_token']==Null){
	$url='/token.php';
header('Location: ' . filter_var($url, FILTER_SANITIZE_URL));

	} 
	elseif($_SESSION['access_token']!=Null) {
		
		

		// if token has expired we will show it here.***********************************************
		$atoken=json_decode($_SESSION['access_token']);    // jason decode token in session
	
	$access_token= $atoken->access_token;                // get the access token from atoken object
	$client = new Google_Client();                         // create new google client
	$client->setAuthConfigFile('client_secrets.json');    // enter client secrets
	$client->setAccessToken($_SESSION['access_token']);    // set the access token
	
	
	$checktoken=$client->isAccessTokenExpired($_SESSION['access_token']);   // check if token has expired
	if ($checktoken==True){
		
		                                                 // if expired show the authrize again.
		echo '<button type="button" class="btn btn-cta btn-sm"><a href="/authorize.php">Session Expired. Authorize Again</a></button>';
		
		
	}
		else {

                echo ' <p ><button type="button" class="btn btn-cta btn-sm">Authorized as : '.$_SESSION['email'].'</button><br><button type="button" class="btn-link btn-cta btn-sm">><a href="/change_account.php" style="font-size:18px;">Change Account</a></button></p>';


		




	echo '	<button id="status" class="btn-info btn-cta btn-xs" ></button><br><button id="progress" class="btn-info btn-cta btn-sm"></button><br>
	
<button  class="btn-info btn-cta btn-xs">File Size Limit :'.$max_file_size_allowed .'</button>';

// various status codes like google storage out of quota , file not found etc.******************
                if (isset($_GET['status']) && !empty($_GET['status'])){
		$status=response($_GET['status']);
echo '<p ><button type="button" class="btn-success btn-cta btn-sm">'.$status;
		
if (isset($_GET['filename']) && !empty($_GET['filename'])){

echo ':: '.$_GET['filename'].':: has been uploaded';

}echo '</p></button>';
}


echo '<form action="/canbeuploaded.php" enctype="multipart/form-data" method="post" name="upload" id="formurl">
	<input class="inputc" type="text" size="50" id="url" name="url"><br/><br/>
<button onclick="putcontent()"  type="submit"   id="btnupload" class="btn-primary btn-cta btn-sm">
<span>Upload</span></button>
	
	</form>';
    echo '<script>
    
    function putcontent (){
    
      var x = document.getElementById("btnupload");
      var y= document.getElementById("formurl");
      y.submit();
    x.disabled = true;
    x.innerHTML=\'<table><th><img src=https://i.imgur.com/V010DVL.gif></th><th><span style=color:#ffffff>Do not close the window <br>until upload is  not completed</span></th></table>\';
    
    var url=document.getElementById("url").value;
    var status=document.getElementById("status");
    var basename=url.split("/");
    status.innerHTML=basename[basename.length-1]+\'<br>\';

    var filename=basename[basename.length-1];
    var codedfilename=btoa(filename);
  function xml(){
   var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("progress").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "status.php?file="+codedfilename+"&t="+ Math.random(), true);
  xhttp.send();


    }  setInterval(function(){
    xml() // this will run after every 1 seconds
}, 1000);
}
    
    </script>
    
    
   
    
    
    
    
    
    ';
		}
	}
	else {
		echo 'something went wrong';
	}
	
	
	
	
	
	
	
	
	
	
	
}





?>

</div><!-- /row -->
    	</div><!-- /container -->
    </div> <!-- /headerwrap -->
<div ><img src="<?php echo $footer_small_logo; ?>"/></div>
</body>
</html>
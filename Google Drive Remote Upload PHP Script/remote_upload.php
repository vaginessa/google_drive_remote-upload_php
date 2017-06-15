<?php
/**********************************

// Your are free to modify and distribute it as long as below three lines are there
// in the file.
// @author Sachin Khokhar
// @website http://savedrive.faltutech.club

*****************************************/
/*
Remote url upload script
Functions it does:
Download the file from the url
upload it to the google drive
and return to index

downloading starts from here
*/
require ('config.php');

require __DIR__ . '/vendor/autoload.php';

// maximum execution time in seconds

set_time_limit(300);

if (!isset($_GET['url'])) die();
$url = base64_decode($_GET['url']);
$url1 = filter_var($url, FILTER_SANITIZE_URL);

if (!filter_var($url1, FILTER_VALIDATE_URL) === FALSE)
	{

	// folder to save downloaded files to. must end with slash

	$destination_folder = 'files/';
	$path_info = pathinfo($url);
	$check_fname = $path_info['basename'];
	$txtfile = 'files/' . $check_fname . ".txt"; // create a text file for status
	$bae = fopen($txtfile, "w+");
	fclose($bae); // End of creation of text file
	$txtdata = array(
		"filesize" => "",
		"doneuploading" => ""
	); // array to be inserted in status text file
	$txthead = get_headers($url, TRUE);
	$txtheader = array_change_key_case($txthead);
	$txtremote_file_size = $txtheader['content-length']; // get file size for status text file
	$append = serialize($txtdata);
	$write = file_put_contents($txtfile, $append);
	$read = unserialize(file_get_contents($txtfile));
	$read['filesize'] = $txtremote_file_size;
	$write = file_put_contents($txtfile, serialize($read)); // till here entered the size of file and serialized the array
	if (file_exists('files/' . $check_fname))
		{ // checks if file exists
		gotofileexits;
		}

	$newfname = $destination_folder . basename($url);
	$file = fopen($url, "rb");
	if ($file)
		{
		$newf = fopen($newfname, "wb");
		if ($newf)
		while (!feof($file))
			{
			fwrite($newf, fread($file, 1024 * 8) , 1024 * 8);
			}
		}

	if ($file)
		{
		fclose($file);
		}

	if ($newf)
		{
		fclose($newf);
		fileexits:

			// Start File Uploading   *********************************************

			$filename = pathinfo($url) ['basename'];
			$title1 = '/files/' . $filename;
			$fileinfoinarray = pathinfo($title1); // array for file basename etc.
			$drivename = $fileinfoinarray['basename'];
			if (file_exists(__DIR__ . $title1) == False)
				{
				$url2 = '/index.php?status=fileurlisnotvalid';
				header('Location: ' . filter_var($url2, FILTER_SANITIZE_URL));
				}

			$size = filesize(__DIR__ . $title1); //get file size

			// title and file path and file size are above

			$atoken = json_decode($_SESSION['access_token']);
			$access_token = $atoken->access_token;

			// *******************************************************************************

			$client = new Google_Client();
			$client->setAuthConfigFile('client_secrets.json');
			$client->setAccessToken($_SESSION['access_token']);
			$checktoken = $client->isAccessTokenExpired($_SESSION['access_token']);
			if ($checktoken == True)
				{
				$gettokennow = '/index.php';
				header('Location: ' . filter_var($gettokennow, FILTER_SANITIZE_URL));
				}
			  else
				{
				$service = new Google_Service_Drive($client);
				$file = new Google_Service_Drive_DriveFile();
				$file->title = $drivename;
				$chunkSizeBytes = 1 * 1024 * 1024;

				// Call the API with the media upload, defer so it doesn't immediately return.

				$client->setDefer(true);
				$request = $service->files->insert($file);

				// Get file mime with function mime_content_type

				$mimee = mime_content_type(__DIR__ . $title1);

				// Create a media file upload to represent our upload process.

				$media = new Google_Http_MediaFileUpload($client, $request, $mimee, null, true, $chunkSizeBytes);
				$media->setFileSize(filesize(__DIR__ . $title1));

				// Upload the various chunks. $status will be false until the process is
				// complete.

				$status = false;
				$mbuploaded = 0;
				$handle = fopen(__DIR__ . $title1, "rb");
				while (!$status && !feof($handle))
					{
					$chunk = fread($handle, $chunkSizeBytes);
					$status = $media->nextChunk($chunk);

					// for text file get the mb uploaded start

					$read = unserialize(file_get_contents($txtfile));
					$mbuploaded++;
					$read['doneuploading'] = $mbuploaded;
					$write = file_put_contents($txtfile, serialize($read)); //  for text file entered mb uploaded
					}

				// The final value of $status will be the data from the API for the object
				// that has been uploaded.

				$result = false;
				if ($status != false)
					{
					$result = $status;
					}

				fclose($handle);

				// Reset to the client to execute requests immediately in the future.

				$client->setDefer(false);

				// *********************************************************************************
				// *********************************************************************************

				unlink(__DIR__ . $title1); // delte file
				unlink($txtfile); //delete text file of status
				$urluploaded = '/index.php?status=uploaded&filename=' . $check_fname;
				header('Location: ' . filter_var($urluploaded, FILTER_SANITIZE_URL));
				}
			}
		}
	  else
		{
		$url2 = '/index.php?status=fileurlisnotvalid';
		header('Location: ' . filter_var($url2, FILTER_SANITIZE_URL));
		}

?>

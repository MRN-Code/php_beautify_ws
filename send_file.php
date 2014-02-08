<?php
/*
 * Command line script to send a PHP script to webservice for beautification
 * first argument is file to be beautified (required)
 * second argument is the name of the file to be written to (optional)
 * if only one arg is specified, then the new file will be written
 * to the a new file that has the same name as the original filename
 * with ".beautiful" appended to the end.
 * @Dylan Wood
*/
$new_line = "\n";
//set target url
$target_url = 'http://lintcoin.mind.unm.edu/php_beautifier/index.php';
//this is the extension that will be assigned to the new file
$response_filename_ext = '.beautiful';
//attempt to get filename to send
if (!empty($argv[1])) {
   $filename = $argv[1];
} else {
   throw new Exception('No input file given');
   die;
}
//get username
echo "running as " . get_active_user();
echo $new_line;
//send the file
$new_filename = send_file($target_url, $filename, $response_filename_ext);
if ($new_filename) {
   echo $new_line;
   echo "Be sure to view the diff between new file and the original with vimdiff";
   echo $new_line;
   echo "Your beautiul new file has been written to $new_filename";
}
function get_active_user() {
   $userInfo = posix_getpwuid(posix_getuid());
   $user = $userInfo['name'];
}
function send_file($target_url, $filename, $response_filename_ext = ".log") {
   $new_line = "\n";
   //setup new filename for writing to the local dir
   $new_filename = basename($filename) . $response_filename_ext;
   $tmp_filename = $new_filename;
   $ext = 1;
   //make sure that we do not overwrite anything
   //by looping until we arrive at a filename that is not in use
   while (file_exists($tmp_filename)) {
      $tmp_filename = $new_filename . '.' . $ext;
      $ext = $ext + 1;
   }
   //reassign new_filename to be the safe filename
   $new_filename = $tmp_filename;
   //make sure that we can write to the new filename
   if (!touch($new_filename)) {
      throw new Exception("Could not write to $new_filename");
      die;
   }
   //obtain absolute path of filename
   $filename = realpath($filename);
   //get handle pointing to new filename
   $new_file_handle = fopen($new_filename, 'w+');
   //set up post data
   $post = array('file_contents' => '@' . $filename);
   //initialize cURL object
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $target_url);
   curl_setopt($ch, CURLOPT_FILE, $new_file_handle);
   curl_setopt($ch, CURLOPT_POST, 1);
   curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
   curl_setopt($ch, CURLOPT_TIMEOUT, 60);
   echo "Sending $filename to $target_url...";
   echo $new_line;
   //send request
   curl_exec($ch);
   //check for errors
   if (curl_errno($ch)) {
      unlink($response_filename);
      throw new Exception("Error during HTTP request: cURL error: " . curl_error($ch));
      die;
   }
   //see if anything was written to the new file
   if (!filesize($new_filename)) {
      throw new Exception("Error: no data written to $response_filename");
   }
   curl_close($ch);
   echo "Complete";
   return $new_filename;
}
?>


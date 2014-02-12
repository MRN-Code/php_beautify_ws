<?php
require_once 'PHP/Beautifier.php';
error_reporting(E_ALL);
ini_set('display_errors', '1');
ob_start();
$tmp_filename = '/tmp/tidy_me.php' . microtime();
//get uploaded file
if ($filename = get_uploaded_file($tmp_filename)) {
   $uploaded_filename = get_uploaded_file_name();
   $new_filename = tidy($filename);
   ob_clean();
   header('Content-Description: File Transfer');
   header('Content-Type: application/octet-stream');
   header('Content-Disposition: attachment; filename=' . $uploaded_filename . '.beautiful');
   header('Expires: 0');
   header('Cache-Control: must-revalidate');
   header('Pragma: public');
   echo file_get_contents($new_filename);
   ob_end_flush();
   unlink($tmp_filename);
   unlink($new_filename);
} else {
   //there was a problem with the uploaded file.
   reply_with_error('Could not locate uploaded file');
   die;
}
function tidy($filename) {
   $tidy_path = '/usr/share/php/PHP/phptidy/phptidy.php';
   chmod($filename, '777');
   exec('php ' . $tidy_path . ' suffix ' 
      . escapeshellarg($filename), $outputs, $return_var);
   //DEBUG
   //error_log(print_r($outputs), true);
   $safe_output = trim($outputs[4]);
   return str_replace(' saved.', '', $safe_output);
}
function get_uploaded_file_name() {
   if (!empty($_GET['test'])) {
      return 'test.php';
   }
   return $_FILES['file_contents']['name'];
}
function get_uploaded_file($tmp_filename) {
   /*
    * Looks for an uploaded file and attempts to move it to confirm that it is valid
    * Returns boolean false, or a file handle to the uploaded file if it is valid
   */
   if (!empty($_GET['test'])) {
      return 'test.php';
   }
   if (move_uploaded_file($_FILES['file_contents']['tmp_name'], $tmp_filename)) {
      return $tmp_filename;
   }
   return false;
}
function reply_with_error($message) {
   header($_SERVER['SERVER_PROTOCOL'] . $message, true, 500);
}
?>

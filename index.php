<?php
require_once 'PHP/Beautifier.php';
error_reporting(E_ALL);
ini_set('display_errors', '1');
ob_start();
$tmp_filename = '/tmp/beautify_me.php' . microtime();
//get uploaded file
if ($filename = get_uploaded_file($tmp_filename)) {
   $uploaded_filename = get_uploaded_file_name();
   ob_clean();
   header('Content-Description: File Transfer');
   header('Content-Type: application/octet-stream');
   header('Content-Disposition: attachment; filename=' . $uploaded_filename . '.beautiful');
   header('Expires: 0');
   header('Cache-Control: must-revalidate');
   header('Pragma: public');
   beautify($filename);
   ob_end_flush();
   unlink($tmp_filename);
} else {
   //there was a problem with the uploaded file.
   reply_with_error('Could not locate uploaded file');
   die;
}
function beautify($filename) {
   // Create the instance
   $oBeautifier = new PHP_Beautifier();
   // Add another filter, with one parameter
   //$oBeautifier->addFilter('Pear', array('add_header' => 'php'));
   // Set the indent char, number of chars to indent and newline char
   $oBeautifier->setIndentChar(' ');
   $oBeautifier->setIndentNumber(3);
   $oBeautifier->setNewLine("\n");
   // Define the input file
   $oBeautifier->setInputFile($filename);
   // Process the file. DON'T FORGET TO USE IT
   $oBeautifier->process();
   // Show the file (echo to screen)
   $oBeautifier->show();
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

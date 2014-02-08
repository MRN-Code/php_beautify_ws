php_beautify_ws

This repo contains an index.php file to accept HTTP requests. index.php expects a PHP file to be attached in the POST of the HTTP request. It then applies PHP_Beautifier to that file, and returns the result as a stream/octet.


Requirements: you need to have PHP_Beautifier installed (google PEAR PHP Beautifier). Index.php expexts to find Beautifier.php in PHP/Beautifier.php, where PHP/ is a subdirectory of some directory in your include path. For example, on Ubuntu, PEAR installs are located at /usr/share/php/, and PHP Beautifier is installed in /usr/share/php/PHP. Since /user/share/php is in the php include path, using ```PHP/Beautifier.php``` is sufficient.

Also included is send_file.php. This file can be used to send a request to the index.php hosted on another server. To use send_file.php, copy it to your home directory. You can then beautify foo.php by executing ```php ~/send_file.php foo.php``` if all goes according to plan then you will end up with a ```foo.php.beautiful``` in your current directory. You can then perform a vimdiff of foo.php and foo.php.beautiful, and copy the beautiful version over the existing version if everything looks good.

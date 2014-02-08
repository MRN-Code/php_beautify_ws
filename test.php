<?php
/*This is a test file to prove that the webservice is working correctly*/
// php_beautifier->setBeautify(false);
$a=array('1',array('1.1','1.2','1.3'));
// php_beautifier->setBeautify(true);
$a=array('1',array('1.1','1.2','1.3'));
// php_beautifier->setBeautify(false);
function ugly_function($item1,$item2){
echo $item1;
	foreach($item_n in $item2) {
	echo $item_n ;
	}
	if($item1) print'lksjf';
print "the longest"." ". "string ever".
	" foo bar baz ";
		}
// php_beautifier->setBeautify(true);
function ugly_function($item1,$item2){
echo $item1;
	foreach($item_n in $item2) {
	echo $item_n ;
	}
	if($item1) print'lksjf';
print "the longest"." ". "string ever".
	" foo bar baz ";
		}
?>
this text should
	be
		ignored
because it is outside of php brackets
<?php
this text should
        not be
                ignored
because it is inside of php brackets
?>

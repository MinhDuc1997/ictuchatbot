<?php
mysqli_report(MYSQLI_REPORT_STRICT);

try{
	$con = new mysqli('localhost','techitvn_minhduc','Minhduc1998','techitvn_ictuchatbot');	
}catch(Exception $e){
	echo 'Cannot connect to database';
}
?>

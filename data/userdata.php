<?php
function a(){
	$a = array();
	array_push($a, "aaa");
	array_push($a, "bbb");

	$random_keys=array_rand($a,1);
	return $a[$random_keys];
}
echo a();
?>
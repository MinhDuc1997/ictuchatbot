<?php
function send_request($url,$jsonData,$message){
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

	    if(!empty($message)){
	        $result = curl_exec($ch); 
	    }
	}
?>
<?php
function return_text($sender, $text){
	$jsonData = '{
		"messaging_type": "RESPONSE",
	    "recipient":{
	        "id": "' . $sender . '"
	        },
	    "message":{
	        "text": "' . $text .'"
	        }
	    }';
	return $jsonData; 
}
?>
<?php
function return_image($sender, $url_image){
	$jsonData = '{
		"messaging_type": "RESPONSE",
	    "recipient":{
	        "id": "' . $sender . '"
	        },
	    "message":{
	        "attachment":{
			    "type":"image", 
			    "payload":{
			    	"url": "'.$url_image.'", 
			        "is_reusable":true
			    	}
			    }
	        }
	    }';
	return $jsonData; 
}
?>
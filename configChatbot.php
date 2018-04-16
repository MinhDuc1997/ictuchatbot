<?php
header('Content-Type: text/html; charset=utf-8');

if(isset($_GET['hub_verify_token'])){ 
    if($_GET['hub_verify_token'] === '123456789'){
    	echo $_GET['hub_challenge'];
        return true;
    } 
    else{
    	echo 'Invalid Verify Token';
        return false;
    }
}
?>
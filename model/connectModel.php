<?php
class connect{
	public $connect;

	function __construct(){
	    if(!@require('configDB.php')){
	        require '../configDB.php';
		    $this->connect = $con;
	    }else{
	        require 'configDB.php';
		    $this->connect = $con;
	    }
	}

	function getConnect(){
		return $this->connect;
	}
}
?>
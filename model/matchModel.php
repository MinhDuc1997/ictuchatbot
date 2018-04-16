<?php
require 'connectModel.php';

class match{
	private $connect;

	function __construct(){
		$obj = new connect();
		$this->connect = $obj->getConnect();
	}

	function checkStatus($row){
		if(!empty($row)){
			return $row['status']; //0: Q&A. 1: matched. 2: really match
		}else{
			return "null";//empty value
		}
	}

	function matching($userid){
		$arr = array();//queue
		$sql = "SELECT * FROM userdata WHERE status = 2 and userid != '".$userid."'";
		$query = $this->connect->query($sql);
		while($row = $query->fetch_assoc()){
			array_push($arr, $row['userid']);
		}
		
		$random_keys = array_rand($arr, 1);
		return $arr[$random_keys];
	}

	function update($userid,$status,$matched){
		if($matched == 'null'){
			$sql = "UPDATE userdata SET status = ".$status.", matched = null WHERE userid='".$userid."'";
		}
		if($matched != 'null'){
			$sql = "UPDATE userdata SET status = ".$status.", matched = '".$matched."' WHERE userid = '".$userid."'";
		}
		$this->connect->query($sql);
	}

	function main($userid){
		$sql = "SELECT * FROM userdata WHERE userid='".$userid."'";
		$query = $this->connect->query($sql);
		$row = $query->fetch_assoc();

		if($row['userid'] == $userid){
			echo $row['userid'].'<br>';
			return $row;
		}else{
			$sql = "INSERT INTO userdata(userid,status) VALUES('".$userid."','0')";
			$query = $this->connect->query($sql);
			if($query){
			    $sql = "SELECT * FROM userdata WHERE userid='".$userid."'";
		        $query = $this->connect->query($sql);
		        $row = $query->fetch_assoc();
			    return $row;
			}
		}
		
	}
}
?>
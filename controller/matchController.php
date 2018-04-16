<?php
require_once 'model/matchModel.php';

class matchController{

	function start($userid){
		$obj = new match();
		return $obj->main($userid);//ID
	}

	function status($row){
		$obj = new match();
		return $obj->checkStatus($row);
	}

	function matched($userid,$matchedid){
		$obj = new match();
		$obj->update($userid,'1',$matchedid);
	}

	function matching_($userid){
		$obj = new match();
		return $obj->matching($userid);
	}

	function QA($userid){ //Question, Answer
		$obj = new match();
		$obj->update($userid,'0','null');
	}

	function reallyChat($userid){
		$obj = new match();
		$obj->update($userid,'2','null');
	}
}
?>
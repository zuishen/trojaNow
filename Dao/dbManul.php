<?php
	require_once 'dbConnect.php';
	class dbManul {
		private $mysqli;
		function __construct($mysqli) {
			$this->mysqli = $mysqli;
		}
		
		function db_query($str) {
			$sql = $str;
			$result = $this->mysqli->query($str);
			if($result) return $result;
			else return 0;
		}
		
		function db_insert($str) {
			$sql = $str;
			if($this->mysqli->query($sql))	return true;
			else return false;			
		}
		
		function db_delete($str) {
			$sql = $str;
			if($this->mysqli->query($sql))	return true;
			else return false;			
		}
		
		function update($str) {
			$sql = $str;
			if($this->mysqli->query($sql))	return true;
			else return false;			
		}
		
		
	}
	
	
	
	
	
	
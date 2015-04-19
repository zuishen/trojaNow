<?php

	class createdb {
		private $addr;
		private $usrname;
		private $usrpwd;
		private $db;
		private $mysqli;

		function __construct($dbAddr, $dbName, $dbPWD, $database) {
			$this->addr = $dbAddr;
			$this->usrname = $dbName;
			$this->usrpwd = $dbPWD;
			$this->db = $database;
			$this->mysqli = null;
		}
		
		
		function db_connect() {
			$this->mysqli =new mysqli();
			$this->mysqli->connect($this->addr, $this->usrname, $this->usrpwd, $this->db);
			return $this->mysqli;
		}
		
		function db_close() {
			$this->mysqli->close();
		}
	}
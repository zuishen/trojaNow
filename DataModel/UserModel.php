<?php
	class UserModel {
		private $usr_account;
		private $usr_pwd;
		private $usr_name;
		private $usr_email;
		private $usr_pic = null;
		
		
		function __construct($account, $pwd, $name, $email, $pic) {
			$this->usr_account = $account;
			$this->usr_pwd = $pwd;
			$this->usr_name = $name;
			$this->usr_email = $email;
			$this->usr_pic = $pic;	
		}
		
		function db_insert() {
			if($this->usr_account && $this->usr_pwd && $this->usr_name && $this->usr_email) {
				
				$str = "INSERT INTO users VALUES ('".$this->usr_account."',
												'".$this->usr_pwd."',
												'".$this->usr_name."',
												'".$this->usr_email."',
												'".$this->usr_pic."')";
				
				$db = new createdb(SQL_ADDR, USERNAME, USERPASSWD, DATABASE);
				$mysqli = $db->db_connect();
				$manul = new dbManul($mysqli);
				if($manul->db_insert($str))
				{
					$db->db_close();
					return true;
				}
				else {
					$db->db_close();
					return false;
				}
			} else {
				echo json_encode(array("status" => "false", "message" => "member var is null", "result" => ""));
			}
		}
		
		
		function db_auth() {
			if($this->usr_account && $this->usr_pwd) {
		
				$sql = "SELECT * 
						FROM users 
						WHERE usr_account = '".$this->usr_account."' 
								AND usr_pwd = '".$this->usr_pwd."'";
									//			'".$this->usr_name."',
									//			'".$this->usr_email."',
									//			'".$this->usr_pic."')";
		
				$db = new createdb(SQL_ADDR, USERNAME, USERPASSWD, DATABASE);
				$mysqli = $db->db_connect();
				$manul = new dbManul($mysqli);
				$result = $manul->db_query($sql);
				if($result)
				{
					if($result->num_rows > 0){
						$row = $result->fetch_array();
						$this->SetUsrName($row[2]);
						$this->SetUsrEmail($row[3]);
						$this->SetUsrPic($row[4]);
						return true;
					} else {
						$db->db_close();
						return false;
					}
				}
				else {
					$this->db_close();
					return false;
				}
			} else {
				echo json_encode(array("status" => "false", "message" => "member var is null", "result" => ""));
			}
		}
		
		function GetUsrAcount () {
			return $this->usr_account;
		}		
		
		function SetUsrAccount($account) {
			$this->usr_account = $account;
		}
		
		
		function GetUsrPwd () {
			return $this->usr_pwd;
		}
		
		function SetUsrPwd($pwd) {
			$this->usr_pwd = $pwd;
		}
		
		
		function GetUsrName () {
			return $this->usr_name;
		}
		
		function SetUsrName($name) {
			$this->usr_name = $name;
		}		
		
		
		function GetUsrEmail () {
			return $this->usr_email;
		}
		
		function SetUsrEmail($email) {
			$this->usr_email = $email;
		}
		
		
		function GetUsrPic () {
			return $this->usr_pic;
		}
		function SetUsrPic($pic) {
			$this->usr_pic = $pic;
		}
		
	}
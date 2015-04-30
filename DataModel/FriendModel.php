<?php

	class FriendModel {
		
		private $follower = array();
		private $followee = array();
		private $name = array();
		private $account = array();
		
		function __construct($user_account, $friend_account) {
		 	$follower[0] = $user_account;
		 	$followee[0] = $friend_account;
		 	$name[0] = null;
		}
		
		function list_friends($user_account, $follow_fan_all) {
			$sql = null;


			switch ($follow_fan_all) {
				case "follow": {$sql = "SELECT followee, usr_name 
											  FROM (SELECT * 
													FROM friend_lists 
													WHERE follower = '".$user_account."') F 
											  INNER JOIN users U 
											  ON F.followee = U.usr_account";   //list your follows
								break;}		
				case "fan": {$sql = "SELECT follower, usr_name
										 FROM (SELECT *
											   FROM friend_lists
											   WHERE followee = '".$user_account."') F
										 INNER JOIN users U
										 ON F.follower = U.usr_account";   //list your followee
							break;}			
/*				case "all": {$sql = "SELECT follower, usr_name
										FROM (SELECT *
												FROM friend_lists
												WHERE followee = '".$user_account."') F
										INNER JOIN users U
										ON F.follower = U.user_account";   //list your followee 
							break:} */
				default: return -3;
			}
			$db = new createdb(SQL_ADDR, USERNAME, USERPASSWD, DATABASE);
			$mysqli = $db->db_connect();
			$manul = new dbManul($mysqli);
			$result = $manul->db_query($sql);
			if($result)
			{
				if($result->num_rows > 0){
					$j = 0;
					while($row = $result->fetch_array()){
						$this->setFriendAccount( $j, $row[0] );
						$this->setFriendName( $j, $row[1] );

						$j++;
					}
					// use j
			
					return $j;
				} else {
					$db->db_close();
					return -1;
				}
			} else {
				$db->db_close();
				return -2;
			}
			
		}
		
		function add_delete_friend($add_delete, $user_account, $friend_account) {
			$sql = null;
			$return = 0;

			//			$sql_all = null;
			switch ($add_delete) {
				case "add": {
					$sql = "INSERT INTO friend_lists VALUES('".$friend_account."', '".$user_account."')";   //list your follows
					$db = new createdb(SQL_ADDR, USERNAME, USERPASSWD, DATABASE);
					$mysqli = $db->db_connect();
					$manul = new dbManul($mysqli);
					if($manul->db_query("SELECT * FROM friend_lists WHERE follower = '".$user_account."' AND followee = '".$friend_account."'")->num_rows < 1) {
					if($manul->db_insert($sql)) $return = 1;	
					}
					else $return = 2;
					$db->db_close();
					break;
				}
				case "delete": {
					$sql = "DELETE FROM friend_lists WHERE follower = '".$user_account."' AND followee = '".$friend_account."'";   //list your followee
					$db = new createdb(SQL_ADDR, USERNAME, USERPASSWD, DATABASE);
					$mysqli = $db->db_connect();
					$manul = new dbManul($mysqli);
					if($manul->db_query("SELECT * FROM friend_lists WHERE follower = '".$user_account."' AND followee = '".$friend_account."'")->num_rows > 0) {
						if($manul->db_delete($sql)) $return = 1;
					}
					else $return = 2;
					$db->db_close();
					break;}

				default: return $return;
				
			}
			return $return;
			
		}
		
		function count_friend($follow_fan_all, $user_account) {
			$sql = null;
			
			
			switch ($follow_fan_all) {
				case "follow": {$sql = "SELECT count(*) AS num FROM friend_lists WHERE followee = '".$user_account."'";   //list your follows
				break;}
				case "fan": {$sql = "SELECT count(*) AS num FROM friend_lists WHERE follower = '".$user_account."'";   //list your followee
				break;}
				/*				case "all": {$sql = "SELECT follower, usr_name
				 FROM (SELECT *
				 FROM friend_lists
				 WHERE followee = '".$user_account."') F
				 INNER JOIN users U
				 ON F.follower = U.user_account";   //list your followee
				 break:} */
				default: return -3;
			}
			$db = new createdb(SQL_ADDR, USERNAME, USERPASSWD, DATABASE);
			$mysqli = $db->db_connect();
			$manul = new dbManul($mysqli);
			$result = $manul->db_query($sql);
			if($result)
			{
				$j = 0;
					// use j
				if($result->num_rows > 0){
					
					$row = $result->fetch_array();
					$j = $row[0];
					}
						
				return $j;			
			} else {
				$db->db_close();
				return -2;
			}
		}
		
		
		
		function getFriendFollower($i) {
			return $this->follower[$i];
		}
		
		function setFriendFollower($i, $follower) {
			$this->follower[$i] = $follower;
		}
		
		function getFriendFollowee($i) {
			return $this->followee[$i];
		}
		
		function setFriendFollowee($i, $followee) {
			$this->followee[$i] = $followee;
		}
		
		function getFriendName($i) {
			return $this->name[$i];
		}
		
		function setFriendName($i, $name) {
			$this->name[$i] = $name;
		}
		
		function getFriendAccount($i) {
			return $this->account[$i];
		}
		
		function setFriendAccount($i, $account) {
			$this->account[$i] = $account;
		}
	}
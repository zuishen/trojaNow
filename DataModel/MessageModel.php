<?php

	class MessageModel {
		
		private $msg_id = array();
		private $msg_from = array();
		private $msg_to = array();
		private $msg_cont = array();
		private $msg_time = array();
		private $msg_status = array();
		private $msg_unread_num = array();
		private $msg_uname = array();
		
		
		function __construct($from, $to, $cont, $time) {
			$this->msg_id[0] = null;
			$this->msg_from[0] = $from;
			$this->msg_to[0] = $to;
			$this->msg_cont[0] = $cont;
			$this->msg_time[0] = $time;
			$this->msg_status[0] = 0;
			$this->msg_unread_num[0] = 0;
		}
		
		function db_list_view($usr_account) {
			$sql = "SELECT msg_id, msg_from, msg_to, msg_cont, msg_time, msg_status, count(msg_status = 0) as msg_unread_num
					FROM (SELECT *
							FROM messages
							WHERE msg_from = '".$usr_account."' OR msg_to = '".$usr_account."'
						    ORDER BY msg_time DESC) T
					GROUP BY least(T.msg_from, T.msg_to), greatest(T.msg_from, T.msg_to)
					ORDER BY msg_time DESC";
			
		   $tmp_name = null;
			
			
			$db = new createdb(SQL_ADDR, USERNAME, USERPASSWD, DATABASE);
			$mysqli = $db->db_connect();
			$manul = new dbManul($mysqli);
			$result = $manul->db_query($sql);
			if($result)
			{
				if($result->num_rows > 0){
					$j = 0;
					while($row = $result->fetch_array()){
						$this->setMssageId( $j, $row[0] );
						$this->setMssageFrom( $j, $row[1] );
						$this->setMssageTo( $j, $row[2] );
						$this->setMssageCont( $j, $row[3] );
						$this->setMssageTime( $j, $row[4] );
						$this->setMssageStatus( $j, $row[5] );
						$this->setMssageUnreadnum( $j, $row[6] );
						
						// friend's name
						if($usr_account == $this->getMessageFrom($j)) {
							$tmp_name = $this->getMessageTo($j);
							$this->setMssageUnreadnum($j, 0);
						}
						else if ($usr_account == $this->getMessageTo($j)) 
								$tmp_name = $this->getMessageFrom($j); 
						
						$sql_name = "SELECT usr_name FROM users WHERE usr_account='".$tmp_name."'";
						$tmp_result = $manul->db_query($sql_name);
						$tmp_row = $tmp_result->fetch_array();
//						$tmp_name = $tmp_row[0];
						$this->setMssageUname($j, $tmp_row[0]);
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
		
		
		function db_view($usr_account, $friend_account) {
			
			$sql = "SELECT * 
					FROM messages 
					WHERE (msg_from = '".$usr_account."' AND msg_to = '".$friend_account."') 
							OR (msg_from = '".$friend_account."' AND msg_to = '".$usr_account."')
					ORDER BY msg_time DESC";
				
				
			$db = new createdb(SQL_ADDR, USERNAME, USERPASSWD, DATABASE);
			$mysqli = $db->db_connect();
			$manul = new dbManul($mysqli);
			$result = $manul->db_query($sql);
			if($result)
			{
				if($result->num_rows > 0){
					$j = 0;
					while($row = $result->fetch_array()){
						$this->setMssageId( $j, $row[0] );
						$this->setMssageFrom( $j, $row[1] );
						$this->setMssageTo( $j, $row[2] );
						$this->setMssageCont( $j, $row[3] );
						$this->setMssageTime( $j, $row[4] );

			
						// change status
						if ($usr_account == $this->getMessageTo($j)) {
			
							$sql_name = "UPDATE messages SET msg_status = 1 WHERE msg_id = ".$this->getMessageId($j); 
							$tmp_result = $manul->db_query($sql_name);
						}
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
		
		
		function db_insert() {
			
			if($this->msg_from[0] && $this->msg_to[0] && $this->msg_cont[0] && $this->msg_time[0]) {
					
				$sql = "INSERT INTO messages VALUES ('".$this->msg_id[0]."',
												'".$this->msg_from[0]."',
												'".$this->msg_to[0]."',
												'".$this->msg_cont[0]."',
												'".$this->msg_time[0]."',
												'".$this->msg_status[0]."')";
					
				$db = new createdb(SQL_ADDR, USERNAME, USERPASSWD, DATABASE);
				$mysqli = $db->db_connect();
				$manul = new dbManul($mysqli);
				if($manul->db_insert($sql))
				{
					$db->db_close();
					return true;
				}
				else {
					$db->db_close();
					return false;
				}
			} else {
				echo json_encode(array("status" => "false", "message" => "post parameters are null"));
				exit();
			}
			
		}
		
		function db_check($usr_account) {
			
			$sql = "SELECT count(*) AS new_num FROM messages WHERE msg_to = '".$usr_account."' AND msg_status = 0";
			
			$db = new createdb(SQL_ADDR, USERNAME, USERPASSWD, DATABASE);
			$mysqli = $db->db_connect();
			$manul = new dbManul($mysqli);
			$result = $manul->db_query($sql);
			if($result)
			{
				if($result->num_rows > 0){
					$row = $result->fetch_array();
					$new_num = $row[0];								
					return $new_num;
				} else {
					$db->db_close();
					return -1;
				}
			} else {
				$db->db_close();
				return -2;
			}
		}
		
		
		
		function getMessageId($i) {
			return $this->msg_id[$i];
		}
		
		function setMssageId($i, $id) {
			$this->msg_id[$i] = $id;
		}
		
		
		function getMessageFrom($i) {
			return $this->msg_from[$i];
		}
		
		function setMssageFrom($i, $from) {
			$this->msg_from[$i] = $from;	
		}
		
		
		function getMessageTo($i) {
			return $this->msg_to[$i];	
		}
		
		function setMssageTo($i, $to) {
			$this->msg_to[$i] = $to;	
		}
		
		
		function getMessageCont($i) {
			return $this->msg_cont[$i];	
		}
		
		function setMssageCont($i, $cont) {
			$this->msg_cont[$i] = $cont;	
		}
		
		
		function getMessageTime($i) {
			return $this->msg_time[$i];	
		}
		
		function setMssageTime($i, $time) {
			$this->msg_time[$i] = $time;	
		}
		
		
		function getMessageStatus($i) {
			return $this->msg_status[$i];	
		}
		
		function setMssageStatus($i, $status) {
			$this->msg_status[$i] = $status;	
		}
		
		
		function getMessageUnreadnum($i) {
			return $this->msg_unread_num[$i];	
		}
		
		function setMssageUnreadnum($i, $num) {
			$this->msg_unread_num[$i] = $num;
		}
		
		function getMessageUname($i) {
			return $this->msg_uname[$i];
		}
		
		function setMssageUname($i, $name) {
			$this->msg_uname[$i] = $name;
		}
		

	}
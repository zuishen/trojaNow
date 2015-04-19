<?php

	class CommentModel {
		
		private $comt_id = array();
		private $comt_mid = array();
		private $comt_uaccount = array();
		private $comt_content = array();
		private $comt_time = array();
		private $comt_uname = array();
		
		function __construct ($mid, $uaccount, $content, $time) {

			$this->comt_id[0] = null;
			$this->comt_mid[0] = $mid;
			$this->comt_uaccount[0] = $uaccount;
			$this->comt_content[0] = $content;
			$this->comt_time[0] = $time;
			$this->comt_usrname[0] = null;

		}
		
		function db_insert() {
//			echo "$this->comt_content[0],  $this->comt_time[0]";
			if($this->comt_mid[0] && $this->comt_uaccount[0] && $this->comt_content[0] && $this->comt_time[0]) {
					
				$sql = "INSERT INTO comments VALUES ('".$this->comt_id[0]."',
												'".$this->comt_mid[0]."',
												'".$this->comt_uaccount[0]."',
												'".$this->comt_content[0]."',
												'".$this->comt_time[0]."')";
					
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
				echo json_encode(array("status" => "false", "message" => "member var is null"));
				exit();
			}
		}
		
		
		function sql_make($mid) {
			$sql = null;
			$sql = "SELECT  comt_id, comt_mid, comt_uaccount, comt_content, comt_time, usr_name
					FROM  ( SELECT * FROM comments WHERE comt_mid =".$mid."  ORDER BY comt_time DESC ) A
					INNER JOIN users B
					ON B.usr_account = A.comt_uaccount";
			return $sql;
		}
		
		
		
		function db_view($mid) {
			$sql = $this->sql_make($mid);
				
				
			$db = new createdb(SQL_ADDR, USERNAME, USERPASSWD, DATABASE);
			$mysqli = $db->db_connect();
			$manul = new dbManul($mysqli);
			$result = $manul->db_query($sql);
			if($result)
			{
				if($result->num_rows > 0){
					$j = 0;
					while($row = $result->fetch_array()){
						$this->setCommentId( $j, $row[0] );
						$this->setCommentMid( $j, $row[1] );
						$this->setCommentUaccount( $j, $row[2] );
						$this->setCommentContent( $j, $row[3] );
						$this->setCommentTime( $j, $row[4] );
						$this->setCommentUname( $j, $row[5] );
						
						// the number of comments of each blog
						//					$sql_blog_num = "SELECT count(*) FROM comments WHERE comt_mid='".$this->getBlogId($j)."'";
						//					$result_blog_num = $manul->db_query($sql_blog_num);
						//					$row_blog_num = $result_blog_num->fetch_array();
						//					$this->setBlogNum($j, $row_blog_num);
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
		
		function getCommentId($i) {
			return $this->comt_id[$i];
		}
		
		function setCommentId($i, $id) {
			$this->comt_id[$i] = $id;
		}
		
		function getCommentMid($i) {
			return $this->comt_mid[$i];
		}
		
		function setCommentMid($i, $mid) {
			$this->comt_mid[$i] = $mid;
		}
		
		function getCommentUaccount($i) {
			return $this->comt_uaccount[$i];
		}
		
		function setCommentUaccount($i, $uaccount) {
			$this->comt_uaccount[$i] = $uaccount;
		}
		
		function getCommentContent($i) {
			return $this->comt_content[$i];
		}
		
		function setCommentContent($i, $content) {
			$this->comt_content[$i] = $content;
		}
		
		function getCommentTime($i) {
			return $this->comt_time[$i];
		}
		
		function setCommentTime($i, $time) {
			$this->comt_time[$i] = $time;
		}
		
		function getCommentUname($i) {
			return $this->comt_uname[$i];
		}
		
		function setCommentUname($i, $uname) {
			$this->comt_uname[$i] = $uname;
		}
	}
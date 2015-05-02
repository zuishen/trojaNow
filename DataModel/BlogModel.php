<?php
	class BlogModel {
		private $mb_id = array();
		private $mb_uaccount = array();
		private $mb_content = array();
		private $mb_time = array();
		private $mb_location = array();
		private $mb_degree = array();
		private $mb_weather = array();
		private $mb_level = array();
		private $mb_x = array();
		private $mb_y = array();
		private $mb_uname = array();
		private $mb_num = array();
		public  $list_num;
		
		function __construct($list_num, $uaccount, $content, $time, $location, $degree, $weather, $level, $x, $y) {
			$this->list_num = $list_num;
			for($i = 0; $i < $list_num; $i++) {
				$this->mb_id[$i] = null;
				$this->mb_uaccount[$i] = $uaccount;
				$this->mb_content[$i] = $content;
				$this->mb_time[$i] = $time;
				$this->mb_location[$i] = $location;
				$this->mb_degree[$i] = $degree;
				$this->mb_weather[$i] = $weather;
				$this->mb_level[$i] = $level;
				$this->mb_num[$i] = 0;
				$this->mb_x[$i] = $x;
				$this->mb_y[$i] = $y;
			}
		}
		
		function db_insert() {
				if($this->mb_uaccount[0] && $this->mb_content[0] && $this->mb_time[0] && $this->mb_level[0]) {
			
					$str = "INSERT INTO micro_blogs VALUES ('".$this->mb_id[0]."',
												'".$this->mb_uaccount[0]."',
												'".$this->mb_content[0]."',
												'".$this->mb_time[0]."',
												'".$this->mb_location[0]."',
												'".$this->mb_degree[0]."',
												'".$this->mb_weather[0]."',
												'".$this->mb_level[0]."',
												'".$this->mb_x[0]."',
												'".$this->mb_y[0]."')";
			
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
					echo json_encode(array("status" => "false", "message" => "member var is null"));
				}
			}
			
			
		function sql_make($last_id, $list_num, $level, $user_x = 0, $user_y = 0) {
			$sql = null;
			switch ($level) {
				case "1": {
					if($last_id == 0)
						$sql = "SELECT  mb_id, mb_uaccount, mb_content, mb_time, mb_location, mb_degree, mb_weather, mb_level, mb_x, mb_y, usr_name
								FROM  ( SELECT * FROM micro_blogs ORDER BY mb_time DESC LIMIT ".$list_num." ) A
								INNER JOIN users B
								ON B.usr_account = A.mb_uaccount";
					else
						$sql = "SELECT  mb_id, mb_uaccount, mb_content, mb_time, mb_location, mb_degree, mb_weather, mb_level, mb_x, mb_y, usr_name
								FROM  (SELECT * FROM micro_blogs WHERE mb_id < ".$last_id." ORDER BY mb_time DESC LIMIT ".$list_num.") A
								INNER JOIN users B
								ON B.usr_account = A.mb_uaccount";
					break;
				}
				case "2": {
					if($last_id == 0)
						$sql = "SELECT  mb_id, mb_uaccount, mb_content, mb_time, mb_location, mb_degree, mb_weather, mb_level, mb_x, mb_y, usr_name
								FROM  (SELECT *
										FROM micro_blogs
										WHERE (mb_x - ".$user_x.")*(mb_x - ".$user_x.") + (mb_y - ".$user_y.")*(mb_y - ".$user_y.") < ".SQUER_DISTANCE."
										AND mb_level = 2
										ORDER BY mb_time DESC
										LIMIT ".$list_num.") A
								INNER JOIN users B
								ON B.usr_account = A.mb_uaccount";
					else
						$sql =  "SELECT  mb_id, mb_uaccount, mb_content, mb_time, mb_location, mb_degree, mb_weather, mb_level, mb_x, mb_y, usr_name
								FROM  (SELECT *
										FROM micro_blogs
										WHERE (mb_x - ".$user_x.")*(mb_x - ".$user_x.") + (mb_y - ".$user_y.")*(mb_y - ".$user_y.") < ".SQUER_DISTANCE."
											AND mb_level = 2 AND mb_id < ".$last_id."
										ORDER BY mb_time DESC
										LIMIT ".$list_num.") A
								INNER JOIN users B
								ON B.usr_account = A.mb_uaccount";
					break;
				}
				default: return -3;
					
			}
			return $sql;
		}
			

		
		function db_view($last_id, $list_num, $level, $user_x = 0, $user_y = 0) {
			$sql = $this->sql_make($last_id, $list_num, $level, $user_x, $user_y);
			
			if ($sql == -3) return $sql;
			
			$db = new createdb(SQL_ADDR, USERNAME, USERPASSWD, DATABASE);
			$mysqli = $db->db_connect();
			$manul = new dbManul($mysqli);
			$result = $manul->db_query($sql);
			if($result)
			{
				if($result->num_rows > 0){
					$j = 0;
					while($row = $result->fetch_array()){
					$this->setBlogId( $j, $row[0] );
					$this->setBlogUaccount( $j, $row[1] );
					$this->setBlogContent( $j, $row[2] );
					$this->setBlogTime( $j, $row[3] );
					$this->setBlogLocation( $j, $row[4] );
					$this->setBlogDegree( $j, $row[5] );
					$this->setBlogWeather( $j, $row[6] );
					$this->setBlogLevel( $j, $row[7] );
					$this->setBlogX( $j, $row[8] );
					$this->setBlogY($j, $row[9]);
					$this->setBlogUname($j, $row[10]);
// the number of comments of each blog 					
					$sql_blog_num = "SELECT count(*) FROM comments WHERE comt_mid='".$this->getBlogId($j)."'";
					$result_blog_num = $manul->db_query($sql_blog_num);
					$row_blog_num = $result_blog_num->fetch_array();
					$this->setBlogNum($j, $row_blog_num[0]);
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

		
		
		function getBlogId($i = 0) {
			return $this->mb_id[$i];
		}
		
		function setBlogId($i = 0, $id) {
			$this->mb_id[$i] = $id;
		}
		
		function getBlogUaccount($i = 0) {
			return $this->mb_uaccount[$i];
		}
		
		function setBlogUaccount($i = 0, $uaccount) {
			$this->mb_uaccount[$i] = $uaccount;
		}
		
		function getBlogContent($i = 0) {
			return $this->mb_content[$i];
		}
		
		function setBlogContent($i = 0, $content) {
			$this->mb_content[$i] = $content;
		}
		
		function getBlogTime($i = 0) {
			return $this->mb_time[$i];
		}
		
		function setBlogTime($i = 0, $time) {
			$this->mb_time[$i] = $time;
		}
		
		function getBlogLocation($i = 0) {
			return $this->mb_location[$i];
		}
		
		function setBlogLocation($i = 0, $location) {
			$this->mb_location[$i] = $location;
		}
		
		function getBlogDegree($i = 0) {
			return $this->mb_degree[$i];
		}
		
		function setBlogDegree($i = 0, $degree) {
			$this->mb_degree[$i] = $degree;
		}
		
		function getBlogWeather($i = 0) {
			return $this->mb_weather[$i];
		}
		
		function setBlogWeather($i = 0, $weather) {
			$this->mb_weather[$i] = $weather;
		}
		
		function getBlogLevel($i = 0) {
			return $this->mb_level[$i];
		}
		
		function setBlogLevel($i = 0, $level) {
			$this->mb_level[$i] = $level;
		}
		
		function getBlogNum($i = 0) {
			return $this->mb_num[$i];
		}
		
		function setBlogNum($i = 0, $num) {
			$this->mb_num[$i] = $num;
		}
		
		function getBlogX($i = 0) {
			return $this->mb_x[$i];
		}
		
		function setBlogX($i = 0, $x) {
			$this->mb_x[$i] = $x;
		}
		
		function getBlogY($i = 0) {
			return $this->mb_y[$i];
		}
		
		function setBlogY($i = 0, $y) {
			$this->mb_y[$i] = $y;
		}
		
		function getBlogUname($i = 0) {
			return $this->mb_uname[$i];
		}
		
		function setBlogUname($i = 0, $uname) {
			$this->mb_uname[$i] = $uname;
		}
	}
<?php

	class blogServer {
		protected $action;
		
		function __construct($act) {
			$this->action = $act;
		}
		
		function run() {
			switch ($this->action)
			{
				case 'list': {$this->ListAction(); break;}
				case 'post': {$this->PostAction(); break;}
				default: echo json_encode(array("status" => "false",
				"message" => "invalid action",
				"result" => ""));;
			}
		}
		
		
		function ListAction() {
			$last_id = 0;
			$list_num = 0;
			$level = 0;
			if(isset($_POST["last_id"]) && isset($_POST["list_num"]) && isset($_POST["level"])) {
				$last_id = $_POST["last_id"];
				$list_num = $_POST["list_num"];
				$level = $_POST["level"];
			} else {
				echo json_encode(array("status" => "false", "message" => "invalid post parameters", "result" => ""));
				exit();
			}
			$blog = new BlogModel($list_num, null, null, null, null, null, null, null, null, null);
			$j = $blog->db_view($last_id, $list_num, $level);
			if($j >= 0) {
			//transfer to json
				$arr = array();
				for($i = 0; $i < $j; $i++) {
					$arr[$i] = array("mb_id"       => "".$blog->getBlogId($i)."",
									 "mb_uaccount" => "".$blog->getBlogUaccount($i)."",
									 "mb_content"  => "".$blog->getBlogContent($i)."",
									 "mb_time"     => "".$blog->getBlogTime($i)."",
									 "mb_location" => "".$blog->getBlogLocation($i)."",
									 "mb_degree"   => "".$blog->getBlogDegree($i)."",
									 "mb_weather"  => "".$blog->getBlogWeather($i)."",
									 "mb_level"    => "".$blog->getBlogLevel($i)."",
									 "mb_x"        => "".$blog->getBlogX($i)."",
									 "mb_y"		   => "".$blog->getBlogY($i)."",
									 "mb_uname"    => "".$blog->getBlogUname($i).""  
								// , "mb_num" 	   => "".$blog->getBlogNum($i).""
					);
				}
				$res = array("status" => "true",
							 "message" => "got blogs successfully!",
							 "result"  => $arr);
				$jstr = json_encode($res);
				echo $jstr;
			} else {
				switch ($j) {
				case -1: echo json_encode(array("status" => "false", "message" => "no blog in database!", "result" => "")); break;
				case -2: echo json_encode(array("status" => "false", "message" => "wrong result!", "result" => "")); break;
				case -3: echo json_encode(array("status" => "false", "message" => "undefined code!", "result" => "")); break;
				default: echo json_encode(array("status" => "false", "message" => "unknow error!", "result" => ""));
				}
			}
		}
		

		
		function PostAction() {
			if(isset($_POST["mb_uaccount"]) && isset($_POST["mb_content"]) && isset($_POST["mb_time"]) && isset($_POST["mb_level"])) {
				$uaccount = $_POST["mb_uaccount"];
				$content = $_POST["mb_content"];
				$time = $_POST["mb_time"];
				$level = $_POST["mb_level"];
				$x = 0.0;
				$y = 0.0;
				
				if(isset($_POST["list_num"])) $list_num = $_POST["list_num"];
				else $list_num = BLOGLISTNUM;
				
				if(isset($_POST["mb_location"])) $location = $_POST["mb_location"];
				else $location = null;
				
				if(isset($_POST["mb_degree"])) $degree = $_POST["mb_degree"];
				else $degree = null;
				
				if(isset($_POST["mb_weather"])) $weather = $_POST["mb_weather"];
				else $weather = null;
				
				if($level == 2) {
					if(isset($_POST["mb_x"]) && isset($_POST["mb_y"])) {
						$x = 0.0 + $_POST["mb_x"];
						$y = 0.0 + $_POST["mb_y"];
					} else {
						echo json_encode(array("status" => "false", "message" => "Invalid coordinates!"));
						exit();
					}
				} 
				
				$blog = new BlogModel($list_num, $uaccount, $content, $time, $location, $degree, $weather, $level, $x, $y);
				if($blog->db_insert())
				{
					$res = array("status" => "true",
							"message" => "post successfully!",
							"result" => "");
					$jstr = json_encode($res);
					echo $jstr;
				}
			}
			else echo json_encode(array("status" => "false",
					"message" => "invalid post parameters",
					"result" => ""));
		}
		
	}

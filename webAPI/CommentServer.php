<?php
	
	class CommentServer {
		
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

			$mid = null;
			
			if(isset($_POST["mb_id"])) {
				$mid = $_POST["mb_id"];
			} else {
				echo json_encode(array("status" => "false", "message" => "invalid post parameters", "result" => ""));
				exit();
			}
			$comment = new CommentModel(null, null, null, null, null);
			$j = $comment->db_view($mid);
			if($j >= 0) {
				//transfer to json
				$arr = array();
				for($i = 0; $i < $j; $i++) {
					$arr[$i] = array("comt_id"      => "".$comment->getCommentId($i)."",
									"comt_mid" 		=> "".$comment->getCommentMid($i)."",
									"comt_uaccount" => "".$comment->getCommentUaccount($i)."",
									"comt_content"  => "".$comment->getCommentContent($i)."",
									"comt_time" 	=> "".$comment->getCommentTime($i)."",
									"comt_uname"    => "".$comment->getCommentUname($i).""

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
					case -1: echo json_encode(array("status" => "false", "message" => "no comment in database!", "result" => "")); break;
					case -2: echo json_encode(array("status" => "false", "message" => "wrong result!", "result" => "")); break;
					case -3: echo json_encode(array("status" => "false", "message" => "undefined code!", "result" => "")); break;
					default: echo json_encode(array("status" => "false", "message" => "unknow error!", "result" => ""));
				}
			
		}
	}
		
		
		
		function PostAction() {
			if(isset($_POST["comt_mid"]) && isset($_POST["comt_uaccount"]) && isset($_POST["comt_content"]) && isset($_POST["comt_time"])) {
				$mid = $_POST["comt_mid"];
				$uaccount = $_POST["comt_uaccount"];
				$content = $_POST["comt_content"];
				$time = $_POST["comt_time"];
	
		
				$comment = new CommentModel($mid, $uaccount, $content, $time);
				if($comment->db_insert())
				{
					$res = array("status" => "true",
							"message" => "post successfully!",
							"result" => "");
					$jstr = json_encode($res);
					echo $jstr;
				} else {
					echo json_encode(array("status" => "false",
							"message" => "post unsuccessfully!",
							"result" => ""));
				}
			}
			else echo json_encode(array("status" => "false",
					"message" => "invalid post parameters",
					"result" => ""));
		}
		
	}

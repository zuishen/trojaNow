<?php

	class MessageServer {
		
		protected $action;
		
		function __construct($act) {
			$this->action = $act;
		} 
		
		function run() {
			switch ($this->action)
			{
				case 'list': {$this->ListAction(); break;}
				case 'messages': {$this->MessagesAction(); break;}
				case 'send':	{$this->SendAction(); break;}
				case 'update':	{$this->UpdateAction(); break;}
				default: echo json_encode(array("status" => "false",
				"message" => "invalid action",
				"result" => ""));;
			}
		}
		
		function ListAction() {
			$usr_account = null;
				
			if(isset($_POST["user_account"])) {
				$usr_account = $_POST["user_account"];
			} else {
				echo json_encode(array("status" => "false", "message" => "invalid post parameters", "result" => ""));
				exit();
			}
			$message = new messageModel(null, null, null, null);
			$j = $message->db_list_view($usr_account);
			if($j >= 0) {
				//transfer to json
				$arr = array();
				for($i = 0; $i < $j; $i++) {
					$arr[$i] = array("message_id"      => "".$message->getMessageId($i)."",
							"message_from" 		=> "".$message->getMessageFrom($i)."",
							"message_to" => "".$message->getMessageTo($i)."",
							"message_content"  => "".$message->getMessageCont($i)."",
							"message_time" 	=> "".$message->getMessageTime($i)."",
							"message_status"    => "".$message->getMessageStatus($i)."",
							"message_unread_num"    => "".$message->getMessageUnreadnum($i)."",
							"message_usr_name"    => "".$message->getMessageUname($i).""
			
					);
				}
				$res = array("status" => "true",
						"message" => "got messages successfully!",
						"result"  => $arr);
				$jstr = json_encode($res);
				echo $jstr;
			} else {
				switch ($j) {
					case -1: echo json_encode(array("status" => "false", "message" => "no message in database!", "result" => "")); break;
					case -2: echo json_encode(array("status" => "false", "message" => "wrong result!", "result" => "")); break;
					case -3: echo json_encode(array("status" => "false", "message" => "undefined code!", "result" => "")); break;
					default: echo json_encode(array("status" => "false", "message" => "unknow error!", "result" => ""));
				}
					
			}
		}
		
		
		function MessagesAction(){
			$usr_account = null;
			$friend_account = null;
			
			if(isset($_POST["user_account"]) && isset($_POST["friend_account"])) {
				$usr_account = $_POST["user_account"];
				$friend_account = $_POST["friend_account"];
			} else {
				echo json_encode(array("status" => "false", "message" => "invalid post parameters", "result" => ""));
				exit();
			}
			$message = new messageModel(null, null, null, null);
			$j = $message->db_view($usr_account, $friend_account);
			if($j >= 0) {
				//transfer to json
				$arr = array();
				for($i = 0; $i < $j; $i++) {
					$arr[$i] = array("message_id"      => "".$message->getMessageId($i)."",
							"message_from" 		=> "".$message->getMessageFrom($i)."",
							"message_to" => "".$message->getMessageTo($i)."",
							"message_content"  => "".$message->getMessageCont($i)."",
							"message_time" 	=> "".$message->getMessageTime($i).""		
					);
				}
				$res = array("status" => "true",
						"message" => "got messages successfully!",
						"result"  => $arr);
				$jstr = json_encode($res);
				echo $jstr;
			} else {
				switch ($j) {
					case -1: echo json_encode(array("status" => "false", "message" => "no message in database!", "result" => "")); break;
					case -2: echo json_encode(array("status" => "false", "message" => "wrong result!", "result" => "")); break;
					case -3: echo json_encode(array("status" => "false", "message" => "undefined code!", "result" => "")); break;
					default: echo json_encode(array("status" => "false", "message" => "unknow error!", "result" => ""));
				}
					
			}
		}
		
		function SendAction() {
			
			if(isset($_POST["message_from"]) && isset($_POST["message_to"]) && isset($_POST["message_content"]) && isset($_POST["message_time"])) {
				$from = $_POST["message_from"];
				$to = $_POST["message_to"];
				$content = $_POST["message_content"];
				$time = $_POST["message_time"];
			
			
				$message = new MessageModel($from, $to, $content, $time);
				if($message->db_insert())
				{
					$res = array("status" => "true",
							"message" => "sent successfully!",
							"result" => "");
					$jstr = json_encode($res);
					echo $jstr;
				} else {
					echo json_encode(array("status" => "false",
							"message" => "sent unsuccessfully!",
							"result" => ""));
				}
			}
			else echo json_encode(array("status" => "false",
					"message" => "invalid post parameters",
					"result" => ""));
			}
			
		
		
		function UpdateAction() {
			
			$usr_account = null;
			
			if(isset($_POST["user_account"])) {
				$usr_account = $_POST["user_account"];
			} else {
				echo json_encode(array("status" => "false", "message" => "invalid post parameters", "result" => ""));
				exit();
			}
			
			$message = new messageModel(null, null, null, null);
			$new_num = $message->db_check($usr_account);
			if($new_num >= 0) {
				$arr = array("new_message_num" => $new_num);
				$res = array("status" => "true",
							"message" => "got messages successfully!",
							"result"  => $arr);
				$jstr = json_encode($res);
				echo $jstr;
			} else {
				switch ($j) {
					case -1: echo json_encode(array("status" => "false", "message" => "no message in database!", "result" => "")); break;
					case -2: echo json_encode(array("status" => "false", "message" => "wrong result!", "result" => "")); break;
					default: echo json_encode(array("status" => "false", "message" => "unknow error!", "result" => ""));
				}
			}
	}
	
}

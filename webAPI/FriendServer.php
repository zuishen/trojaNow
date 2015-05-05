<?php

class FriendServer {
	protected $action;

	function __construct($act) {
		$this->action = $act;
	}

	function run() {
		switch ($this->action)
		{
			case 'list': {$this->ListAction(); break;}
			case 'add': {$this->addAction(); break;}
			case 'delete':{$this->deleteAction();break;}
			case 'sum':{$this->count();break;}
			default: echo json_encode(array("status" => "false",
			"message" => "invalid action",
			"result" => ""));
		}
	}


	function ListAction() {
		$user_account = 0;
		$follow_fan_all = null;

		if(isset($_POST["user_account"]) && isset($_POST["follow_fan_all"]) ) {
			$user_account = $_POST["user_account"];
			$follow_fan_all = $_POST["follow_fan_all"];

			$flist = new FriendModel(null, null);
			$j = $flist->list_friends($user_account, $follow_fan_all);
		if($j >= 0) {
			//transfer to json
			$arr = array();
			for($i = 0; $i < $j; $i++) {
				$arr[$i] = array("friend_account"    => "".$flist->getFriendAccount($i)."",
						"friend_name" => "".$flist->getFriendName($i).""
				);
			}
			$res = array("status" => "true",
					"message" => "got friend list successfully!",
					"result"  => $arr);
			$jstr = json_encode($res);
			echo $jstr;
		} else {
			switch ($j) {
				case -1: echo json_encode(array("status" => "false", "message" => "no friend in database!", "result" => "")); break;
				case -2: echo json_encode(array("status" => "false", "message" => "wrong result!", "result" => "")); break;
				case -3: echo json_encode(array("status" => "false", "message" => "wrong parameters!", "result" => "")); break;
				default: echo json_encode(array("status" => "false", "message" => "unknow error!", "result" => ""));
			}
		}
	}
}



	function AddAction() {
		if(isset($_POST["user_account"]) && isset($_POST["friend_account"])) {
			$user_account = $_POST["user_account"];
			$friend_account = $_POST["friend_account"];
		
			$flist = new FriendModel($user_account, $friend_account);
			switch ($flist->add_delete_friend("add", $user_account, $friend_account)) {
				case 1: {
					$res = array("status" => "true",
							"message" => "Add successfully!",
							"result" => "");
					$jstr = json_encode($res);
					echo $jstr;
					break;
				} 
				case 0: {
					echo json_encode(array("status" => "false",
							"message" => "Add unsuccessfully!",
							"result" => ""));
					break;
				}
				case 2: {
					echo json_encode(array("status" => "false",
							"message" => "repeated add!",
							"result" => ""));
					break;
				}
				case 3: {
					echo json_encode(array("status" => "false",
							"message" => "can not add yourself!",
							"result" => ""));
					break;
				}
				default: echo json_encode(array("status" => "false",
							"message" => "unknow problem!",
							"result" => ""));
			}
		}  else echo json_encode(array("status" => "false",
				"message" => "invalid post parameters",
				"result" => ""));
	}
	
	function DeleteAction() {
		if(isset($_POST["user_account"]) && isset($_POST["friend_account"])) {
			$user_account = $_POST["user_account"];
			$friend_account = $_POST["friend_account"];
		
			$flist = new FriendModel($user_account, $friend_account);
		switch ($flist->add_delete_friend("delete", $user_account, $friend_account)) {
				case 1: {
					$res = array("status" => "true",
							"message" => "Delete successfully!",
							"result" => "");
					$jstr = json_encode($res);
					echo $jstr;
					break;
				} 
				case 0: {
					echo json_encode(array("status" => "false",
							"message" => "Delete unsuccessfully!",
							"result" => ""));
					break;
				}
				case 2: {
					echo json_encode(array("status" => "false",
							"message" => "repeated delete!",
							"result" => ""));
					break;
				}
				default: echo json_encode(array("status" => "false",
							"message" => "unknow problem!",
							"result" => ""));
			}
		}
		else echo json_encode(array("status" => "false",
				"message" => "invalid post parameters",
				"result" => ""));
	}
	
	
	function count() {
		$user_account = 0;
		$follow_fan_all = null;
		$arr = null;
		if(isset($_POST["user_account"]) && isset($_POST["follow_fan_all"]) ) {
			$user_account = $_POST["user_account"];
			$follow_fan_all = $_POST["follow_fan_all"];
		
			$flist = new FriendModel(null, null);
			$j = $flist->count_friend($follow_fan_all, $user_account);
			if($j >= 0) {
				//transfer to json
				$arr = array("friend_account" => $j);
				}
				$res = array("status" => "true",
						"message" => "got num successfully!",
						"result"  => $arr);
				$jstr = json_encode($res);
				echo $jstr;
			} else {
				switch ($j) {
					case -1: echo json_encode(array("status" => "false", "message" => "no friend in database!", "result" => "")); break;
					case -2: echo json_encode(array("status" => "false", "message" => "wrong result!", "result" => "")); break;
					case -3: echo json_encode(array("status" => "false", "message" => "wrong parameters!", "result" => "")); break;
					default: echo json_encode(array("status" => "false", "message" => "unknow error!", "result" => ""));
				}
			}
	}

}
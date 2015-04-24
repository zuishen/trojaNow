<?php

	class IndexServer {
		
		protected $action;
		
		function __construct($act) {
			$this->action = $act;
//			echo $this->action;
		}
		
		function run() {
			switch ($this->action)
			{
				case 'signup': {$this->signupAction(); break;}
				case 'login':  {$this->loginAction(); break;}
				case 'logout': {$this->logoutAction(); break;}
				default: echo json_encode(array("status" => "false", 
												 "message" => "invalid action",
	 											 "result" => ""));;
			}
		}
		
		
		
		function signupAction() {
			if(isset($_POST["user_account"]) && isset($_POST["user_pwd"]) && isset($_POST["user_name"]) && isset($_POST["user_email"])) {
			$account = $_POST["user_account"];
			$passwd = $_POST["user_pwd"];
			$name = $_POST["user_name"];
			$email = $_POST["user_email"];
			$pic = null;

			$user = new UserModel($account, $passwd, $name, $email, $pic);
			if($user->db_insert())
			{
				$res = array("status" => "true",
							 "message" => "Sign up successfully!",
							 "result" => "");
				$jstr = json_encode($res);
				echo $jstr;
			} else {
				echo json_encode(array("status" => "false",
						"message" => "Sign up unsuccessfully!",
						"result" => ""));
			}
			}
			else echo json_encode(array("status" => "false", 
										"message" => "invalid post parameters",
										"result" => ""));
		}

		
		
		function loginAction() {
//			echo $_POST["user_account"];
			if(isset($_POST["user_account"]) && isset($_POST["user_pwd"]) ) {
				$account = $_POST["user_account"];
				$passwd = $_POST["user_pwd"];
			
				$user = new UserModel($account, $passwd, null, null, null);
				if($user->db_auth())
				{
					session_start();
					$sid = session_id();
					$_SESSION["user"] = $user;
					$res = array("status" => "true",
							"message" => "login successfully!",
							"result" => array("user_account" => "".$user->GetUsrAcount()."",
											  "user_name" => "".$user->GetUsrName()."",
											  "user_email" => "".$user->GetUsrEmail()."",
											  "user_pic" => "".$user->GetUsrPic()."",
											  "user_sid" => "".$sid.""
							));
					$jstr = json_encode($res);
					
					
					echo $jstr;
				}
			}
			else echo json_encode(array("status" => "false",
					"message" => "invalid account & password",
					"result" => ""));
		}
		
		function logoutAction(){
			session_start();
			$_SESSION["user"] = null;
			session_destroy();
			echo json_encode(array("status" => "true",
					"message" => "logout",
					"result" => ""));
		}
	}
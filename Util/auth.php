<?php
  
   	 
   	 function auth() {
     session_start();
   	 if (!isset($_SESSION["user"])) {
			echo json_encode(array("status" => "false",
					"message" => "Please login first",
					"result" => ""));
			exit(0);
		} 
   	 }
   	 
  
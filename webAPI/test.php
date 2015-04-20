<?php
 if(isset($_POST["check"]))
 { session_start();
   $sid = session_id();
   echo $sid;
   $_SESSION["set"]= "GOOD";
 } else if(isset($_POST["id"])){
 	$session_id = $_POST["id"];
// 	session_id($session_id);
 	session_start();
 	echo $_SESSION["set"];
 } else {session_start(); echo session_id();}
 


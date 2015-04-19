<?php
	require_once 'IndexServer.php';

	$res = $_GET['res'];
	$action = $GET['action'];
	
	switch ($res)
	{
		case 'index': {$indexServer = new IndexServer($action); $indexServer.run();break;}
		default: echo "invalid request";
	}
	 
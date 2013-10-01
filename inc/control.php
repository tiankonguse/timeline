<?php
session_start();
require("./common.php");

$json = new Services_JSON();
require("./function.php");

if((!$conn || !$result) && $ret){
	// db error
	echo $json->encode($ret);
}else if(!isset($_GET["state"])){
	//url error
	echo $json->encode(output(OUTPUT_ERROR,"非法操作"));
}else{
	// have permission
	$code = $_GET["state"];
	switch($code){
		case 1 :echo $json->encode(loadProjectList());break;
		case 2 :echo $json->encode(addProject());break;
		case 3 :echo $json->encode(loadProjectEvent());break;
		case 4 :echo $json->encode(addProjectEvent());break;
	}
}



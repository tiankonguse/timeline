<?php
session_start();
require("./common.php");

require("JSON.php");
$json = new Services_JSON();
require("function.php");

if((!$conn || !$result) && $ret){
	// db error
	echo $json->encode($ret);
}else if(!isset($_GET["state"])){
	//url error
	$ret = output(14,"非法操作");
	echo $json->encode($ret);
}else{
	// have permission
	$code = $_GET["state"];
	switch($code){
		case 1 :echo $json->encode(loadProjectList());break;
	}
}



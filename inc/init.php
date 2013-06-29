<?php

ini_set('date.timezone','Asia/Shanghai');
date_default_timezone_set('Asia/Shanghai');

define('DB_HOST','127.0.0.1');
define('DB_USER','timeline');
define('DB_PASS','timeline');
define('DB_NAME','timeline');
define('SALT','timeline');

// define('DB_NAME','tiankong_tl');
// define('DB_USER','tiankong_tl');



$conn = false;
$result = false;
$ret = connectDB();

function connectDB(){
	global $conn;
	global $result;
	
	//连接mysql
	$conn = @mysql_connect(DB_HOST,DB_USER,DB_PASS);
	if(!$conn)return (output(1,"连接mysql失败,请联系管理员."));

	//设置编码为utf8
	$result = @mysql_query("set names utf8");
	if(!$result)return (output(2,"设置编码为utf8失败,请联系管理员."));

	//设置严格模式
	// $result = @mysql_query("set sql_mode='STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'");
	// if(!$result)return (output(2,"设置严格模式失败,请联系管理员."));

	
	//设置数据库
	$result = @mysql_select_db(DB_NAME);
	if(!$result)return (output(3,"选择数据库失败,请联系管理员."));
	
	

	
	return false;
}

function output($id, $message){
	$ret = array(
			'code' => $id,
			'message' => $message
		    );
	return $ret; 
}






?>
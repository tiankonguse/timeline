<?php
session_start();
require_once("init.php");
require_once("JSON.php");
$json = new Services_JSON();


if(!$ret){
	if(!isset($_SESSION["username"]) || $_SESSION["username"]==""){
		$ret = output(9,"请先登录在操作");
	}
}

if(!$conn || !$result || $ret){
	echo $json->encode($ret);
}else{
	echo $json->encode(addNewProject());
}

require_once("end.php");
?>

<?php
function addNewProject(){
	global $conn;

	if(isset($_POST['title']) && isset($_POST['content'])){
		
		//检查表单数据
		if(strcmp(trim($_POST['title']), "") == 0 || strcmp(trim($_POST['content']), "") == 0){
			return output(6,"表单填写不完整");
		}
		
		//添加新content
		$title = mysql_real_escape_string($_POST['title']);
		$content = mysql_real_escape_string($_POST['content']);
		
		
		$sql = "INSERT INTO `project`(`name`, `description`) VALUES ('$title','$content')";
		
		if(!@mysql_query($sql ,$conn)){
			return output(4,"数据库操作失败，请联系管理员");
		}
		
		$sql = "SELECT * FROM project WHERE name = '$title' AND description = '$content' ORDER BY id DESC LIMIT 0 , 1";

		if(  ! ($result = @mysql_query($sql ,$conn)) 
		  || mysql_num_rows($result) != 1
		  || ! ($row=@mysql_fetch_array($result))
		  ){
			return output(4,"数据库操作失败，请联系管理员");
		}
		
		$projectId = $row['id'];
		$time = mysql_real_escape_string(time());
		
		$sql = "INSERT INTO `event`(`projectId`, `time`, `title`, `content`) VALUES ('$projectId','$time','$title','$content')";
		
		if(!@mysql_query($sql ,$conn)){
			return output(4,"数据库操作失败，请联系管理员");
		}
		
		
		return output(0,"新项目添加成功");

	}else{
		return output(14,"非法操作");
	}
}



?>
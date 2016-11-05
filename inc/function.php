<?php
function loginCheck() {
    $login = true;
    if (! isset ( $_SESSION ["username"] ) || $_SESSION ["username"] == "") {
        $login = false;
    }
    return $login;
}
function loadProjectList() {
    global $conn;
    
    if (isset ( $_POST ['num'] ) && isset ( $_POST ['id'] )) {
        $dataNumber = intval ( $_POST ['num'] );
        $lastProjectId = intval ( $_POST ['id'] );
        $sql = "SELECT * FROM timeline_project where id < '$lastProjectId' ORDER BY id DESC limit 0,$dataNumber ";
        $resourceId = @mysql_query ( $sql, $conn );
        
        $html = "";
        $lastProjectId = - 1;
        $code = - 1;
        while ( $project = @mysql_fetch_array ( $resourceId ) ) {
            $code = 0;
            $lastProjectId = $projecId = $project ['id'];
            $projectName = $project ['name'];
            $html .= "
                  <li>
                <a href=\"" . MAIN_DOMAIN . "timeline.php?id=" . $projecId . "\"> 
                $projectName
                </a>
                </li>
                 ";
        }
        
        return output ( $code, array (
                "id" => $lastProjectId,
                "html" => $html 
        ) );
    } else {
        return output ( OUTPUT_ERROR, "表单填写不完整" );
    }
}
function loadProjectEvent() {
    global $conn;
    
    if (isset ( $_POST ['projectId'] ) && isset ( $_POST ['lastEventId'] ) && isset ( $_POST ['nextDataNumber'] ) && isset ( $_POST ['nowProjectEventNum'] )) {
        $projectId = intval ( $_POST ['projectId'] );
        $lastEventId = intval ( $_POST ['lastEventId'] );
        $nextDataNumber = intval ( $_POST ['nextDataNumber'] );
        $nowProjectEventNum = intval ( $_POST ['nowProjectEventNum'] );
        $sql = "SELECT * FROM timeline_event where projectId = '$projectId' and id < '$lastEventId' ORDER BY id DESC limit 0,$nextDataNumber ";
        $resourceId = @mysql_query ( $sql, $conn );
        
        $html = "";
        $lastEventId = - 1;
        $code = - 1;
        while ( $_projectEvent = @mysql_fetch_array ( $resourceId ) ) {
            $code = 0;
            $lastEventId = $projectEventId = $_projectEvent ["id"];
            $projectEventTime = $_projectEvent ["time"];
            $projectEventTitle = $_projectEvent ["title"];
            $projectEventContent = $_projectEvent ["content"];
            $date = date ( "Y-m-d", $projectEventTime );
            $time = date ( "H:i:s", $projectEventTime );
            $html .= "
                <li class=\"eventli\">
                    <div class=\"date\">{$date}</div>
                    <div class=\"time\">{$time}</div>
                    <div class=\"number\"> {$nowProjectEventNum}</div>
                    <div class=\"content\">
                        <article>
                        <div class=\"timeline-title\">{$projectEventTitle}</div>
                        <div><pre>{$projectEventContent}</pre></div>
                        <div><textarea style=\" display: none; \">{$projectEventContent}</textarea></div
                        </article>
                    </div>
                </li>
                 ";
            $nowProjectEventNum --;
        }
        
        return output ( $code, array (
                "lastEventId" => $lastEventId,
                "nowProjectEventNum" => $nowProjectEventNum,
                "html" => $html 
        ) );
    } else {
        return output ( OUTPUT_ERROR, "表单填写不完整" );
    }
}
function addProject() {
    global $conn;
    
    if (! loginCheck ()) {
        return output ( OUTPUT_ERROR, "请先登录在操作" );
    }
    
    if (isset ( $_POST ['title'] ) && isset ( $_POST ['content'] )) {
        $title = $_POST ['title'];
        $content = $_POST ['content'];
        // 检查表单数据
        if (strcmp ( $title, "" ) == 0 || strcmp ( $content, "" ) == 0) {
            return output ( OUTPUT_ERROR, "表单填写不完整" );
        }
        
        // 添加新content
        $title = mysql_real_escape_string ( $title );
        $content = mysql_real_escape_string ( $content );
        $time = time ();
        
        $sql = "select * from timeline_project where name = '$title'";
        
        if (! ($result = mysql_query ( $sql, $conn ))) {
            return output ( OUTPUT_ERROR, "数据库操作失败，请联系管理员" );
        }
        
        if(mysql_num_rows ( $result ) > 0){
            return output ( OUTPUT_ERROR, "这个项目已经存在" );
        }
        
        $sql = "INSERT INTO `timeline_project`(`name`, `description`) VALUES ('$title','$content')";
        
        if (! mysql_query ( $sql, $conn )) {
            return output ( OUTPUT_ERROR, "数据库操作失败，请联系管理" );
        }
        
        $sql = "SELECT * FROM timeline_project WHERE  name = '$title' AND description = '$content' ORDER BY id DESC LIMIT 0 , 1";
        
        if (! ($result = mysql_query ( $sql, $conn )) || mysql_num_rows ( $result ) != 1 || ! ($row = @mysql_fetch_array ( $result ))) {
            return output ( OUTPUT_ERROR, "数据库操作失败，请联系管理员" );
        }
        
        $projectId = $row ['id'];
        
        $sql = "INSERT INTO `timeline_event`(`projectId`, `time`, `title`, `content`) VALUES ('$projectId','$time','$title','$content')";
        
        if (! @mysql_query ( $sql, $conn )) {
            return output ( OUTPUT_ERROR, "数据库操作失败，请联系管理员" );
        }
        
        return output ( 0, "新项目添加成功" );
    } else {
        return output ( OUTPUT_ERROR, "非法操作" );
    }
}



function addProjectEvent() {
    global $conn;
    if (! loginCheck ()) {
        return output ( OUTPUT_ERROR, "请先登录在操作" );
    }
    if (isset ( $_POST ['title'] ) && isset ( $_POST ['content'] ) && isset ( $_POST ['projectId'] )) {
        $title = $_POST ['title'];
        $content = $_POST ['content'];
        $projectId = intval ( $_POST ['projectId'] );
        // 检查表单数据
        if (strcmp ( $title, "" ) == 0 || strcmp ( $content, "" ) == 0) {
            return output ( OUTPUT_ERROR, "表单填写不完整" );
        }
        
        // 添加新content
        $title = mysql_real_escape_string ( $title );
        $content = mysql_real_escape_string ( $content );
        $time = time ();
        
        $sql = "INSERT INTO `timeline_event`(`projectId`, `time`, `title`, `content`) VALUES ('$projectId','$time','$title','$content')";
        
        if (! @mysql_query ( $sql, $conn )) {
            return output ( OUTPUT_ERROR, "数据库操作失败，请联系管理员" );
        }
        
        return output ( 0, "新项目添加成功" );
    } else {
        return output ( OUTPUT_ERROR, "非法操作" );
    }
}

function alterProjectEvent(){
	global $conn;
	if (! loginCheck ()) {
		return output ( OUTPUT_ERROR, "请先登录在操作" );
	}
	if (isset ( $_POST ['title'] ) && isset ( $_POST ['content'] ) && isset ( $_POST ['id'] )) {
		$title = $_POST ['title'];
		$content = $_POST ['content'];
		$id = intval ( $_POST ['id'] );
		// 检查表单数据
		if (strcmp ( $title, "" ) == 0 || strcmp ( $content, "" ) == 0) {
			return output ( OUTPUT_ERROR, "表单填写不完整" );
		}

		// 添加新content
		$title = mysql_real_escape_string ( $title );
		$content = mysql_real_escape_string ( $content );

		$sql = "UPDATE `timeline_event` SET `title`='$title',`content`='$content' WHERE id = $id";

		if (! @mysql_query ( $sql, $conn )) {
			return output ( OUTPUT_ERROR, "数据库操作失败，请联系管理员" );
		}

		return output ( 0, "事件修改成功" );
	} else {
		return output ( OUTPUT_ERROR, "非法操作" );
	}
}
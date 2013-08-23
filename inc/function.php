<?php
function loadProjectList() {
	global $conn;

	if (isset ($_POST['num']) && isset ($_POST['id'])) {
		$dataNumber = intval($_POST['num']);
		$lastProjectId = intval($_POST['id']);
		$sql = "SELECT * FROM timeline_project where id > '$lastProjectId' ORDER BY id  limit 0,$dataNumber ";
		$resourceId=@mysql_query($sql,$conn);

		$html = "";
		$lastProjectId = -1;
		$code = -1;
		while($project=@mysql_fetch_array($resourceId)){
			$code = 0;
			$lastProjectId = $projecId = $project['id'];
			$projectName = $project['name'];
			$html .= "
                  <li>
                <a target=\"_blank\" href=\"".MAIN_DOMAIN."timeline.php?id=".$projecId."\"> 
                $projectName
                </a>
                </li>
                 ";
		}
			
        
		return output($code , array(
		  "id"=>$lastProjectId,
		  "html"=>$html
		));
	} else {
		return output(OUTPUT_ERROR, "表单填写不完整");
	}

}
<?php
session_start();
require("./inc/common.php");
?>
<!DOCTYPE HTML>
<html lang="zh-cn">
<head>
<?php

if(!isset($_GET["id"])){
	header('Location:index.php?message=非法操作');
	die();
}else{
	$id = intval($_GET["id"]);
}

$sql = "SELECT * FROM timeline_project WHERE id = '$id'";
$resourceId = @mysql_query($sql ,$conn);
$_project = @mysql_fetch_array($resourceId);

if(count($_project) == 0){
	header('Location:index.php?message=这个项目可能已经被删除');
	die();
}

$projectId = $_project["id"];
$projectName = $_project["name"];
$projectDescription = $_project["description"];

$sql = "SELECT count(*) num FROM timeline_event WHERE projectId = '$id'";
$resourceId = @mysql_query($sql ,$conn);
$_projectEventNum = @mysql_fetch_array($resourceId);
$projectEventNum = $_projectEventNum["num"];


$title = $projectName;
require BASE_INC . 'head.inc.php';
?>
<link href="<?php echo MAIN_DOMAIN;?>css/main.css" rel="stylesheet">
</head>
<body>
<?php
$login = true;
if(!isset($_SESSION["username"]) || $_SESSION["username"]==""){
    $login = false;
}
echo "<a href=\"".MAIN_DOMAIN."\"><div class=\"top-fixed \" >项目列表</div></a>";
if($login || 1){
    echo "<div class=\"top-fixed top2-fixed\" >添加新活动</div>";
}
?>
	<header>
		<div class="title">
			<a href="<?php echo MAIN_DOMAIN."timeline.php?id=".$projectId;?>"><?php echo $title; ?>
			</a>
			<div class="sub-title">
			<?php echo $projectDescription; ?>
			</div>
		</div>
	</header>
	<section>
		<div class="container">
			<ul class="timeline">
			<?php
			$dataNumber = 10;
			$sql = "SELECT * FROM timeline_event WHERE projectId = '$projectId' ORDER BY id DESC limit 0,$dataNumber ";
			$resourceId = mysql_query($sql ,$conn);

			while($_projectEvent=@mysql_fetch_array($resourceId)){
				$lastEventId = $projectEventId = $_projectEvent["id"];
				$projectEventTime = $_projectEvent["time"];
				$projectEventTitle = $_projectEvent["title"];
				$projectEventContent = $_projectEvent["content"];
				$date = date("Y-m-d",$projectEventTime);
				$time = date("H-i-s",$projectEventTime);
				echo "
                <li>
                    <div class=\"date\">{$date}</div>
                    <div class=\"time\">{$time}</div>
                    <div class=\"number\"> {$projectEventNum}</div>
                    <div class=\"content\">
                        <article>
                        {$projectEventContent}
                        </article>
                    </div>
                </li>
                ";
                        $projectEventNum--;
			}
			?>

			</ul>
			<div style="margin-left: 180px;">
				<button id="fetchNextData" class="btn" style="width: 100%;">查看更多.</button>
			</div>
		</div>
	</section>
	<footer>
	<?php  require BASE_INC . 'footer.inc.php'; ?>
	</footer>
	<script src="<?php echo MAIN_DOMAIN;?>js/jquery.js"></script>
	<script>
	jQuery(function() {
        var nowProjectEventNum = <?php echo $projectEventNum;?>;
        var lastEventId        = <?php echo $lastEventId;?>;
	    var nextDataNumber = 5;
	    var ajaxLoading = false;
	    var docNode = jQuery(document);
	    var ulNode =  jQuery("ul.timeline");
		    
	    if(nowProjectEventNum == 0){
	        jQuery("#fetchNextData").css("display","none");
	    }
	    
	    jQuery('#fetchNextData').click(function() {
	        if(nowProjectEventNum > 0){
	            var $this = jQuery(this);
	            $this.addClass('disabled').text('正在加载后面的数据...');
	            ajaxLoading = true;

	            jQuery.get(
	    	            './inc/', 
	    	            {

		    	            },
	    	            function(data) {
	                ajaxLoading = false;
	                ulNode.append(data);
	            });
	        }

	    });

	    docNode.scroll(function() {
	        if (nowProjectEventNum > 0 && docNode.height() - jQuery(window).height() - docNode.scrollTop() < 150) {
	            if (!ajaxLoading) {
	                jQuery('#fetchNextData').click();
	            }
	        }

	    });

	});

	</script>

	
</body>
</html>

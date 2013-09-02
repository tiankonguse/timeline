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
echo "<a href=\"".MAIN_DOMAIN."\" ><div class=\"top-fixed handcursor\" >项目列表</div></a>";
if($login){
	echo "<div class=\"top-fixed top2-fixed handcursor\" >添加新活动</div>";
}
?>
    <header>

        <div class="title">
            <a
                href="<?php echo MAIN_DOMAIN."timeline.php?id=".$projectId;?>"><?php echo $title; ?>
            </a>
        </div>
    </header>
    <section>
        <div class="title sub-title">
        <?php echo $projectDescription; ?>
        </div>
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
            	$time = date("H:i:s",$projectEventTime);
            	echo "
                <li>
                    <div class=\"date\">{$date}</div>
                    <div class=\"time\">{$time}</div>
                    <div class=\"number\"> {$projectEventNum}</div>
                    <div class=\"content\">
                        <article>
                        <div class=\"timeline-title\">{$projectEventTitle}</div>
                        <div><pre>{$projectEventContent}</pre></div>
                        </article>
                    </div>
                </li>
                ";
            	$projectEventNum--;
            }
            ?>

            </ul>
            <div>
                <button id="fetchNextData" class="btn"
                    style="width: 100%;">查看更多</button>
            </div>
        </div>
    </section>
    <footer>
    <?php  require BASE_INC . 'footer.inc.php'; ?>
    </footer>
    <div id="addevent" class="modal hide">
        <div class="modal-header"
            style="text-align: center; cursor: move;">
            <button type="button" class="close">&times;</button>
            <h3>添加新事件</h3>
        </div>
        <div class="modal-body">
            <p>
                <span id="addevent_titlePre">新事件的名称：</span> <input
                    id="addevent_title" type="text" class="longtext">
            </p>
            <p>
                请输入 描述：
                <textarea id="addevent_content"
                    style="width: 100%; height: 200px;"></textarea>
            </p>
        </div>
        <div class="modal-footer">
            <button class="btn cancel">取消</button>
            <button class="btn btn-primary ok">确认</button>
        </div>
    </div>
    <div class="modal-load hide">
        <div class="modal-load-img">
            正在努力加载中。。。 <img
                src="<?php echo MAIN_DOMAIN;?>img/loading.gif"
                style="height: 30px;" />
        </div>
    </div>
    <script src="<?php echo DOMAIN_JS;?>jquery.js"></script>
    <script>
	jQuery(function() {
        var nowProjectEventNum = <?php echo $projectEventNum;?>;
        var lastEventId        = <?php echo $lastEventId;?>;
        var projectId = <?php echo $projectId;?>;
	    var nextDataNumber = 5;
	    var ajaxLoading = false;
	    var docNode = jQuery(document);
	    var ulNode =  jQuery("ul.timeline");
		var posting = false;

        if (nowProjectEventNum == 0) {
            jQuery("#fetchNextData").css("display", "none");
        }
        jQuery('#fetchNextData').click(function() {
            if (nowProjectEventNum > 0) {
            var $this = jQuery(this);
            $this.text('正在加载后面的数据...');
            ajaxLoading = true;

            jQuery.post('./inc/control.php?state=3', {
                "projectId" : projectId,
                "lastEventId" : lastEventId,
                "nextDataNumber" : nextDataNumber,
                "nowProjectEventNum" : nowProjectEventNum
            }, function(data) {
                if (data.code == -1) {
                jQuery("#fetchNextData").css("display", "none");
                haveData = false;
                nowProjectEventNum = 0;
                } else if (data.code == 0) {
                lastEventId = data.message.id;
                nowProjectEventNum = data.message.nowProjectEventNum;
                if (nowProjectEventNum == 0) {
                    jQuery("#fetchNextData").css("display", "none");
                }
                ulNode.append(data.message.html);
                $this.text('查看更多');
                } else {
                $this.text('加载出错');
                }
                ajaxLoading = false;
            }, "json");
            }

        });

        docNode
            .scroll(function() {
                if (nowProjectEventNum > 0
                    && docNode.height() - jQuery(window).height()
                        - docNode.scrollTop() < 150) {
                if (!ajaxLoading) {
                    jQuery('#fetchNextData').click();
                }
                }

            });
        jQuery('.top2-fixed').click(function() {
            jQuery("#addevent_title").val("");
            jQuery("#addevent_content").val("");
            jQuery('#addevent').addClass("in");
            jQuery(".modal-backdrop").addClass("in");
        });
        jQuery("#addevent .close").click(function() {
            if (!posting) {
            jQuery('#addevent').removeClass("in");
            jQuery('.modal-backdrop').removeClass("in");
            }

        });
        jQuery("#addevent .modal-footer .cancel").click(function() {
            if (!posting) {
            jQuery('#addevent').removeClass("in");
            jQuery('.modal-backdrop').removeClass("in");
            }

        });
        jQuery(".modal-backdrop").click(function() {
            if (!posting) {
            jQuery('#addevent').removeClass("in");
            jQuery('.modal-backdrop').removeClass("in");
            }

        });
        jQuery("#addevent .modal-footer .ok").click(function() {
            var title = jQuery("#addevent_title").val();
            var content = jQuery("#addevent_content").val();

            if (content == "" || title == "") {
        	    alert("该项目事件的名称或描述不能为空");
            } else {
            jQuery('.modal-load').addClass("in");
            posting = true;
            jQuery.post("./inc/control.php?state=4", {
                "projectId" : projectId,
                "title" : title,
                "content" : content
            }, function(d) {
                posting = false;
                jQuery('.modal-load').removeClass("in");
                if (d.code == 0) {
                    window.location.reload();
                } else {
                    alert(d.message);
                }
            }, "json");
            }
            return false;
        });
	});

	</script>


</body>
</html>

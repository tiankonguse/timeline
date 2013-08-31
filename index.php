<?php
session_start();
require("./inc/common.php");
?>
<!DOCTYPE HTML>
<html lang="zh-cn">
<head>
<?php
$title = "tiankonguse's timeline";
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

if($login){
	echo "<div class=\"top-fixed handcursor\" >添加新项目</div>";
}
?>

    <header>
        <div class="title">
            <a href="<?php echo MAIN_DOMAIN;?>"><?php echo $title; ?> </a>
            <div class="sub-title">记录下自己项目的足迹。</div>
        </div>
    </header>
    <section>
        <div class="container">
            <ul class="item-list">
            <?php
            $dataNumber = 10;
            $resourceId=@mysql_query("SELECT * FROM timeline_project ORDER BY id DESC limit 0,$dataNumber ",$conn);
            while($project=@mysql_fetch_array($resourceId)){
            	$lastProjectId = $projecId = $project['id'];
            	$projectName = $project['name'];
            	echo "
			      <li>
                <a href=\"".MAIN_DOMAIN."timeline.php?id=".$projecId."\"> 
                $projectName
                </a>
                </li>
			     ";
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
                <span id="addevent_titlePre">sssss</span> <input
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

    <div class="modal-backdrop hide"></div>
    <div class="modal-load hide">
        <div class="modal-load-img">
            正在努力加载中。。。 <img
                src="<?php echo MAIN_DOMAIN;?>img/loading.gif"
                style="height: 30px;" />
        </div>
    </div>
    <script src="<?php echo MAIN_DOMAIN;?>js/jquery.js"></script>
    <script>

    jQuery(function() {

        <?php 
            if(isset($_GET["message"])){
            	echo "alert(\"{$_GET["message"]}\")";
            }
        ?>
        
        var lastProjectId        = <?php echo $lastProjectId;?>;
        var nextDataNumber = 5;
        var ajaxLoading = false;
        var docNode = jQuery(document);
        var ulNode =  jQuery("ul.item-list");
        var haveData = true;
        
        jQuery('#fetchNextData').click(function() {
            if(!ajaxLoading && haveData){
                var $this = jQuery(this);
                $this.text('正在加载后面的数据...');
                ajaxLoading = true;

                jQuery.post(
                	"./inc/control.php?state=1", 
                    {
                	    "num":nextDataNumber,
                	    "id":lastProjectId
                     },
                     function(data) {
                	    if(data.code == -1){
                		    jQuery("#fetchNextData").css("display","none");
                		    haveData = false;
                		}else if(data.code == 0){
                			    lastProjectId = data.message.id;
                			    ulNode.append(data.message.html);
                			    $this.text('查看更多');
                        }else{
                        	   $this.text('加载出错');
                        }
                    	    ajaxLoading = false;
                    },"json"); 
                }
        });

        docNode.scroll(function() {
            if (haveData && docNode.height() - jQuery(window).height() - docNode.scrollTop() < 150) {
                if (!ajaxLoading && haveData) {
                    jQuery('#fetchNextData').click();
                }
            }
        });

        jQuery('.top-fixed').click(function(){
            jQuery("#addevent_title").val("");
            jQuery("#addevent_content").val("")
            jQuery("#addevent .modal-header h3").html("添加新项目");
            jQuery("#addevent_titlePre").text("新项目的名称：");
            jQuery('#addevent').addClass("in");
            jQuery(".modal-backdrop").addClass("in");
        });
        
        jQuery("#addevent .close").click(function(){
            jQuery('#addevent').removeClass("in");
            jQuery('.modal-backdrop').removeClass("in");
        });
        jQuery("#addevent .modal-footer .cancel").click(function(){
            jQuery('#addevent').removeClass("in");
            jQuery('.modal-backdrop').removeClass("in");
        });
        jQuery(".modal-backdrop").click(function(){
            jQuery('#addevent').removeClass("in");
            jQuery('.modal-backdrop').removeClass("in");
        });

        jQuery("#addevent .modal-footer .ok").click(function(){
            var title = jQuery("#addevent_title").val();
            var content = jQuery("#addevent_content").val();
            
            if(content == "" || title == ""){
                alert("Project名称或描述为空");
            }else{
                jQuery('.modal-load').addClass("in");
                jQuery.post("./inc/control.php?state=2",{
                    "title":title,
                    "content":content
                },function(d){
                    jQuery('.modal-load').removeClass("in");
                    if(d.code == 0){
                	   window.location.reload();
                    }else{
                        alert(d.message);
                    }
                },"json");
            }
            return false;
        });
    });

    </script>

</body>
</html>

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

if($login || 1){
	echo "<div class=\"top-fixed\" >添加新项目</div>";
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
            $resourceId=@mysql_query("SELECT * FROM timeline_project ORDER BY id limit 0,$dataNumber ",$conn);
            while($project=@mysql_fetch_array($resourceId)){
            	$lastProjectId = $projecId = $project['id'];
            	$projectName = $project['name'];
            	echo "
			      <li>
                <a target=\"_blank\" href=\"".MAIN_DOMAIN."timeline.php?id=".$projecId."\"> 
                $projectName
                </a>
                </li>
			     ";
            }
            ?>
            </ul>
            <div>
                <button id="fetchNextData" class="btn"
                    style="width: 100%;"
                >查看更多</button>
            </div>
        </div>
    </section>
    <footer>
    <?php  require BASE_INC . 'footer.inc.php'; ?>
    </footer>
    <div id="addevent" class="modal hide">
        <div class="modal-header"
            style="text-align: center;
    cursor: move;"
        >
            <button type="button" class="close">&times;</button>
            <h3>添加新事件</h3>
        </div>
        <div class="modal-body">
            <p>
                <span id="titlePre">sssss</span> <input id="title"
                    type="text" class="longtext"
                >
            </p>
            <p>
                请输入 描述：
                <textarea id="content"
                    style="width: 100%;
    height: 200px;"
                ></textarea>
            </p>
        </div>
        <div class="modal-footer">
            <button class="btn cancel">取消</button>
            <button class="btn btn-primary" onclick="">确认</button>
        </div>
    </div>
    <div class="modal-backdrop hide"></div>
    <script src="<?php echo MAIN_DOMAIN;?>js/jquery.js"></script>
    <script>
    jQuery(function() {
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
                			    console.log(lastProjectId);
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

        $('.top-fixed').click(function(){
            $("#title").val("");
            $("#content").val("")
            $("#addevent .modal-header h3").html("添加新项目");
            $("#titlePre").text("新项目的名称：");
            $("#addevent .modal-footer button.btn-primary").click(function(){

            });
            $('#addevent').addClass("in");
            $(".modal-backdrop").addClass("in");
        });
        $("#addevent .close").click(function(){
            $('#addevent').removeClass("in");
            $('.modal-backdrop').removeClass("in");
        });
        $("#addevent .modal-footer .cancel").click(function(){
            $('#addevent').removeClass("in");
            $('.modal-backdrop').removeClass("in");
        });
        $(".modal-backdrop").click(function(){
            $('#addevent').removeClass("in");
            $('.modal-backdrop').removeClass("in");
        });

    });

    </script>

</body>
</html>

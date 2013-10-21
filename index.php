<?php
session_start ();
require ("./inc/common.php");
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
if (! isset ( $_SESSION ["username"] ) || $_SESSION ["username"] == "") {
    $login = false;
}

if ($login) {
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
            $resourceId = @mysql_query ( "SELECT * FROM timeline_project ORDER BY id DESC limit 0,$dataNumber ", $conn );
            while ( $project = @mysql_fetch_array ( $resourceId ) ) {
                $lastProjectId = $projecId = $project ['id'];
                $projectName = $project ['name'];
                echo "
			      <li>
                <a href=\"" . MAIN_DOMAIN . "timeline.php?id=" . $projecId . "\"> 
                $projectName
                </a>
                </li>
			     ";
            }
            ?>
            </ul>
			<div>
				<button id="fetchNextData" class="btn" style="width: 100%;">查看更多</button>
			</div>
		</div>
	</section>


	<div class="addevent modal-backdrop hide"></div>
	<div id="addevent" class="modal hide modal-transition">

		<div class="modal-header" style="text-align: center; cursor: move;">
			<button type="button" class="close">&times;</button>
			<h3>添加新项目</h3>
		</div>
		<div class="modal-body">

			<p>
				<span id="addevent_titlePre">新项目的名称:</span> <input
					id="addevent_title" type="text" class="longtext">
			</p>
			<p>
				请输入 描述：
				<textarea id="addevent_content" style="width: 100%; height: 200px;"></textarea>
			</p>
		</div>
		<div class="modal-footer">
			<button class="btn cancel">取消</button>
			<button class="btn btn-primary ok">确认</button>
		</div>

	</div>

	<div class="modal-load hide modal-transition">
		<div class="modal-load-img">
			正在努力加载中。。。 <img src="<?php echo MAIN_DOMAIN;?>img/loading.gif"
				style="height: 30px;" />
		</div>
	</div>
	
	<script src="<?php echo DOMAIN_JS;?>jquery.js"></script>
	<script src="<?php echo DOMAIN_JS;?>main.js"></script>
	<footer>
    <?php  require BASE_INC . 'footer.inc.php'; ?>
    </footer>



	<script>

    jQuery(function() {
        var lastProjectId        = <?php echo $lastProjectId || -1;?>;
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
    });

    jQuery(document).ready(function() {
        var $addevent = jQuery('#addevent');
        var $addevent_title = jQuery("#addevent_title");
        var $addevent_content = jQuery("#addevent_content");        
        var $addevent_close = jQuery("#addevent .close");
        var $addevent_cancel = jQuery("#addevent .cancel");
        var $addevent_ok = jQuery("#addevent .ok");
        var $addevent_modal_backdrop = jQuery(".addevent.modal-backdrop");

        
        jQuery('.top-fixed').click(function(){
            $addevent_title.val("");
            $addevent_content.val("");
            $addevent.addClass("in");
            $addevent_modal_backdrop.addClass("in");
        });

        
        $addevent_close.click(function() {
            $addevent.removeClass("in");
            $addevent_modal_backdrop.removeClass("in");
        });
        
        $addevent_cancel.click(function() {
            $addevent.removeClass("in");
            $addevent_modal_backdrop.removeClass("in");
        });



        
        $addevent_ok.click(function(){
            var title = $addevent_title.val();
            var content = $addevent_content.val();
            
            if(content == "" || title == ""){
        	   showMessage("Project名称或描述为空");
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
                	   showMessage(d.message);
                    }
                },"json");
            }
            return false;
        });
        
    });
    
    jQuery(function() {
        <?php
        if (isset ( $_GET ["message"] )) {
            echo "showMessage(\"{$_GET["message"]}\")";
        }
        ?>
	 });
    </script>

</body>
</html>

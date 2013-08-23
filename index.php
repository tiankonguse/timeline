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
<link
	href="<?php echo MAIN_DOMAIN;?>css/main.css"
	rel="stylesheet"
>
</head>
<body>
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
				<button
					id="fetchNextData"
					class="btn"
					style="width: 100%;"
				>查看更多.</button>
			</div>
		</div>
	</section>
	<footer>
	<?php  require BASE_INC . 'footer.inc.php'; ?>
	</footer>
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
                $this.addClass('disabled').text('正在加载后面的数据...');
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
                        }else{
                        	   $this.addClass('disabled').text('加载出错');
                        }
                    	    ajaxLoading = false;
                    },"json"); 
                }
        });

        docNode.scroll(function() {
            if (haveData && docNode.height() - jQuery(window).height() - docNode.scrollTop() < 10) {
                if (!ajaxLoading && haveData) {
                    jQuery('#fetchNextData').click();
                }
            }
        });

    });

    </script>
</body>
</html>

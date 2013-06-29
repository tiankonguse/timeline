<?php
session_start();
require_once("inc/init.php");
$title = "tiankonguse的时间轴";
include_once('inc/header.php');
$login = true;
if(!isset($_SESSION["username"]) || $_SESSION["username"]==""){
	$login = false;
}

if($login){
	echo "<div class=\"top-fixed\" >添加新活动</div>";
}
?>

<div style="margin-top:40px;">

<?php 

	if(!$projectList=@mysql_query("SELECT * FROM project ORDER BY id DESC",$conn)){ 
		setErroe();
	}else{
		while($projectListId=@mysql_fetch_array($projectList)){
			$projecId = $projectListId['id'];
			$projectName = $projectListId['name'];
			$sql = "SELECT * FROM event WHERE projectId = '$projecId' ORDER BY time DESC LIMIT 0 , 10";
			if(!$project=@mysql_query($sql ,$conn)){
				setErroe();
			}else{ 
				echo "
				<div class=\"timeline-thing\">
					<div class=\"headline-ctn\"> 
					<h2 class=\"headline\">$projectName</h2> 
					<span class=\"headline-arr\"></span> 
				</div>
				";
				
				$oldyear = date('Y', time());
				
				echo "
				<div class=\"timeline-cat separate\">
					<div class=\"timeline-year-ind clearfix\">                  
						<div class=\"overlayer clearfix\">                        
							<span class=\"year-label\">$oldyear 年</span>
							<span class=\"ind-arr\"></span>                         
						</div>                        
					</div>
				</div>
				";
				if($login){
					echo "
					<div class=\"timeline-cat\"> 
						<div class=\"timeline-main clearfix\">
							<div class=\"timeline-rec-timestamp\">现在</div>
							<div class=\"timeline-rec-cnt\">     
								<div class=\"lightgray title-bot\" > 
									<div class=\"rec-arr\"></div> 
									<div class=\"rec-title\">
										<button class=\"btn\" onclick=\"clickAddNewEvent('$projecId','$projectName');\">添加事件</button>
									</div>  
								</div>   
							</div>
						 </div>
					</div>	
					";
				}

	
				while($row=@mysql_fetch_array($project)){
					$time = $row['time'];
					$title = $row['title'];
					$content = $row['content'];
					$year = date('Y', $time);
					$month_day = date('m-d', $time);
					$hour = date('h:i:s', $time);
					if($year != $oldyear){
						$oldyear = $year;
						echo "
						<div class=\"timeline-cat separate\">
							<div class=\"timeline-year-ind clearfix\">                  
								<div class=\"overlayer clearfix\">                        
									<span class=\"year-label\">$time 年</span>
									<span class=\"ind-arr\"></span>                         
								</div>                        
							</div>
						</div>
						";
					} 
					
					echo "<div class=\"timeline-cat separate\"></div>";
					
					echo "
					<div class=\"timeline-cat\"> 
						<div class=\"timeline-main clearfix\">
							<div class=\"timeline-rec-timestamp\">
								$month_day<br/>
								$hour
							</div>
							<span  class=\"popovers\" data-toggle=\"popover\" data-placement=\"right\" title data-content=\"$content\" data-original-title=\"$title\" >
								<div class=\"timeline-rec-cnt\">     
									<div class=\"lightgray title-bot\" > 
										<div class=\"rec-arr\"></div> 
										<div class=\"rec-title\">
										".$title."
										</div>  
									</div>   
								</div>
							</span>
						 </div>
					</div>	
					";		
				}
				echo "<div class=\"timeline-cat separate\"></div>";
				echo "
				<div class=\"headline-ctn\"> 
					<span class=\"headline-down\"></span> 
					<h2 class=\"headline\"><b>$projectName</b>项目</h2> 
				</div>
				";
			echo "</div>";
			}
		}
	}
	?>

<div style="clear:both;"></div>
</div>

<?php function setErroe(){ ?>
	<div class="timeline-thing">
		<div class="headline-ctn"> 
			<h2 class="headline">数据库操作失败</h2> <span class="headline-arr"></span> 
		</div>
		<div class="timeline-cat separate"></div>
		<div class="headline-ctn"> 
			<span class="headline-down"></span> 
			<h2 class="headline">数据库操作失败</h2> 
		</div>
	</div>
<?php } ?>

<script>
	$(document).ready(function(){
		$('.popovers').popover();
		//$oldpopovers = null;
		$('.popovers').click(function(){
			$(".popover").draggable();
			// if($oldpopovers){
				// $($oldpopovers).popover('hide');
			// }
			// $(this).pop over('show');
			// $('.popovers').popover('destroy');
			//$oldpopovers = this;
			//data-toggle=\"popover\" 
			
			// $(this).popover('show');
			
			// var top = parseFloat($(".popover").css("top"));
			// if(top < 0 )$(".popover").css("top","10px");
			
			// var htmlval = $(".popover-content").html();
			//showMessage(htmlval);
			// $(".popover-content").html("<pre>"+htmlval+"</pre>");
			

		});
		$('.top-fixed').click(clickAddNewProject);
	});
	
	function clickAddNewEvent(projectId, projectName){
		$("#title").val("");
		$("#content").val("")
		$("#addevent .modal-header h3").html("为"+projectName+"项目添加新事件");
		$("#titlePre").text("新事件标题：");
		$("#addevent .modal-footer button.btn-primary").attr("onclick","addNewEvent('"+projectId+"','"+projectName+"');");
		$('#addevent').modal();
		$( "#addevent").draggable();
	}

	
	function addNewEvent(projectId, projectName){
		var title = $("#title").val();
		var content = $("#content").val();
		
		if(content == "" || title == ""){
			showMessage("title或描述为空");
		}else{
			$('#addevent').modal('hide');
			$.post("inc/addNewEvent.php",{
				projectId:projectId,
				title:title,
				content:content
			},function(d){
				if(d.code == 0){
					showMessage(d.message,function(){window.location.reload();},4000);
				}else{
					showMessage(d.message);
				}
			},"json");
		}
		return false;
	}
	
	function clickAddNewProject(){
		$("#title").val("");
		$("#content").val("")
		$("#addevent .modal-header h3").html("添加新项目");
		$("#titlePre").text("新项目的名称：");
		$("#addevent .modal-footer button.btn-primary").attr("onclick","addNewProject();");
		$('#addevent').modal();
	}
	
	function addNewProject(){
		var title = $("#title").val();
		var content = $("#content").val();
		
		if(content == "" || title == ""){
			showMessage("Project名称或描述为空");
		}else{
			$('#addevent').modal('hide');
			$.post("inc/addNewProject.php",{
				title:title,
				content:content
			},function(d){
				if(d.code == 0){
					showMessage(d.message,function(){window.location.reload();},4000);
				}else{
					showMessage(d.message);
				}
			},"json");
		}
		return false;
	}

</script>

<div id="addevent"  class="modal hide fade">
  <div class="modal-header" style="text-align: center;cursor: move;">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>添加新事件</h3>
  </div>
  <div class="modal-body">
    <p>
		<span id="titlePre" ></span>
		<input id="title" type="text" class="longtext" >
	</p>
    <p>请输入 描述：
		<textarea id="content" style="width:100%;height:200px;"></textarea>
	</p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true" >取消</button>
    <button class="btn btn-primary" onclick="">确认</button>
  </div>
</div>
<?php include_once('inc/footer.php'); ?>
<?php include_once('inc/end.php'); ?>
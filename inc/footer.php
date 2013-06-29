
<div id="message" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="messageModalLabel">注意</h3>
  </div>
  <div class="modal-body">
    <p></p>
  </div>
  <div class="modal-footer">
    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">确定</button>
  </div>
</div>
<script>

function showMessage(message,cb,now){
	$message = $("#message");
	$message.find(".modal-body>p").text(message);
	$message.modal("show");
	$message.on("hide",cb);
	if(now > 0){
		setTimeout(cb,now);
	}
	setTimeout(function(){$message.modal('hide');},2500);
}
$(function() {
    $("#message").draggable();
});
</script>
<script src="js/bootstrap.js"></script>	
<script src="js/bootbox.min.js"></script>	
</body>
</html>
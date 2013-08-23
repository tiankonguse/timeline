jQuery(function() {
	
	var nextDataNumber = 5;
	var ajaxLoading = false;
	var docNode = jQuery(document);
	
	if(nowProjectEventNum == 0){
		jQuery("#fetchNextData").css("display","none");
	}
	
	jQuery('#fetchNextData').click(function() {
		if(nowProjectEventNum > 0){
			var $this = jQuery(this);
			$this.addClass('disabled').text('正在加载后面的数据...');
			ajaxLoading = true;

			jQuery.get('./version_data_' + nextDataNumber + '.txt', function(data) {
				ajaxLoading = false;
				ulNode.append(data);
			});
		}

	});

	docNode.scroll(function() {
		if (nowProjectEventNum > 0 && docNode.height() - jQuery(window).height() - docNode.scrollTop() < 10) {
			if (!ajaxLoading) {
				jQuery('#fetchNextData').click();
			}
		}

	});

});
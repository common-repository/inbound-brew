<div class="ib-wizzard-instruction" id="ib-wizzard-instruction" style="display:none;">
	<p><img src="<?php echo BREW_PLUGIN_IMAGES_URL; ?>icons/icon_wizzard.png" width="48px" align="absmiddle"> <strong>Landing Pages:</strong> Create a new landing page. This will allow you to have a page on your site to capture leads.</p>
</div>
<script>
	$(function(){
		var $instruction = $("#ib-wizzard-instruction");
		$instruction.delay(1000).slideDown("fast",function(){
			$instruction.delay(5000).slideUp();
		});
	});
</script>
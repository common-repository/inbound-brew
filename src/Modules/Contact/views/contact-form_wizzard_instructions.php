<div class="ib-wizzard-instruction" id="ib-wizzard-instruction" style="display:none;">
	<p><img src="<?php echo BREW_PLUGIN_IMAGES_URL; ?>icons/icon_wizzard.png" width="48px" align="absmiddle"> <strong>Contact Forms:</strong> Create a contact form to track lead data.</p>
</div>
<script>
	$(function(){
		var $instruction = $("#ib-wizzard-instruction");
		$instruction.delay(1000).slideDown("fast",function(){
			$instruction.delay(5000).slideUp();
		});
	});
</script>
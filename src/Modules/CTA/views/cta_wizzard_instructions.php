<div class="ib-wizzard-instruction" id="ib-wizzard-instruction" style="display:none;">
    <p><img src="<?php echo BREW_PLUGIN_IMAGES_URL; ?>icons/icon_wizzard.png" width="48px" align="absmiddle"> <strong>Click-through Actions:</strong> Create a new click-through action to link back to a landing page.</p>
</div>
<script>
	$(function(){
		var $instruction = $("#ib-wizzard-instruction");
		$instruction.delay(1000).slideDown("fast",function(){
			$instruction.delay(5000).slideUp();
		});
	});
</script>
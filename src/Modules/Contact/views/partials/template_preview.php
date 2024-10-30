<iframe width='100%' id="ib-email-template-iframe" style="display: none;" src="<?php echo get_site_url() . "/" . TEMPLATE_PREVIEW_SLUG . "/" ?>"></iframe>
<div class="editor-test-email">
	<div id="spinner" class="fa fa-spinner fa-spin fa-2x fa-fw"></div>
	<button id="ib_send-text-email" class="ib-button">Send Test Email</button>
	<?php echo $Form->text("send_test_to",array("div"=>false)); ?>
</div>
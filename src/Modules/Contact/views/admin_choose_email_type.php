<?php
	$custom_url = "admin.php?page={$post_type}&section=add_email&email_type=custom";
	$template_url = "admin.php?page={$post_type}&section=choose_template";
?>
<div class="">
	<div class="ib_text-section"><a href="<?php echo $custom_url; ?>">Custom Email</a></div>
	<div class="ib-column ib-column-8">
		Create a new, from scratch, custom email by using our email template generator. Our tool will help you to create fantastic and professional looking emails in no time.
	</div>
	<div class="ib-column ib-column-4 text-center">
		<a href="<?php echo $custom_url; ?>" class="ib-button">Select</a>
	</div>
	<div class="clear" style="margin-bottom:25px;"></div>
	<div class="ib_text-section"><a href="<?php echo $template_url; ?>">From Template</a></div>
	<div class="ib-column ib-column-8">
		Why reinvent the wheel? For your starting point, select one of your amazing email templates that you already put blood, sweat, and tears into perfecting.
	</div>
	<div class="ib-column ib-column-4 text-center">
		<a href="<?php echo $template_url; ?>" class="ib-button" id="ib_choose_template">Select</a>
	</div>
	<div class="clear"></div>
</div>
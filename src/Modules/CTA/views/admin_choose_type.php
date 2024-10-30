<?php
$custom_url = get_admin_url()."admin.php?page={$post_type}&section=ib_add_cta&cta_type=custom";
$image_url = get_admin_url()."admin.php?page={$post_type}&section=ib_add_cta&cta_type=image";
$template_url = get_admin_url()."admin.php?page={$post_type}&section=ib_choose_template";
?>
<div class="">
    <div class="ib_text-section"><a href="<?php echo $custom_url; ?>">Custom Button</a></div>
    <div class="ib-column ib-column-8">
        Create a new, from scratch, custom button by using our CTA generator. Our tool will help you to create fantastic and professional looking buttons in no time.
    </div>
    <div class="ib-column ib-column-4 text-center">
        <a href="<?php echo $custom_url; ?>" class="ib-button">Select</a>
    </div>
    <div class="clear" style="margin-bottom:25px;"></div>
    <div class="ib_text-section"><a href="<?php echo $image_url; ?>">Upload Image</a></div>
    <div class="ib-column ib-column-8">
        If you already have a sharp looking CTA image you'd rather use, we approve! Go ahead and upload or select the image and attach an action, Viola! CTA magic!
    </div>
    <div class="ib-column ib-column-4 text-center">
        <a href="<?php echo $image_url; ?>" class="ib-button">Select</a>
    </div>
    <div class="clear" style="margin-bottom:25px;"></div>
    <div class="ib_text-section"><a href="<?php echo $template_url; ?>">From Template</a></div>
    <div class="ib-column ib-column-8">
        Why reinvent the wheel? For your starting point, select one of your amazing CTA templates that you already put blood, sweat, and tears into perfecting.
    </div>
    <div class="ib-column ib-column-4 text-center">
        <a href="<?php echo $template_url; ?>" class="ib-button" id="ib_choose_template">Select</a>
    </div>
    <!---->
    <div class="clear"></div>
</div>
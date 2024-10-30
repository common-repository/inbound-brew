<div class="ib_list-buttons">
    <a href="<?php echo get_admin_url(); ?>admin.php?page=<?php echo $post_type; ?>&section=ib_choose_cta_type" class="ib-button next-to-extra"><span class="fa fa-plus" id="new-cta-button"></span> New CTA</span></a>
    <div id="ib_new_cta_options" class="ib_extra-options">

        <a href="<?php echo get_admin_url(); ?>admin.php?page=<?php echo $post_type; ?>&section=ib_choose_cta_type" class="ib-button ib-icon" href="admin.php?page=<?php echo $post_type; ?>&section=ib_choose_cta_type"><span class="fa fa-caret-down"></span></a>
        
        <ul style="display:none;">
            <li data-href="<?php echo get_admin_url(); ?>admin.php?page=<?php echo $post_type; ?>&section=ib_add_cta&cta_type=custom">Custom Button &raquo;</li>
            <li data-href="<?php echo get_admin_url(); ?>admin.php?page=<?php echo $post_type; ?>&section=ib_add_cta&cta_type=image">Upload Image &raquo;</li>
            <li data-href="<?php echo get_admin_url(); ?>admin.php?page=<?php echo $post_type; ?>&section=ib_choose_template">From Template &raquo;</li>
            <!---->
        </ul>
    </div>

    <a href="<?php echo get_admin_url(); ?>admin.php?page=<?php echo $post_type; ?>&section=ib_add_cta&cta_type=new_template" id ="new-template-button" class="ib-button" id="new-template-button">New Template</a>
</div>
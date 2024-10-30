<div class="ib_list-buttons">
	<a href="admin.php?page=<?php echo $post_type; ?>&section=choose_email_type" id="new-email" class="ib-button next-to-extra"><span class="fa fa-plus"></span> New Email</span></a>
	<div id="ib_list_options" class="ib_extra-options">
		<a href="admin.php?page=<?php echo $post_type; ?>&section=choose_email_type" class="ib-button ib-icon"><span class="fa fa-caret-down"></span></a>
		<ul style="display:none;">
			<li data-href="admin.php?page=<?php echo $post_type; ?>&section=add_email&email_type=custom">Custom Email &raquo;</li>
			<li data-href="admin.php?page=<?php echo $post_type; ?>&section=choose_template">From Template &raquo;</li>
		</ul>
	</div>
	<a href="admin.php?page=<?php echo $post_type; ?>&section=add_template" class="ib-button">New Template</a>
</div>
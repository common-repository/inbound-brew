<div class="ib-row- ib-td"><strong>This custom field cannot be deleted because it is linked to the following items:</strong></div>
<?php if(@$email_templates): ?>
<div class="ib-row- ib-th-dotted">Email Templates:</div>
<?php foreach($email_templates as $template): ?>
			<div class="ib-row- ib-td">
				<a href="admin.php?page=ib-email-admin&section=template&email_id=<?php echo $template->email_id; ?>" class="ib-link"><?php echo $template->email_title; ?> &raquo;</a> 
			</div>
<?php endforeach;
endif;
if(@$contact_forms): ?>
<div class="ib-row ib-th-dotted">Contact Forms:</div>
<?php foreach($contact_forms as $form): ?>
			<div class="ib-row- ib-td">
				<a href="admin.php?page=ib-contact-forms&section=edit&cf_id=<?php echo $form->ID; ?>" class="ib-link"><?php echo $form->post_title; ?> &raquo;</a> 
			</div>
<?php endforeach;
endif; ?>
<div class="ib-row"><strong><span class="red">*</span> Before you can delete this field you have to remove it from the items above.</strong></div>
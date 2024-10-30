<div id="ib_email_templates">
	<?php echo $Layout->element($partials_path . "list_actions",array('post_type' => $post_type)); ?>
	<div class="ib-tabs" id="ib-tabs">
		<?php echo $Layout->element($partials_path . "list_tabs",array(
			'post_type' => $post_type,
			'active' => "templates")); ?>
		<div class="tabs">
			<div>
				<table cellpadding="0" cellspacing="0" width="100%" class="ib_data-tables stripe">
					<thead>
                        <tr>
                            <!-- <th>ID</th> -->
                            <th>Template Name</th>
                            <th>Description</th>
                            <th>Emails</th>
                            <th>Last Updated</th>
                            <th>&nbsp;</th>
                        </tr>
					</thead>
					<tbody id="ib_cta_list">
						<?php $i = 0; foreach($templates as $template):
							$edit_url = "admin.php?page={$post_type}&section=edit_template&template_id={$template->email_template_id}";
							$trash_url = wp_nonce_url("admin.php?page={$post_type}&section=delete_template&template_id={$template->email_template_id}","ib_delete_template_nonce");
							$clone_url = "admin.php?page={$post_type}&section=clone_template&template_id={$template->email_template_id}";
							$preview_url = "admin.php?page={$post_type}&section=templates_list&action=ib_preview_email&template_id={$template->email_template_id}";
							if ($i++ % 2 == 0) $class="alt0"; ?>
						<tr id="ib_cta-row-<?php echo $template->email_template_id; ?>">
							<!-- <td><?php echo $template->email_template_id; ?></td> -->
							<td><?php echo $template->name; ?></td>
							<td><?php echo $template->description; ?></td>
							<td><?php
								$count = count($template->emails()->get()->toArray());
								echo number_format($count); ?></td>
							<td>
								<?php echo $Date->format(BREW_WP_DATE_FORMAT,$template->updated_at,true); ?>
							</td>
							<td class="ib_tools">
								<a href="<?php echo $clone_url; ?>" class="ib_icon-link fa fa-clone" title="Clone"></a>
								<a href="<?php echo $edit_url; ?>" class="ib_icon-link fa fa-pencil" title="Edit"></a>
								<a href="<?php echo $trash_url; ?>" class="ib_icon-link delete fa fa-trash delete-template" title="Delete"></a>
								<a href="<?php echo $preview_url; ?>" data-title="<?php echo str_replace('"', "&quot;", $template->name); ?>" class="ib_icon-link delete fa fa-eye ib_preview"  title="Preview"></a>
							</td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<script>
		jQuery(document).ready(function($) {
			$("#ib_email_templates").ib_emailsLists({
				list:"templates",
				nonce: "<?php echo wp_create_nonce("ib-email-preview-nonce"); ?>"
			});
		});
	</script>
</div>
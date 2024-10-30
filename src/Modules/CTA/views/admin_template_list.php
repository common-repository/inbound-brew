<div>
	<?php echo $Layout->element($partials_path . "list_actions",array('post_type' => $post_type)); ?>
	<div class="ib-tabs" id="ib-tabs">
		<?php echo $Layout->element($partials_path . "list_tabs",array(
			'post_type' => $post_type,
			'active' => "templates")); ?>
		<div class="tabs">
			<!-- Manage Redirect Tabs -->
			<div id="ib_cta">
				<table cellpadding="0" cellspacing="0" width="100%" class="ib_data-tables stripe" id="ib_template_list">
					<thead>
                        <tr>
                            <!-- <th class="text-center" width="4%">ID</th> -->
                            <th>Preview</th>
                            <th>Last Updated</th>
                            <th># of CTA's</th>
                            <th width="20%">&nbsp;</th>
                            <!-- <th>&nbsp;</th> -->
                        </tr>
					</thead>
					<tbody id="ib_cta_list">
						<?php foreach($templates as $template):
							$tid = $template->template_id;
							$edit_url = get_admin_url()."admin.php?page={$post_type}&section=ib_edit_template&tid={$tid}";
							$clone_url = get_admin_url()."admin.php?page={$post_type}&section=ib_add_cta&cta_type=new_template&tid={$tid}";
							$trash_url = wp_nonce_url("admin.php?page={$post_type}&section=ib_delete_template&tid={$tid}","ib-delete-template");
							$add_url = get_admin_url()."admin.php?page={$post_type}&section=ib_add_cta&cta_type=new_cta&tid={$tid}";
                        ?>
						<tr id="template_<?php echo $tid; ?>" data-id="<?php echo $tid; ?>" data-type="template_ctas">
							<!-- <td class="text-center"><?php echo $template->template_id; ?></td> -->
							<td>
                                <div class="ib-cta_preview-list" data-url="<?php echo $edit_url; ?>"><?php echo stripslashes($template->html); ?></div>
                            </td>
                            <td><?php echo $Date->format(BREW_WP_DATE_FORMAT." ".BREW_WP_TIME_FORMAT,$template->updated_at,true); ?></td>
                            <td id="cta_count"><?php echo number_format($template->ctas()->count()); ?></td>
							<td class="ib_tools">
								<a href="<?php echo $clone_url; ?>" class="ib_icon-link fa fa-clone" title="Clone"></a>
								<a href="<?php echo $edit_url; ?>" class="ib_icon-link fa fa-pencil" title="Edit This Template"></a>
								<a href="<?php echo $trash_url; ?>" class="ib_icon-link delete-template fa fa-trash" title="Delete"></a>
								<a href="<?php echo $add_url; ?>" class="ib_icon-link fa fa-plus" title="Create a new CTA Using This Template"></a>
							</td>
							<!-- <td class="details-control">
								<span class="fa fa-chevron-right"></span>
							</td>-->
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
            $("#ib_template_list").ib_ctaLists({
				list:"templates",
				blog_url:"<?php echo get_site_url(); ?>",
				post_type:"<?php echo $post_type; ?>"
			});
		});
	</script>
</div>
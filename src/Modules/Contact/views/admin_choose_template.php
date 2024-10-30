<div id="ib_template_list">
	<div class="ib_text-section">Choose Template</div>
	<div id="ib_cta">
		<table cellpadding="0" cellspacing="0" width="100%" class="ib_data-tables stripe" id="ib_template_list">
			<thead>
                <tr>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>
			</thead>
			<tbody>
				<?php foreach($templates as $template):
					$tid = $template->email_template_id;
					$select_url = "admin.php?page={$post_type}&section=add_email&template_id={$tid}";
                ?>
				<tr id="template_<?php echo $tid; ?>" data-id="<?php echo $tid; ?>">
					<td>
	                    <strong class="ib_uppercase"><?php echo $template->name; ?></strong><br>
	                    <span class="ib_details">
							Last Edited: <?php echo $Date->format(BREW_WP_DATE_FORMAT,$template->updated_at,true); ?><br>
							Used: <?php $used = count($template->emails()->get()->toArray());
							echo sprintf("%s time%s",number_format($used),($used != 1)? "s":""); ?>
						</span>
	                </td>
	                <td><?php echo $Layout->addLineBreaks($template->description); ?></td>
					<td>
						<a class="ib-button ib_select-gray" href="<?php echo $select_url; ?>">select</a>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {
			$("#ib_email_templates").ib_emailsLists({
				list:"choose_template",
			post_type:"<?php echo $post_type; ?>"
			});
		});
</script>
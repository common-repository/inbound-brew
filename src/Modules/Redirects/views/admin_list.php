<div id="ib_redirects">
	<div class="ib-tabs" id="ib-tabs">
		<div class="tab-wrapper noselect">
			<a href="admin.php?page=<?php echo $post_type; ?>&section=list" class="ib-tab-link selected">Redirects</a>
			<a href="admin.php?page=<?php echo $post_type; ?>&section=import" class="ib-tab-link">Import/Export</a>
		</div>
		<div class="tabs">
			<!-- Manage Redirect Tabs -->
			<div id="ib_add_redirect">
				<div class="ib-td">
					<div class="ib-notes ib-margin-bottom"><strong>301:</strong> Permanent Redirect&nbsp;&nbsp;&nbsp;<strong>302:</strong> Temporary Redirect)</div>
					<?php echo $Form->create("ib_add_redirect"); 
					echo $Form->hidden("nonce", wp_create_nonce( 'ib-redirect-nonce' )); ?>
					<table class="ib_form-table" cellpadding="0" cellspacing="0">
						<thead>
							<tr>
								<th>Status</th>
								<th>Redirect From <?php bloginfo('url'); ?>/</th>
								<th>Redirect To:</th>
								<th>Wildcards? <span class="fa fa-info-circle ib-inline_education" data-index="redirect_wildcards" data-title="What Are Wildcards?"> </span></th>
								<th>&nbsp;</th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody id="new_redirects_list">
							<tr id="redirect_row0">
								<td><?php echo $Form->select('Redirect.0.status',array(
										301 => '301',
										302 => '302'
									) ,array(
										'label' => false,
										'required' => true,
									)); ?>
								</td>
								<td>
									<?php echo $Form->urlpath('Redirect.0.redirect_from',array(
										'label' => false,
										'required' => true,
										'div' => false,
										'size' => 40
									)); ?>
								</td>
								<td>
									<?php echo $Form->select('Redirect.0.redirect_type',$options['types'],array(
										'label' => false,
										'required' => true,
										'id' => "redirect_type_select",
										'div' => "input select fl ib-margin-right"
									)); ?>
									<div id="ib_post-type-options" class="fl">
										<?php 
											echo "<div class=\"fl\" id=\"ib_url_options\">";
											echo $Form->text('Redirect.0.url_options',array(
											'label' => false,
											'required' => true,
											'div' => false,
											'size' => 40,
											'data-type' => 'url'
										)); 
										echo "</div>";
										// create post type options dropdowns
										foreach($options['options'] as $postType => $pOptions):
										echo "<div class=\"select input fl\" id=\"ib_{$postType}_options\" style=\"display:none;\">";
											$fieldName = "Redirect.0." . $postType . "_options";
											echo $Form->select($fieldName,$pOptions,array(
												'empty' => "Choose One",
												'label' => false,
												'required' => true,
												'data-type' => "post-type-options",
												'div' =>false
											));
										echo "</div>";
										endforeach;
									?>	
									</div>
								</td>
								<td><?php 
									echo $Form->checkbox("Redirect.0.is_wildcard","1",array(
										'label' => false,
										'id' => "is_wildcard"
									)); ?>
								</td>
								<td><a href="" id="delete_row" class="ib_icon-link fa fa-trash" style="display:none;"></a></td>
								<td><button class="ib-button ib_add-new" id="add_new_redirect_row_button">Add Another Redirect</button></td>
							</tr>
						</tbody>
					</table>
					<div class="ib-margin-top"><button id="submit_form" class="ib-button">Save Above Redirect(s)</button></div>
					<?php echo $Form->end(); ?>
				</div>
				<div id="ib_redirect_list">
					<table cellpadding="0" cellspacing="0" width="100%" class="ib_data-tables stripe">
						<thead>
							<tr>
								<th>ID</th>
								<th>Status</th>
								<th>From</th>
								<th>To</th>
								<th>Using Wildcards</th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody id="ib_redirect-list">
						<?php foreach($redirects as $redirect):
							$arr = $redirect->toArray();
							$arr[$arr['redirect_type']."_options"] = $arr['redirect_to'];
							$Form->data['Redirect'] = $arr; ?>
						<tr id="ib_redirect-row-<?php echo $redirect->redirect_id; ?>">
							<?php echo $Form->create("ib_edit_redirect");
								echo $Form->hidden("Redirect.redirect_id",$redirect->redirect_id); ?>
							<td><?php echo $redirect->redirect_id; ?></td>
							<!-- redirect status -->
							<td>
								<div class="ib_display"  id="disp_status"><?php echo $redirect->status; ?></div>
								<div class="form-field" style="display:none">
									<?php echo $Form->select('Redirect.status',array(
										301 => '301',
										302 => '302'
									) ,array(
										'label' => false,
										'required' => true,
										'div' => "input select fl"
									)); ?>
								</div>
							</td>
							<!-- redirect from -->
							<td id="redirect_from">
								<div class="ib_display" id="disp_redirect_from"><?php bloginfo('url'); ?>/<strong><?php echo $redirect->redirect_from; ?></strong></div>
								<div class="form-field" style="display:none">
									<?php echo $Form->text('Redirect.redirect_from',array(
										'label' => false,
										'required' => true,
										'div' => false,
										'size' => 40,
									)); ?>
								</div>
							</td>
							<!-- redirect to -->
							<td id="redirect_to">
								<div class="ib_display"  id="disp_redirect_to"><?php
								$type = strtoupper($redirect->redirect_type);
								if($type == "IB-LANDING-P") $type = "LANDING PAGE";
								if($redirect->redirect_type =="url"):
									echo "URL: ".$redirect->redirect_to;
								else:
									echo $type .": " .get_permalink((int)$redirect->redirect_to); 
								endif; ?></div>
								<div class="form-field" style="display:none">
									<?php echo $Form->select('Redirect.redirect_type',$options['types'],array(
										'label' => false,
										'required' => true,
										'id' => "redirect_type_select",
										'div' => "input select fl ib-margin-right"
									)); ?>
									<div id="ib_post-type-options" class="fl">
										<?php 
											echo "<div class=\"fl\" id=\"ib_url_options\">";
											echo $Form->text('Redirect.url_options',array(
											'label' => false,
											'required' => true,
											'div' => false,
											'size' => 40,
											'data-type' => 'url'
										)); 
										echo "</div>";
										// create post type options dropdowns
										foreach($options['options'] as $postType => $pOptions):
										echo "<div class=\"select input fl\" id=\"ib_{$postType}_options\" style=\"display:none;\">";
											$fieldName = "Redirect." . $postType . "_options";
											echo $Form->select($fieldName,$pOptions,array(
												'empty' => "Choose One",
												'label' => false,
												'required' => true,
												'data-type' => "post-type-options",
												'div' =>false
											));
										echo "</div>";
										endforeach;
									?>	
									</div>
								</div>
							</td>
							<!-- is wildcard -->
							<td>
								<div class="ib_display" id="disp_is_wildcard"><?php echo ($redirect->is_wildcard)? "YES" : "NO"; ?></div>
								<div class="form-field" style="display:none">
									<?php echo $Form->select('Redirect.is_wildcard',array(
										0 => 'NO',
										1 => 'YES'
									) ,array(
										'label' => false,
										'required' => true,
										'div' => "input select fl"
									)); ?>
								</div>
							</td>
							<!-- control button -->
							<td id="control_buttons">
								<div class="ib_display">
									<a href="" class="ib_edit-button ib_icon-link fa fa-pencil"></a>
									<a href="" data-id="<?php echo $redirect->redirect_id; ?>" class="ib_icon-link delete fa fa-trash"></a>
								</div>
								<div class="form-field" style="display:none">
									<button class="ib-button ib_cancel ib-fa"><span class="fa fa-times"></span></button>
									<button class="ib-button ib-fa ib_save"><span class="fa fa-check"></span></button>
								</div>
							</td>
							<?php echo $Form->end(); ?>
						</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$("#ib_redirects").ib_redirects({
				redirect_submit: $("#submit_form"),
				post_type:"<?php echo $post_type; ?>"
			});
		});
	</script>
</div>
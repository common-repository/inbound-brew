<div class="ib-tabs" id="ib-tabs">
	<?php echo $Layout->element($partials_path . "settings_tabs",array(
		'post_type' => $post_type,
		'active' => "Leads")); 
	$layout = get_option(BREW_DEFAULT_LAYOUT_OPTION);
	?>
	<div class="tabs">
		<div class="tab ib-padding-top" id="leads_field_list">
			<div class="ib-header no-bottom-margin">Custom Fields</div>
			<div class="ib-td">
				<table cellpadding="0" cellspacing="0" width="100%" class="ib_data-tables minimalist" id="custom-list">
					<thead>
						<tr>
							<th><?php _e('Name', 'inbound-brew'); ?></th>
							<th><?php _e('Type', 'inbound-brew'); ?></th>
							<th><?php _e('Options', 'inbound-brew'); ?></th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php if(!empty($custom_fields)): 
							foreach($custom_fields as $field): ?>
						<tr id="field_<?php echo $field->field_id; ?>">
							<td><?php echo $field->field_name; ?></td>
							<td><?php echo strtoupper($field->field_type); ?></td>
							<td><?php echo stripcslashes(str_replace("\r",",", $field->field_value )); ?></td>
							<td>
								<a href="#" class="ib_icon-link fa fa-pencil"></a>
								<a href="#" class="ib_icon-link delete fa fa-trash ib_delete-field"></a>
							</td>
						</tr>					
						<?php endforeach;
						endif; ?>
					</tbody>
				</table>
			</div>
			<div class="ib-header no-bottom-margin">Static Fields</div>
			<div class="ib-column ib-column-5 ib-td" style="padding-top:0px;">
				<table cellpadding="0" cellspacing="0" width="100%" class="ib_data-tables minimalist">
					<thead>
						<tr>
							<th>Name</th>
							<th>Type</th>
						</tr>
					</thead>
					<tbody>
						<?php if(!empty($static_fields)): 
							foreach($static_fields as $field): ?>
						<tr>
							<td><?php echo $field->field_name; ?></td>
							<td><?php echo strtoupper($field->field_type); ?></td>
						</tr>					
						<?php endforeach;
						endif; ?>
					</tbody>
				</table>
			</div>
			<div class="ib-column ib-column-7 ib-td custom-form">
				<?php echo $Form->create("ib_add_custom_lead_field",array('class' => "ib_logo-form"));
				echo $Form->hidden("LeadField.lead_field_id",""); ?>
				<div class="ib-form-title">Add Custom Field</div>
                                <div class="custom-form-item clear">
                                <div class="ib_label">Type*</div>
				<?php echo $Form->select("LeadField.field_type",array(
					'text' => "Text",
					'date' => "Date",
					'textarea' => "Textarea",
					'radio' => "Radio",
					'singlecheckbox' => "Single Checkbox",
					'checkbox' => "Multiple Checkbox",
					'select' => "Dropdown"
					),array("required" => true,'empty'=>"Choose One")); ?>
                                </div>
				<div class="custom-form-item clear">
				<div class="ib_label">Name*</div>
				<?php echo $Form->text("LeadField.field_name",array('required' => true)); ?>
                                </div>
				<div class="custom-form-item clear option_div">
                                    <div class="ib_label">Options*</div>
                                    <div class="input textarea">
                                            <?php echo $Form->textarea("LeadField.field_value",array('rows' => 6,'div'=>false)); ?>
                                            <div class="ib-notes">One option per line</div>
                                    </div>
                                </div>
				
				<div class="fr">
					<buttom class="ib_cancel ib-button" id="cancelButton" style="display:none;">Cancel</buttom>
					<button class="ib_save ib-button">Save</button>
				</div>
				<div class="clear"></div>
				<?php echo $Form->end(); ?>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>
<script>
	jQuery(document).ready(function($) {
		$("#leads_field_list").ib_leadSettings();
	});
</script>
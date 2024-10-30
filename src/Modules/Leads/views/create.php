<div>
	<p class="ib-inline-education">System uses <strong>"email"</strong> as the unique field. If email has already been used, you won't be able to create a new lead using the same email.</p>
	<?php echo $Form->create("ib_lead_form",array('class'=>"ib-form plain"));
		wp_nonce_field('ib_lead_nonce' ); ?>
	<div class="ib-widget">
		<h3 class="handle">Lead Fields:</h3>
		<div class="ib-inside">
			<?php echo $Form->text("Lead.lead_name",array('required'=>true,'label' => "Name")); ?>
			<?php echo $Form->email("Lead.lead_email",array('required'=>true,'label' => "Email")); ?>
			<?php echo $Form->select("Lead.type_id",array(
				1=>"Prospect",
				2=>"Lead",
				3=>"Customer"),array('required'=>true,'label' => "Lead Type")); ?>
			<?php echo $Form->text("Lead.lead_address",array('label' => "Address")); ?>
			<?php echo $Form->text("Lead.lead_address2",array('label' => "Address 2")); ?>
			<?php echo $Form->text("Lead.lead_city",array('label' => "City")); ?>
			<?php echo $Form->select("Lead.country_id",$countries,array('label' => "Country")); ?>
			<div class='input select' id="us_states">
				<?php echo $Form->select("Lead.us_state",$states,array('label' => "State",'div'=>false)); ?>
			</div>
			<div class='input select' id="nonus_states">
				<?php echo $Form->text("Lead.non_state",array('label' => "State/Province",'div'=>false)); ?>
			</div>
			<?php echo $Form->text("Lead.lead_postal",array('label' => "Postal Code")); ?>
			<?php echo $Form->text("Lead.lead_phone",array('label' => "Phone Number",'class'=>"ib-phone")); ?>
			<?php echo $Form->text("Lead.lead_ip",array('label' => "IP Address")); ?>
			<?php echo $Form->text("Lead.lead_dob",array("label"=>"DOB","class"=>"ib-date")); ?>
		</div>
	</div>
	<?php if(!empty($custom_fields)): ?>
	<div class="ib-widget">
		<h3 class="handle">Custom Lead Fields:</h3>
		<div class="ib-inside">
			<?php foreach($custom_fields as $field):
				$type = $field['field_type'];
				switch($type):
					case "text":
						echo $Form->text("LeadData.".$field['field_token'],array("label"=>$field['field_name']));
					break;
					case "textarea":
						echo $Form->textarea("LeadData.".$field['field_token'],array("label"=>$field['field_name']));
					break;
					case "date":
						echo $Form->text("LeadData.".$field['field_token'],array("label"=>$field['field_name'],"class"=>"ib-date"));
					break;
					case "checkbox":
						$values = explode("\n",$field['field_value']);
						$options = array();
						foreach($values as $value):
							$value = rtrim(str_replace("\r", "", $value));
							$options[$value] = $value;
						endforeach;
						echo $Form->checkboxes("LeadData.".$field['field_token'],$options,array('label'=>$field['field_name']));
					break;
					case "select":
						$values = explode("\n",stripslashes($field['field_value']));
						$options = array();
						foreach($values as $value):
							$value = rtrim(str_replace("\r", "", $value));
							$options[$value] = $value;
						endforeach;
						echo "<div class=\"input\" style=\"margin:0px;\"><label>{$field['field_name']}</label></div>";
						echo $Form->select("LeadData.".$field['field_token'],$options,array('label'=>false,'empty' => "Choose One"));
					break;
					case "radio":
						$values = explode("\n",stripslashes($field['field_value']));
						$options = array();
						foreach($values as $value):
							$value = rtrim(str_replace("\r", "", $value));
							$options[$value] = $value;
						endforeach;
						echo $Form->radio("LeadData.".$field['field_token'],$options,array('label'=>$field['field_name']));
					break;
				endswitch;
			endforeach;?>
		</div>
	</div>	
	<?php endif; ?>
	<div class="ib-margin-top">
		<button class="ib-button"><?php echo $submit_button; ?></button>
	</div>
	<?php echo $Form->end(); ?>
</div>
<script type="text/javascript">
	jQuery(document).ready(function($){
		if($.fn.datepicker) $( ".ib-date" ).datepicker();
		$("#ib_lead_form").validate({
			rules:{
				LeadName:"required",
				LeadEmail:{
					required:true,
					email:true
				},
			submitHandler:function(form){
				form.submit();
	    	}}
		});
		// handle country
		$("#LeadCountryId").change(function(){
			var selected = $(this).val();
			if(selected == 228){ // united states
				$("#us_states").show();
				$("#nonus_states").hide();
			}else{
				$("#us_states").hide();
				$("#nonus_states").show();
			}
		}).trigger("change");
		// phone
		$('.ib-phone').mask('(999) 999-9999');
	});
</script>
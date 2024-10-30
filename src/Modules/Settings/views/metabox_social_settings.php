<?php wp_nonce_field( "ib_save_post_settings_{$network}", "inboundbrew_post_settings_{$network}_nonce" );
	$form_prefix = "SocialNetworkPostSetting.about_page.{$network}."; ?>
<p class="ib-inline-education" style="margin:0;">
	<span class="ib_blog-link">For more information about Automatic Social Network Push visit <a href="<?php echo BREW_PLUGIN_BLOG_URL; ?>auto-social-network-push" target="_blank">The Inbound Brew Blog</a>.</span>
</p>
<div id="ib_post_settings_list_<?php echo $network; ?>">
	<div class="ib-dotted-header">About This Page:</div>
	<div class="ib-post-settings-about-page">
		<?php echo $Form->radio($form_prefix."use",array(
			'about_this_page' => "Same info as \"About This ".$post_type_object->labels->singular_name." Box\". <a href='#' class='ib-about-this-page-edit'>[edit]</a>",
			'custom' => "Custom"),array("id"=>"data_use")); ?>
		<div class="ib-message-error" id="ib-message-error" style="display:none;">Your "About This <?php echo $post_type_object->labels->singular_name; ?>" description is too long from <?php echo $network_name; ?>. You must use a custom description.</div>
		<div class="ib-message-error" id="ib-thumbnail-error" style="display:none;">Your "About This <?php echo $post_type_object->labels->singular_name; ?>" thumbnail is too big for <?php echo $network_name; ?>. You must use a custom thumbnail.</div>
		<div class="ib-post-setting-about-custom ib-alt0">
			<div class="fl">
				<div class="ib-label">Thumbnail:</div>
				<div class="ib-meta-preview-image" id="ib-meta-preview-image">
				<?php
					$thumbnail = @$Form->data['SocialNetworkPostSetting']['about_page'][$network]['about_thumbnail'];
					if($thumbnail):
						$cancel_display = "";
						echo "<img src=\"{$thumbnail}\">";
					else:
						$cancel_display = "display:none;";
						echo "<span>Use \"".$post_type_object->labels->singular_name."\" thumbnail.</span>";
				endif;?>
				</div>
				<?php echo $Form->hidden($form_prefix."about_image","",array('id'=>"ib_social_about_image"));
					echo $Form->hidden($form_prefix."about_thumbnail","",array('id'=>"ib_social_about_thumbnail")); ?>
				<button class="ib-button" id="ib_set_meta_image_button">Set Image</button>
				<button class="ib-button cancel" id="ib_set_meta_image_button_cancel" style="<?php echo $cancel_display; ?>">X</button>
			</div>
			<div class="fl" id="ib-social-custom-description">
				<div class="ib-label">Description:</div>
				<?php echo $Form->textarea($form_prefix."about_description",array(
					'value' => "",
					'class' => 'ib-textarea',
					'id'=> "social_settings_about_description",
					'rows' => 4,
					'placeholder' => "Enter ".strtolower($post_type_object->labels->singular_name) ." description",
					'div' => false)); ?>
				<?php if(@$limit_description): ?>
					<div class="ib-character-limit">
						<div class="ib-character-usage" id="social_settings_about_description_character_usage"></div>
						<?php echo sprintf("%s only allows %s characters",$network_name,$limit_description); ?><br>
						<?php if($network == "twitter"): ?>
						<div class="ib-post-link"  id="ib-post-link">
							<span><?php echo wp_get_shortlink(); ?></span> will be added to the end of your description.
						</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<div class="ib-dotted-header">Post Settings:</div>
	<div class="ib-network-info">
		<div class='fr'>
			<a href="#" id="id_social-posting-save-as-defaults" class="ib-button green" data-network="<?php echo $network; ?>">SAVE AS DEFAULTS</a>
			<a href="#" id="id_social-posting-defaults" class="ib-button green" data-network="<?php echo $network; ?>">LOAD DEFAULTS</a>
			<a href="#" id="ib-add-social-option-<?php echo $network; ?>" class="ib-button">ADD OPTION</a>
		</div>
		<div class="ib-network-instructions">
			Please choose when and how many times you would like to post content to <?php echo $network_name; ?>.
		</div>
		<div class="clear"></div>
	</div>	
	<div class="ib-post-settings"></div>
	<!-- clone -->
	<div class="ib-post-setting" id="social-post-settings-clone" style="display: none;">
		<?php echo $Form->hidden("Clone.0.is_deleted","0");
		echo $Form->hidden("Clone.0.post_setting_id","0"); ?>
		<a href="" id="posting-status" style="display:none;" class="noselect">
			<div class="icon"></div>
			<span></span>
		<a>
		<div class="fr" id="settings-delete" style="display:none;">
			<a href="#" class="ib-button delete" id="delete_row">X</a>
		</div>
		<div class="row-number">1</div>
		<div class="settings">
			<?php echo $Form->radio("Clone.0.when_to_post",array(
				'on' => "On",
				'now' => "Post Imediately"),
				array(
					'id' => "Clone0WhenToPost",
					'div' => "input radio fl")); ?>
			<div id="choose-date-disclamer" class="red fl">* Cannot be added to defaults.</div> 
			<div class="clear-right"></div>
			<div class="post_on_settings">
				<?php echo $Form->select("Clone.0.when_to_post_on_option",array(
					'days' => "Days After",
					'date' => "Choose Date",
					'tomorrow' => "Next Day",
					'next sunday' => "Sunday After",
					'next monday' => "Monday After",
					'next tuesday' => "Tuesday After",
					'next wednesday' => "Wednesday After",
					'next thursday' => "Thursday After",
					'next friday' => "Friday After",
					'next saturday' => "Saturday After",
					),array(
						'div' => "fl"
					)); ?>
				<?php echo $Form->text("Clone.0.when_to_post_on_option_value",array(
					'label'=>false,
					'div' => "ib-number fl"));
					echo $Form->text("Clone.0.when_to_post_on_option_date",array(
					'label'=>false,
					'div' => "ib-date fl",
					'size' => 10,
					'class' => "ib-date-picker")); ?>
				<div class="fl ib-between-fields">AT</div>
				<?php echo $Form->time("Clone.0.when_to_post_time",array('on_the_hour' => true,'div' => "fl")); ?>
				<div class="clear"></div>
			</div>
			<div class="ib_social-accounts">
				<div class="ib-dotted-header">Post to these accounts:</div>
				<?php $counter = 0; foreach($accounts as $account):
					echo $Form->checkbox("Clone.0.social_network_token_id",$account['account_id'],array(
						'label' => sprintf("%s: %s",ucfirst($account['account_type']),$account['display_name']),
						'div' => "ib_social-account"
					));
					$counter++;
					if($counter == 2) echo "<div class=\"clear\"></div>";
				endforeach; ?>
			</div>
			<div class="ib_post-now-text" style="display: none;"><span class="red">*</span> System will post to <?php echo $network_name; ?> when <?php echo get_post_type(); ?> is published</div>
			<div class="clear"></div>
		</div>
	</div>
	<!-- end clone -->
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$("#ib_post_settings_list_<?php echo $network; ?>").ib_postSettings({
			clone_selector:"#social-post-settings-clone",
			list_selector:".ib-post-settings",
			add_option_selector:"#ib-add-social-option-<?php echo $network; ?>",
			defaults_button:"#id_social-posting-defaults",
			save_as_defaults_button:"#id_social-posting-save-as-defaults",
			data:<?php echo json_encode($post_settings); ?>,
			network:"<?php echo $network; ?>",
			network_name:"<?php echo $network_name; ?>",
			post_link:"<?php echo wp_get_shortlink(); ?>",
			default_image_message:"<span>Use \"About This <?php echo $post_type_object->labels->singular_name; ?>\" thumbnail.</span>",
			default_no_image_message:"<span>No Image.</span>",
			<?php if(@$limit_description): ?>
			limit_description:<?php echo $limit_description; ?>
			<?php endif; ?>
	    });
	});
</script>
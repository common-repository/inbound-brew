<div class="ib-tabs" id="ib-tabs">
	<?php echo $Layout->element($partials_path . "settings_tabs",array(
		'post_type' => $post_type,
		'active' => "Social Settings")); ?>
</div>
<div class="ib-header">Manage All Your Social Media Settings Here</div>
<div class="ib-tabs" id="ib-tabs">
	<?php echo $Layout->element($partials_path . "social_settings_tabs",array(
	'post_type' => $post_type,
	'active_networks' => $active_networks,
	'active' => "General")); ?>
	<div class="tabs">
		<div class="tab ib-padding">
			<?php $now = date("Y-m-d H:i:s"); ?>
			<div class="ib-header">
				Social Network URLs:
			</div>
			<p class="ib-instructions" style="margin-bottom:10px;">Please provide URLs to your social network pages. These are used in the Email Template Module.</p>
			<div class="ib-row">
				<?php echo $Form->create("ib_save_social_urls",array('url'=>admin_url("admin-post.php")));
					wp_nonce_field('ib_save_social_urls_nonce');
				?>
				<div class="ib_editor-fields">
					<!-- facebook -->
					<div class="ib_label">Facebook:</div>
					<div class="ib_fields">
						<?php echo $Form->text("Setting.social_url_facebook",array(
							'div'=>false,
							'style' => "width:300px")); ?>
					</div>
					<div class="clear"></div>
					<!-- twitter -->
					<div class="ib_label">Twitter:</div>
					<div class="ib_fields">
						<?php echo $Form->text("Setting.social_url_twitter",array(
							'div'=>false,
							'style' => "width:300px")); ?>
					</div>
					<div class="clear"></div>
					<!-- LinkedIn -->
					<div class="ib_label">LinkedIn:</div>
					<div class="ib_fields">
						<?php echo $Form->text("Setting.social_url_linkedin",array(
							'div'=>false,
							'style' => "width:300px")); ?>
					</div>
					<div class="clear"></div>
					<!-- google plus -->
					<div class="ib_label">Google+:</div>
					<div class="ib_fields">
						<?php echo $Form->text("Setting.social_url_google_plus",array(
							'div'=>false,
							'style' => "width:300px")); ?>
					</div>
					<div class="clear"></div>
					<div class="ib-margin-top"><button id="widget_submit" class="ib-button">Save Urls</button></div>
				</div>			
				<?php echo $Form->end(); ?>
			</div>
		</div>
	</div>
</div>
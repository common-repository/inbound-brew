<div class="ib-tabs" id="ib-tabs">
	<?php echo $Layout->element($partials_path . "settings_tabs",array(
		'post_type' => $post_type,
		'active' => "General")); 
	$layout = get_option(BREW_DEFAULT_LAYOUT_OPTION);
	?>
	<div class="tabs">
		<div class="tab ib-padding-top">
			<?php echo $Form->create("Settings",array("id"=>"ib_settings_form")); ?>
			<?php wp_nonce_field( 'ib_save_settings', 'ib_settings_nonce' ); ?>
			<!-- Navigation orientation -->
			<div class="ib_settings">
				<div class="ib-column ib-column-4 setting_label">
					Navigation Orientation: <span class="fa fa-info-circle ib-inline_education" data-index="navigation_orientation" data-title="Navigation Orientation"> </span>
				</div>
				<div class="ib-column ib-column-8">
					<div class="ib_plugin-navigation-previews" id="navigation_previews">
						<?php echo $Form->hidden("Settings.default_layout",$layout); ?>
						<div class="ib_nav-preview <?php if($layout == 'side_nav') echo 'active'; ?>" data-id="side_nav">
							<img src="<?php echo BREW_PLUGIN_IMAGES_URL; ?>nav_previews/navigation_type-side.jpg" width="100">
							<div class="title">Side Navigation</div>
						</div>
						<div class="ib_nav-preview <?php if($layout == 'top_nav') echo 'active'; ?>" data-id="top_nav">
							<img src="<?php echo BREW_PLUGIN_IMAGES_URL; ?>nav_previews/navigation_type-top.jpg" width="100">
							<div class="title">Top Navigation</div>
						</div>
						<div class="ib_nav-preview <?php if($layout == 'wp_nav') echo 'active'; ?>" data-id="wp_nav">
							<img src="<?php echo BREW_PLUGIN_IMAGES_URL; ?>nav_previews/navigation_type-wp.jpg" width="100">
							<div class="title">WordPress Native</div>
						</div>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<!-- Auto-collapse WordPress NavBar -->
			<div class="ib_settings">
				<div class="ib-column ib-column-4 setting_label">
					Auto-collapse the WordPress Sidebar? <span class="fa fa-info-circle ib-inline_education" data-index="auto_collapse_wp" data-title="Auto-collapse WordPress Sidebar?"> </span>
				</div>
				<div class="ib-column ib-column-8">
					<?php echo $Form->radio("Settings.auto_collapse_wp",array(
						'true' => "Auto-collapse",
						'false' => "Leave it open")); ?>
				</div>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
			<!-- Auto-collapse Inbound Brew Side Bar -->
			<div class="ib_settings">
				<div class="ib-column ib-column-4 setting_label">
					Auto-collapse the Inbound Brew Sidebar? <span class="fa fa-info-circle ib-inline_education" data-index="auto_collapse_ib" data-title="Auto-collapse Inbound Brew Sidebar?"> </span>
				</div>
				<div class="ib-column ib-column-8">
					<?php echo $Form->radio("Settings.auto_collapse_ib",array(
						'true' => "Auto-collapse",
						'false' => "Leave it open")); ?>
				</div>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
			


			<!-- Usage Data -->
			<div class="ib_settings">
				<div class="ib-column ib-column-4 setting_label">
					Usage Data: <span class="fa fa-info-circle ib-inline_education" data-index="usage_data" data-title="Usage Data"> </span>
				</div>
				<div class="ib-column ib-column-8">
					<?php echo $Form->radio("Settings.usage_data",array(
						'send' => "Send Usage Data",
						'dont_send' => "Don't Send Usage Data")); ?>
				</div>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>


			

			<!-- Active Modules -->
			<div class="ib_settings">
				<div class="ib-column ib-column-4 setting_label">
					Active Modules: <span class="fa fa-info-circle ib-inline_education" data-index="active_modules" data-title="Active Modules"> </span>
				</div>
				<div class="ib-column ib-column-8 ib_active-modules-list">
					<?php 
						// modules that can be deactivated.
						foreach($nav_values['navigation'] as $name => $values):
							if(!@$values['is_module'] || !@$values['can_turn_off']) continue; // not a module
							echo $this->Form->checkbox("Settings.modules.{$name}","on",array(
								'label' => $values['module_name'] . " Module"));					
						endforeach;
						// modules that can't be turned off.
						foreach($nav_values['navigation'] as $name => $values):
							if(!@$values['is_module'] || @$values['can_turn_off']) continue; // not a module
							echo $this->Form->checkbox("Settings.modules.{$name}","on",array(
								'label' => $values['module_name'] . " Module",
								'disabled' => "disabled"));					
						endforeach;
					?>
				</div>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
			<!-- Show Getting Started Menu Item -->
			<div class="ib_settings">
				<div class="ib-column ib-column-4 setting_label">
					Show "Getting Started" in the menu?:</span>
				</div>
				<div class="ib-column ib-column-8">
					<?php echo $Form->radio("Settings.ib_show_getting_started_menu",array(
						'0' => "No, hide it",
						'1' => "Yes, show it")); ?>
				</div>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
			<div class="ib_settings">
				<div class="ib-column ib-column-4 setting_label">
					Need Help?
				</div>
				<div>
					Read our <a id='getting-started-link' href='<?php echo get_admin_url()."admin.php?page=ib-admin-getting-started"; ?>'>Getting Started Page</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;Visit our <a href='https://inboundbrew.com/pluginresources/' target="_blank">Resource Center</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
					Download our <a href='https://inboundbrew.com/inboundmarketingblog/free-download-the-inbound-brew-user-guide/' target="_blank">User Guide</a>
				</div>
			
			</div>
			<div class="clear"></div>
			<?php echo $Form->end(); ?>
		</div>
	</div>
	<div class="ib-margin-top"><button id="submit_form" class="ib-button">Save Settings</button></div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$("#navigation_previews .ib_nav-preview").click(function(){
			var $me = $(this);
			if($me.hasClass("active")) return;
			var $parent = $me.parent();
			$parent.find(".active").removeClass("active");
			$me.addClass("active");
			$parent.find("input").val($me.data("id"));
		});
		$("#submit_form").click(function(){
			$("#ib_settings_form").submit();
		})
	});
</script>
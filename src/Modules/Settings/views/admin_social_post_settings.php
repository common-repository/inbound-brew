<div>
	<?php echo $Form->create("ib_post_settings",array('class'=>"ib-form plain")); ?>
	<div id="poststuff" style="padding-top: 0px;">
		<div id="post-body" class="columns-2">
			<div class="postbox-container" id="postbox-container-1">
				<div class="meta-box-sortables" id="side-sortables">
					<div class="ib-widget">
						<h3 class="handle">Update Settings</h3>
						<div class="ib-inside">
							<div class="ib-actions-instructions">
								Please click the disconnect button below if you don't wish to use <?php echo $network_name; ?> when managing posts/pages.
							</div>
							<div class="publish-actions">
								<div id="delete-action">
									<?php $disconnect_url = wp_nonce_url("admin.php?page={$post_type}&section=ib_disconnect_network&network={$network}","ib-disconnect-network");?>
									<a href="<?php echo $disconnect_url; ?>" class="submitdelete deletion red" id="disconnect_network">[disconnect]</a>
								</div>
								<div class="publishing-action">
									<input type="submit" name="save" class="button fr button-primary button-large" id="save_settings" value="Update" data-id="2">
								</div>
								<div class="clear"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="postbox-container-2" class="postbox-container">
				<div class="ib-network-info">
					<div class="ib-network-logo">
						<img src="<?php echo BREW_PLUGIN_IMAGES_URL;?>/social/logo_<?php echo $network; ?>.png" width="50"/>
					</div>
					<div class="ib-network-instructions">
						<span class="ib-network-name"><?php echo $network_name; ?></span><br>
						Please choose when and how many times you would like to post content to <?php echo $network_name; ?>. These will be your default settings. <strong>You can change them creating or editing Pages/Posts.</strong>
					</div>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
				<div id="ib_post_settings_list">
					<div class="ib-header">
						<div class="fr"><a href="#" id="ib-add-social-option" class="ib-button">Add Option</a></div>
						When To Post to <?php echo $network_name; ?>: <span class="ib-notes">(after post/page is created/updated)</span>
						<div class="clear"></div>
					</div>
					<div class="ib-post-settings"></div>
					<!-- clone -->
					<div class="ib-post-setting" id="social-post-settings-clone" style="display: none;">
						<?php echo $Form->hidden("Clone.0.is_deleted","0");
						echo $Form->hidden("Clone.0.post_setting_id","0"); ?>
						<div id="posting-status" style="display:none;" class="noselect">
							<div class="icon"></div>
							<span></span>
						</div>
						<div class="fr" id="settings-delete" style="display:none;">
							<a href="#" class="ib-button delete" id="delete_row">X</a>
						</div>
						<div class="row-number">1</div>
						<div class="settings">
							<?php echo $Form->radio("Clone.0.when_to_post",array(
								'on' => "On",
								'now' => "Post Imediately"),
								array('div' => "input radio fl")); ?>
							<div id="choose-date-disclamer" class="red fl">* Cannot be added to defaults.</div> 
							<div class="clear-right"></div>
							<div class="post_on_settings">
								<?php echo $Form->select("Clone.0.when_to_post_on_option",array(
									'days' => "Days After",
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
			</div>
		</div>
	</div>
	<?php echo $Form->end(); ?>
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {
	    $("#ib_post_settings_list").ib_postSettings({
			clone_selector:"#social-post-settings-clone",
			list_selector:".ib-post-settings",
			add_option_selector:"#ib-add-social-option",
			data:<?php echo json_encode($post_settings); ?>,
			network:"<?php echo $network; ?>"
	    });
	    $("#disconnect_network").click(function(e){
		   var $me = $(this);
		   e.preventDefault();
		   $.confirm({
				title: 'Disconnect <?php echo $network_name; ?>?',
		        content: "Are you sure you want to disconnect your <?php echo $network_name; ?> connection? The system won't be able to post to  <?php echo $network_name; ?>.",
		        confirm: function(){
					window.location.href = $me.attr('href');
				},
				confirmButton: 'DISCONNECT',
			    cancelButton: 'CANCEL',
			    confirmButtonClass: 'ib_save',
				cancelButtonClass: 'ib_cancel'
			}); 
	    });
	});
</script>
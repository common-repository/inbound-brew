<div class="ib-tabs" id="ib-tabs">
	<?php echo $Layout->element($partials_path . "settings_tabs",array(
		'post_type' => $post_type,
		'active' => "Social Settings")); ?>
	<div class="tabs">
		<!-- Manage Redirect Tabs -->
		<div class="tab ib-padding">
			<?php $now = date("Y-m-d H:i:s"); ?>
			<div class="ib-header">Social Push Settings:</div>
			<!-- facebook -->
			<div class="ib-row ib-social-connection">
				<div class="icon"><span class="fa fa-facebook"></span></div>
				<div class="network">Facebook</div>
				<?php if($settings->social_connected_facebook):
					// calculate expiration
					$diff = strtotime($settings->social_connected_facebook) - strtotime($now);
					$days = floor($diff/86400);
					//links
					$disconnect_url = wp_nonce_url("admin.php?page={$post_type}&section=ib_disconnect_network&network=facebook","ib-disconnect-network");
					$posting_settings = "admin.php?page={$post_type}&section=ib_social_post_settings&network=facebook";
					$posting_history = "admin.php?page={$post_type}&section=ib_social_posting_records&network=facebook";
					$settings_url = "admin.php?page={$post_type}&section=posting_settings&network=facebook"; ?>
					<div class="details">
						<span class="ib_connection-name">Connected as <?php echo $settings->social_name_facebook; ?></span> <span class="ib-notes">(<?php echo sprintf("Expires in %s days",$days); ?>)</span><br>
						<a href="<?php echo $facebook_login_url; ?>" class="ib_connect-network" data-value="Facebook">Update Connected Pages</a>&nbsp;&nbsp;&#124;&nbsp;&nbsp;
						<a href="<?php echo $posting_settings; ?>">Posting Settings</a>&nbsp;&nbsp;&#124;&nbsp;&nbsp;
						<a href="<?php echo $posting_history; ?>">History</a>
					</div>
				<?php endif; ?>
				<div class="buttons">
					<?php if($settings->social_connected_facebook): ?>
						<a href="<?php echo $facebook_login_url; ?>" class="ib_connect-network ib-button" data-value="Twitter">Re-validate</a>
						<a href="<?php echo $disconnect_url; ?>" class="ib-disconnect-network ib-button cancel" data-value="Twitter">Disconnect</a>
					<?php else: ?>
						<a href="<?php echo $facebook_login_url; ?>" class="ib_connect-network ib-button">Connect</a>
					<?php endif; ?>
				</div>
				<div class="clear"></div>
			</div>
			<!-- Twitter -->
			<div class="ib-row ib-social-connection">
				<div class="icon"><span class="fa fa-twitter"></span></div>
				<div class="network">Twitter</div>
				<?php if($settings->social_connected_twitter):
					// calculate expiration 
					if($settings->social_connected_twitter == "0000-00-00 00:00:00"):
						$exp =  "Never";
					else:
						$diff = strtotime($settings->social_connected_twitter) - strtotime($now);
						$days = floor($diff/86400);
						$exp = sprintf("in %s days",$days);
					endif;
					//links
					$disconnect_url = wp_nonce_url("admin.php?page={$post_type}&section=ib_disconnect_network&network=twitter","ib-disconnect-network");
					$posting_settings = "admin.php?page={$post_type}&section=ib_social_post_settings&network=twitter";
					$posting_history = "admin.php?page={$post_type}&section=ib_social_posting_records&network=twitter";
					$settings_url = "admin.php?page={$post_type}&section=posting_settings&network=twitter"; ?>
					<div class="details">
						<span class="ib_connection-name">Connected as <?php echo $settings->social_name_twitter; ?></span> <span class="ib-notes">(<?php echo sprintf("Expires %s",$exp); ?>)</span><br>
						<a href="<?php echo $facebook_login_url; ?>" class="ib_connect-network" data-value="Facebook">Update Connected Pages</a>&nbsp;&nbsp;&#124;&nbsp;&nbsp;
						<a href="<?php echo $posting_settings; ?>">Posting Settings</a>&nbsp;&nbsp;&#124;&nbsp;&nbsp;
						<a href="<?php echo $posting_history; ?>">History</a>
					</div>
				<?php endif; ?>
				<div class="buttons">
					<?php if($settings->social_connected_twitter): ?>
						<a href="<?php echo $twitter_login_url; ?>" class="ib_connect-network ib-button" data-value="Twitter">Re-validate</a>
						<a href="<?php echo $disconnect_url; ?>" class="ib-disconnect-network ib-button cancel" data-value="Twitter">Disconnect</a>
					<?php else: ?>
						<a href="<?php echo $twitter_login_url; ?>" class="ib_connect-network ib-button">Connect</a>
					<?php endif; ?>
				</div>
				<div class="clear"></div>
			</div>
			<!-- LinkedIn -->
			<div class="ib-row ib-social-connection">
				<div class="icon"><span class="fa fa-linkedin"></span></div>
				<div class="network">LinkedIn</div>
				<?php if($settings->social_connected_linked_in):
					// calculate expiration
					$diff = strtotime($settings->social_connected_linked_in) - strtotime($now);
					$days = floor($diff/86400);
					//links
					$disconnect_url = wp_nonce_url("admin.php?page={$post_type}&section=ib_disconnect_network&network=linked_in","ib-disconnect-network");
					$posting_settings = "admin.php?page={$post_type}&section=ib_social_post_settings&network=linked_in";
					$posting_history = "admin.php?page={$post_type}&section=ib_social_posting_records&network=linked_in";
					$settings_url = "admin.php?page={$post_type}&section=posting_settings&network=linked_in"; ?>
					<div class="details">
						<span class="ib_connection-name">Connected as <?php echo $settings->social_name_linked_in; ?></span> <span class="ib-notes">(<?php echo sprintf("Expires in %s days",$days); ?>)</span><br>
						<a href="<?php echo $linked_in_login_url; ?>" class="ib_connect-network" data-value="Facebook">Update Connected Pages</a>&nbsp;&nbsp;&#124;&nbsp;&nbsp;
						<a href="<?php echo $posting_settings; ?>">Posting Settings</a>&nbsp;&nbsp;&#124;&nbsp;&nbsp;
						<a href="<?php echo $posting_history; ?>">History</a>
					</div>
				<?php endif; ?>
				<div class="buttons">
					<?php if($settings->social_connected_linked_in): ?>
						<a href="<?php echo $linked_in_login_url; ?>" class="ib_connect-network ib-button" data-value="Twitter">Re-validate</a>
						<a href="<?php echo $disconnect_url; ?>" class="ib-disconnect-network ib-button cancel" data-value="Twitter">Disconnect</a>
					<?php else: ?>
						<a href="<?php echo $linked_in_login_url; ?>" class="ib_connect-network ib-button">Connect</a>
					<?php endif; ?>
				</div>
				<div class="clear"></div>
			</div>
			<div class="ib_settings-divider"></div>
			<div class="ib-header">Social Sharing Widget: <span class="ib-notes">(widget will be on every page and post)</span></div>
			<p class="ib-instructions">You can allow your users to share your content on social media. Select the Networks you want available and how the widget will look like.</p>
			<div class="ib-margin-top">
				<form method="POST" action="admin.php?page=<?php echo $post_type; ?>&section=update_social_widget" id="ib-social-share-settings">
					<input type="hidden" name="action" value="ib_manage_social_share_widget"/>
					<div class="ib-column ib-column-8 ib-margin-bottom">
						<div class="ib-tabs" id="social-widget-tabs">
							<div class="tab-wrapper">
								<a href="#networks">Networks</a>
								<a href="#title">Title</a>
								<a href="#background">Background</a>
								<a href="#icons">Icons</a>
								<a href="#position">Position</a>
								<a href="#options">Options</a>
							</div>
							<div class="tabs">
								<!-- list of available networks -->
								<div id="networks" class="ib-social-widget-network-list">
									<?php foreach($widget_networks as $wnetwork => $values):
											$field = "{$wnetwork}_share";
											$class = $values['class'];
											$checked = (@$widget[$field])? "checked":""; // connected ?>
											<div class="ib-row ib-td ib-white">
												<div data-network="<?php echo $wnetwork; ?>" class="ib-available-network noselect">
													<?php $checked = (@$widget[$field])? "checked":""; ?>
													<input type="hidden" name="<?php echo $wnetwork; ?>_share" value="<?php if(!empty($checked)) echo '1'; ?>">
													<span class="fa fa-check-circle fa-2x ib-seo-item-checkbox <?php echo $checked; ?>"></span> 
													<span class="fa fa-<?php echo $class; ?> fa-2x"></span> <?php echo $values['name']; ?>
												</div>
											</div>
									<?php endforeach; ?>
								</div>
								<!-- title settings -->
								<div id="title" class="ib-social-widget-title">
									<table class="ib_fields-table" cellpadding="0" cellspacing="0">
										<tr class="ib-white">
											<td class="label-left" width="75px">Text:</td>
											<td><input data-group="title" id="widget_title_field" name="title[text]" type="text" value="<?php echo @$widget['title']['text'];?>" style="width:98%;"></td>
										</tr>
										<tr>
											<td class="label-left" width="75px">Color:</td>
											<td class="field-right"><input data-group="title" name="title[color]" class="ib_color-picker" type="text" maxlength="6" size="6" value="<?php echo @$widget['title']['color'];?>" /></td>
										</tr>
										<tr  class="ib-white">
											<td class="label-left">Size:</td>
											<td class="field-right"><input data-group="title" name="title[font_size]" type="text" class="slider-default" value="<?php echo @$widget['title']['font_size'];?>" size="4" readonly/>
					                            <span class="text-info"><span class="text-value"></span>px</span>
					                        </td>
					                    </tr>
										<tr  class="ib-white">
											<td colspan="2" class="ib_slider">
												<div data-group="title" data-name="font_size" data-min="0" data-max="100" data-value="<?php echo @$widget['title']['font_size'];?>" class="ui-slider" aria-disabled="false"></div>
						                    </td>
										</tr>
										<tr>
											<td class="label-left">Margin:</td>
											<td class="field-right"><input data-group="title" name="title[margin_bottom]" type="text" class="slider-default" value="<?php echo @$widget['title']['margin_bottom'];?>" size="4" readonly/>
					                        <span class="text-info"><span class="text-value"></span>px</span></td>
										</tr>
										<tr>
											<td colspan="2" class="ib_slider">
												<div data-group="title" data-name="margin_bottom" data-min="0" data-max="20" data-value="<?php echo @$widget['title']['margin_bottom'];?>" class="ui-slider" aria-disabled="false"></div></td>
										</tr>
									</table>
								</div>
								<!-- background settings -->
								<div id="background" class="ib-social-widget-background">
									<table class="ib_fields-table" cellpadding="0" cellspacing="0">
										<tr class="ib-white">
											<td colspan="2" class="ib_section-header">Background Color:</td>
										</tr>
										<tr>
											<td class="label-left" width="75px">Color:</td>
											<td class="field-right"><input data-group="background" name="background[background_color]" class="ib_color-picker" type="text" maxlength="6" size="6" value="<?php echo @$widget['background']['background_color'];?>" /></td>
										</tr>
										<tr class="ib-white">
											<td colspan="2" class="ib_section-header">Border:</td>
										</tr>
										<tr>
											<td class="label-left" width="75px">Color:</td>
											<td class="field-right"><input data-group="background" name="background[border_color]" class="ib_color-picker" type="text" maxlength="6" size="6" value="<?php echo @$widget['background']['border_color'];?>" /></td>
										</tr>
										<tr class="ib-white">
											<td class="label-left">Size:</td>
											<td class="field-right"><input data-group="background" name="background[border_width]" type="text" class="slider-default" value="<?php echo @$widget['background']['border_width'];?>" size="4" readonly/>
					                        <span class="text-info"><span class="text-value"></span>px</span></td>
										</tr>
										<tr class="ib-white">
											<td colspan="2" class="ib_slider">
												<div data-group="background" data-name="border_width" data-min="0" data-max="50" data-value="<?php echo @$widget['background']['border_width'];?>" class="ui-slider" aria-disabled="false"></div></td>
										</tr>
										<tr>
											<td class="label-left">Radius:</td>
											<td class="field-right"><input data-group="background" name="background[border_radius]" type="text" class="slider-default" value="<?php echo @$widget['background']['border_radius'];?>" size="4" readonly/>
					                            <span class="text-info"><span class="text-value"></span>px</span>
					                        </td>
					                    </tr>
										<tr>
											<td colspan="2" class="ib_slider">
												<div data-group="background" data-name="border_radius" data-min="0" data-max="100" data-value="<?php echo @$widget['background']['border_radius'];?>" class="ui-slider" aria-disabled="false"></div>
						                    </td>
										</tr>
										<tr class="ib-white">
											<td colspan="2" class="ib_section-header">Padding:</td>
										</tr>
										<tr>
											<td class="label-left">Horizontal:</td>
											<td class="field-right"><input data-group="background" name="background[h_padding]" type="text" class="slider-default" data-type="slider" value="<?php echo @$widget['background']['h_padding'];?>" size="4" readonly/><span>px</span></td>
										</tr>
										<tr>
											<td colspan="2" class="ib_slider">
												<div data-group="background" data-name="h_padding" data-min="0" data-max="60" data-value="<?php echo @$widget['background']['h_padding'];?>" class="ui-slider" aria-disabled="false"></div></td>
										</tr>
										<tr class="ib-white">
											<td class="label-left">Vertical:</td>
											<td class="field-right"><input data-group="background" name="background[v_padding]" type="text" class="slider-default" data-type="slider" value="<?php echo @$widget['background']['v_padding'];?>" size="4" readonly/><span>px</span>
					                        </td>
					                    </tr>
										<tr class="ib-white">
											<td colspan="2" class="ib_slider">
												<div data-group="background" data-name="v_padding" data-min="0" data-max="60" data-value="<?php echo @$widget['background']['v_padding'];?>" class="ui-slider" aria-disabled="false"></div>
						                    </td>
										</tr>
									</table>
								</div>
								<!-- icon settings -->
								<div id="icons" class="ib-social-widget-icons">
									<table class="ib_fields-table" cellpadding="0" cellspacing="0">
										<tr>
											<td class="label-left">Type:</td>
											<td>
												<input type="radio" data-group="icons" name="icons[type]" value="normal" <?php if(@$widget['icons']['type'] == "normal") echo "checked"; ?>/ > <span class="fa fa-1x fa-facebook"> </span> <span class="ib-notes">Normal</span> &nbsp;&nbsp;&nbsp;
												<input type="radio" data-group="icons" name="icons[type]" value="square" <?php if(@$widget['icons']['type'] == "square") echo "checked"; ?>/ > <span class="fa fa-1x fa-facebook-square"> </span> <span class="ib-notes">Square</span>								
											</td>
										</tr>
										<tr class='ib-white'>
											<td class="label-left">Size:</td>
											<td class="field-right"><input data-group="icons" name="icons[size]" type="text" class="slider-default" value="<?php echo @$widget['icons']['size'];?>" size="4" readonly/>
					                            <span class="text-info"><span class="text-value"></span>x</span>
					                        </td>
					                    </tr>
										<tr class='ib-white'>
											<td colspan="2" class="ib_slider">
												<div data-group="icons" data-name="size" data-min="1" data-max="5" data-value="<?php echo @$widget['icons']['size'];?>" class="ui-slider" aria-disabled="false"></div>
						                    </td>
										</tr>
										<tr>
											<td class="label-left">Spacing:</td>
											<td class="field-right"><input data-group="icons" name="icons[margin_bottom]" type="text" class="slider-default" value="<?php echo @$widget['icons']['margin_bottom'];?>" size="4" readonly/>
					                            <span class="text-info"><span class="text-value"></span>px</span>
					                        </td>
					                    </tr>
										<tr>
											<td colspan="2" class="ib_slider">
												<div data-group="icons" data-name="margin_bottom" data-min="0" data-max="50" data-value="<?php echo @$widget['icons']['margin_bottom'];?>" class="ui-slider" aria-disabled="false"></div>
						                    </td>
										</tr>
										<tr class="ib-white">
											<td colspan="2" class="ib_section-header">Icon Colors:</td>
										</tr>
										<tr>
											<td class="label-left" width="75px">Facebook:</td>
											<td class="field-right"><input data-group="icons" name="icons[facebook]" class="ib_color-picker" type="text" maxlength="6" size="6" value="<?php echo @$widget['icons']['facebook'];?>" /></td>
										</tr>
										<tr class="ib-white">
											<td class="label-left" width="75px">Twitter:</td>
											<td class="field-right"><input data-group="icons" name="icons[twitter]" class="ib_color-picker" type="text" maxlength="6" size="6" value="<?php echo @$widget['icons']['twitter'];?>" /></td>
										</tr>
										<tr>
											<td class="label-left" width="75px">LinkedIn:</td>
											<td class="field-right"><input data-group="icons" name="icons[linked_in]" class="ib_color-picker" type="text" maxlength="6" size="6" value="<?php echo @$widget['icons']['linked_in'];?>" /></td>
										</tr>
										<tr class="ib-white">
											<td class="label-left" width="75px">Google +:</td>
											<td class="field-right"><input data-group="icons" name="icons[google_plus]" class="ib_color-picker" type="text" maxlength="6" size="6" value="<?php echo @$widget['icons']['google_plus'];?>" /></td>
										</tr>
									</table>
								</div>
								<!-- position -->
								<div id="position" class="ib-social-widget-background">
									<table class="ib_fields-table" cellpadding="0" cellspacing="0">
										<tr>
											<td class="label-left">Location:</td>
											<td>
												<input type="radio" data-group="position" name="position[location]" value="left" <?php if(@$widget['position']['location'] == "left") echo "checked"; ?>/ > <span class="ib-notes">Left of Screen</span>								
												<input type="radio" data-group="position" name="position[location]" value="right" <?php if(@$widget['position']['location'] == "right") echo "checked"; ?>/ > <span class="ib-notes">Right of Screen</span> &nbsp;&nbsp;&nbsp;
											</td>
										</tr>
										<tr class='ib-white'>
											<td class="label-left">% From Top:</td>
											<td class="field-right"><input data-group="position" name="position[top]" type="text" class="slider-default" value="<?php echo @$widget['position']['top'];?>" size="4" readonly/>
					                            <span class="text-info"><span class="text-value"></span>%</span>
					                        </td>
					                    </tr>
										<tr class='ib-white'>
											<td colspan="2" class="ib_slider">
												<div data-group="position" data-name="top" data-min="0" data-max="100" data-value="<?php echo @$widget['position']['top'];?>" class="ui-slider" aria-disabled="false"></div>
						                    </td>
										</tr>
									</table>
								</div>
								<!-- position -->
								<div id="options" class="ib-social-widget-background">
									<table class="ib_fields-table" cellpadding="0" cellspacing="0">
										<tr>
											<td class="label-left text-top">Show on:</td>
											<td>
												<div class="input checkbox" style="padding-top: 0px;">
													<?php $checked = (@$widget_options['show_on_mobile'])? "checked":""; ?>
													<input type="checkbox" name="options[show_on_mobile]" value="on" <?php echo $checked; ?>>
													<strong>Mobile version of the site.</strong>
												</div>
												<?php foreach($post_types as $pt):
													$checked = "";
													foreach($widget_options as $index =>$opt):
													
														if($index == $pt->name && $opt == "on"):
															$checked = "checked";
														endif;
													endforeach; ?>
												<div class="input checkbox">
													<input type="checkbox" name="options[<?php echo $pt->name; ?>]" value="on" <?php echo $checked; ?>>
													<strong><?php echo $pt->labels->name; ?></strong>
												</div>
												<?php endforeach; ?>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>	
					</div>
					<div class="ib-column ib-column-4 ib-margin-bottom">
						<div class="ib-widget">
							<h3 class="handle">Preview:</h3>
							<div class="ib-inside" id="widget-preview">
								<div class="ib-sharing-widget">
									<div class="ib-share-title" id='share_widget_title'>share</div>
									<!--div class="ib-networks" id="active-networks"-->
										<?php foreach($widget_networks as $wnetwork => $values):
											$field = "{$wnetwork}_share";
											$class = $values['class'];
											$dataClass = str_replace("-square", "", $class);
											$style = (@$widget[$field])? "" : "display:none;"; // connected ?>
												<a href="" class="ib-network" data-id="<?php echo $wnetwork; ?>" style="<?php echo $style; ?>">
													<span class="fa fa-<?php echo $class; ?> fa-3x" data-class="<?php echo $dataClass; ?>">
												</a>
										<?php endforeach; ?>
									<!--/div-->
								</div>
							</div>
						</div>
					</div>
					<div class="clear"></div>
					<div class="ib-margin-top"><button id="widget_submit" class="ib-button">Save Settings</button></div>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(".ib-disconnect-network").click(function(e){
			e.preventDefault();
			var $me = $(this);
			var network = $me.attr('data-value');
			var href = $me.attr('href');
			$.confirm({
				title: "Disconnect " + network,
		        content: "Are you sure you want to disconnect your "+network+" connection? The system won't be able to post to "+network+".",
		        confirm: function(){
					window.location.href = href;
				},
				confirmButton: 'Disconnect',
			    cancelButton: 'CANCEL',
			    confirmButtonClass: 'ib_save',
				cancelButtonClass: 'ib_cancel'
			});
		});
		$("#ib-social-share-settings").ib_socialSharingWidget({admin:true});
	});
</script>	
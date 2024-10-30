<div class="ib-tabs" id="ib-tabs">
	<?php echo $Layout->element($partials_path . "settings_tabs",array(
		'post_type' => $post_type,
		'active' => "Social Share Widget")); ?>
	<div class="tabs">
		<!-- Manage Redirect Tabs -->
		<div class="tab ib-padding">
			<div class="ib-header">Social Sharing Widget: <span class="ib-notes">(widget will be on every page and post)</span></div>
			<p class="ib-instructions">You can allow your users to share your content on social media. Select the Networks you want available and how the widget will look like.</p>
			<div class="ib-margin-top">
				<form method="POST" action="admin.php?page=<?php echo $post_type; ?>&section=update_social_widget" id="ib-social-share-settings">
					<input type="hidden" name="action" value="ib_manage_social_share_widget"/>
					<div class="ib-column ib-column-8 ib_social-share-widget-settings">
						<!-- networks -->
						<?php //print_Debug($Form->data); ?>
						<div class="ib-row ib_share-widget-networks">
							<?php foreach($widget_networks as $wnetwork => $values):
								$field = "{$wnetwork}_share";
								echo $Form->checkbox("ShareWidget.{$field}","1",array(
									'data-network' => $wnetwork,
									'label' => $values['name']
								));
							endforeach; ?>
						</div>
						<!-- Title -->
						<div class="ib-row">
							<div class="fl ib-margin-right">
								<div class="ib_label">Text:</div>
								<div class="ib_fields">
									<?php echo $Form->text("ShareWidget.title.text",array(
										'data-group' => "title",
										'data-property' => "text",
										'div' => false)); ?>
									<!-- uppercase -->
									<?php $uppercase = @$Form->data['ShareWidget']['title']['text_transform']; ?>
									<a id="" class="ib_cta-transform uppercase <?php if($uppercase) echo 'ib-active'; ?>" data-property="text_transform" data-value="uppercase" data-state="normal">Aa</a>
									<?php echo @$Form->hidden("ShareWidget.title.text_transform","",array(
										'data-group' => "title",
										'data-property' => "text_transform")); ?>
									<!-- bold -->
									<?php $bold = @$Form->data['ShareWidget']['title']['font_weight']; ?>
									<a id="" class="ib_cta-transform bold <?php if($bold) echo 'ib-active'; ?>" data-property="font_weight" data-value="bold" data-state="normal">B</a>
									<?php echo $Form->hidden("ShareWidget.title.font_weight","",array(
										'data-group' => "title",
										'data-property' => "font_weight")); ?>
									<!-- italic -->
									<?php $italic = @$Form->data['ShareWidget']['title']['font_style']; ?>
									<a id="" class="ib_cta-transform italic <?php if($italic) echo 'ib-active'; ?>" data-property="font_style" data-value="italic" data-state="normal">I</a>
									<?php echo $Form->hidden("ShareWidget.title.font_style","",array(
										'data-group' => "title",
										'data-property' => "font_style")); ?>
									<?php $underline = @$Form->data['ShareWidget']['title']['text_decoration']; ?>
									<!-- underline -->						
									<a id="" class="ib_cta-transform underline <?php if($underline) echo 'ib-active'; ?>" data-property="text_decoration" data-value="underline" data-state="normal">U</a>
									<?php echo $Form->hidden("ShareWidget.title.text_decoration","",array(
										'data-group' => "title",
										'data-property' => "text_decoration")); ?>
								</div>
							</div>
							<div class="fl">
								<div class="ib_label">Font Size:</div>
								<div class="ib_fields slider-field">
									<?php echo $Form->text("ShareWidget.title.font_size",array(
										'data-group' => "title",
										'size' => 2,
										'read-only' => true,
										'data-property' => "font_size")); ?>
										<div data-group="title" data-name="font_size" data-min="0" data-max="100" data-value="<?php echo @$Form->data['ShareWidget']['title']['font_size'];?>" class="ui-slider" aria-disabled="false"></div>
								</div>
							</div>
							<div class="clear"></div>
						</div>
						<!-- font family and font color -->
						<div class="ib-row">
							<div class="fl ib-margin-right">
								<div class="ib_label">Font Family:</div>
								<div class="ib_fields">
									<?php echo $Form->select("ShareWidget.title.font_family",$fonts,array(
										'data-group' => "title",
										'data-property' => "font_family")) ?>
								</div>
							</div>
							<div class="fl">
								<div class="ib_label">Font Color:</div>
								<div class="ib_fields">
									<?php echo $Form->text("ShareWidget.title.color",array(
										'data-group' => "title",
										'data-property' => "color",
										'class' => "ib_color-picker",
										'div' => false)); ?>
								</div>
							</div>
							<div class="clear"></div>
						</div>
						<!-- icons -->
						<div class="ib-row">
							<div class="ib_label">Icon Style:</div>
							<div class="ib_fields ib_icon-style-list">
								<div class="icons">
									<table cellpadding="0" cellspacing="0">
										<tr>
											<td><?php echo $Form->radio("ShareWidget.icons.type",array(
												'normal' => ""),array(
													'data-group' => "icons",
													'data-property' => "type",
													'label' => false,
													'div' => false,
												)); ?>
											</td>
											<td><?php echo $Form->radio("ShareWidget.icons.type",array(
												'square' => ""),array(
													'data-group' => "icons",
													'data-property' => "type",
													'label' => false,
													'div' => false,
												)); ?>
											</td>
											<td><?php echo $Form->radio("ShareWidget.icons.type",array(
												'image' => ""),array(
													'data-group' => "icons",
													'data-property' => "type",
													'label' => false,
													'div' => false,
												)); ?>
											</td>
										</tr>
										<tr>
											<td><span class="fa fa-2x fa-facebook"></span></td>
											<td><span class="fa fa-2x fa-facebook-square"></span></td>
											<td><span class="fa fa-2x fa-facebook-image"></span></td>
										</tr>
										<tr>
											<td><span class="fa fa-2x fa-twitter"></span></td>
											<td><span class="fa fa-2x fa-twitter-square"></span></td>
											<td><span class="fa fa-2x fa-twitter-image"></span></td>
										</tr>
										<tr>
											<td><span class="fa fa-2x fa-linkedin"></span></td>
											<td><span class="fa fa-2x fa-linkedin-square"></span></td>
											<td><span class="fa fa-2x fa-linkedin-image"></span></td>
										</tr>
										<tr>
											<td><span class="fa fa-2x fa-google-plus"></span></td>
											<td><span class="fa fa-2x fa-google-plus-square"></span></td>
											<td><span class="fa fa-2x fa-google-plus-image"></span></td>
										</tr>
									</table>
								</div>
								<div class="colors" id="share_widget_icon_colors">
									<div class="ib-row">
										<div class="ib_label">Facebook Color:</div>
										<div class="ib_fields">
											<?php echo $Form->text("ShareWidget.icons.facebook",array(
											'data-group' => "icons",
											'data-property' => "facebook",
											'class' => "ib_color-picker",
											'div' => false)); ?>
										</div>
										<div class="clear"></div>
									</div>
									<div class="ib-row">
										<div class="ib_label">Twitter Color:</div>
										<div class="ib_fields">
											<?php echo $Form->text("ShareWidget.icons.twitter",array(
											'data-group' => "icons",
											'data-property' => "twitter",
											'class' => "ib_color-picker",
											'div' => false)); ?>
										</div>
										<div class="clear"></div>
									</div>
									<div class="ib-row">
										<div class="ib_label">LinkedIn Color:</div>
										<div class="ib_fields">
											<?php echo $Form->text("ShareWidget.icons.linked_in",array(
											'data-group' => "icons",
											'data-property' => "linked_in",
											'class' => "ib_color-picker",
											'div' => false)); ?>
										</div>
										<div class="clear"></div>
									</div>
									<div class="ib-row">
										<div class="ib_label">Google Plus Color:</div>
										<div class="ib_fields">
											<?php echo $Form->text("ShareWidget.icons.google_plus",array(
											'data-group' => "icons",
											'data-property' => "google_plus",
											'class' => "ib_color-picker",
											'div' => false)); ?>
										</div>
										<div class="clear"></div>
									</div>
								</div>
								<div class="clear"></div>
							</div>
						</div>
						<div class="clear"></div>
						<!-- icon size and spacing -->
						<div class="ib-row">
							<div class="fl ib-margin-right">
								<div class="ib_label">Icon Size:</div>
								<div class="ib_fields slider-field">
								<?php echo $Form->text("ShareWidget.icons.size",array(
									'data-group' => "icons",
									'size' => 2,
									'read-only' => true,
									'data-property' => "size")); ?>
									<div data-group="title" data-name="size" data-min="1" data-max="5" data-value="<?php echo @$Form->data['ShareWidget']['icons']['size'];?>" class="ui-slider" aria-disabled="false"></div>
								</div>
							</div>
							<div class="fl">
								<div class="ib_label">Icon Spacing:</div>
								<div class="ib_fields slider-field">
								<?php echo $Form->text("ShareWidget.icons.margin_bottom",array(
									'data-group' => "icons",
									'size' => 2,
									'read-only' => true,
									'data-property' => "margin_bottom")); ?>
									<div data-group="title" data-name="margin_bottom" data-min="0" data-max="100" data-value="<?php echo @$Form->data['ShareWidget']['icons']['margin_bottom'];?>" class="ui-slider" aria-disabled="false"></div>
								</div>
							</div>
							<div class="clear"></div>
						</div>
						<div class="clear"></div>
						<!-- box margins -->
						<div class="ib-row">
							<div class="fl ib-margin-right">
								<div class="ib_label">Top Padding:</div>
								<div class="ib_fields slider-field">
									<?php echo $Form->text("ShareWidget.background.v_padding",array(
										'data-group' => "background",
										'size' => 2,
										'read-only' => true,
										'data-property' => "v_padding")); ?>
										<div data-group="title" data-name="v_padding" data-min="0" data-max="60" data-value="<?php echo @$Form->data['ShareWidget']['background']['v_padding'];?>" class="ui-slider" aria-disabled="false"></div>
								</div>
							</div>
							<div class="fl">
								<div class="ib_label">Side Padding:</div>
								<div class="ib_fields slider-field">
									<?php echo $Form->text("ShareWidget.background.h_padding",array(
										'data-group' => "background",
										'size' => 2,
										'read-only' => true,
										'data-property' => "h_padding")); ?>
									<div data-group="title" data-name="h_padding" data-min="0" data-max="60" data-value="<?php echo @$Form->data['ShareWidget']['background']['h_padding'];?>" class="ui-slider" aria-disabled="false"></div>
								</div>
							</div>
							<div class="clear"></div>
						</div>
						<div class="clear"></div>
						<!-- background color & border color -->
						<div class="ib-row">
							<div class="fl ib-margin-right">
								<div class="ib_label">Background Color:</div>
								<div class="ib_fields">
									<?php echo $Form->text("ShareWidget.background.background_color",array(
										'data-group' => "background",
										'data-property' => "background_color",
										'class' => "ib_color-picker",
										'div' => false)); ?>
								</div>
							</div>
							<div class="fl">
								<div class="ib_label">Border Color:</div>
								<div class="ib_fields">
									<?php echo $Form->text("ShareWidget.background.border_color",array(
										'data-group' => "background",
										'data-property' => "border_color",
										'class' => "ib_color-picker",
										'div' => false)); ?>
								</div>
							</div>
							<div class="clear"></div>
						</div>
						<!-- border size and border radius -->
						<div class="ib-row">
							<div class="fl ib-margin-right">
								<div class="ib_label">Border Size:</div>
								<div class="ib_fields slider-field">
									<?php echo $Form->text("ShareWidget.background.border_width",array(
										'data-group' => "background",
										'size' => 2,
										'read-only' => true,
										'data-property' => "border_width")); ?>
										<div data-group="title" data-name="border_width" data-min="0" data-max="60" data-value="<?php echo @$Form->data['ShareWidget']['background']['border_width'];?>" class="ui-slider" aria-disabled="false"></div>
								</div>
							</div>
							<div class="fl">
								<div class="ib_label">Border Radius:</div>
								<div class="ib_fields slider-field">
									<?php echo $Form->text("ShareWidget.background.border_radius",array(
										'data-group' => "background",
										'size' => 2,
										'read-only' => true,
										'data-property' => "border_radius")); ?>
										<div data-group="title" data-name="border_radius" data-min="0" data-max="60" data-value="<?php echo @$Form->data['ShareWidget']['background']['border_radius'];?>" class="ui-slider" aria-disabled="false"></div>
								</div>
							</div>
							<div class="clear"></div>
						</div>
						<!-- screen position -->
						<div class="ib-row">
							<div class="fl ib-margin-right">
								<div class="ib_label">Screen Position:</div>
								<div class="ib_fields">
									<?php echo $Form->select("ShareWidget.position.location",array(
										'right' => "Right",
										'left'=>"Left"),array(
										'data-group' => "position",
										'data-property' => "location")) ?>
								</div>
							</div>
							<div class="fl">
								<div class="ib_label">% from Top:</div>
								<div class="ib_fields slider-field">
									<?php echo $Form->text("ShareWidget.position.top",array(
										'data-group' => "position",
										'size' => 2,
										'read-only' => true,
										'data-property' => "top")); ?>
									<div data-group="title" data-name="top" data-min="0" data-max="100" data-value="<?php echo @$Form->data['ShareWidget']['position']['top'];?>" class="ui-slider" aria-disabled="false"></div>
								</div>
							</div>
							<div class="clear"></div>
						</div>
						<!-- show on -->
						<div class="ib-row">
							<div class="ib_label">Show on:</div>
							<div class="ib_fields">
								<div class="input checkbox" style="padding-top: 0px;">
									<?php echo $Form->checkbox("ShareWidget.options.show_on_mobile","on",array(
										'label' => "Mobile version of the site",
										'div' => false,
									)); ?>
								</div>
								<?php foreach($post_types as $pt):
									if($pt->name == "attachment") continue;
									echo $Form->checkbox("ShareWidget.options.{$pt->name}","on",array(
										'label' => $pt->labels->name
									)); ?>
								<?php endforeach; ?>
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
										<?php 
											$widget = $Form->data['ShareWidget'];
											foreach($widget_networks as $wnetwork => $values):
											$field = "{$wnetwork}_share";
											$class = $values['class'];
											$dataClass = str_replace("-square", "", $class);
											$size = $widget['icons']['size'];
											$style = (@$widget[$field])? "" : "display:none;"; // connected ?>
												<a href="" class="ib-network" data-id="<?php echo $wnetwork; ?>" style="<?php echo $style; ?>">
													<span class="fa fa-<?php echo $class; ?> fa-<?php echo $size;?>x" data-class="<?php echo $dataClass; ?>">
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
		$("#ib-social-share-settings").ib_socialSharingWidget({
			admin:true,
			options:<?php echo json_encode($Form->data['ShareWidget']); ?>
		});
	});
</script>	
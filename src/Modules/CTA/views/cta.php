<div class="wrap">
	<?php echo $Breadcrumb->printPath(); ?>
	<p class="ib-inline-education">A CTA (or call-to-action) is either a text link, button, or image that prompt the user to take action such as download content, buy a product, or request a consultation. Once a user clicks on a CTA, they are redirected to a landing page that captures their information. Our CTA tool offers you the ability to create, customize, and publish CTA buttons on your website. <span class="ib_blog-link">For more information about CTA Management visit <a href="<?php echo BREW_PLUGIN_BLOG_URL; ?>ctas" target="_blank">The Inbound Brew Blog</a>.</span></p>
	<?php echo $Form->create("ib_cta",array("url"=>$submit_url));
	wp_nonce_field('ib_cta_meta_box_nonce' ); ?>
	<div id="post-body-content">
		<div id="titlediv">
			<div id="titlewrap">
				<input type="text" spellcheck="true" id="title" value="<?php if(isset($post_title)) echo $post_title; ?>" size="30" name="post_title" placeholder="Enter CTA Name" required>
			</div>
		</div>
	</div>
	<!-- CTA PREVIEW -->
	<?php if(!@$post_content) $post_content = '<a class="cta-btn" data-role="cta-button">Button text</a>'; ?>
	<div class="ib-cta-preview">
		<div class="fr"><a href="#" id="save_as_template" class="ib-button green">Save As Template</a><a href="#" class="ib-button green" id="load_from_template">Load From Template</a></div>
		<div class="clear"></div>
		<div class="ib-widget">
			<h3 class="handle">Preview</h3>
			<div>
				<div id="button-area">
		            <div class="button-area-wrapper" style="text-align: center;margin-top:1em;" data-role="cta-display-wrapper">
						<div class="ib-column ib-column-12" id="cta-display-wrapper">
						<?php echo $post_content; ?>
						</div>
		            </div>
		        </div>
		        <div style="display:none;">
		            <textarea rows="4" cols="50" data-role="cta-input" name="post_content" id="ib_post_content"><?php echo $post_content; ?></textarea>
		        </div>
				<div class="clear"></div>
			</div>
		</div>
		<div class="ib-margin-top">
	    	<button class="ib-button save" id="submit_form"><?php echo $submit_button_title; ?></button>
	    </div>
	</div>
	<!-- END CTA PREVIEW -->
	<!-- SETTINGS -->
		<div class="ib-cta-tabs" id="cta_tabs">
		<a href="#cta-font" class="font"></a>
		<a href="#cta-background" class="background"></a>
		<a href="#cta-border" class="border"></a>
		<a href="#cta-target" class="target"></a>
		<!--    <a href="#cta-points" class="points"></a>-->
		<div class="tabs">
			<!-- fonts -->
			<div id="cta-font">
				<h3 class="handle">Type Options:</h3>
				<div class="ib_no-padding">
					<table class="ib_fields-table" cellpadding="0" cellspacing="0">
						<tr>
							<td colspan="3">
								<input type="radio" id="cta_type_button" name="cta_type" data-name="cta-type" data-role="button" value="button" <?php echo ($button)?'checked':'';?>>Button
			                    &nbsp;
			                    <input type="radio" id="cta_type_image" name="cta_type" data-name="cta-type" data-role="image" value="image" <?php echo ($image)?'checked':'';?>>Image
							</td>
						</tr>
						<tr class="alt0 ib_cta_type_button-row">
							<td colspan="3" class="label-top">Button Text:</td>
						</tr>
						<tr class="alt0 ib_cta_type_button-row">
							<td colspan="3" class="field-top-label"><input name="button_text" type="text" placeholder="Button text" value="<?php echo isset($button_text)?$button_text:'Button Text'; ?>" id="button_text" /></td>
						</tr>
						<tr class="ib_cta_type_button-row">
							<td class='label-top' colspan="3">Font:</td>
						</tr>
						<tr class="ib_cta_type_button-row">
							<td colspan="3" class="field-top-label">
								<select name="font" id="font_family" data-name="font-family">
		                            <?php foreach ($fonts as $key=>$value): ?>
		                                <option value="<?php echo $key; ?>" <?php echo (isset($font) && $font == $key)?' selected':'';?> ><?php echo $value; ?></option>
		                            <?php endforeach; ?>
		                        </select>
							</td>
						</tr>
						<tr class="alt0 ib_cta_type_button-row">
							<td class="label" width="50px">Size:</td>
							<td width="80px"><input id="font_size" type="text" name="font_size" value="<?php echo isset($font_size)?$font_size:'';?>" size="4" class="ib_number"/>
	                        <span class="text-info"><span class="text-value"></span>px</span></td>
							<td>
								<input id="font_weight-input" type="hidden" name="font_weight" value="<?php echo (@$font_weight)?'bold':'';?>">
								<a id="font_weight-button" class="ib_cta-transform bold <?php echo (@$font_weight)?'ib-active':'';?>" data-name="font-weight" data-item="bold">B</a>
								<input id="font_style-input" type="hidden" name="font_style" value="<?php echo (@$font_style)?'italic':'';?>">
								<a id="font_style-button" class="ib_cta-transform italic <?php echo (@$font_style)?'ib-active':'';?>" data-name="font-style" data-item="italic">I</a>
								<input id="text_transform-input" type="hidden" name="text_transform" value="<?php echo (@$text_transform)?'uppercase':'';?>">
								<a id="text_transform-button" class="ib_cta-transform uppercase <?php echo (@$text_transform)?'ib-active':'';?>" data-name="text-transform" data-item="uppercase">Aa</a>
							</td>
						</tr>
						<tr class="ib_cta_type_button-row">
							<td class="label">Color:</td>
							<td colspan="2">
								<input name="color" type="text" data-name="color" data-type="color-picker" class="ib_color-picker" maxlength="6" size="6" value="<?php echo isset($color)?$color:'';?>" />
			                </td>
						</tr>
						<tr class="ib_cta_type_image-row alt0" style="display:<?php echo ($image)?'':'none';?>">
							<td colspan="3"><div data-name="image" data-role="cta-type">
			                    <input type="hidden" name="upload_image_id" id="upload_image_id" value="<?php echo @$upload_image_id; ?>" />
			                    <div class="cta-image-preview" id="cta_image_preview" style="<?php echo (@$cta_image_url)? '':'display:none;'; ?>">
									<input type="hidden" id="cta_image_url" name="cta_image_url" value="<?php echo @$cta_image_url; ?>">
									<input type="hidden" id="cta_thumbnail" name="cta_thumbnail" value="<?php echo @$cta_thumbnail; ?>">
									<div class="cta-thumbnail">
										<?php if(@$cta_thumbnail):?>
											<img src="<?php echo $cta_thumbnail; ?>"/>
										<?php endif; ?>
									</div>
									<div class="ib-tools">
										<a href="#" id="remove-cta-image" class="red">Remove image</a>
									</div>
								</div>
								<div class="text-center">
			                        <button type="button" class="ib-button" title="<?php esc_attr_e( 'Set CTA image' ) ?>" id="set-cta-image"><?php _e( 'Set CTA image' ) ?></button>
			                    </div>
			                </div></td>
						</tr>
					</table>
				</div>
			</div>
			<!-- background -->
			<div id="cta-background" style="display:none;">
				<h3>Background Options:</h3>
				<div class="no-padding">
					<table class="ib_fields-table" cellpadding="0" cellspacing="0">
						<tr>
							<td colspan="3">
								<input type="radio" id="ib_bgr_type_solid" name="cta_bgr_type" data-name="cta_bgr_type" data-role="solid" value="solid" <?php echo ($cta_bgr_type == "solid")?'checked':'';?>>Solid Color
			                    &nbsp;
			                    <input type="radio" id="ib_bgr_type_gradient"name="cta_bgr_type" data-name="cta_bgr_type" data-role="gradient" value="gradient" <?php echo ($cta_bgr_type == "gradient")?'checked':'';?>>Gradient Color
							</td>
						</tr>
						<tr class="alt0 ib_cta-bgr-solid-row">
							<td class="label" width="50">Color:</td>
							<td><input name="background_color" data-type="color-picker" class="ib_color-picker" data-name="background-color" type="text" maxlength="6" size="6" id="background-color" value="<?php echo isset($background_color)?$background_color:'';?>" /></td>
						</tr>
						<tr class="alt0 ib_cta-bgr-gradient-row">
							<td class="label" width="50">Top:</td>
							<td><input name="background_top" data-prop-group="gradient"  data-type="color-picker" class="ib_color-picker" data-name="top" type="text" maxlength="6" size="6" id="background-top" value="<?php echo isset($background_top)?$background_top:'';?>" /></td>
						</tr>
						<tr class="ib_cta-bgr-gradient-row">
							<td class="label" width="50">Bottom:</td>
							<td><input name="background_bottom" data-prop-group="gradient" data-type="color-picker" class="ib_color-picker" data-name="bottom" type="text" maxlength="6" size="6" id="background-bottom" value="<?php echo isset($background_bottom)?$background_bottom:'';?>" /></td>
						</tr>
					</table>
				</div>
			</div>
			<!-- border -->
			<div id="cta-border" style="display:none;">
				<h3>Text Border Options:</h3>
				<div class="no-padding">
					<table class="ib_fields-table" cellpadding="0" cellspacing="0">
						<tr>
							<td colspan="2" class="ib_section-header">Border:</td>
						</tr>
						<tr>
							<td class="label-left" width="75px">Color:</td>
							<td class="field-right"><input name="border_color" data-type="color-picker" class="ib_color-picker" data-name="border-color" type="text" maxlength="6" size="6" id="border-color" value="<?php echo isset($border_color)?$border_color:'';?>" /></td>
						</tr>
						<tr class="alt0">
							<td class="label-left">Size:</td>
							<td class="field-right"><input name="border" id="border" data-name="border" type="text" class="slider-default" data-type="slider" value="<?php echo isset($border)?$border:'';?>" size="4" readonly/>
	                        <span class="text-info"><span class="text-value"></span>px</span></td>
						</tr>
						<tr class="alt0">
							<td colspan="2" class="ib_slider"><div id="ib_slider-border" data-name="border" data-min="0" data-max="50" data-value="<?php echo isset($border)?$border:'';?>" class="ui-slider" aria-disabled="false"></div></td>
						</tr>
						<tr>
							<td class="label-left">Radius:</td>
							<td class="field-right"><input name="border_radius" data-name="border_radius" type="text" class="slider-default" data-type="slider" value="<?php echo isset($border_radius)?$border_radius:'';?>" size="4" readonly/>
	                            <span class="text-info"><span class="text-value"></span>px</span>
	                        </td>
	                    </tr>
						<tr>
							<td colspan="2" class="ib_slider">
								<div id="ib_slider-border_radius"  data-name="border_radius" data-min="0" data-max="100" data-value="<?php echo isset($border_radius)?$border_radius:'';?>" class="ui-slider" aria-disabled="false"></div>
		                    </td>
						</tr>
						<tr>
							<td colspan="2" class="ib_section-header">Padding:</td>
						</tr>
						<tr >
							<td class="label-left">Horizontal:</td>
							<td class="field-right"><input data-name="h_padding" name="h_padding" type="text" data-prop-group="padding" class="slider-default" data-type="slider" value="<?php echo isset($h_padding)?$h_padding:'';?>" size="4" readonly/><span>px</span></td>
						</tr>
						<tr>
							<td colspan="2" class="ib_slider"><div id="ib_slider-h_padding" data-name="h_padding" data-min="0" data-max="80" data-value="<?php echo isset($h_padding)?$h_padding:'';?>" class="ui-slider" aria-disabled="false"></div></td>
						</tr>
						<tr class="alt0">
							<td class="label-left">Vertical:</td>
							<td class="field-right"><input data-name="v_padding"name="v_padding" type="text" data-prop-group="padding" class="slider-default" data-type="slider" value="<?php echo isset($v_padding)?$v_padding:'';?>" size="4" readonly/><span>px</span>
	                        </td>
	                    </tr>
						<tr class="alt0">
							<td colspan="2" class="ib_slider">
								<div data-name="v_padding" id="ib_slider-v_padding" data-min="0" data-max="60" data-value="<?php echo isset($v_padding)?$v_padding:'';?>" class="ui-slider" aria-disabled="false"></div>
		                    </td>
						</tr>
					</table>
				</div>
			</div>
			<!-- target -->
			<div id="cta-target" style="display:none;">
				<h3>Target:</h3>
				<div class="no-padding">
					<table class="ib_fields-table" cellpadding="0" cellspacing="0">
						<tr><td class='label-top' width="50px">ALT attribute:</td></tr>
						<tr><td class='field-top-label'><input name="alt" type="text" value="<?php echo isset($alt)?$alt:'Alt Text'; ?>" id="alt_copy" /></td></tr>
						<tr class="alt0"><td class='label-top' >Title attribute:</td></tr>
						<tr class="alt0"><td class='field-top-label'><input name="title" type="text" value="<?php echo isset($title)?$title:'Title Text'; ?>" id="title_copy" /></td></tr>
						<tr><td colspan="2" class="ib_section-header">Destination:</td></tr>
						<tr><td colspan="2">
							<input type="radio" id="target_internal" data-name="cta-link" name="cta_link"  data-role="internal" value="internal" <?php echo ($target_type == 'internal')?'checked':'' ;?>>Select a Post/Page
	                        <input type="radio" id="target_external" data-name="cta-link" name="cta_link"  data-role="external" value="external" <?php echo ($target_type == 'external')?'checked':'' ;?>>Add a URL
	                     </td></tr>
						<tr class="alt0" id='target_internal_options' style="display:<?php echo (@$internal_link)?'':'none' ;?>">
							<td colspan="2" class="field-top-label">
								<label for="internal_link">Page/Post</label> <br />
	                            <select name="internal_link" id="internal_link_select">
	                                <option value="">Select a Page</option>
	                                <?php
	                                foreach ($pages as $page) : ?>
	                                    <option <?php echo(isset($selected) && $selected == get_permalink( $page->ID ))?'selected':''; ?> value="<?php echo get_permalink( $page->ID ); ?>"><?php echo $page->post_title; ?></option>
	                                <?php endforeach; ?>
	                            </select>
							</td>
						</tr>
						<tr class="alt0" id="target_external_options" style="display:<?php echo (@$external_link)?'':'none' ;?>">
							<td colspan="2" class="field-top-label">
								<label for="external_link">URL</label> <br />
	                            <input type="text" name="external_link" id="external_link_text" value="<?php echo(isset($external_link) && !empty($external_link) && !$internal_link)?$external_link:''; ?>" />
							</td>
						</tr>
					</table>
				</div>
			</div>
			<!-- Lead Points -->
			<div id="cta-points" style="display:none;">
				<h3>Lead Points:</h3>
				<div>
	                <select name="lead_points" id="lead_points">
	                    <option value="">Select Value</option>
	                    <option value="1">1</option>
	                    <option value="2">2</option>
	                    <option value="3">3</option>
	                    <option value="4">4</option>
	                    <option value="5">5</option>
	                    <option value="6">6</option>
	                    <option value="7">7</option>
	                    <option value="8">8</option>
	                    <option value="9">9</option>
	                    <option value="10">10</option>
	                </select>
	            </div>
			</div>
		</div>
	</div>
	<div class="clear"></div>
	<!-- END SETTINGS -->
	<?php echo $Form->end(); ?>
</div>
<!-- dialog -->
<div id="dialog" title="Basic dialog">
  <div id="dialog-body"></div>
</div>
<script>
	jQuery(document).ready(function($){
		$("#cta_tabs").ib_ctaTabs();
	});
</script>
<!-- template name -->
<div id="template_name" style="display:none;">
	You can save all these settings as a template for future use.<br>
	<label for="template_name">Name:</label><br>
	<input name="template_name" type="text" placeholder="Template Name">
</div>
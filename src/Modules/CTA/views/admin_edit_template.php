<div class="wrap" id="ib_edit_template">
	<?php echo $Breadcrumb->printPath(); ?>
	<!-- preview -->
	<form id="post" method="post" action="admin.php?page=<?php echo $post_type; ?>&section=ib_edit_template&tid=<?php echo $cta_template->template_id; ?>">
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<!-- template name -->
			<div id="post-body-content">
				<div id="titlediv">
					<div id="titlewrap">
						<label for="title" id="title-prompt-text" class="screen-reader-text">Enter title here</label>
						<input type="text" autocomplete="off" spellcheck="true" id="title" value="<?php echo $cta_template->name; ?>" size="30" name="name">
					</div>
				</div>
			</div>
			<!-- save widget -->
			<div id="postbox-container-1" class="postbox-container">
				<div id="side-sortables" class="meta-box-sortables">
					<div id="submitTemplateDiv" class="postbox ">
						<h3 class="ui-sortable-handle"><span>Publish</span></h3>
						<div class="inside">
							<div class="submitbox">
								<div id="major-publishing-actions">
									<div id="delete-action">
										<?php $trash_url = wp_nonce_url("admin.php?page={$post_type}&section=ib_delete_template&tid={$cta_template->template_id}","ib-delete-template");?>
										<a href="<?php echo $trash_url; ?>" class="submitdelete deletion" id="delete_template">Delete Template</a>
									</div>
									<div id="publishing-action">
										<span class="spinner"></span>
										<input type="submit" data-id="<?php echo $cta_template->template_id; ?>" value="Update" id="publish" class="button button-primary button-large" name="save">
									</div>
									<div class="clear"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- cta settings -->
			<div id="postbox-container-2" class="postbox-container">
				<div class="ib-template-settings">
					<!-- tabs -->
					<div class="ib-cta-tabs" id="cta_tabs">
						<a href="#cta-font" class="font"></a>
						<a href="#cta-background" class="background"></a>
						<a href="#cta-border" class="border"></a>
						<a href="#cta-target" class="target"></a>
<!--						<a href="#cta-points" class="points"></a>-->
						<div class="tabs">
							<!-- fonts -->
							<div id="cta-font">
								<h3>Type Options:</h3>
								<div class="ib_no-padding">
									<table class="ib_fields-table" cellpadding="0" cellspacing="0">
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
												<input id="font_weight-input" type="hidden" name="font_weight" value="<?php echo ($font_weight)?'bold':'';?>">
												<a id="font_weight-button" class="ib_cta-transform bold <?php echo ($font_weight)?'ib-active':'';?>" data-name="font-weight" data-item="bold">B</a>
												<input id="font_style-input" type="hidden" name="font_style" value="<?php echo ($font_style)?'italic':'';?>">
												<a id="font_style-button" class="ib_cta-transform italic <?php echo ($font_style)?'ib-active':'';?>" data-name="font-style" data-item="italic">I</a>
												<input id="text_transform-input" type="hidden" name="text_transform" value="<?php echo ($text_transform)?'uppercase':'';?>">
												<a id="text_transform-button" class="ib_cta-transform uppercase <?php echo ($text_transform)?'ib-active':'';?>" data-name="text-transform" data-item="uppercase">U</a>
											</td>
										</tr>
										<tr class="ib_cta_type_button-row">
											<td class="label">Color:</td>
											<td colspan="2">
												<input name="color" type="text" data-name="color" data-type="color-picker" maxlength="6" size="6" value="<?php echo isset($color)?$color:'';?>" />
							                </td>
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
												<input type="radio" id="ib_bgr_type_solid" name="cta_bgr_type" data-name="cta_bgr_type" data-role="solid" value="solid" <?php echo ($bgr_type == "solid")?'checked':'';?>>Solid Color
							                    &nbsp;
							                    <input type="radio" id="ib_bgr_type_gradient"name="cta_bgr_type" data-name="cta_bgr_type" data-role="gradient" value="gradient" <?php echo ($bgr_type == "gradient")?'checked':'';?>>Gradient Color
											</td>
										</tr>
										<tr class="alt0 ib_cta-bgr-solid-row">
											<td class="label" width="50">Color:</td>
											<td><input name="background_color" data-type="color-picker" data-name="background-color" type="text" maxlength="6" size="6" id="background-color" value="<?php echo isset($background_color)?$background_color:'';?>" /></td>
										</tr>
										<tr class="alt0 ib_cta-bgr-gradient-row">
											<td class="label" width="50">Top:</td>
											<td><input name="background_top" data-prop-group="gradient"  data-type="color-picker" data-name="top" type="text" maxlength="6" size="6" id="background-top" value="<?php echo isset($background_top)?$background_top:'';?>" /></td>
										</tr>
										<tr class="ib_cta-bgr-gradient-row">
											<td class="label" width="50">Bottom:</td>
											<td><input name="background_bottom" data-prop-group="gradient" data-type="color-picker" data-name="bottom" type="text" maxlength="6" size="6" id="background-bottom" value="<?php echo isset($background_bottom)?$background_bottom:'';?>" /></td>
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
											<td class="field-right"><input name="border_color" data-type="color-picker" data-name="border-color" type="text" maxlength="6" size="6" id="border-color" value="<?php echo isset($border_color)?$border_color:'';?>" /></td>
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
										<tr class="alt0" id='target_internal_options' style="display:<?php echo ($internal_link)?'':'none' ;?>">
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
										<tr class="alt0" id="target_external_options" style="display:<?php echo ($external_link)?'':'none' ;?>">
											<td colspan="2" class="field-top-label">
												<label for="external_link">URL</label> <br />
					                            <input type="text" name="external_link" id="external_link_text" value="<?php echo(isset($external_link) && !empty($external_link) && !$internal_link)?$external_link:''; ?> " />
											</td>
										</tr>
									</table>
								</div>
							</div>
							<!-- Lead Points -->
							<div id="cta-points" style="display:none;">
								<h3>Lead Points:</h3>
								<div>&nbsp;</div>
							</div>
						</div>
					</div>
					<?php if(!@$post_content) $post_content = '<a class="cta-btn" data-role="cta-button">Button text</a>'; ?>
					
					<div class="ib-cta-preview">
						<div class="ib-widget">
							<h3 class="handle">Preview</h3>
							<div>
								<div id="button-area">
						            <div class="button-area-wrapper" style="text-align: center;margin-top:1em;" data-role="cta-display-wrapper">
										<div class="ib-column ib-column-12" id="cta-display-wrapper"><?php echo $post_content; ?></div>
						            </div>
						        </div>
						        <div style="display:none;">
						            <textarea rows="4" cols="50" data-role="cta-input" name="post_content"><?php echo $post_content; ?></textarea>
						        </div>
								<div class="clear"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</form>
</div>
<script>
jQuery(document).ready(function($) {
	$("#cta_tabs").ib_ctaTabs();
});
</script>
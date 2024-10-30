<div id="ib_mail">
	<div class="ib-tabs" id="ib-tabs">
        <div class="tab-wrapper noselect">
            <a href="admin.php?page=ib-email-admin&section=templates" class="ib-tab-link">Emails</a>
            <a href="admin.php?page=ib-email-admin&section=header" class="ib-tab-link selected">Header</a>
            <a href="admin.php?page=ib-email-admin&section=footer" class="ib-tab-link">Footer</a>
            <a href="admin.php?page=ib-email-admin&section=content" class="ib-tab-link">Content</a>
            <a href="admin.php?page=ib-email-admin&section=social" class="ib-tab-link">Social</a>
            <a href="admin.php?page=ib-email-admin&section=advanced" class="ib-tab-link">Send Options</a>
        </div>
        <div class="tabs">
            <div id="ib_template_settings" class="tab-content">
                <div class="ib-row">
                    <div class="ib-column ib-column-5">
                        <form action="/wp-admin/admin-post.php" method="post">
                            <div class="ib-widget">
                            	<h3 class="handle">Header Settings:</h3>
								<div class="ib-inside">
									<table class="ib_fields-table" cellpadding="0" cellspacing="0">
                                        <!-- Logo Image -->
                                        <tr>
                                            <td colspan="2" class="ib_section-header">Logo:</td>
                                        </tr>
                                        <tr class="alt0">
                                            <td colspan="2" class="label-left checkbox-row"><input type="checkbox" data-name="logo" id="no_logo_container" name="no_logo_container" value="1" <?php echo (@$default->no_logo_container)? "checked":""; ?>> I DON'T WANT A LOGO.</td>
                                        </tr>
                                        <tr data-container="logo">
                                            <td class="label-left" width="75px">Image:</td>
                                            <td class="right"><input id="logo_image" name="logo_image" data-name="logo-image" type="text" value="<?php echo isset($default->logo_image)?$default->logo_image:'';?>" /><input id="upload_email_logo_image" class="ib-button" type="button" value="Select"></td>
                                        </tr>
                                        <tr class="alt0" data-container="logo">
                                            <td class="label-left" width="75px">Alignment:</td>
                                            <td class="field-right">
                                                <select name="logo_image_align">
                                                    <option value="">Select Option</option>
                                                    <option value="left" <?php echo($default->logo_image_align == 'left')?'selected':'';?>>Left</option>
                                                    <option value="right" <?php echo($default->logo_image_align == 'right')?'selected':'';?>>Right</option>
                                                    <option value="center" <?php echo($default->logo_image_align == 'center')?'selected':'';?>>Center</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <!-- End Logo Image

                                        <!-- Logo Container Padding -->
                                        <tr data-container="logo">
                                            <td colspan="2" class="ib_section-header">Logo Container Padding:</td>
                                        </tr>
                                        <!-- Image Container Padding Top -->
                                        <tr class="alt0" data-container="logo">
                                            <td class="label-left" width="75px">Top:</td>
                                            <td class="field-right">
                                                <input name="logo_padding_top" id="share_padding_top" data-name="logo_padding_top" type="text" class="slider-default" data-type="slider" value="<?php echo isset($default->logo_padding_top)?$default->logo_padding_top:'15';?>" size="4" readonly/>
                                                <span class="text-info"><span class="text-value"></span>px</span>
                                            </td>
                                        </tr>
                                        <tr class="alt0" data-container="logo">
                                            <td colspan="2" class="ib_slider">
                                                <div id="ib_slider-logo_padding_top"  data-name="logo_padding_top" data-min="0" data-max="100" data-value="<?php echo isset($default->logo_padding_top)?$default->logo_padding_top:'15';?>" class="ui-slider" aria-disabled="false"></div>
                                            </td>
                                        </tr>
                                        <!-- Image Container padding Bottom -->
                                        <tr data-container="logo">
                                            <td class="label-left" width="75px">Bottom:</td>
                                            <td class="field-right">
                                                <input name="logo_padding_bottom" id="logo_padding_bottom" data-name="logo_padding_bottom" type="text" class="slider-default" data-type="slider" value="<?php echo isset($default->logo_padding_bottom)?$default->logo_padding_bottom:'15';?>" size="4" readonly/>
                                                <span class="text-info"><span class="text-value"></span>px</span>
                                            </td>
                                        </tr>
                                        <tr data-container="logo">
                                            <td colspan="2" class="ib_slider">
                                                <div id="ib_slider-logo_padding_bottom"  data-name="logo_padding_bottom" data-min="0" data-max="100" data-value="<?php echo isset($default->logo_padding_bottom)?$default->logo_padding_bottom:'15';?>" class="ui-slider" aria-disabled="false"></div>
                                            </td>
                                        </tr>
                                        <!-- Image Container Padding Left -->
                                        <tr class="alt0" data-container="logo">
                                            <td class="label-left" width="75px">Left:</td>
                                            <td class="field-right">
                                                <input name="logo_padding_left" id="logo_padding_left" data-name="logo_padding_left" type="text" class="slider-default" data-type="slider" value="<?php echo isset($default->logo_padding_left)?$default->logo_padding_left:'30';?>" size="4" readonly/>
                                                <span class="text-info"><span class="text-value"></span>px</span>
                                            </td>
                                        </tr>
                                        <tr class="alt0" data-container="logo">
                                            <td colspan="2" class="ib_slider">
                                                <div id="ib_slider-logo_padding_left"  data-name="logo_padding_left" data-min="0" data-max="100" data-value="<?php echo isset($default->logo_padding_left)?$default->logo_padding_left:'30';?>" class="ui-slider" aria-disabled="false"></div>
                                            </td>
                                        </tr>
                                        <!-- Image Container Padding Right -->
                                        <tr data-container="logo">
                                            <td class="label-left" width="75px">Right:</td>
                                            <td class="field-right"><input name="logo_padding_right" id="logo_padding_right" data-name="logo_padding_right" type="text" class="slider-default" data-type="slider" value="<?php echo isset($default->logo_padding_right)?$default->logo_padding_right:'15';?>" size="4" readonly/>
                                                <span class="text-info"><span class="text-value"></span>px</span>
                                            </td>
                                        </tr>
                                        <tr data-container="logo">
                                            <td colspan="2" class="ib_slider">
                                                <div id="ib_slider-logo_padding_right"  data-name="logo_padding_right" data-min="0" data-max="100" data-value="<?php echo isset($default->logo_padding_right)?$default->logo_padding_right:'';?>" class="ui-slider" aria-disabled="false"></div>
                                            </td>
                                        </tr>
                                        <!-- background color -->
                                        <tr class="alt0" data-container="logo">
                                            <td class="label-left" width="120px">Background Color:</td>
                                            <td class="field-right">
                                                <input name="logo_background" data-type="color-picker" data-name="logo-background" type="text" maxlength="6" size="6" id="logo-background" value="<?php echo isset($default->logo_background)?$default->logo_background:'';?>" />
                                            </td>
                                        </tr>
                                        <!-- end logo setting -->
	                                    <tr data-container="social">
	                                        <td colspan="2" class="ib_section-header">Social Container Padding:</td>
	                                    </tr>
	                                    <tr class="alt0">
                                            <td colspan="2" class="label-left checkbox-row"><input type="checkbox" data-name="social" id="no_social_container" name="no_social_container" value="1" <?php echo (@$default->no_social_container)? "checked":""; ?>> I DON'T WANT A SOCIAL CONTAINER.</td>
                                        </tr>
	                                    <!-- Share Container Padding Left -->
	                                    <tr data-container="social">
	                                        <td class="label-left" width="75px">Top:</td>
	                                        <td class="field-right">
	                                            <input name="share_padding_top" id="share_padding_top" data-name="share_padding_top" type="text" class="slider-default" data-type="slider" value="<?php echo isset($default->share_padding_top)?$default->share_padding_top:'10';?>" size="4" readonly/>
	                                            <span class="text-info"><span class="text-value"></span>px</span>
	                                        </td>
	                                    </tr>
	                                    <tr data-container="social">
	                                        <td colspan="2" class="ib_slider">
	                                            <div id="ib_slider-share_padding_top"  data-name="share_padding_top" data-min="0" data-max="100" data-value="<?php echo isset($default->share_padding_top)?$default->share_padding_top:'10';?>" class="ui-slider" aria-disabled="false"></div>
	                                        </td>
	                                    </tr>
	                                    <!-- Share Container Padding Right -->
	                                    <tr data-container="social" class="alt0">
	                                        <td class="label-left" width="75px">Bottom:</td>
	                                        <td class="field-right"><input name="share_padding_bottom" id="share_padding_bottom" data-name="share_padding_bottom" type="text" class="slider-default" data-type="slider" value="<?php echo isset($default->share_padding_bottom)?$default->share_padding_bottom:'10';?>" size="4" readonly/>
	                                            <span class="text-info"><span class="text-value"></span>px</span>
	                                        </td>
	                                    </tr>
	                                    <tr data-container="social" class="alt0">
	                                        <td colspan="2" class="ib_slider">
	                                            <div id="ib_slider-share_padding_bottom"  data-name="share_padding_bottom" data-min="0" data-max="100" data-value="<?php echo isset($default->share_padding_bottom)?$default->share_padding_bottom:'10';?>" class="ui-slider" aria-disabled="false"></div>
	                                        </td>
	                                    </tr>
	
	                                    <!-- Share Container Padding Left -->
	                                    <tr data-container="social">
	                                        <td class="label-left" width="75px">Left:</td>
	                                        <td class="field-right">
	                                            <input name="share_padding_left" id="share_padding_left" data-name="share_padding_left" type="text" class="slider-default" data-type="slider" value="<?php echo isset($default->share_padding_left)?$default->share_padding_left:'5';?>" size="4" readonly/>
	                                            <span class="text-info"><span class="text-value"></span>px</span>
	                                        </td>
	                                    </tr>
	                                    <tr data-container="social">
	                                        <td colspan="2" class="ib_slider">
	                                            <div id="ib_slider-share_padding_left"  data-name="share_padding_left" data-min="0" data-max="100" data-value="<?php echo isset($default->share_padding_left)?$default->share_padding_left:'5';?>" class="ui-slider" aria-disabled="false"></div>
	                                        </td>
	                                    </tr>
	                                    <!-- Share Container Padding Right -->
	                                    <tr data-container="social" class="alt0">
	                                        <td class="label-left" width="75px">Right:</td>
	                                        <td class="field-right"><input name="share_padding_right" id="share_padding_right" data-name="share_padding_right" type="text" class="slider-default" data-type="slider" value="<?php echo isset($default->share_padding_right)?$default->share_padding_right:'5';?>" size="4" readonly/>
	                                            <span class="text-info"><span class="text-value"></span>px</span>
	                                        </td>
	                                    </tr>
	                                    <tr data-container="social" class="alt0">
	                                        <td colspan="2" class="ib_slider">
	                                            <div id="ib_slider-share_padding_right"  data-name="share_padding_right" data-min="0" data-max="100" data-value="<?php echo isset($default->share_padding_right)?$default->share_padding_right:'5';?>" class="ui-slider" aria-disabled="false"></div>
	                                        </td>
	                                    </tr>
										<!-- Share Container Background Color -->
	                                    <tr data-container="social">
	                                        <td class="label-left" width="120px">Background Color:</td>
	                                        <td class="field-right">
	                                            <input name="share_background" data-type="color-picker" data-name="share-background" type="text" maxlength="6" size="6" id="share-background" value="<?php echo isset($default->share_background)?$default->share_background:'';?>" />
	                                        </td>
	                                    </tr>
	                                    <tr data-container="social" class="alt0">
	                                        <td class="label-left" width="120px">Font Color:</td>
	                                        <td class="field-right">
	                                            <input name="share_color" data-type="color-picker" data-name="share-color" type="text" maxlength="6" size="6" id="share-color" value="<?php echo isset($default->share_color)?$default->share_color:'';?>" />
	                                        </td>
	                                    </tr>
	                                    <tr data-container="social">
	                                        <td class="label-left" width="75px">Alignment:</td>
	                                        <td class="field-right">
	                                            <select name="share_align">
	                                                <option value="">Select Option</option>
	                                                <option value="left" <?php echo($default->share_align == 'left')?'selected':'';?>>Left</option>
	                                                <option value="right" <?php echo($default->share_align == 'right')?'selected':'';?>>Right</option>
	                                                <option value="center" <?php echo($default->share_align == 'center')?'selected':'';?>>Center</option>
	                                            </select>
	                                        </td>
	                                    </tr>
	                                    <!-- End Share Container-->
                            		</table>
                           		</div>
                            </div>
                            <div class="ib-margin-top">
                                <?php wp_nonce_field('save_email_setting'); ?>
                                <input type="hidden" name="setting_type" value="header" />
                                <input type="hidden" name="action" value="save_email_settings" />
                                <input type="submit" class="ib-button" value="Save Changes" id="save_button_in_form" style="display: none;"/>
                            </div>
                        </form>
                    </div>
                    <div class="fr ib-column-7">
	                    <div class="ib-email-template-preview">
		                	<iframe width='100%' height="600px" id="ib-email-template-iframe"></iframe>
		                	<div id='ib-overlay'><span>Change will be available after save.<br><button class="ib-button" id="save_changes_button">Save Changes</button></span></div> 
		                </div>                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
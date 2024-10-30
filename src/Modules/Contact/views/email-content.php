<div id="ib_mail">
    <div class="ib-tabs" id="ib-tabs">
        <div class="tab-wrapper noselect">
            <a href="admin.php?page=ib-email-admin&section=templates" class="ib-tab-link">Emails</a>
            <a href="admin.php?page=ib-email-admin&section=header" class="ib-tab-link">Header</a>
            <a href="admin.php?page=ib-email-admin&section=footer" class="ib-tab-link">Footer</a>
            <a href="admin.php?page=ib-email-admin&section=content" class="ib-tab-link selected">Content</a>
            <a href="admin.php?page=ib-email-admin&section=social" class="ib-tab-link">Social</a>
            <a href="admin.php?page=ib-email-admin&section=advanced" class="ib-tab-link">Send Options</a>
        </div>
        <div class="tabs">
            <div id="ib_template_settings" class="tab-content">
                <div class="ib-row">
                    <div class="ib-column ib-column-5">
                        <form action="/wp-admin/admin-post.php" method="post">
                            <div class="ib-widget">
	                            <h3 class="handle">Content Settings:</h3>
                                <div class="ib-inside">
	                                <table class="ib_fields-table" cellpadding="0" cellspacing="0">
	                                    <tr>
	                                        <td colspan="2" class="ib_section-header">Banner Image:</td>
	                                    </tr>
	                                    <tr class="alt0">
	                                        <td class="label-left" width="75px">Image:</td>
	                                        <td class="right"><input id="banner_image" name="banner_image" data-name="banner-image" type="text" value="<?php echo isset($default->banner_image)?$default->banner_image:'';?>" /><input id="upload_email_banner_image" class="ib-button" type="button" value="Select"></td>
	                                    </tr>
	                                    <!-- End Logo Image
	
	                                    <!-- Logo Container Padding -->
	                                    <tr>
	                                        <td colspan="2" class="ib_section-header">Padding:</td>
	                                    </tr>
	                                    <!-- Banner Container Padding Top -->
	                                    <tr class="alt0">
	                                        <td class="label-left" width="75px">Top:</td>
	                                        <td class="field-right">
	                                            <input name="banner_padding_top" id="share_padding_top" data-name="banner_padding_top" type="text" class="slider-default" data-type="slider" value="<?php echo isset($default->banner_padding_top)?$default->banner_padding_top:'';?>" size="4" readonly/>
	                                            <span class="text-info"><span class="text-value"></span>px</span>
	                                        </td>
	                                    </tr>
	                                    <tr class="alt0">
	                                        <td colspan="2" class="ib_slider">
	                                            <div id="ib_slider-banner_padding_top" data-name="banner_padding_top" data-min="0" data-max="100" data-value="<?php echo isset($default->banner_padding_top)?$default->banner_padding_top:'';?>" class="ui-slider" aria-disabled="false"></div>
	                                        </td>
	                                    </tr>
	                                    <!-- Banner Container padding Bottom -->
	                                    <tr>
	                                        <td class="label-left" width="75px">Bottom:</td>
	                                        <td class="field-right">
	                                            <input name="banner_padding_bottom" id="banner_padding_bottom" data-name="banner_padding_bottom" type="text" class="slider-default" data-type="slider" value="<?php echo isset($default->banner_padding_bottom)?$default->banner_padding_bottom:'';?>" size="4" readonly/>
	                                            <span class="text-info"><span class="text-value"></span>px</span>
	                                        </td>
	                                    </tr>
	                                    <tr>
	                                        <td colspan="2" class="ib_slider">
	                                            <div id="ib_slider-banner_padding_bottom"  data-name="banner_padding_bottom" data-min="0" data-max="100" data-value="<?php echo isset($default->banner_padding_bottom)?$default->banner_padding_bottom:'';?>" class="ui-slider" aria-disabled="false"></div>
	                                        </td>
	                                    </tr>
	
	                                    <!-- background color -->
	                                    <tr class="alt0">
	                                        <td class="label-left" width="120px">Background Color:</td>
	                                        <td class="field-right">
	                                            <input name="banner_background" data-type="color-picker" data-name="banner-background" type="text" maxlength="6" size="6" id="banner-background" value="<?php echo isset($default->banner_background)?$default->banner_background:'';?>" />
	                                        </td>
	                                    </tr>
	                                    <!-- End Banner -->
	
	                                    <!-- begin Body -->
	                                    <tr>
	                                        <td colspan="2" class="ib_section-header">Email Body:</td>
	                                    </tr>
	                                    <tr>
	                                        <td colspan="2" class="ib_section-header">Padding:</td>
	                                    </tr>
	                                    <!-- Body Padding Top -->
	                                    <tr class="alt0">
	                                        <td class="label-left" width="75px">Top:</td>
	                                        <td class="field-right"><input name="body_padding_top" id="body_padding_top" data-name="body_padding_top" type="text" class="slider-default" data-type="slider" value="<?php echo $default->body_padding_top;?>" size="4" readonly/>
	                                            <span class="text-info"><span class="text-value"></span>px</span>
	                                        </td>
	                                    </tr>
	                                    <tr class="alt0">
	                                        <td colspan="2" class="ib_slider">
	                                            <div id="ib_slider-body_padding_top"  data-name="body_padding_top" data-min="0" data-max="100" data-value="<?php echo $default->body_padding_top;?>" class="ui-slider" aria-disabled="false"></div>
	                                        </td>
	                                    </tr>
	                                    <!-- Body Padding Bottom -->
	                                    <tr>
	                                        <td class="label-left" width="75px">Bottom:</td>
	                                        <td class="field-right"><input name="body_padding_bottom" id="body_padding_bottom" data-name="body_padding_bottom" type="text" class="slider-default" data-type="slider" value="<?php echo $default->body_padding_bottom;?>" size="4" readonly/>
	                                            <span class="text-info"><span class="text-value"></span>px</span>
	                                        </td>
	                                    </tr>
	                                    <tr>
	                                        <td colspan="2" class="ib_slider">
	                                            <div id="ib_slider-body_padding_bottom"  data-name="body_padding_bottom" data-min="0" data-max="100" data-value="<?php echo $default->body_padding_bottom;?>" class="ui-slider" aria-disabled="false"></div>
	                                        </td>
	                                    </tr>
	                                    <!-- Body Padding Left -->
	                                    <tr class="alt0">
	                                        <td class="label-left" width="75px">Left:</td>
	                                        <td class="field-right">
	                                            <input name="body_padding_left" id="body_padding_left" data-name="body_padding_left" type="text" class="slider-default" data-type="slider" value="<?php echo $default->body_padding_left;?>" size="4" readonly/>
	                                            <span class="text-info"><span class="text-value"></span>px</span>
	                                        </td>
	                                    </tr>
	                                    <tr class="alt0">
	                                        <td colspan="2" class="ib_slider">
	                                            <div id="ib_slider-body_padding_left"  data-name="body_padding_left" data-min="0" data-max="100" data-value="<?php echo $default->body_padding_left;?>" class="ui-slider" aria-disabled="false"></div>
	                                        </td>
	                                    </tr>
	                                    <!-- Body Padding Right -->
	                                    <tr>
	                                        <td class="label-left" width="75px">Right:</td>
	                                        <td class="field-right"><input name="body_padding_right" id="body_padding_right" data-name="body_padding_right" type="text" class="slider-default" data-type="slider" value="<?php echo $default->body_padding_right;?>" size="4" readonly/>
	                                            <span class="text-info"><span class="text-value"></span>px</span>
	                                        </td>
	                                    </tr>
	                                    <tr>
	                                        <td colspan="2" class="ib_slider">
	                                            <div id="ib_slider-body_padding_right"  data-name="body_padding_right" data-min="0" data-max="100" data-value="<?php echo $default->body_padding_right;?>" class="ui-slider" aria-disabled="false"></div>
	                                        </td>
	                                    </tr>
	                                    <!-- background color -->
	                                    <tr class="alt0">
	                                        <td class="label-left" width="120px">Background Color:</td>
	                                        <td class="field-right">
	                                            <input name="body_background" data-type="color-picker" data-name="body-background" type="text" maxlength="6" size="6" id="body-background" value="<?php echo $default->body_background;?>" />
	                                        </td>
	                                    </tr>
	                                    <!-- background color -->
	                                    <tr>
	                                        <td class="label-left" width="120px">Font Color:</td>
	                                        <td class="field-right">
	                                            <input name="body_color" data-type="color-picker" data-name="body-color" type="text" maxlength="6" size="6" id="body-background" value="<?php echo $default->body_color;?>" />
	                                        </td>
	                                    </tr>
	                                    <!-- End Body -->
	                                </table>
	                            </div>
                            </div>
                            <div class="ib-margin-top">
                                <?php wp_nonce_field('save_email_setting'); ?>
                                <input type="hidden" name="setting_type" value="footer" />
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
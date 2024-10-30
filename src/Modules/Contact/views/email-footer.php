<div id="ib_mail">
	<div class="ib-tabs" id="ib-tabs">
        <div class="tab-wrapper noselect">
            <a href="admin.php?page=ib-email-admin&section=templates" class="ib-tab-link">Emails</a>
            <a href="admin.php?page=ib-email-admin&section=header" class="ib-tab-link">Header</a>
            <a href="admin.php?page=ib-email-admin&section=footer" class="ib-tab-link selected">Footer</a>
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
	                            <h3 class="handle">Footer Settings:</h3>
                                <div class="ib-inside">
	                                <table class="ib_fields-table" cellpadding="0" cellspacing="0">
	                                    <!-- background color -->
	                                    <tr>
	                                        <td colspan="2" class="ib_section-header">Colors:</td>
	                                    </tr>
	                                    <tr class="alt0">
	                                        <td class="label-left" width="75px">Font Color:</td>
	                                        <td class="field-right"><input name="footer_color" data-type="color-picker" data-name="footer-color" type="text" maxlength="6" size="6" id="footer-color" value="<?php echo $default->footer_color;?>" /></td>
	                                    </tr>
	                                    <tr>
	                                        <td class="label-left" width="75">Background Color:</td>
	                                        <td class="field-right">
	                                            <input name="footer_background" data-type="color-picker" data-name="footer-background" type="text" maxlength="6" size="6" id="footer-background" value="<?php echo $default->footer_background;?>" />
	                                        </td>
	                                    </tr>
	                                    <!-- Logo Container Padding -->
	                                    <tr>
	                                        <td colspan="2" class="ib_section-header">Padding:</td>
	                                    </tr>
	                                    <!--  Padding Top -->
	                                    <tr class="alt0">
	                                        <td class="label-left" width="75px">Top:</td>
	                                        <td class="field-right">
	                                            <input name="footer_padding_top" id="share_padding_top" data-name="footer_padding_top" type="text" class="slider-default" data-type="slider" value="<?php echo $default->footer_padding_top;?>" size="4" readonly/>
	                                            <span class="text-info"><span class="text-value"></span>px</span>
	                                        </td>
	                                    </tr>
	                                    <tr class="alt0">
	                                        <td colspan="2" class="ib_slider">
	                                            <div id="ib_slider-footer_padding_top"  data-name="footer_padding_top" data-min="0" data-max="100" data-value="<?php echo $default->footer_padding_top;?>" class="ui-slider" aria-disabled="false"></div>
	                                        </td>
	                                    </tr>
	                                    <!--  padding Bottom -->
	                                    <tr>
	                                        <td class="label-left" width="75px">Bottom:</td>
	                                        <td class="field-right">
	                                            <input name="footer_padding_bottom" id="footer_padding_bottom" data-name="footer_padding_bottom" type="text" class="slider-default" data-type="slider" value="<?php echo $default->footer_padding_bottom;?>" size="4" readonly/>
	                                            <span class="text-info"><span class="text-value"></span>px</span>
	                                        </td>
	                                    </tr>
	                                    <tr>
	                                        <td colspan="2" class="ib_slider">
	                                            <div id="ib_slider-footer_padding_bottom"  data-name="footer_padding_bottom" data-min="0" data-max="100" data-value="<?php echo $default->footer_padding_bottom;?>" class="ui-slider" aria-disabled="false"></div>
	                                        </td>
	                                    </tr>
	                                    <!--  Padding Left -->
	                                    <tr class="alt0">
	                                        <td class="label-left" width="75px">Left:</td>
	                                        <td class="field-right">
	                                            <input name="footer_padding_left" id="footer_padding_left" data-name="footer_padding_left" type="text" class="slider-default" data-type="slider" value="<?php echo $default->footer_padding_left;?>" size="4" readonly/>
	                                            <span class="text-info"><span class="text-value"></span>px</span>
	                                        </td>
	                                    </tr>
	                                    <tr class="alt0">
	                                        <td colspan="2" class="ib_slider">
	                                            <div id="ib_slider-footer_padding_left"  data-name="footer_padding_left" data-min="0" data-max="100" data-value="<?php echo $default->footer_padding_left;?>" class="ui-slider" aria-disabled="false"></div>
	                                        </td>
	                                    </tr>
	                                    <!--  Padding Right -->
	                                    <tr>
	                                        <td class="label-left" width="75px">Right:</td>
	                                        <td class="field-right"><input name="footer_padding_right" id="footer_padding_right" data-name="footer_padding_right" type="text" class="slider-default" data-type="slider" value="<?php echo $default->footer_padding_right;?>" size="4" readonly/>
	                                            <span class="text-info"><span class="text-value"></span>px</span>
	                                        </td>
	                                    </tr>
	                                    <tr>
	                                        <td colspan="2" class="ib_slider">
	                                            <div id="ib_slider-footer_padding_right"  data-name="footer_padding_right" data-min="0" data-max="100" data-value="<?php echo $default->footer_padding_right;?>" class="ui-slider" aria-disabled="false"></div>
	                                        </td>
	                                    </tr>
	                                    <!-- site info settings -->
	                                    <tr>
	                                        <td colspan="2" class="ib_section-header">Site Info:</td>
	                                    </tr>
	                                    <tr class="alt0">
	                                        <td class="label-left" width="120px">Site Name</td>
	                                        <td class="field-right">
	                                            <input type="text" name="site_name" value="<?php echo isset($default->site_name)?$default->site_name:bloginfo('name');?>" />
	                                        </td>
	                                    </tr>
	                                    <tr>
	                                        <td class="label-left" width="120px">Contact Email</td>
	                                        <td class="field-right">
	                                            <input type="text" name="contact_email" value="<?php echo isset($default->contact_email)?$default->contact_email:get_option('admin_email');;?>" />
	                                        </td>
	                                    </tr>
	                                    <tr class="alt0">
	                                        <td class="label-left" width="120px">Contact Phone</td>
	                                        <td class="field-right">
	                                            <input type="text" name="contact_phone" value="<?php echo isset($default->contact_phone)?$default->contact_phone:'';?>" />
	                                        </td>
	                                    </tr>
	                                    <tr>
	                                        <td colspan="2" class="ib_section-header">Footer Options:</td>
	                                    </tr>
	                                    <tr class="alt0">
                                            <td colspan="2" class="label-left checkbox-row"><input type="checkbox" data-name="no_social_footer" id="no_social_footer" name="no_social_footer" value="1" <?php echo (@$default->no_social_footer)? "checked":""; ?>> I DON'T WANT SOCIAL ICONS.</td>
                                        </tr>
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
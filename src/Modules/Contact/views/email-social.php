<div id="ib_mail">
	<div class="ib-tabs" id="ib-tabs">
        <div class="tab-wrapper noselect">
            <a href="admin.php?page=ib-email-admin&section=templates" class="ib-tab-link">Emails</a>
            <a href="admin.php?page=ib-email-admin&section=header" class="ib-tab-link">Header</a>
            <a href="admin.php?page=ib-email-admin&section=footer" class="ib-tab-link">Footer</a>
            <a href="admin.php?page=ib-email-admin&section=content" class="ib-tab-link">Content</a>
            <a href="admin.php?page=ib-email-admin&section=social" class="ib-tab-link selected">Social</a>
            <a href="admin.php?page=ib-email-admin&section=advanced" class="ib-tab-link">Send Options</a>
        </div>
        <div class="tabs">
            <!-- Manage Redirect Tabs -->
            <div id="ib_template_settings" class="tab-content">
                <div class="ib-row">
                    <div class="fl ib-column-5">
                        <form action="/wp-admin/admin-post.php" method="post">
                            <div class="ib-widget">
	                            <h3 class="handle">Social Settings:</h3>
                                <div class="ib-inside">
	                                <table class="ib_fields-table" cellpadding="0" cellspacing="0">
	                                    <!-- background color -->
	                                    <tr>
	                                        <td colspan="2" class="ib_section-header">Facebook:</td>
	                                    </tr>
	                                    <tr class="alt0">
	                                        <td class="label-left" width="120px">URL</td>
	                                        <td class="field-right"><input type="text" name="facebook_link" value="<?php echo isset($default->facebook_link)?@$default->facebook_link:'';?>" /></td>
	                                    </tr>
	                                    <tr>
	                                        <td class="label-left" width="75px">Logo:</td>
	                                        <td class="field-right">
	                                            <input type="radio" name="facebook_logo" value="facebook-square" <?php echo (@$default->facebook_logo == 'facebook-square')?'checked':'';?>><img style="vertical-align:middle" src="<?php echo BREW_PLUGIN_IMAGES_URL; ?>social/32/facebook-square.png" />
	                                            <input type="radio" name="facebook_logo" value="facebook-circle" <?php echo (@$default->facebook_logo == 'facebook-circle')?'checked':'';?>><img style="vertical-align:middle" src="<?php echo BREW_PLUGIN_IMAGES_URL; ?>social/32/facebook-circle.png" />
	                                        </td>
	                                    </tr>
	                                    <tr>
	                                        <td colspan="2" class="ib_section-header">Twitter:</td>
	                                    </tr>
	                                    <tr class="alt0">
	                                        <td class="label-left" width="120px">URL</td>
	                                        <td class="field-right"><input type="text" name="twitter_link" value="<?php echo isset($default->twitter_link)?@$default->twitter_link:'';?>" /></td>
	                                    </tr>
	                                    <tr>
	                                        <td class="label-left" width="75px">Logo:</td>
	                                        <td class="field-right">
	                                            <input type="radio" name="twitter_logo" value="twitter-square" <?php echo (@$default->twitter_logo == 'twitter-square')?'checked':'';?>><img style="vertical-align:middle" src="<?php echo BREW_PLUGIN_IMAGES_URL; ?>social/32/twitter-square.png" />
	                                            <input type="radio" name="twitter_logo" value="twitter-circle" <?php echo (@$default->twitter_logo == 'twitter-circle')?'checked':'';?>><img style="vertical-align:middle" src="<?php echo BREW_PLUGIN_IMAGES_URL; ?>social/32/twitter-circle.png" />
	                                        </td>
	                                    </tr>
	                                    <tr>
	                                        <td colspan="2" class="ib_section-header">LinkedIn:</td>
	                                    </tr>
	                                    <tr class="alt0">
	                                        <td class="label-left" width="120px">URL</td>
	                                        <td class="field-right"><input type="text" name="linkedin_link" value="<?php echo isset($default->linkedin_link)?@$default->linkedin_link:'';?>" /></td>
	                                    </tr>
	                                    <tr>
	                                        <td class="label-left" width="75px">Logo:</td>
	                                        <td class="field-right">
	                                            <input type="radio" name="linkedin_logo" value="linkedin-square" <?php echo (@$default->linkedin_logo == 'linkedin-square')?'checked':'';?>><img style="vertical-align:middle" src="<?php echo BREW_PLUGIN_IMAGES_URL; ?>social/32/linkedin-square.png" />
	                                            <input type="radio" name="linkedin_logo" value="linkedin-circle" <?php echo (@$default->linkedin_logo == 'linkedin-circle')?'checked':'';?>><img style="vertical-align:middle" src="<?php echo BREW_PLUGIN_IMAGES_URL; ?>social/32/linkedin-circle.png" />
	                                        </td>
	                                    </tr>
	                                    <tr>
	                                        <td colspan="2" class="ib_section-header">Google+:</td>
	                                    </tr>
	                                    <tr class="alt0">
	                                        <td class="label-left" width="120px">URL</td>
	                                        <td class="field-right"><input type="text" name="google_link" value="<?php echo isset($default->google_link)?@$default->google_link:'';?>" /></td>
	                                    </tr>
	                                    <tr>
	                                        <td class="label-left" width="75px">Logo:</td>
	                                        <td class="field-right">
	                                            <input type="radio" name="google_logo" value="google-square" <?php echo (@$default->google_logo == 'google-square')?'checked':'';?>><img style="vertical-align:middle" src="<?php echo BREW_PLUGIN_IMAGES_URL;?>social/32/google-square.png" />
	                                            <input type="radio" name="google_logo" value="google-circle" <?php echo (@$default->google_logo == 'google-circle')?'checked':'';?>><img style="vertical-align:middle" src="<?php echo BREW_PLUGIN_IMAGES_URL;?>social/32/google-circle.png" />
	                                        </td>
	                                    </tr>
	                                    <tr>
	                                        <td colspan="2" class="ib_section-header">Social Icon Sizes:</td>
	                                    </tr>
	                                    <tr class="alt0">
	                                        <td class="label-left" width="120px">Size Top:</td>
	                                        <td class="field-right">
	                                            <input type="number" name="icon_top_size" min="1" max="5" value="<?php echo isset($default->icon_top_size)?@$default->icon_top_size:'2';?>">
	                                        </td>
	                                    </tr>
	                                    <tr>
	                                        <td class="label-left" width="120px">Size Bottom:</td>
	                                        <td class="field-right">
	                                            <input type="number" name="icon_bottom_size" min="1" max="5" value="<?php echo isset($default->icon_bottom_size)?@$default->icon_bottom_size:'3';?>">
	                                        </td>
	                                    </tr>
	                                </table>
	                            </div>
                            </div>
                            <div class="ib-margin-top">
                                <?php wp_nonce_field('save_email_setting'); ?>
                                <input type="hidden" name="setting_type" value="general" />
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
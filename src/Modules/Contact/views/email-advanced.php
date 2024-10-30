<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 11/5/15
 * Time: 8:48 AM
 */
?>
<div id="ib_mail">
	<div class="ib-tabs" id="ib-tabs">
        <div class="tab-wrapper noselect">
            <a href="admin.php?page=ib-email-admin&section=templates" class="ib-tab-link">Emails</a>
            <a href="admin.php?page=ib-email-admin&section=header" class="ib-tab-link">Header</a>
            <a href="admin.php?page=ib-email-admin&section=footer" class="ib-tab-link">Footer</a>
            <a href="admin.php?page=ib-email-admin&section=content" class="ib-tab-link">Content</a>
            <a href="admin.php?page=ib-email-admin&section=social" class="ib-tab-link">Social</a>
            <a href="admin.php?page=ib-email-admin&section=advanced" class="ib-tab-link selected">Send Options</a>
        </div>
        <div class="tabs">
            <div id="ib_template_settings" class="tab-content">
                <div class="ib-row">
                    <div class="ib-column ib-column-12">
	                    <form action="/wp-admin/admin-post.php" method="post">
		                    <div class="ib-widget">
			                    <h3 class="handle">Mail Settings:</h3>
								<div class="ib-inside">
									<table class="ib_fields-table" cellpadding="0" cellspacing="0">
			                            <tr>
			                                <td class="label-left" width="175px">
			                                    <label for="mail_from">From Email:</label>
			                                </td>
			                                <td class="right">
			                                    <input name="mail_from" type="text" id="mail_from" value="<?php echo isset($smtp->mail_from)?$smtp->mail_from:''; ?>" size="40"/><br>
			                                    You can specify the email address that emails should be sent from. If you leave this blank, the default email will be used.
			                                </td>
			                            </tr>
			                            <tr class="alt0">
			                                <td class="label-left" width="175px">
			                                    <label for="mail_from_name">From Name</label>
			                                </td>
			                                <td class="right">
			                                    <input name="mail_from_name" type="text" id="mail_from_name" value="<?php echo isset($smtp->mail_from_name)?$smtp->mail_from_name:''; ?>" size="40"/><br>
			                                    You can specify the name that emails should be sent from. If you leave this blank, the emails will be sent from WordPress.
			                                </td>
			                            </tr>
			                                <tr>
			                                    <td class="label-left" width="175px" valign="top">Content Type</td>
			                                    <td class="right">
			                                        <fieldset>
			                                            <input id="mail_text" type="radio" name="mail_content_type" value="text" <?php echo (@$smtp->mail_content_type == 'text')?'checked':''; ?> >
			                                            <label for="mail_text">Use text.</label><br />
			                                            <input id="mailer_html" type="radio" name="mail_content_type" value="html" <?php echo (@$smtp->mail_content_type == 'html')?'checked':''; ?>>
			                                            <label for="mailer_html">Use HTML.</label>
			                                        </fieldset>
			                                    </td>
			                                    </td>
			                                </tr>
			                            <tr class="alt0">
			                                <td class="label-left" width="175px">
			                                    <label for="mail_from_name">Return Path</label>
			                                </td>
			                                <td class="right">
			                                    <input name="mail_set_return_path" type="checkbox" id="mail_set_return_path" <?php echo !empty($smtp->mail_set_return_path)?'checked':''; ?> value="1">
			                                    Set the return-path to match the From Email.
			                                </td>
			                            </tr>
			                            <tr class="alt0">
			                                <td class="label-left" width="175px" valign="top">
			                                    Send Method
			                                </td>
			                                <td class="right">
			                                    <fieldset>
			                                        <input id="mailer_smtp" type="radio" name="mailer" value="smtp" <?php echo ($smtp->mailer == 'smtp')?'checked':''; ?> >
			                                        <label for="mailer_smtp">Send all WordPress emails via SMTP.</label><br />
			                                        <input id="mailer_mail" type="radio" name="mailer" value="mail" <?php echo ($smtp->mailer == 'mail')?'checked':''; ?>>
			                                        <label for="mailer_mail">Use the PHP mail() function to send emails.</label>
			                                    </fieldset>
			                                </td>
			                                </td>
			                            </tr>
			                        </table>	
			                    </div>
			                </div>
			                <div class="ib-widget">
				                <h3 class="handle">SMTP Options:</h3>
								<div class="ib-inside">
									<table class="ib_fields-table" cellpadding="0" cellspacing="0">
			                            <tr>
			                                <td colspan="2">
			                                    <p><strong>These options only apply if you have chosen to send mail by SMTP above.</strong></p>
			                                </td>
			                            </tr>
			                            <tr class="alt0">
			                                <td class="label-left" width="175px">
			                                    <label for="smtp_host">SMTP Host</label>
			                                </td>
			                                <td class="right">
			                                    <input name="smtp_host" type="text" id="smtp_host" value="<?php echo isset($smtp->smtp_host)?$smtp->smtp_host:''; ?>" size="40">
			                                </td>
			                            </tr>
			                            <tr>
			                                <td class="label-left" width="175px">
			                                    <label for="smtp_port">SMTP Port</label>
			                                </td>
			                                <td class="right">
			                                    <input name="smtp_port" type="text" id="smtp_port" value="<?php echo isset($smtp->smtp_port)?$smtp->smtp_port:''; ?>" size="6">
			                                </td>
			                            </tr>
			                            <tr class="alt0">
			                                <td class="label-left" width="175px" valign="top">
			                                    Encryption
			                                </td>
			                                <td class="right">
			                                    <fieldset>
			                                        <legend class="screen-reader-text"><span>Encryption</span></legend>
			                                        <input id="smtp_ssl_none" type="radio" name="smtp_ssl" value="none" <?php echo ($smtp->smtp_ssl == 'none')?'checked':''; ?>>
			                                        <label for="smtp_ssl_none"><span>No encryption.</span></label><br/>
			                                        <input id="smtp_ssl_ssl" type="radio" name="smtp_ssl" value="ssl" <?php echo ($smtp->smtp_ssl == 'ssl')?'checked':''; ?>>
			                                        <label for="smtp_ssl_ssl"><span>Use SSL encryption.</span></label><br/>
			                                        <input id="smtp_ssl_tls" type="radio" name="smtp_ssl" value="tls" <?php echo ($smtp->smtp_ssl == 'tls')?'checked':''; ?>>
			                                        <label for="smtp_ssl_tls"><span>Use TLS encryption. This is not the same as STARTTLS. For most servers SSL is the recommended option.</span></label>
			                                    </fieldset>
			                                </td>
			                            </tr>
			                                <td class="label-left" width="175px" valign="top">
			                                    Authentication
			                                </td>
			                                <td>
			                                    <fieldset>
			                                        <input id="smtp_auth_false" type="radio" name="smtp_auth" value="0" <?php echo (!$smtp->smtp_auth)?'checked':''; ?>>
			                                        <label for="smtp_auth_false"><span>No: Do not use SMTP authentication.</span></label><br>
			                                        <input id="smtp_auth_true" type="radio" name="smtp_auth" value="1" <?php echo ($smtp->smtp_auth)?'checked':''; ?>>
			                                        <label for="smtp_auth_true"><span>Yes: Use SMTP authentication.</span></label><br>
			                                        <span class="description">If this is set to no, the values below are ignored.</span>
			                                    </fieldset>
			                                </td>
			                            </tr>
			                            <tr class="alt0">
			                                <td class="label-left" width="175px">
			                                    <label for="smtp_user">Username</label>
			                                </td>
			                                <td class="right">
			                                    <input name="smtp_user" type="text" id="smtp_user" value="<?php echo isset($smtp->smtp_user)?$smtp->smtp_user:''; ?>" size="40"/>
			                                </td>
			                            </tr>
			                            <tr>
			                                <td class="label-left" width="175px">
			                                    <label for="smtp_pass">Password</label>
			                                </td>
			                                <td class="right">
			                                    <input name="smtp_pass" type="password" id="smtp_pass" value="<?php echo isset($smtp->smtp_pass)?$smtp->smtp_pass:''; ?>" size="40" />
			                                </td>
			                            </tr>
			                            <tr class="alt0">
			                                <td colspan="2">
			                                    <?php wp_nonce_field('ib_email_settings'); ?>
			                                    <input type="hidden" name="action" value="save_send_settings" />
			                                    <input type="submit" class="ib-button" value="Save Settings" />
			                                </td>
			                            </tr>
			                        </table>
			                    </div>
			                </div>
		            	</form>
                   	</div>
                </div>
                <div class="ib-widget ib-margin-top">
	                <h3 class="handle">Test Email:</h3>
	                <form action="/wp-admin/admin-post.php" method="post">
	                    <div class="ib-inside">
		                    <table class="ib_fields-table" cellpadding="0" cellspacing="0"> 
	                        <tr class="alt0">
	                            <td class="label-left" width="175px">
	                                <label for="to">To</label
	                            </td>
	                            <td class="right">
	                                <input name="to" type="text" id="to" value="" size="40" class="code">
	                            </td>
	                        </tr>
	                        <tr>
		                        <td colspan="2">
	                                <?php wp_nonce_field('ib_test_email'); ?>
	                                <input type="hidden" name="action" value="send_test_email" />
	                                <input type="submit" class="ib-button" value="Send Test" />
	                            </td>
	                        </tr>
	                    </table>
	                   </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ib-tabs" id="ib-tabs">
	<?php echo $Layout->element($partials_path . "settings_tabs",array(
		'post_type' => $post_type,
		'active' => "Email")); 
	$layout = get_option(BREW_DEFAULT_LAYOUT_OPTION);
	?>
	<div class="tabs">
		<div class="tab ib-padding-top">
			<?php echo $Form->create("Settings",array("id"=>"ib_settings_form")); ?>
			<?php wp_nonce_field( 'ib_save_settings', 'ib_settings_nonce' ); ?>
			<div class="ib-header">Mail Settings:</div>
			<!-- From Email -->
			<div class="ib_settings">
				<div class="ib-column ib-column-4 setting_label">
					From Email: <span class="fa fa-info-circle ib-inline_education" data-index="mail_settings_from_email" data-title="From Email"> </span>
				</div>
				<div class="ib-column ib-column-8">
					<?php echo $Form->text("SendSettings.mail_from",array(
						'div'=>false,
						'label' => false));
					?>
				</div>
				<div class="clear"></div>
			</div>
			<!-- From Name -->
			<div class="ib_settings">
				<div class="ib-column ib-column-4 setting_label">
					From Name: <span class="fa fa-info-circle ib-inline_education" data-index="mail_settings_from_name" data-title="From Name"> </span>
				</div>
				<div class="ib-column ib-column-8">
					<?php echo $Form->text("SendSettings.mail_from_name",array(
						'div'=>false,
						'label' => false));
					?>
				</div>
				<div class="clear"></div>
			</div>
			<!-- Content Type -->
			<div class="ib_settings">
				<div class="ib-column ib-column-4 setting_label">
					Content Type: <span class="fa fa-info-circle ib-inline_education" data-index="mail_settings_content_type" data-title="Content Type"> </span>
				</div>
				<div class="ib-column ib-column-8">
					<?php echo $Form->radio("SendSettings.mail_content_type",array(
						'text' => "Send emails as plain text",
						'html' => "Send emails as html text"),array(
						'in_divs'=>"ib-radio-row",
						'label' => false));
					?>
				</div>
				<div class="clear"></div>
			</div>
			<!-- Return Path -->
			<div class="ib_settings">
				<div class="ib-column ib-column-4 setting_label">
					Return Path: <span class="fa fa-info-circle ib-inline_education" data-index="mail_settings_return_path" data-title="Return Path"> </span>
				</div>
				<div class="ib-column ib-column-8">
					<?php echo $Form->checkbox("SendSettings.mail_set_return_path","1",array(
						'div'=>false,
						'label' => "Set the return-path to match the From Email."));
					?>
				</div>
				<div class="clear"></div>
			</div>
			<!-- Send Method -->
			<div class="ib_settings">
				<div class="ib-column ib-column-4 setting_label">
					Send Method: <span class="fa fa-info-circle ib-inline_education" data-index="mail_settings_send_method" data-title="Send Method"> </span>
				</div>
				<div class="ib-column ib-column-8">
					<?php echo $Form->radio("SendSettings.mailer",array(
						'smtp' => "Send all WordPress emails via SMTP",
						'mail' => "Use the PHP mail() function to send emails"),array(
						'in_divs'=>"ib-radio-row",
						'label' => false));
					?>
				</div>
				<div class="clear"></div>
			</div>
			<?php
			?>

			<div id="smtp_options">
				<div class="ib-header">SMTP Options:</div>
				<!-- SMTP host -->
				<div class="ib_settings">
					<div class="ib-column ib-column-4 setting_label">
						SMTP Host: <span class="fa fa-info-circle ib-inline_education" data-index="mail_settings_smtp_host" data-title="SMTP Host"> </span>
					</div>
					<div class="ib-column ib-column-8">
						<?php echo $Form->text("SendSettings.smtp_host",array(
							'div'=>false,
							'label' => false));
						?>
					</div>
					<div class="clear"></div>
				</div>
				<!-- SMTP post -->
				<div class="ib_settings">
					<div class="ib-column ib-column-4 setting_label">
						SMTP Port: <span class="fa fa-info-circle ib-inline_education" data-index="mail_settings_smtp_port" data-title="SMTP Port"> </span>
					</div>
					<div class="ib-column ib-column-8">
						<?php echo $Form->text("SendSettings.smtp_port",array(
							'div'=>false,
							'size' => 6,
							'label' => false));
						?>
					</div>
					<div class="clear"></div>
				</div>
				<!-- Encryption Type -->
				<div class="ib_settings">
					<div class="ib-column ib-column-4 setting_label">
						Encryption Type: <span class="fa fa-info-circle ib-inline_education" data-index="mail_settings_smtp_encryption" data-title="SMTP Encryption Type"> </span>
					</div>
					<div class="ib-column ib-column-8">
						<?php echo $Form->radio("SendSettings.smtp_ssl",array(
							'none' => "No encryption",
							'ssl' => "Use SSL encryption",
							'tls' => "Use TLS encryption. This is not the same as STARTTLS. For most servers SSL is the recommended option."),array(
							'in_divs'=>"ib-radio-row",
							'label' => false));
						?>
					</div>
					<div class="clear"></div>
				</div>
				<!-- Authentication -->
				<div class="ib_settings">
					<div class="ib-column ib-column-4 setting_label">
						Authentication: <span class="fa fa-info-circle ib-inline_education" data-index="mail_settings_smtp_authentication" data-title="SMTP Authentication"> </span>
					</div>
					<div class="ib-column ib-column-8">
						<?php echo $Form->radio("SendSettings.smtp_auth",array(
							'0' => "No: Do not use SMTP authentication.",
							'1' => "Yes: Use SMTP authentication"),array(
								'in_divs'=>"ib-radio-row",
								'label' => false));
						?>
					</div>
					<div class="clear"></div>
				</div>
				<div id="ib_smtp_auth">
					<!-- UserName -->
					<div class="ib_settings">
						<div class="ib-column ib-column-4 setting_label">
							Username: <span class="fa fa-info-circle ib-inline_education" data-index="mail_settings_smtp_username" data-title="Email Username"> </span>
						</div>
						<div class="ib-column ib-column-8">
							<?php echo $Form->text("SendSettings.smtp_user",array(
								'div'=>false,
								'label' => false));
							?>
						</div>
						<div class="clear"></div>
					</div>
					<!-- Auth User-->
					<div class="ib_settings">
						<div class="ib-column ib-column-4 setting_label">
							Password: <span class="fa fa-info-circle ib-inline_education" data-index="mail_settings_smtp_password" data-title="Email Password"> </span>
						</div>
						<div class="ib-column ib-column-8">
							<?php echo $Form->password("SendSettings.smtp_pass",array(
								'div'=>false,
								'label' => false));
							?>
						</div>
						<div class="clear"></div>
					</div>

					<!-- TODO: send test email from email settings
		
					<div class="ib_settings">
						<div class="editor-test-email">
							<div id="spinner" class="fa fa-spinner fa-spin fa-2x fa-fw"></div>
							<button id="ib_send-text-email" class="ib-button">Send Test Email</button>
							<?php echo $Form->text("send_test_to",array("div"=>false)); ?>
						</div>
					</div> -->


				</div>
			</div>
			<div class="clear"></div>
			<?php echo $Form->end(); ?>
		</div>
	</div>
	<div class="ib-margin-top"><button id="submit_form" class="ib-button">Save Settings</button></div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		/* TODO: send test email from email settings
			$("#ib_settings_form").ib_emailSettings();
		*/

		$("#submit_form").click(function(){
			$("#ib_settings_form").submit();
		});
		// smtp options
		function toggleSmtpOptions(smtp){
			if(smtp){
				$("#smtp_options").slideDown();
			}else{
				$("#smtp_options").slideUp();
			}
		}
		var $smtp = $("#SendSettingsMailer0");
		$smtp.click(function(){
			toggleSmtpOptions(true);
		});
		var $phpmail = $("#SendSettingsMailer1");
		$phpmail.click(function(){
			toggleSmtpOptions(false);
		});
		if($phpmail.prop("checked")) $("#smtp_options").hide();
		// authentication
		function toggleSmtpAuth(auth){
			if(auth){
				$("#ib_smtp_auth").slideDown();
			}else{
				$("#ib_smtp_auth").slideUp();
			}
		}
		var $noAuth = $("#SendSettingsSmtpAuth0")
		$noAuth.click(function(){
			toggleSmtpAuth(false);
		});
		var $useAuth = $("#SendSettingsSmtpAuth1");
		$useAuth.click(function(){
			toggleSmtpAuth(true);
		});
		if($noAuth.prop("checked")) $("#ib_smtp_auth").hide();
	});
</script>
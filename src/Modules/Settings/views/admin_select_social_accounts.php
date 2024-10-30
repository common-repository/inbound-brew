<div>
	<div class="ib-network-info" style="margin-bottom:10px;">
		<div class="ib-network-logo">
			<img src="<?php echo BREW_PLUGIN_IMAGES_URL;?>/social/logo_<?php echo $network; ?>.png" width="50"/>
		</div>
		<div class="ib-network-instructions">
			<span class="ib-network-name"><?php echo $network_name; ?></span><br>
			Please choose the accounts you want this website to have access to. <strong>You can click on the account to toggle the status.</strong>
		</div>
		<div class="clear"></div>
	</div>
	<?php echo $Form->create("ib_social_accounts",array(
				'url' => "admin.php?page={$post_type}&section=ib_save_social_accounts"));
			echo $Form->hidden("SocialSetting.network",$network);
			echo $Form->hidden("SocialSetting.screen_name",$screen_name);
			echo $Form->hidden("SocialSetting.expiration",$expiration);
			wp_nonce_field( 'ib_save_social_accounts', 'ib_save_social_accounts_nonce' ); ?>
	<div class='ib-column ib-column-8'>
		<div id="ib_social_accounts">
			<div class="ib-header"><?php echo $network_name; ?> Account(s): </div>
			<div class="ib_key">
				<?php echo $this->Layout->icon("add",array('style'=>"vertical-align:middle")); ?> New Account 
				<?php echo $this->Layout->icon("reload",array('style'=>"vertical-align:middle")); ?> Re-validate Token 
				<?php echo $this->Layout->icon("trash",array('style'=>"vertical-align:middle")); ?> Delete Account 
			</div>
			<?php 
			$counter = 0; $i = 0;
			foreach($new_accounts as $account):
				$prefix = "SocialNetworkAccount.{$counter}.";
				foreach($account as $field=>$value):
					echo $Form->hidden($prefix.$field,addslashes($value)); // hidden data
				endforeach;
				$status = "add";
				$alt = "";
				if ($i++ % 2 == 0) $alt="ib_alt0";
				// check if account already exists
				foreach($old_accounts as $old_account):
					if($old_account['account_type_id'] == $account['id']):
						$status = "reload";
						break;
					endif;
				endforeach;
			?>
			<div class="ib-social-validate-account noselect <?php echo $alt . " " . $status; ?> " data-status="<?php echo $status; ?>">
				<?php echo $Form->hidden($prefix."is_active","1"); ?>
				<div data-status="<?php echo $status; ?>" class="ib-account-status ib_icon i_<?php echo $status; ?>"></div>
				<div class="ib-account-name"><?php echo ucfirst($account['account_type']).": ". $account['name']; ?></div>
			</div>
			<?php $counter++; endforeach; ?>
		</div>
	</div>
	<div class="ib-column ib-column-4">
		<div class="ib-widget">
			<h3 class="handle">Link Accounts</h3>
			<div class="ib-inside">
				<div class="ib-actions-instructions">
					To cancel connection please click cancel below.
				</div>
				<div class="publish-actions">
					<div id="delete-action">
						<a href="<?php echo "admin.php?page={$post_type}"; ?>" class="submitdelete deletion red" id="disconnect_network">[cancel]</a>
					</div>
					<div class="publishing-action">
						<input type="submit" name="save" class=" fr button button-primary button-large" id="save_settings" value="Save" data-id="2">
					</div>
					<div class="clear"></div>
				</div>
			</div>
		</div>
	</div>
	<?php echo $Form->end(); ?>
</div>
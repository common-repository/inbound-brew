<div>
	<div class="ib-header">Post Details:</div>
	<div class="ib_post-record-details">
		<div class="posted-image" style="background-image:url(<?php echo $post_setting->posted_image; ?>)"></div>
		<table cellpadding="0" cellspacing="0" class="">
			<tr>
				<td class="ib_cal-day-small"><?php echo $Layout->calDay($post_setting->updated_at,array('add_year' => true)); ?></td>
				<td class="text-top text-left">
					<div class="network"><img src="<?php echo $network_image; ?>" width="16px" align="left">&nbsp;<?php echo $network_name; ?></div>
					<div class="posted-time"><?php echo $Date->format(BREW_WP_TIME_FORMAT,$post_setting->updated_at); ?>
					<div class="posted-title"><?php echo $post_setting->posted_title; ?></div>
					<div class="posted-description"><?php echo $post_setting->posted_description; ?></div>
				</td>
			</tr>
		</table>
		<div class="clear"></div>
	</div>
	<div class="ib-header">Posts:</div>
	<div class="ib_post-records-with-stats">
		<?php $i = 0;
		foreach($records as $record):
			$class = "";
			if ($i++ % 2 == 0) $class="alt0";
			$status = ($record['post_id'])? "posted":"error";
			foreach($accounts as $account):
				if($account['account_id'] == $record['social_network_account_id']):
					$account_name = ($account['account_type'] == "me")? "My Feed" : $account['display_name'];
					$account_type = ($account['account_type'] == "me")? "Feed" : ucfirst($account['account_type']);
					break;
				endif;
			endforeach;	?>
			<div class="ib_post-record <?php echo $class; ?>">
				<div class="details">
					<div class="account-name"><?php echo $account_type." : ".$account_name; ?></div>
					<div class="ib-posting-status-<?php echo $status;?>"><?php 
						echo ucfirst($status);
						if($record['error_message']) echo " : " . $record['error_message'] ?>
					</div>
				</div>
				<div class="stats"><?php 
					if(@$record['social_stats']):
						$e = 0;
						foreach($record['social_stats'] as $item =>$value):
								$sclass = "alt0";
								if ($e++ % 2 == 0) $sclass="";
							echo "<div class='ib_stats-label {$sclass}'>{$item}:</div>";
							echo "<div class='ib_stats-value {$sclass}'>$value</div>";
							echo "<div class='clear'></div>";
						endforeach;
					else:
						echo "<div class='ib-error'>Unable to retrieve social stats.</div>";
					endif; ?>
				</div>
				<div class="clear"></div>
			</div>		
		<?php endforeach; ?>
	</div>
</div>
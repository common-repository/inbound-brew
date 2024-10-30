<div>
	<div class="fr"><a href="<?php echo get_admin_url(); ?>post-new.php" class="ib-button">New Blog Post</a></div>
	<p class="ib-inline-education">
		<?php if($network): ?>
		This is a list of all posting activity for <?php echo $network_name; ?>.
		<?php else: ?>
		This is a list of all posting activity for all social networks.
		<?php endif; ?> It shows when the system posted each item or if there were any problems while doing so. <span class="ib_blog-link">For more information about Social Network Posting Activity visit <a href="<?php echo BREW_PLUGIN_BLOG_URL; ?>social-network-sharing" target="_blank">The Inbound Brew Blog</a>.</span>
	</p>
	<?php if(count($history)): ?>
		<table cellpadding="0" cellspacing="0" width="100%" class="ib_data-tables">
			<thead>
				<tr>
					<th>ID</th>
					<th>Social Network</th>
					<th>Post/Page</th>
					<th>Date/Time</th>
					<th>Status</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody id="ib_redirect-list">
			<?php foreach($history as $posting): ?>
				<tr>
					<td><?php echo $posting->post_setting_id; ?></td>
					<td><img src="<?php echo BREW_PLUGIN_IMAGES_URL;?>/social/logo_<?php echo $posting->social_network;?>.png" width="60px"></td>
					<td><strong><?php echo get_post_field("post_title",$posting->wp_post_id); ?></strong></td>
					<td><?php echo $Date->format(BREW_WP_DATE_FORMAT." ".BREW_WP_TIME_FORMAT,$posting->updated_at); ?></td>
					<td class="ib-posting-status-<?php echo $posting->posting_status;?>"><?php echo ucfirst($posting->posting_status); ?></td>
					<td>
						<a href="admin.php?page=ib-admin-settings&section=ib_social_posting_records&pid=<?php echo $posting->post_setting_id; ?>">[details]</a>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
	<?php endif; ?>
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('.ib_data-tables').DataTable({
            "columnDefs": [
                { "orderable": false, "targets": [0,4] }
            ],
            "pageLength": 25
        });
	});
</script>
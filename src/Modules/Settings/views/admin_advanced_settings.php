<div class="ib-tabs" id="ib-tabs">
	<?php echo $Layout->element($partials_path . "settings_tabs",array(
		'post_type' => $post_type,
		'active' => "Sitemap/Routing")); 
	?>
	<div class="tabs">
		<div class="tab ib-padding-top">
			<?php echo $Form->create("Settings",array('id' => "inboundbrew_settings")); ?>
			<?php wp_nonce_field( 'ib_save_settings', 'ib_settings_nonce' ); ?>
			<!-- 301 Redirect Settings -->
			<div class="ib-header">301 Redirects:</div>
			<?php if(@$active_modules['redirects']): ?>
				<div class="ib-subheader">Auto Redirects:</div>
				<div class="ib_settings">
						<?php echo $Form->checkbox("Redirects.auto_redirect_on_url_change","on",array(
						'div' => false,
						'label' => "Automatically create redirect when page URL changes.")); ?>
				</div>
			<?php else: ?>
				<div class="ib_settings">
					<?php echo $Layout->inactiveModule("redirects"); ?>
				</div>
			<?php endif; ?>
			<div class="clear"></div>
			<!-- Sitemap Management -->
			<div class="ib-header">Sitemap:</div>
			<?php if(@$active_modules['sitemap']): ?>
				<div class="ib-subheader">Update Notifications:</div>
				<div class="ib_settings">
					<div class="ib-column ib-column-6">
						<?php echo $Form->checkbox("Sitemap.service.google","on",array(
						'div'=>false,
						'label' => "Notify Google about updates of your Blog")); ?>
					</div>
					<div class="ib-column ib-column-6">
						<?php echo $Form->checkbox("Sitemap.service.bing","on",array(
						'div'=>false,
						'label' => "Notify Bing (formerly MSN Live Search) about updates of your Blog")); ?>
					</div>
					<div class="clear"></div>
				</div>
				<div class="ib-subheader">WordPress Standard Content:</div>
				<div class="ib_settings">
					<div class="ib-column ib-column-3">
						<?php echo $Form->checkbox("Sitemap.standard.home","on",array(
						'div'=>false,
						'label' => "Include Homepage")); ?>
					</div>
					<div class="ib-column ib-column-3">
						<?php echo $Form->checkbox("Sitemap.standard.post","on",array(
						'div'=>false,
						'label' => "Include Posts")); ?>
					</div>
					<div class="ib-column ib-column-3">
						<?php echo $Form->checkbox("Sitemap.standard.page","on",array(
						'div'=>false,
						'label' => "Include Static Pages")); ?>
					</div>
					<div class="ib-column ib-column-3">
						<?php echo $Form->checkbox("Sitemap.standard.categories","on",array(
						'div'=>false,
						'label' => "Include Categories")); ?>
					</div>
					<div class="clear"></div>
					<div class="ib-column ib-column-3">
						<?php echo $Form->checkbox("Sitemap.standard.archives","on",array(
						'div'=>false,
						'label' => "Include Archives")); ?>
					</div>
					<div class="ib-column ib-column-3">
						<?php echo $Form->checkbox("Sitemap.standard.author","on",array(
						'div'=>false,
						'label' => "Include Author Pages")); ?>
					</div>
					<div class="ib-column ib-column-3">
						<?php echo $Form->checkbox("Sitemap.standard.tags","on",array(
						'div'=>false,
						'label' => "Include Tag Pages")); ?>
					</div>
					<div class="clear"></div>
				</div>
				<!-- taxonomies -->
				<?php if (isset($taxonomies) && !empty($taxonomies)): ?>
				<div class="ib-subheader">Custom Taxonomies:</div>
				<div class="ib_settings">
					<?php foreach ($taxonomies as $key=>$value): ?>
					<div class="ib-column ib-column-12">
						<?php echo $Form->checkbox("Sitemap.custom_taxonomy.{$key}","on",array(
						'div'=>false,
						'label' => $value->label)); ?>
					</div>
					<div class="clear"></div>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
				<!-- custom post types -->
				<?php if(isset($post_types) && !empty($post_types)): ?>
				<div class="ib-subheader">Custom Post Types:</div>
				<div class="ib_settings">
					<?php foreach ($post_types as $key=>$value): ?>
					<div class="ib-column ib-column-12">
						<?php echo $Form->checkbox("Sitemap.custom_post_types.{$key}","on",array(
						'div'=>false,
						'label' => $value->label)); ?>
					</div>
					<div class="clear"></div>
					<?php endforeach; ?>
				</div>
	            <?php endif; ?>
	            <div class="ib-subheader">Results of Last Ping:</div>
				<div class="ib_settings">
					<div class="ib-column ib-column-8">
	                    <ul>
	                        <li>
	                            <b>URL:</b> to your sitemap index file is:
	                            <a href="/sitemap.xml" target="_blank">sitemap.xml</a>.
	                        </li>
	                    <?php if(!empty($ping)): ?>
	                        <li><b>Date:</b> <?php echo date('Y-m-d H:i:s',$ping[0]->date); ?></li>
	                        <?php foreach ($ping as $key=>$value):
	                            if ($value->success): ?>
	                                <li><b><?php echo ucwords($value->service);?>:</b> successfully notified about changes. Ping duration of <?php echo round($value->duration,3);?> seconds</li>
	                            <?php else: ?>
	                                <li><?php echo ucwords($value->service);?>:</b> notification attempt unsuccessful. Ping duration of <?php echo round($value->duration,3);?> seconds</li>
	                            <?php endif; ?>
	                        <?php endforeach; ?>
	                    <?php else: ?>
	                        <li>Your Sitemap has yet to be pinged.</li>
	                    <?php endif; ?>
	                    </ul>
	                </div>
	                <div class="ib-column ib-column-4 ib-td">
		                <a href="#" id="ib_ping-button" class="ib-button"><span class="fa fa-refresh"> </span> Ping Now</a>
	                </div>
	                <div class="clear"></div>
				</div>
			<?php else: ?>
				<div class="ib_settings">
					<?php echo $Layout->inactiveModule("sitemap"); ?>
				</div>
			<?php endif; ?>
			<?php if(@$active_modules['robots']): ?>
			<div class="ib-header">ROBOTS.TXT:</div>
			<div class="ib_settings">
				<div class="input textarea" style="padding-left:0px;">
					<?PHP echo $Form->textarea("Robots.content",array(
						'label'=>false,
						'div' => false,
						'style' => "width:485px;",
						'rows' =>6)); ?>
					<div class="ib-notes">The content of your robots.txt file.</div>
				</div>
				<?php echo $Form->checkbox("Robots.blog_public","on",array(
				'div'=>false,
				'label' => "Discourage search engines from indexing this site*")); ?>
			</div>
			<?php else: ?>
				<div class="ib_settings">
					<?php echo $Layout->inactiveModule("robots"); ?>
				</div>
			<?php endif; ?>
			<?php echo $Form->end(); ?>
		</div>
	</div>
</div>
<?php if(@$active_modules['robots'] || @$active_modules['sitemap'] || @$active_modules['redirects']): ?>
<div class="ib-margin-top"><button id="settings_submit" class="ib-button">Save Settings</button></div>
<?php endif; ?>
<form action="<?php echo admin_url("admin-post.php"); ?>" method="post" id="ib_ping-form" style="display:none;">
    <input type="hidden" name="action" value="call_ib_sitemap_ping">
    <?php wp_nonce_field('ib-send-manual-ping'); ?>
    <input class="ib-button" type="submit" value="Ping Now">
</form>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		// nav previews
		$("#navigation_previews .ib_nav-preview").click(function(){
			var $me = $(this);
			if($me.hasClass("active")) return;
			var $parent = $me.parent();
			$parent.find(".active").removeClass("active");
			$me.addClass("active");
			$parent.find("input").val($me.data("id"));
		});
		// click on ping
		$("#ib_ping-button").click(function(e){
			e.preventDefault();
			$("#ib_ping-form").submit();
		});
		// save form
		$("#settings_submit").click(function(e){
			e.preventDefault();
			$("#inboundbrew_settings").submit();
		});
		// robots disabled
		function toggleBlogsPublic($checkbox){
	        if ($checkbox.prop("checked")) {
                $('#RobotsContent').prop('readonly',true);
            } else {
                $('#RobotsContent').prop('readonly',false);
            }
        }
        var $blogsPublic = $('#RobotsBlogPublic')
		$blogsPublic.on( "click", function (){
            toggleBlogsPublic($(this));
        });
        if($blogsPublic.prop("checked")) toggleBlogsPublic($blogsPublic);
	});
</script>
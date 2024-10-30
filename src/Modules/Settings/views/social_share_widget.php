<?php //print_debug($settings); exit(); ?>
<div class="ib-sharing-widget frontend" style="<?php echo $widget_style; ?>">
	<div class="ib-share-title" style="<?php echo $title_styles; ?>"><?php echo $settings['title']['text']; ?></div>
	<div class="ib-networks">
		<?php foreach($widget_networks as $wnetwork => $values):
			$url = @$settings["{$wnetwork}_share"];
			if($url):
				$class = $values['class'];
				$icon_color = (@$settings['icons'][$wnetwork])? "color:#{$settings['icons'][$wnetwork]};" : "";
				if($settings['icons']['type'] == "normal") $class = str_replace("-square", "", $class);
				if($settings['icons']['type'] == "image") $class = str_replace("-square", "-image", $class); ?>
				<div><a href="<?php echo $url; ?>" class="ib-network" target="_blank">
					<span class="fa fa-<?php echo $class; ?> fa-<?php echo $settings['icons']['size']; ?>x" style="<?php echo $icon_color; ?>margin-bottom:<?php echo $settings['icons']['margin_bottom']; ?>px;"></span>
				</a></div>
			<?php endif;
		endforeach; ?>
	</div>
</div>
<?php wp_nonce_field( 'ib_save_about_this_page_data', 'inboundbrew_meta_box_nonce' ); ?>
<table class="ib_fields-table" cellpadding="0" cellspacing="0">
	<tr><td colspan="2"class="ib-small-text">Social networks and search engines will use this information to let users know what the <?php echo strtolower($post_type_object->labels->singular_name); ?> is about and what type of content might be on it.</td></tr>
	<tr class="alt0">
		<td class="label" width="35%">Thumbnail:</td>
		<td class="text-center">
			<div class="ib-meta-preview-image">
				<?php 
					$thumbnail = @$Form->data['Meta']['ib_meta_image_thumbnail'];
					if($thumbnail):
					echo "<img src=\"{$thumbnail}\">";
				endif;?>
			</div>
			<?php echo $Form->hidden("Meta.ib_meta_image","",array('id'=>"ib_meta_image"));
			echo $Form->hidden("Meta.ib_meta_image_thumbnail","",array('id'=>"ib_meta_image_thumbnail"));
			echo $Form->hidden("Meta.ib_meta_image_size","",array('id'=>"ib_meta_image_size")); ?>
			<button class="ib-button" id="ib_set_meta_image_button">Set Image</button>
		</td>
	</tr>
	<tr>
		<td class="label-top" colspan="2">Description:</td>
	</tr>
	<tr>
		<td colspan="2"><?php echo $Form->textarea("Meta.ib_meta_description",array(
			'value' => "",
			'class' => 'ib-textarea',
			'rows' => 4,
			'placeholder' => "Enter ".strtolower($post_type_object->labels->singular_name)." description",
			'div' => false)); ?></td>
	</tr>
	<tr class="alt0">
		<td class="label">Type:</td>
		<td class="text-center"><?php echo $Form->select("Meta.ib_meta_content_type",array(
				'text' => "Standard (text)",
				'image' => "Image Gallery",
				'video' => "Video"
			)); ?></td>
	</tr>
</table>
<script type="text/javascript">
    (function($) {
        $(function () {
            $("#ib_about_this_page").ib_metaboxMeta();
        });
    }(jQuery));
</script>
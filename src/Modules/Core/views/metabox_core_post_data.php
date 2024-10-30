<?php 
	echo $Form->hidden("Post.core_nonce", wp_create_nonce( 'ib-core-data-nonce' ));
	echo $Form->hidden("Post.old_slug",$post->post_name);
	echo $Form->hidden("Post.old_status",$post->post_status);
?>
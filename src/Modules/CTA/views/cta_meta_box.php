<div id='ib-cta-list'>
<?php
	$count = count($ctas);
	if($count):
		foreach($ctas as $cta):
			echo "<div class='ib-cta-divider'><div class='ib-cta' data-id='".$cta->ID."'>".$cta->post_content."</div></div>";
		endforeach;
	else:
		echo "<h2>No CTA's Available.</h2>";
	endif;
?>
</div>
<script>
jQuery(document).ready(function($){
	$("#ib-cta-list").ib_ctaMetaBox();
	$('.meta-box-sortables').sortable({
        disabled: true
    });
    $('.postbox .hndle').css('cursor', 'pointer');
});
</script>
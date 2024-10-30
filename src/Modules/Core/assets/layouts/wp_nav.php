<?php 
    $post_id = "";
    $type = "";
    if(@$_GET['post']) {
        $post_id = @$_GET['post'];
    }

    if(!empty($post_id)) {
        $type = get_post_type($post_id);
    }
?>
<div class="wrap ib_wrap">
	<?php 
        $page_title = (isset($activeModule['page_title']))?  $activeModule['page_title'] : $activeModule['title'];  
        $page_title_info_id = strtolower(preg_replace("/[^A-Za-z0-9\-]/", '', preg_replace("/\s/", '-',$page_title)));
    ?>
	<div class="ib_page-title"><span class="fa fa-<?php echo $activeModule['class']; ?> fa-2"></span> <?php echo $page_title; ?> <span class="fa fa-info-circle fa-1x ib-module-info-icon" id="<?php echo $page_title_info_id; ?>" data-index="<?php echo $module_index; ?>" data-title='<?php echo $page_title; ?>'></span></div>
	<?php 
            if (isset($Breadcrumb)){
                echo $Breadcrumb->printPath(); 
            } 
    ?>
	<div class="ib_layout-content">
		<?php echo $content_for_layout; ?>
	</div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($){
        $(".ib-inline_education").ib_inlineEducation('init');
    });
</script>
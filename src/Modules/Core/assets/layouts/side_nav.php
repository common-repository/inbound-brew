<?php 
    global $_wp_admin_css_colors;
    $admin_theme = get_user_meta(get_current_user_id(), 'admin_color', true);
    $theme = $_wp_admin_css_colors[$admin_theme];
    $nav_color = ($admin_theme == "light")? "#888888" : $theme->colors[2];
    $highlight_color = $theme->colors[3];
    $post_id = "";
    $type = "";
    if(@$_GET['post']) {
        $post_id = @$_GET['post'];
    }

    if(!empty($post_id)) {
        $type = get_post_type($post_id);
    }
        
?>

<style>
	#wpcontent{
	margin-left: 140px;
}

 /* ib wrap */
 .ib_wrap{margin-left:195px !important;}
 body.folded .ib_wrap{margin-left:152px;}
 body.ib_collapsed .ib_wrap{
	 margin-left:95px !important;
 }
 body.folded.ib_collapsed .ib_wrap{
	 margin-left:56px;
 }
/* wpfooter */
 #wpfooter{margin-left:330px;}
 body.folded #wpfooter{margin-left:208px;}
 body.ib_collapsed #wpfooter{
	 margin-left:234px;
 }
 body.folded.ib_collapsed #wpfooter{
	 margin-left:112px;
 }
/* assign theme colors */
div.ib_side-nav{
	 background-color:<?php echo $nav_color; ?> !important;
 }
div.ib_side-nav #ib_navigation_items li.active,
div.ib_side-nav #ib_navigation_items li:hover{
	 background-color:<?php echo $highlight_color; ?> !important;
	 color:#FFF;
 }
div.ib_side-nav .inner.in-edit-mode #ib_navigation_items li.active,
div.ib_side-nav .inner.in-edit-mode #ib_navigation_items li:hover{
 background-color:<?php echo $nav_color; ?> !important;
}
div.ib_side-nav div.inner{
	background-color: <?php echo $nav_color; ?> !important;
}
#toplevel_page_inboundbrew .wp-submenu{display: none; }

/** notice **/
body.folded.ib_collapsed .notice,
body.folded.ib_collapsed .error,
body.folded.ib_collapsed .notice-info,
body.folded.ib_collapsed .notice-error,
body.folded.ib_collapsed .notice-warning,
body.folded.ib_collapsed .notice-success,
body.ib_collapsed .notice,
body.ib_collapsed .error,
body.ib_collapsed .notice-info,
body.ib_collapsed .notice-error,
body.ib_collapsed .notice-warning,
body.ib_collapsed .notice-success{margin-left:95px !important;}

body.folded .notice,
body.folded .error,
body.folded .notice-info,
body.folded .notice-error,
body.folded .notice-warning,
body.folded .notice-sucess{margin-left:195px !important;}

/*.notice, .error, .notice-info, .notice-warning, .notice-info, .notice-success{
	margin-left:195px !important;
	margin-top:20px !important;
}*/

pre{margin-left:200px;}

</style>
<div class="ib_side-nav wp-ui-highlight" id="ib_side_nav">
	<div class="inner ui-droppable">
		<ul id="ib_navigation_items">
			<?php 
				//echo "<pre>"; print_r(@$_GET['page']);
				//echo "<pre>"; print_r($navigation['order']); die;
				//echo "<pre>"; print_r($modules); die;
				//echo "<pre>"; print_r($active_modules); die;
				
				$current_page = @$_GET['page'];
				foreach($navigation['order'] as $index):
					if ($index == 'getting_started' && !get_option('ib_show_getting_started_menu')){
		                continue;
		            }
					$module = $modules[$index];
					if(@$module['is_module'] && @!$active_modules[$index]) continue; // check if module is active.
					$active = ($module['page'] == $current_page)? "active" : "";
					if(@$_GET['post_type'] == "ib-landing-page" && $index == "landing_page") {
	                                    $active = "active";
					} else if($type == "ib-landing-page" && $index == "landing_page") {
	                                    $active = "active";
	                                } 
					echo "<li data-index=\"{$index}\" data-href=\"{$module['page']}\" class=\"noselect {$active}\"><span class=\"fa fa-{$module['class']}\"> </span> <span class=\"ib_nav-name\">{$module['title']}</span></li>";
				endforeach; ?>
		</ul>
		<ul class="ib_side-controllers">
			<li class="ib_locked noselect ib_collapse" id="ib_collapse_menu" data-href="collapse">
				<span class="fa fa-chevron-circle-left"> </span>
				<span class="fa fa-chevron-circle-right"> </span> <span class="ib_nav-name">Collapse</span>
			</li>
			<li class="ib_locked noselect ib_edit_menu" id="ib_edit_nav">
				<span class="fa fa-pencil"> </span>
				<span class="fa fa-check-circle"> </span> <span class="ib_nav-name">Edit Menu</span>
			</li>
		</ul>
	</div>
	<div class="ib_available-options" id="side_nav_available_options" style="display:none;">
		<div class="options-inner">
			<p class="ib-inline-education">
				To customize your navigation, drag your modules to and from the menu bar on the left.
			</p>
			<ul class="ib-nav-options">
			<?php $counter = 0;
			foreach($modules as $index => $module):
				if(@$module['ignore']) continue;
				if(@$module['is_module'] && @!$active_modules[$index]) continue; // check if module is active.
				$inactive = (in_array($index,$navigation['order']))? "inactive": "";
				echo "<li data-index=\"{$index}\" class=\"noselect {$inactive}\" data-href=\"{$module['page']}\"><span class=\"fa fa-{$module['class']}\"> </span> {$module['title']}</li>";
			endforeach; ?>
			</ul>
			<div class="clear"></div>
		</div>
	</div>
</div>
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
		$("#ib_side_nav").ib_navigation({location:"side"});
		$(".ib-inline_education").ib_inlineEducation('init');
	});
</script>
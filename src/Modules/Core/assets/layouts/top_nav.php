<?php
$post_id = "";
$type = "";
if (@$_GET['post']) {
    $post_id = @$_GET['post'];
}

if (!empty($post_id)) {
    $type = get_post_type($post_id);
}
?>
<style>
    #wpcontent{
        margin-left: 140px;
        padding-top: 70px;
    }

</style>
<div class="ib-top-nav" id="ib_top_nav">
    <ul class="ib-nav-items" id="ib_nav_items">
        <?php
        $counter = 0;
        foreach ($navigation['order'] as $index):
            if ($index == 'getting_started' && !get_option('ib_show_getting_started_menu')){
                continue;
            }
            $module = $modules[$index];
            if (@$module['is_module'] && @!$active_modules[$index])
                continue; // check if module is active.
            echo "<li data-index=\"{$index}\" data-href=\"{$module['page']}\" class=\"noselect\"><span class=\"fa fa-{$module['class']}\"> </span> {$module['title']}</li>";
        endforeach;
        ?>
    </ul>
    <div class="ib-edit-nav" id="ib_edit_nav">Edit <span class="fa fa-chevron-down"></span></div>
    <div class="clear"></div>
    <div class="ib_available-options" id="top_nav_available_options" style="display:none;background-color:#9A9A9A;">
        <p class="ib-inline-education">
            To customize your navigation shortcuts your modules to and from the menu bar above.
        </p>
        <?php
        $counter = 0;
        $column = 0;
        foreach ($modules as $index => $module):
            if ($index == 'getting_started' && !get_option('ib_show_getting_started_menu')){
                continue;
            }
            $is_closed = false;
            if (@$module['ignore'])
                continue;
            if (@$module['is_module'] && @!$active_modules[$index])
                continue; // check if module is active.
            if ($counter == 0):
                echo "<ul class=\"ib-column ib-column-4 ib-nav-options\">";
            endif;
            $inactive = (in_array($index, $navigation['order'])) ? "inactive" : "";
            echo "<li data-index=\"{$index}\" class=\"noselect {$inactive}\"><span class=\"fa fa-{$module['class']}\"> </span> {$module['title']}</li>";
            $counter ++;
            if ($counter == (($column === 0 || $column == 1) ? 4 : 3)):
                $counter = 0;
                $column ++;
                echo "</ul>";
                $is_closed = true;
            endif;
        endforeach;
        if (!$is_closed)
            echo "</ul>";
        ?>
        <div class="clear"></div>
    </div>
</div>
<div class="wrap ib_wrap">
	<?php 
		$page_title = (isset($activeModule['page_title']))?  $activeModule['page_title'] : $activeModule['title'];  
		$page_title_info_id = strtolower(preg_replace("/[^A-Za-z0-9\-]/", '', preg_replace("/\s/", '-',$page_title)));
	?>
	<h1 class="ib_page-title"><span class="fa fa-<?php echo $activeModule['class']; ?> fa-2"></span> <?php echo $page_title; ?> <span class="fa fa-info-circle fa-1x ib-module-info-icon" id="<?php echo $page_title_info_id; ?>" data-index="<?php echo $module_index; ?>" data-title='<?php echo $page_title; ?>'></span></h1>
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
		$("#ib_top_nav").ib_navigation({location:"top"});
        $(".ib-inline_education").ib_inlineEducation('init');
	});
</script>
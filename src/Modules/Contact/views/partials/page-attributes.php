<p><strong>Template</strong></p>
<select name="ib_lp_page_template" id="ib_lp_page_template">
    <?php foreach ($templates as $key=>$value) : ?>
        <option <?php echo(isset($selected) && $selected == $key)?'selected':''; ?> value="<?php echo $key;?>"><?php echo $value;?></option>
    <?php endforeach; ?>    
</select>

<p><strong>Order</strong></p>
<p><label class="screen-reader-text" for="menu_order">Order</label><input name="menu_order" type="text" size="4" id="menu_order" value="0"></p>
<p>Need help? Use the Help tab in the upper right of your screen.</p>
<script>
	jQuery(document).ready(function($){
		$(".wrap h1").first().replaceWith("<?php echo addslashes($Breadcrumb->printPath()); ?>");
	});
</script>
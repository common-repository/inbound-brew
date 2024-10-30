<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 11/17/15
 * Time: 9:45 AM
 */
?>
<script type="text/javascript">
    var ib_kw_percent = <?php echo isset($kw_percent)?$kw_percent:'undefined';?>
</script>
<div class="ib-row">
    <div>
        <b>Keyword Usage:</b>&nbsp;<span id="ib_kw_selected"><?php echo isset($kw_count)?$kw_count:'';?></span>&nbsp;selected&nbsp;/&nbsp;<span id="ib_kw_used"><?php echo isset($kw_used)?$kw_used:'';?></span>&nbsp;used
    </div>
    <div class="ib-row ib-td">
        <div class="ib-column ib-column-2">
            <span id="ib_kw_percent_score" style="line-height: 2.4em;"><?php echo isset($kw_percent)?$kw_percent:'';?></span>%
        </div>
        <div class="ib-column ib-column-10">
            <div class="ib-progress-bar" id="ib_kw_progress_bar"></div>
        </div>
    </div>
</div>
<div class="ib-row">
	<!-- seo url -->
    <div class="ib-td grey">
	    <div class="ib-column ib-column-2">
		    <?php $checked =  (isset($seo_url) && $seo_url !== false)? "checked" : ""; ?>
		    <span class="fa fa-check-circle fa-2x ib-seo-item-checkbox <?php echo $checked; ?>" id="seo_url_check"></span>
		    <input type="hidden" name="seo_url" value="<?php if($checked) echo '1'; ?>" id="seo_url_input"/>
		</div>
	    <div class="ib-column ib-column-10  ib-seo-item-title">
        	At least one (1) keyword in slug.
        </div>
		<div class="clear"></div>
    </div>
    <!-- seo title -->
    <div class="ib-td">
	    <div class="ib-column ib-column-2">
		    <?php $checked =  (isset($seo_title) && $seo_title !== false)? "checked" : ""; ?>
		    <span class="fa fa-check-circle fa-2x ib-seo-item-checkbox <?php echo $checked; ?>" id="seo_title_check"></span>
		    <input type="hidden" name="seo_title" value="<?php if($checked) echo '1'; ?>" id="seo_title_input"/>
		</div>
	    <div class="ib-column ib-column-10 ib-seo-item-title">
        	At least one (1) keyword in title.
        </div>
		<div class="clear"></div>
    </div>
	<!-- seo_h_one -->
    <div class="ib-td grey">
	    <div class="ib-column ib-column-2">
		    <?php $checked =  (isset($seo_h_one) && $seo_h_one !== false)? "checked" : ""; ?>
		    <span class="fa fa-check-circle fa-2x ib-seo-item-checkbox <?php echo $checked; ?>" id="seo_h_one_check"></span>
		    <input type="hidden" name="seo_h_one" value="<?php if($checked) echo '1'; ?>" id="seo_h_one_input"/>
		</div>
	    <div class="ib-column ib-column-10  ib-seo-item-title">
        	At least one (1) H1 tag in page.
        </div>
		<div class="clear"></div>
    </div>
    <!-- seo alt flag -->
    <div class="ib-td">
	    <div class="ib-column ib-column-2">
		    <?php $checked =  (isset($seo_alt_tag) && $seo_alt_tag !== false)? "checked" : ""; ?>
		    <span class="fa fa-check-circle fa-2x ib-seo-item-checkbox <?php echo $checked; ?>" id="seo_alt_tag_check"></span>
		    <input type="hidden" name="seo_alt_tag" value="<?php if($checked) echo '1'; ?>" id="seo_alt_tag_input"/>
		</div>
	    <div class="ib-column ib-column-10  ib-seo-item-title">
        	Images have descriptions (ALT Tags).
        </div>
		<div class="clear"></div>
    </div>
	<!-- seo_title_tag -->
    <div class="ib-td grey">
	    <div class="ib-column ib-column-2">
		    <?php $checked =  (isset($seo_title_tag) && $seo_title_tag !== false)? "checked" : ""; ?>
		    <span class="fa fa-check-circle fa-2x ib-seo-item-checkbox <?php echo $checked; ?>" id="seo_title_tag_check"></span>
		    <input type="hidden" name="seo_title_tag" value="<?php if($checked) echo '1'; ?>" id="seo_title_tag_input"/>
		</div>
	    <div class="ib-column ib-column-10  ib-seo-item-title">
        	Links have descriptions (TITLE tags).
        </div>
		<div class="clear"></div>
    </div>
    <div class="ib-td">
	    <div class="ib-column ib-column-2">
        	<input type="checkbox" name="seo_robots">
	    </div>
	    <div class="ib-column ib-column-10  ib-seo-item-title">Discourage search engines from crawling this page.</div>
	    <div class="clear"></div>
    </div>
    <div class="ib-td ib_no-padding-top">
	    <span class="ib-notes">
        You can ask search engines not to crawl this page when they are looking for content on your site.<br>
        <span class="red">NOTE:</span> you can ask them not to but they might do it anyway.</span>
    </div>
</div>
<div class="ib-row alt0" style="padding:5px;">
	<span class="ib_blog-link">For help with On Page SEO visit <br><a href="<?php echo BREW_PLUGIN_BLOG_URL; ?>auto-social-network-push" target="_blank">The Inbound Brew Blog</a>.</span>
</div>

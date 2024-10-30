<script type="text/javascript">
    (function($){
        $(document).ready( function () {
            $('#ib_cf_setting_ajax_dialog').dialog({autoOpen: false});
            $('#ib_cf_about_ajax').on('click', function () {
                $("#ib_cf_setting_ajax_dialog").dialog("open");
            });
            $('#ib_cf_settings_virtual_dialog').dialog({autoOpen: false});
            $('#ib_cf_about_virtual').on('click', function () {
                $("#ib_cf_settings_virtual_dialog").dialog("open");
            });

            $('input[type="radio"]').on('change',function(){
                $('#submit-option_holder .ib-row').each(function(){
                    $(this).hide();
                });
                var id = $(this).attr('id')+'_content';
                $('#'+id).closest('.ib-row').show();
            })
        });
    }(jQuery));
</script>
<div id="ib_contact_forms">
    <div class="ib-tabs" id="ib-tabs">
        <div class="tab-wrapper noselect">
            <a href="admin.php?page=ib-contact-forms&section=list" id="contact-forms-tab" class="ib-tab-link">Contact Forms</a>
            <a href="admin.php?page=ib-contact-forms&section=settings" id="contact-form-settings-tab" class="ib-tab-link selected">Settings</a>
        </div>
        <div class="tabs">
            <form action="<?php echo admin_url("admin-post.php"); ?>" method="post">
                <div class="ib-row" style="padding:2em 2em 2em .5em;">
                    <div class="ib-column-6 ib-column">
                        <div class="ib-widget">
	                    	<h3 class="handle">Form Post Options</h3>
	                    	<div class="ib-inside">
		                        <div class="ib-row">
		                            <div class="ib-column ib-column-1 ib-td">
		                                <input type="radio" name="submit_type" id="ajax" value="ajax" <?php echo ($config->submit_type == "ajax")?'checked':'';?>>
		                            </div>
		                            <div class="ib-column ib-column-7 ib-td">
		                                <label for="ajax"><b>Use ajax for form submissions</b><br/><i>(IE, don't go to a thank you page and just show a message instead)</i></label>
		                            </div>
		                            <div class="ib-column ib-column-4 ib-td">
		                                <span id="ib_cf_about_ajax" class="ib-link">[what's ajax]</span>
		                            </div>
		                        </div>
		                        <div class="ib-row alt0">
		                            <div class="ib-column ib-column-1 ib-td">
		                                <input type="radio" name="submit_type" id="virtual" value="virtual" <?php echo ($config->submit_type == "virtual")?'checked':'';?>>
		                            </div>
		                            <div class="ib-column ib-column-7 ib-td">
		                                <b>Use a virtual page</b><br/><i>(dynamically load a "new page")</i>
		                            </div>
		                            <div class="ib-column ib-column-4 ib-td">
		                                <span id="ib_cf_about_virtual" class="ib-link">[what's a virtual page]</span>
		                            </div>
		                        </div>
		                        <div class="ib-row">
		                            <div class="ib-column ib-column-1 ib-td">
		                                <input type="radio" name="submit_type" id="page" value="page" <?php echo ($config->submit_type == "page")?'checked':'';?>>
		                            </div>
		                            <div class="ib-column ib-column-7 ib-td">
		                                <b>Redirect to a thank you page or post</b>
		                            </div>
		                            <div class="ib-column ib-column-4 ib-td">&nbsp;</div>
		                        </div>
		                    </div>
                        </div>
                    </div>
                    <div class="ib-column ib-column-6">
	                    <div class="ib-widget">
	                    	<h3 class="handle">Form Post Response</h3>
	                    	<div class="ib-inside">
								<div id="submit-option_holder">
		                            <div class="ib-row <?php echo ($config->submit_type == "ajax")?'':'hidden';?>">
			                            <div class="ib_section-header">Write your default thank you message</div>
		                                <textarea name="ajax_content" id="ajax_content" class="ib-cf-textarea"><?php echo isset($config->ajax_content)?$config->ajax_content:'';?></textarea>
		                            </div>
		                            <div class="ib-row <?php echo ($config->submit_type == "virtual")?'':'hidden';?>">
										<div class="ib_section-header">Design Your Virtual Page</div>
		                                <?php $args = array(
		                                    'textarea_rows' => 15,
		                                    'textarea_name' => 'virtual_content',
		                                    'wpautop' => false
		                                );
		                                $content = isset($config->virtual_content)?$config->virtual_content:'';
		                                wp_editor($content, 'virtual_content', $args );?>
		                            </div>
		                            <div class="ib-row <?php echo ($config->submit_type == "page")?'':'hidden';?>">
		                                <div class="ib_section-header">Select your default thank you page</div>
	                                    <select name="internal_page" id="page_content">
	                                        <option value="">Select a Page</option>
	                                        <?php
	                                        foreach ($pages as $page) : ?>
	                                            <option <?php echo(isset($config->internal_page) && $config->internal_page == get_permalink( $page->ID ))?'selected':''; ?> value="<?php echo get_permalink( $page->ID ); ?>"><?php echo $page->post_title; ?></option>
	                                        <?php endforeach; ?>
	                                    </select>
		                            </div>
		                        </div>
							</div>
                        </div>
                        <!-- save -->
                        <div class="ib-row text-right">
	                        <?php wp_nonce_field('ib_contact_form_settings'); ?>
							<input type="hidden" name="action" value="ib_contact_form_settings" />
							<input type="submit" class="ib-button" value="Save" />
						</div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="ib_cf_setting_ajax_dialog">
        <p>Ajax is a client-side script that communicates to and from a server/database without the need for a postback or a complete page refresh. In other words, it is the method of exchanging data with a server and updating parts of a web page - without reloading the entire page or directing to a new page.</p>
    </div>
    <div id="ib_cf_settings_virtual_dialog">
       <p>A virtual page is a page that <b>does not</b> exist as part of your WordPress pages/posts. Rather is is created programmatically. As such the content and control of the page falls outside the use of the normal page editing tools provided by WordPress</p>
    </div>
</div>
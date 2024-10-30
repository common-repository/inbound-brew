<div id="ib_leads">
		<div class="ib-tabs" id="ib-tabs">
        <div class="tab-wrapper noselect">
            <a href="admin.php?page=ib-leads-admin&section=list" class="ib-tab-link">Leads</a>
            <a href="admin.php?page=ib-leads-admin&section=manage" class="ib-tab-link selected">Import/Export</a>
        </div>
        <div class="tabs">
            <div class="ib-row">
                <div class="ib-column ib-column-6 ib-admin-box">
                    <div class="ib-th">
                        <b>Import:</b>
                    </div>
                    <div class="ib-td">
                        To import leads please <a href="<?php echo BREW_PLUGIN_ASSETS_URL; ?>files/lead_template.csv">download this template.</a> Fill it out and upload in CSV format using the form below. <b>Email is used as the unique key.</b>
                    </div>
                    <div class="ib-td" style="padding-top: 0px;">
	                    <span class="ib-notes"><span class="red">*</span> For helper filling out the template visit <a href="<?php echo BREW_PLUGIN_BLOG_URL; ?>lead-management-import" target="_blank">The Inbound Brew Blog</a>.</span>
                    </div>
                    <form action="<?php echo admin_url("admin-post.php"); ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="import_ib_leads">
                        <?php wp_nonce_field( 'ib_lead_batch_upload' ); ?>
                        <div class="ib-td">
                            <input type="radio" name="lead_duplicates" value="ignore" checked><b>Ignore Duplicate</b>&nbsp;
                            <input type="radio" name="lead_duplicates" value="overwrite"><b>Overwrite Duplicate</b>
                        </div>
                        <div class="ib-td">
                            <div class="fl">
                                <input type="file" name="csv_file" id="csv_file" value="">
                            </div>
                            <div class="fr">
                                <input type='submit' class="ib-button" name='submit_csv'  value='Upload'/>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </form>
                </div>
                <div class="ib-column ib-column-6 ib-admin-box">
                    <div class="ib-th">
                        <b>Export:</b>
                    </div>
                    <div class="ib-td" style="min-height: 138px;">
                        Click the button below to export a CSV file of all leads you have created.
                    </div>
                    <div class="ib-td">
                        <div class="fr">
                            <form action="<?php echo admin_url("admin-post.php"); ?>" method="post">
                                <input type="hidden" name="action" value="export_ib_leads"/>
                                <input type="submit" class="ib-button" value="Export Leads">
                            </form>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
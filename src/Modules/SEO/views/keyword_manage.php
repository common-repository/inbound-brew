<div class="wrap" id="ib_redirects">
    <div class="ib-tabs" id="ib-tabs">
        <div class="tab-wrapper noselect">
            <a href="admin.php?page=keyword-admin&section=keyword_list" class="ib-tab-link">Keywords</a>
            <a href="admin.php?page=keyword-admin&section=keyword_manage" class="ib-tab-link selected">Import/Export</a>
        </div>
        <div class="tabs">
            <div class="ib-row">
                <div class="ib-column ib-column-6 ib-admin-box">
                    <div class="ib-th">
                        <b>Import:</b>
                    </div>
                    <div class="ib-td">
                        To import keywords please <a href="<?php echo BREW_PLUGIN_ASSETS_URL; ?>files/keywords_template.csv">download this template.</a> Fill it out, then upload using the form below:
                    </div>
                    <form action="<?php echo admin_url("admin-post.php"); ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="ib_kw_csv">
                        <?php wp_nonce_field( 'ib_keyword_batch_upload' ); ?>
                        <div class="ib-td">
                            <input type="checkbox" name="replace_all" />&nbsp;<label for="replace_all"><b>Check if you want to replace all keywords</b></label>
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
                    <div class="ib-td" style="min-height: 95px;">
                        Click the button below to export a CSV file of all the keywords you are tracking.
                    </div>
                    <div class="ib-td">
                        <div class="fr">
                            <form action="<?php echo admin_url("admin-post.php"); ?>" method="post">
                                <input type="hidden" name="action" value="export_ib_keywords"/>
                                <input type="submit" class="ib-button" data-role="keyword-export" value="Export Keywords" />
                            </form>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
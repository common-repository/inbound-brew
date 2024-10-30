<style type="text/css">
    div.high {
        line-height: 125%;
    }
    .no-title .ui-dialog-titlebar {
        display: none;
    }
    div.ui-dialog {
        border: 3px solid #0083CA;
    }
    div.ui-dialog .ui-dialog-content {
        padding: 0px;
    }
</style>
<div id="ib_leads">
    <div class="ib-row" style="border-bottom:2px solid grey;margin:1em 0"></div>
    <section id="lead_data" style="margin-bottom:2em;">
        <div id="ib_leads">
            <section id="ib-share-options">
                <div class="ib-row">
                    <div class="ib-column ib-column-6">
                        <h2><?php echo $lead->lead_name; ?></h2>
                        <div class="high" style="color: #0083CA;">
                            <i class="fa fa-envelope-o"></i> <?php echo $lead->lead_email; ?>
                        </div>
                        <div class="high">
                            <?php echo $lead->lead_address."<br />".$lead->lead_city.", ".$lead->lead_state." ".$lead->lead_postal;?>
                        </div>
                        <div class="high">
                            <?php echo $lead->country->country_name; ?>
                        </div>
                    </div>
                    <div class="ib-column ib-column-6 ib-td" style="border-left: 2px solid grey;">
                        <div class="ib-row" style="font-size: larger;" id="lead-tabs">
                            <div class="ib-column ib-column-3 text-center"><i class="fa fa-envelope-square fa-5x ib-blue ib-pointer"></i></div>
                            <div class="ib-column ib-column-3 text-center"><i class="fa fa-facebook-official fa-5x ib-blue ib-pointer inactive"></i></div>
                            <div class="ib-column ib-column-3 text-center"><i class="fa fa-linkedin-square fa-5x ib-blue ib-pointer inactive"></i></div>
                            <div class="ib-column ib-column-3 text-center"><i class="fa fa fa-google-plus-square fa-5x ib-blue ib-pointer inactive"></i></div>
                        </div>
                        <div class="ib-row" style="font-size: larger;" id="lead-share-carrot">
                            <div class="ib-column ib-column-3 text-center"><i class="fa fa-sort-asc ib-orange fa-3x"></i></div>
                            <div class="ib-column ib-column-3 text-center" style="visibility:hidden;"><i class="fa fa-sort-asc ib-orange fa-3x"></i></div>
                            <div class="ib-column ib-column-3 text-center" style="visibility:hidden;"><i class="fa fa-sort-asc ib-orange fa-3x"></i></div>
                            <div class="ib-column ib-column-3 text-center" style="visibility:hidden;"><i class="fa fa-sort-asc ib-orange fa-3x"></i></div>
                        </div>
                    </div>
                </div>
                <div class="ib-row">
                    <div class="fl ib-column-1">
                        <span class="fa-stack fa-4x">
                            <i class="fa fa-circle-thin fa-stack-2x"></i>
                            <i class="fa fa-share-square-o fa-stack-1x"></i>
                        </span>
                    </div>
                    <div class="ib-column-11 fl" style="border-bottom: 3px dashed grey;margin-top:70px;margin-left: 1em;"></div>
                </div>
                <script>
                    $(function(){
                        $("#lead-tabs").ib_leadTabs();
                    });
                </script>
            </section>

            <section id="ib-share-content">
                <div class="ib-row">
                    <div id="lead-share-email">
                        <div class="fr ib-column-4">
                            &nbsp;
                        </div>
                        <div class="fr ib-column-8 text-right">
                            <form action="<?php echo admin_url("admin-post.php"); ?>" method="post">
                                <input type="hidden" name="action" value="add_ib_lead_history"/>
                                <input type="hidden" name="history_type" value="<?php echo BREW_LEAD_HISTORY_TYPE_SHARED; ?>" />
                                <?php wp_nonce_field( 'add_ib_lead_note', 'add_ib_lead_note_nonce' ); ?>
                                <div class="ib-form">
                                    <label for="share_item">Share:</label>
                                    <select name="type">
                                        <option>Media</option>
                                        <option>Other</option>
                                        <option>Something</option>
                                    </select>
                                    <select name="share_item">
                                        <option>Some thing that is dependent on type select</option>
                                    </select>
                                </div>
                                <div class="ib-form">
                                    <label for="subject">Subject:</label>
                                    <input type="text" name="subject" style="border: 2px solid grey;width:60%;"/>
                                </div>
                                <div class="ib-form">
                                <label style="vertical-align: top;" for="message">message:</label>
                                <textarea style="width: 60%;height:20em;border: 2px solid grey;" name="message" id="message"></textarea>
                                </div>
                                <input type="hidden" name="lead_id" value="<?php echo $lead->lead_id; ?>" /><br />
                                <input class="ib-button" type="submit" name="submit" value="SUBMIT" data-type="submit"/>
                                <input class="ib-button cancel" type="button" value="CANCEL"  onclick="window.location='admin.php?page=ib-leads-admin&section=lead&id='+<?php echo $lead->lead_id; ?>; return false;">
                            </form>
                        </div>
                    </div>
                    <div id="lead-share-fb"></div>
                    <div id="lead-share-linkin"></div>
                    <div id="lead-share-gplus"></div>
                </div>
            </section>
        </div>
    </section>
</div>

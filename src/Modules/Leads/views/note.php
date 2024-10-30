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
                    <b>Use Notes to help you track any communication
                        or activity you have with this lead.</b>
                    <p>

                    </p>
                </div>
            </div>
            <div class="ib-row">
                <div class="fl ib-column-1">
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle-thin fa-stack-2x"></i>
                        <i class="fa fa-file-text-o fa-stack-1x"></i>
                    </span>
                </div>
                <div class="ib-column-11 fl" style="border-bottom: 3px dashed grey;margin-top:70px;margin-left: 1em;"></div>
            </div>
            <div class="ib-row">
                <div class="fr ib-column-4">
                    &nbsp;
                </div>
                <div class="fr ib-column-8 text-right">
                    <form action="<?php echo admin_url("admin-post.php"); ?>" method="post">
                        <input type="hidden" name="action" value="add_ib_lead_history"/>
                        <input type="hidden" name="history_type" value="<?php echo BREW_LEAD_HISTORY_TYPE_NOTE; ?>" />
                        <?php wp_nonce_field( 'add_ib_lead_note', 'add_ib_lead_note_nonce' ); ?>
                        <label style="vertical-align: top;" for="history_note">Note:</label>
                        <textarea style="width: 60%;height:20em;border: 2px solid grey;" name="history_note" id="history_note"></textarea>
                        <input type="hidden" name="lead_id" value="<?php echo $lead->lead_id; ?>" /><br />
                        <input class="ib-button" type="submit" name="submit" value="SUBMIT" data-type="submit"/>
                        <input class="ib-button cancel" type="button" value="CANCEL"  onclick="window.location='admin.php?page=ib-leads-admin&section=lead&id='+<?php echo $lead->lead_id; ?>; return false;">
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

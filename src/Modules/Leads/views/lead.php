<style type="text/css" xmlns="http://www.w3.org/1999/html">
    div.ui-dialog {
        border: 3px solid #0083CA;
    }
    div.ui-dialog .ui-dialog-content {
        padding: 0px;
    }
    div.ui-widget-header {
        border-radius: 0px;background-color: #0083CA;
    }
</style>
<script type="application/javascript">
    (function($) {
        $(document).ready(function() {
            $('.ib_data-tables').DataTable({
                "columnDefs": [
                    { "orderable": false, "targets": [0] }
                ],
                "pageLength": 10
            });
            $(".ib-lead-rating").ib_stars({
                edit_text : "<?php echo _('edit'); ?>",
                save_text : "<?php echo _("save"); ?>"
            });

            $('[data-role="type"]').on('click',function() {
                $("#update-lead-type").toggle();
            });

            $("#lead-type-select").change(function(){
                var lead_type = $(this).val();
                $.ajax({
                    method: "POST",
                    data : {
                        'lead_type': lead_type,
                        'lead_id': $('#lead_id').data('value'),
                        'action':'update_lead_type'
                    },
                    url : ibAjax.ajaxurl,
                    success: function (data) {
                        $("#update-lead-type").toggle();
                        switch(lead_type) {
                            case '1':
                                $('#type-swatch').removeClass('ib-lead ib-customer').addClass('ib-prospect');
                                break;
                            case '2':
                                $('#type-swatch').removeClass('ib-lead ib-customer').addClass('ib-lead');
                                break;
                            case '3':
                                $('#type-swatch').removeClass('ib-lead ib-lead').addClass('ib-customer');
                                break;
                        }

                    },
                    error: function () {

                    }
                });
            });
        }); //end document ready
    })(jQuery);
</script>
<div id="ib_leads">
	<div class="fr"><a href="admin.php?page=ib-leads-admin&section=ib_edit_lead&lid=<?php echo $lead->lead_id; ?>" class="ib-button">Edit Lead</a></div>
    <?php switch ($lead->type_id) {
        case 1:
            $type_style = 'ib-prospect';
            break;
        case 2:
            $type_style = 'ib-lead';
            break;
        case 3:
            $type_style = 'ib-customer';
            break;
    }
    ?>
    <div class="ib-row" style="border-bottom:2px solid grey;margin:1em 0"></div>
    <section id="lead_data" style="margin-bottom:2em;">
        <div class="ib-row">
            <div class="ib-column ib-column-6">
                <div id="lead_id" data-value="<?php echo $lead->lead_id; ?>"></div>
                <div id="update-left" style="display: none"></div>
                <div id="name">
                    <h2><?php echo isset($lead->lead_name)?$lead->lead_name:''; ?></h2>
                </div>
                <div id="email" style="color: #0083CA;">
                    <i class="fa fa-envelope-o"></i> <?php echo isset($lead->lead_email)?$lead->lead_email:'NA'; ?>
                </div>
                <div id="address">
                    <?php echo $lead->lead_address; ?>
                </div>
                <div>
                    <?php echo isset($lead->lead_city)?$lead->lead_city.", ":''; ?>
                    <?php echo isset($lead->lead_state)?$lead->lead_state." ":''; ?>
                    <?php echo isset($lead->lead_postal)?$lead->lead_postal:''; ?>
                </div>
                <div id="country">
                    <?php echo @$lead->country->country_name; ?>
                </div>
            </div>
            <div class="ib-column ib-column-6 ib-td" style="border-left: 2px solid grey;">
                <div id="update-right" class="alert" style="display: none"></div>
                <div>
                    <b>Type:</b> <span id="type-swatch" class="lead-type <?php echo $type_style; ?>"></span> <span class="ib-link" data-role="type">[edit]</span>
                </div>
                <div id="update-lead-type" style="display: none;">
                    <select id="lead-type-select">
                        <option value="1" <?php echo ($lead->type_id == 1)?'selected':''; ?>>Prospect</option>
                        <option value="2" <?php echo ($lead->type_id == 2)?'selected':''; ?>>Lead</option>
                        <option value="3" <?php echo ($lead->type_id == 3)?'selected':''; ?>>Customer</option>
                    </select>
                </div>
                <div>
                    <b>Rating:</b>
                    <span class="ib-lead-rating" data-rating="<?php echo $lead->lead_score/20;?>" data-id="<?php echo $lead->lead_id; ?>"></span>
                </div>
                <div>
                    <b>IP:</b> <?php echo $lead->lead_ip?>
                </div>
                <div>
                    <b>Created:</b> <?php echo date('j-M-Y g:ia',strtotime($lead->created_at)); ?>
                </div>
                <div>
                    <b>Updated:</b> <?php echo date('j-M-Y g:ia',strtotime($lead->updated_at)); ?>
                </div>
            </div>
        </div>
        <div class="ib-row" style="border-bottom: 2px dashed lightgrey">
            <h2>Additional Information</h2>
        </div>
        <div class="ib-row" style="margin-top:1em;">
            <div class="ib-column ib-column-6">
                <div class="ib-row ib-td">
                    <b>Birthday</b>
                    <div>
                        <?php $i = 0;
	                        if(isset($lead->lead_dob)):
	                        	echo $Date->format(BREW_WP_DATE_FORMAT,$lead->lead_dob);
	                        else:
	                        	echo "N/A";
	                        endif;
	                        ?>
                    </div>
                </div>
            </div>
            <div class="ib-column ib-column-6">
        <?php
        if (is_array(@$options)):
            foreach($options as $key=>$value): ?>
                <div class="ib-row ib-td">
                    <b><?php echo $key; ?></b>
                    <div>
                        <?php echo implode(', ',$value);; ?>
                    </div>
                </div>
                <?php
            endforeach;
        endif; ?>
            </div>
        </div>
    <!--        <div class="fr" data-role="lead-share" style="margin-bottom: -4em;cursor: pointer;">-->
    <!--            <i class="fa fa-tasks fa-4x"></i>-->
    <!--        </div>-->
    </section>
    <section id="lead_history">
        <div class="ib-tabs ib-tab" id="ib-tabs">
            <div class="tab-wrapper noselect" style="position:relative;">
                <a href="" ib-tab-index="0" class="ib-tab-link selected">History</a>
<!--                <a href="#ib_visits" ib-tab-index="1" class="ib-tab-link">Visits</a>-->
                <div style="position:absolute;bottom:0;right:0;">
                    <a href="/wp-admin/admin.php?page=ib-leads-admin&section=note&id=<?php echo $lead->lead_id; ?>"<i class="fa fa-tasks fa-4x"></i></a>
                </div>
            </div>
            <div class="tabs">
                <!-- History Tabs -->
                <div id="ib_lead_history" class="ib-tab" ib-tab-index="0" style="display: block;">
                    <table cellpadding="0" cellspacing="0" width="100%" class="ib_data-tables stripe">
                        <thead>
                            <tr>
                                <th align="left">Lead Event</th>
                                <th align="left">Event Description</th>
                            </tr>
                        </thead>
                        <tbody>
                <?php	                
                if (isset($history)) :
                    foreach ($history as $item) :
                        switch($item->history_type) {
                            case BREW_LEAD_HISTORY_TYPE_CREATED:
                                $icon = 'fa-clock-o';
                                break;
                            case BREW_LEAD_HISTORY_TYPE_FORM_SUBMISSION:
                                $icon = 'fa-check';
                                break;
                            case BREW_LEAD_HISTORY_TYPE_NOTE:
                                $icon = 'fa-file-text-o';
                                break;
                            case BREW_LEAD_HISTORY_TYPE_SHARED:
                                $icon = 'fa-share-square-o';
                                break;
                            case BREW_LEAD_HISTORY_TYPE_CONTENT_DOWNLOADED:
                                $icon = 'fa fa-download';
                                break;
                            case BREW_LEAD_HISTORY_TYPE_DELETED:
                            	$icon = 'fa fa-trash';
                            	$user_data = get_userdata($item->wp_user_id);
                            	$item->history_note = str_replace("{{user}}", "<span class=\"ib_wp_user\">" . $user_data->first_name . " " . $user_data->last_name  . "</span>", $item->history_note);
                            	break;
							case BREW_LEAD_HISTORY_TYPE_RESTORED:
								$icon = 'fa fa-undo';
								break;
                            default:
                                $icon = 'fa-file-text-o';
                                break;
                        }
                ?>
                        <tr>
                            <td class="left">
                                <i class="fa <?php echo $icon ;?> fa-3x"></i>
                            </td>
                            <td class="left">
                                <?php echo date('j-M-Y g:ia',strtotime($item->created_at));?><br />
                                <b><?php echo $item->history_event;?></b><br />
                                <?php echo $item->history_note; ?>
                            </td>
                        </tr>
                <?php endforeach;
                endif; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Visits Tab -->
                <div id="ib_visits" class="ib-tab" ib-tab-index="1" style="display: none;"></div>
            </div>
        </div>
    </section>

    <div id="lead-management-dialog">
        <div class="ib-row ib-td">
            <a href="http://example.com/wp-admin/admin.php?page=ib-leads-admin&section=share&id=<?php echo $lead->lead_id; ?>">
                <div class="fl" style="margin-right:2em;">
                    <i class="fa fa-share-square-o fa-3x"></i>
                </div>
            </a>
            <div class="fl">
                <b>Share an Item</b><br />
                system will track when it is opened.
            </div>
            <div class="clear"></div>
        </div>
        <div class="ib-row ib-td grey">
            <a href="http://example.com/wp-admin/admin.php?page=ib-leads-admin&section=note&id=<?php echo $lead->lead_id; ?>">
                <div class="fl" style="margin-right:2em;">
                    <i class="fa fa-file-text-o fa-3x"></i>
                </div>
            </a>
            <div class="fl">
                <b>Add a Note</b><br />
                comments for your own benefit
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>

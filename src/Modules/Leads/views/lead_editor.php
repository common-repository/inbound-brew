<?php
    $picture = @$Form->data['Lead']['lead_picture'];
    if ($picture)
        $picture_style = "background-image:url({$picture})";
    $name = @$Form->data['Lead']['lead_first_name']." ".@$Form->data['Lead']['lead_last_name'];
    if ($name):
        $name_class = "";
    else:
        $name_class = "empty";
        $name = "Lead Name";
    endif;
    $params = (@$lead_id) ? array() : array("required" => true);
?>
<div id="lead_editor">
    <?php
    echo $Form->create($form_action, array('class' => "ib_lead-form", 'url' => admin_url('admin-post.php'), 'id' => "lead_editor_form"));
    wp_nonce_field('ib_save_lead_nonce');
    ?>
    <div class="ib-lead-picture" style="<?php echo @$picture_style; ?>" id="lead_preview_image">
        <div class="edit-hover">Change</div>
        <?php echo $Form->hidden("Lead.lead_picture", $picture); ?>
    </div>
    <div class="lead-info">
        <div class="lead-name <?php echo $name_class; ?>" id="lead-name"><?php echo $name; ?></div>
        <div class="lead-description" id="lead-description">
            <?php if ($email = @$Form->data['Lead']['lead_email']): ?>
                <div class='lead-email'><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></div>
                <?php
            endif;
            if ($phone = @$Form->data['Lead']['lead_phone']):
                ?>
                <div class="lead-phone"><?php echo $phone; ?></div>
            <?php endif; ?>
        </div>
        <?php if (isset($Form->data['Lead']) && @$Form->data['Lead']['deleted_at']): ?>
            <a class="ib-button delete" href="#" id="ib_restore_lead_button"><span class="fa fa-undo"></span> Restore Lead</a>
        <?php endif; ?>
    </div>
    <div class="clear"></div>
    <div class="g6 ib-margin-top ib-lead-edit-wrapper">
        <div class="fr ib-lead-settings-icon"><a href="admin.php?page=ib-admin-settings&section=ib_leads_settings" class="ib-button ib_fa-button"><span class="fa fa-cog"></span></a></div>
        <div class="ib-uc-header">Contact Info</div>
        <div class="clear"></div>
        <div class="ib-section">
            <div class='ib-row'>
                <div class="g3">
                    <label>Type:</label>
                    <?php
                    echo $Form->select("Lead.type_id", array(
                        1 => "Prospect",
                        2 => "Lead",
                        3 => "Customer",
                        4 => "Affiliate"
                    ));
                    ?>
                </div>
                <div class="g3">
                    <label>Assigned To:</label>
                    <?php echo $Form->select("Lead.assigned_to", $users, array("empty" => "Choose One")); ?>
                </div>
                <div class="g3">
                    <label>Birthday:</label>
                    <?php echo $Form->text("Lead.lead_dob", array(false, "class" => "ib-date")); ?>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <div class="ib-section">
            <div class="ib-row">
                <div class="g6">
                    <label>First Name:</label>
                    <?php echo $Form->text("Lead.lead_first_name"); ?>
                </div>
                <div class="g6">
                    <label>Last Name:</label>
                    <?php echo $Form->text("Lead.lead_last_name"); ?>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <div class="ib-section">
            <div class="ib-row">
                <div class="g6">
                    <label>* Email Address:</label>
                    <?php echo $Form->email("Lead.lead_email", $params); ?>
                </div>
                <div class="g6">
                    <label>Email Address2:</label>
                    <?php echo $Form->email("Lead.lead_email2"); ?>
                </div>
                <div class="clear"></div>
            </div>
            <div class="ib-row">
                <div class="g6">
                    <label>Phone Number:</label>
                    <?php echo $Form->text("Lead.lead_phone"); ?>
                </div>
                <div class="g6">
                    <label>Phone Number2:</label>
                    <?php echo $Form->text("Lead.lead_phone2"); ?>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <div class="ib-section">
            <div class="ib-row">
                <div class="g6">
                    <label>Address:</label>
                    <?php echo $Form->text("Lead.lead_address"); ?>
                </div>
                <div class="g6">
                    <label>Address 2:</label>
                    <?php echo $Form->text("Lead.lead_address2"); ?>
                </div>
                <div class="clear"></div>
            </div>
            <div class="ib-row">
                <div class="g12">
                    <label>Country:</label>
                    <?php echo $Form->select("Lead.country_id", $countries); ?>
                </div>
                <div class="clear"></div>
            </div>
            <div class="ib-row">
                <div class="g6">
                    <label>City:</label>
                    <?php echo $Form->text("Lead.lead_city"); ?>
                </div>
                <div class="g3" id="us_states">
                    <label>State:</label>
                    <?php echo @$Form->select("Lead.us_state", $states, array('selected' => $Form->data['Lead']['lead_state'])); ?>
                </div>
                <div class="g3" id="nonus_states">
                    <label>State/Province:</label>
                    <?php echo @$Form->text("Lead.non_state", array('selected' => $Form->data['Lead']['lead_state'])); ?>
                </div>
                <div class="g3">
                    <label>Zip Code:</label>
                    <?php echo $Form->text("Lead.lead_postal"); ?>
                </div>
                <div class="clear"></div>
            </div>
        </div>

        <?php 
        ?>
        <div class="ib-section">
            <div class="ib-row">
                <div class="g6">
                    <label>Facebook:</label>
                    <?php echo $Form->text("Lead.lead_social_facebook"); ?>
                </div>
                <div class="g6">
                    <label>Twitter:</label>
                    <?php echo $Form->text("Lead.lead_social_twitter"); ?>
                </div>
                <div class="clear"></div>
            </div>
            <div class="ib-row">
                <div class="g6">
                    <label>LinkedIn:</label>
                    <?php echo $Form->text("Lead.lead_social_linkedin"); ?>
                </div>
                <div class="g6">
                        <label>Opt-In:</label>
                <?php echo $Form->checkbox("Lead.lead_opt_in", "1"); ?>
                </div> 
                <div class="clear"></div>
            </div>
        </div>
        <div class="ib-form-divider">* required fields</div>
        <div class="ib-header">Custom Fields:</div>
        <?php
        if (!empty($custom_fields)):
            $in_row = 1;
            foreach ($custom_fields as $field):
                ?>
                <div class="ib-section">
                    <div class="ib-row">
                        <label><?php if ($field['field_type'] != "singlecheckbox") echo $field['field_name']; ?></label>
                        <?php
                        $type = $field['field_type'];
                        switch ($type):
                            case "text":
                                echo $Form->text("LeadData." . $field['field_token']);
                                break;
                            case "textarea":
                                echo $Form->textarea("LeadData." . $field['field_token']);
                                break;
                            case "date":
                                echo $Form->text("LeadData." . $field['field_token'], array("class" => "ib-date"));
                                break;
                            case "checkbox":
                                $values = explode("\n", $field['field_value']);
                                $options = array();
                                foreach ($values as $value):
                                    $value = rtrim(str_replace("\r", "", $value));
                                    $options[$value] = $value;
                                endforeach;
                                echo $Form->checkboxes("LeadData." . $field['field_token'], $options);
                                break;
                            case "singlecheckbox" :
                                echo $Form->singlecheckbox("LeadData." . $field['field_token'], rtrim(str_replace("\r", "", $field['field_value'])), array('label' => $field['field_name']));
                                break;
                            case "select":
                                $values = explode("\n", stripslashes($field['field_value']));
                                $options = array();
                                foreach ($values as $value):
                                    $value = rtrim(str_replace("\r", "", $value));
                                    $options[$value] = $value;
                                endforeach;
                                echo $Form->select("LeadData." . $field['field_token'], $options, array('label' => false, 'empty' => "Choose One"));
                                break;
                            case "radio":
                                $values = explode("\n", stripslashes($field['field_value']));
                                $options = array();
                                foreach ($values as $value):
                                    $value = rtrim(str_replace("\r", "", $value));
                                    $options[$value] = $value;
                                endforeach;
                                echo $Form->radio("LeadData." . $field['field_token'], $options);
                                break;
                        endswitch;
                        ?>
                    </div>
                </div>
                <?php
            endforeach;
        else:
            ?>
            <p>You have no custom field created. To create custom fields click on the settings icon, or visit the Setting module and create them under <a href="admin.php?page=ib-admin-settings&section=ib_leads_settings">"Lead Management."</a></p>
        <?php endif; ?>
        </div>
    <?php if (@!$lead_id): ?>
        <div id="lead-form-buttons" class="ib-form-buttons">
            <button id="submit_form" class="ib_save ib-button">Save Lead</button>
            <button id="cancel_form" class="ib_cancel ib-button">Cancel</button>
        </div>
    <?php endif; ?>
    <?php echo $Form->end(); ?>
    <!-- lead activity log -->
    <?php if (@$lead_id): ?>
        <div class="g6 ib-margin-top" id="lead_activity_log">
            <div class="fr">
                <a href="" id="new_activity" class="ib-button">
                    <span class='fa fa-plus'></span> New Activity
                    <ul class="ib_action-menu">
                        <li data-action="comment"><span class="fa fa-comment"> </span> Add Comment</li>
                        <li data-action="phone"><span class="fa fa-phone-square"> </span> Add Phone Call</li>
                        <li data-action="email"><span class="fa fa-envelope"> </span> Send Email</li>
                    </ul>
                </a>
            </div>
            <div class="ib-uc-header">Activity Log</div>
            <div class="clear"></div>
            <table class="ib_data-tables ib_lead-log" cellpadding="0" cellspacing="0">
                <thead>
                <th>d</th>
                <th>i</th>
                <th>a</th>
                <th>u</th>
                </thead>
                <tbody>
                    <?php
                    foreach ($lead_history as $item):
                        $match = true;
                        $add_edit = false;
                        ?>
                        <tr id="lead_history_<?php echo $item->history_id; ?>" data-id="<?php echo $item->history_id; ?>">
                            <td><span class="ib-sortable-date"><?php echo $Date->format("YmdHis", $item->created_at); ?></span><?php echo $Date->format(BREW_WP_DATE_FORMAT . " " . BREW_WP_TIME_FORMAT, $item->created_at, true); ?></td>
                            <td>
                                <?php
                                switch ($item->history_type):
                                    case BREW_LEAD_HISTORY_TYPE_CREATED:
                                        $icon = 'fa-user';
                                        break;
                                    case BREW_LEAD_HISTORY_TYPE_UPDATED:
                                        $icon = 'fa-pencil';
                                        break;
                                    case BREW_LEAD_HISTORY_TYPE_FORM_SUBMISSION:
                                        $icon = 'fa-check';
                                        break;
                                    case BREW_LEAD_HISTORY_TYPE_NOTE:
                                        $icon = 'fa-comment';
                                        $add_edit = true;
                                        break;
                                    case BREW_LEAD_HISTORY_TYPE_PHONE_CALL:
                                        $icon = 'fa-phone-square';
                                        $add_edit = true;
                                        break;
                                    case BREW_LEAD_HISTORY_TYPE_EMAIL:
                                        $icon = 'fa-envelope';
                                        $url = "admin.php?page={$post_type}&action=ib_preview_lead_email&history_id={$item->history_id}";
                                        $item->history_note = "To view sent email <a href=\"{$url}\" class=\"ib_preview_sent_email\">click here</a>.";
                                        break;
                                    case BREW_LEAD_HISTORY_TYPE_SHARED:
                                        $icon = 'fa-share-square-o';
                                        break;
                                    case BREW_LEAD_HISTORY_TYPE_CONTENT_DOWNLOADED:
                                        $icon = 'fa-download';
                                        break;
                                    case BREW_LEAD_HISTORY_TYPE_DELETED:
                                        $icon = 'fa-archive';
                                        $user_data = get_userdata($item->wp_user_id);
                                        $item->history_note = str_replace("{{user}}", "<span class=\"ib_wp_user\">" . $user_data->first_name . " " . $user_data->last_name . "</span>", $item->history_note);
                                        break;
                                    case BREW_LEAD_HISTORY_TYPE_RESTORED:
                                        $icon = 'fa-undo';
                                        break;
                                    case BREW_LEAD_HISTORY_TYPE_PICTURE:
                                        $icon = 'fa-picture-o';
                                        break;
                                    case BREW_LEAD_HISTORY_ASSIGNED:
                                        $icon = 'fa-arrow-right';
                                        $match = false;
                                        preg_match("/\{\{user:(.*)\}\}/", $item->history_event, $matches);
                                        if (!empty($matches)) {
                                            $user = $matches[1];
                                            $token = '{{user:' . $user . "}}";
                                            $user_data = get_userdata($user);
                                            $item->history_event = str_replace($token, $user_data->first_name . " " . $user_data->last_name, $item->history_event);
                                        }
                                        break;
                                    default:
                                        $icon = 'fa-file-text-o';
                                        break;
                                endswitch;
                                ?>
                                <span class="fa <?php echo $icon; ?>"> </span>
                            </td>
                            <td>
                                <div class="history-event"><?php
                                    if ($match) {
                                        preg_match("/\{\{(.*)\}\}/", $item->history_event, $matches);
                                        if (!empty($matches)) {
                                            $token = $matches[1];
                                            $item->history_event = str_replace("{{" . $token . "}}", $field_tokens[$token], $item->history_event);
                                        }
                                    }
                                    echo $item->history_event;
                                    ?></div>
                                <div class="history-note"><?php
                                    echo stripslashes($item->history_note);
                                    if ($add_edit)
                                        echo ' <a href="" class="ib_edit_history">[edit]</a>';
                                    ?></div>
                            </td>
                            <td class="history-user">
                                <?php
                                if ($item->wp_user_id):
                                    $user_info = get_userdata($item->wp_user_id);
                                    $first_name = $user_info->first_name;
                                    $last_name = $user_info->last_name;
                                    echo $first_name . " " . $last_name;
                                else:
                                    echo "System";
                                endif;
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    <?php
    if (@$lead_id):
        $Form->hiddenFields = false;
        echo $Form->create("ib_update_lead_field", array("style" => "display:none;"));
        echo $Form->hidden("lead_id", $lead_id);
        echo $Form->textarea("clone", array("id" => "lead_field_value"));
        echo $Form->end();
        ?>
    <?php endif; ?>
</div>
<?php
    if (@$lead_id):
?>
<div id="ib_activity_dialog" title="New Activity">
    <?php
    echo $Form->create("ib_lead_activity");
    echo $Form->hidden("LeadActivity.history_id", "");
    echo @$Form->hidden("LeadActivity.lead_id", $lead_id);
    echo $Form->hidden("LeadActivity.activity_type", "", array("id" => "lead_activity_type"));
    echo $Form->textarea("LeadActivity.comment", array('div' => false, 'rows' => 10));
    echo $Form->end();
    ?>
</div>
<div id="ib_email_dialog" title="Send Email To Lead:">
    <div id="ib_email_dialog_body">
        <?php
        echo $Form->create("ib_send_lead_email", array('class' => "ib-form"));
        echo $Form->textarea("LeadEmail.message_body", array('style' => "display:none", "div" => false));
        wp_nonce_field('ib_send_lead_email_form');
        echo @$Form->hidden('LeadEmail.lead_id', $lead_id);
        ?>
        <?php
        echo $Form->radio('LeadEmail.email_type', array(
            'email' => "Choose Previously Created Email",
            'custom' => "Custom Email"));
        ?>
        <div id="ib_choose_email">
            <label>Email:</label>
            <?php echo @$Form->select('LeadEmail.email_id', $emails, array('empty' => "Choose One")); ?>
        </div>
        <div id="ib_choose_template" style="display:none;">
            <label>Template:</label>
            <?php echo $Form->select('LeadEmail.email_template_id', $templates, array('empty' => "Choose One")); ?>
            <label>Subject:</label>
            <?php echo $Form->text('LeadEmail.email_subject'); ?>
            <?php
            $Form->wpEditor("LeadEmail.email_body", array(), array(
                'div' => false));
            ?>
        </div>
        <?php echo $Form->end(); ?>
    </div>
</div>
<?php endif; ?>
<script type="text/javascript">
    jQuery(document).ready(function($){
    $("#lead_editor").ib_leadEditor({
    post_type:"<?php echo $post_type; ?>",
<?php if (@$lead_id): ?>
        lead_id:<?php echo $lead_id; ?>,
<?php endif; ?>
    mode:"<?php echo (@$lead_id) ? "edit" : "add"; ?>"
    });
    });
</script>
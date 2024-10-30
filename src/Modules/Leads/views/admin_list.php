<div id="admin-leads-list">
    <div class="ib_list-buttons"><a href="admin.php?page=<?php echo $post_type; ?>&section=ib_add_lead" class="ib-button"><span class="fa fa-plus"></span> New Lead</a></div>
    <ul class="ib_lead-views" id="ib_lead_views">
        <?php
        foreach ($views as $index => $view):
            $active = ($index == $active_view) ? "active" : "";
            ?>
            <li class="<?php echo $active; ?>" data-view="<?php echo $view['lead_view_id']; ?>">
                <?php echo $view['view_name']; ?>
                <?php if ($index != "all"): ?>
                    <span class="fa fa-trash"></span>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <div class="ib_lead-view-header">
        <div class="settings-buttons">
            <div class="settings-button" id="filters-button">
                <span class="fa fa-filter ib_tipsy" title="Lead Filters"></span>
                <span class="bubble" id="total-filters" style="display: none;"></span>
            </div>
            <div class="settings-button" id="columns-button">
                <span class='fa fa-columns'  title="Table Columns"></span>
            </div>
            <?php echo $Layout->element($partials_path . "leads_filters", array('filters' => $filters, 'Form' => $Form)); ?>
            <?php echo $Layout->element($partials_path . "leads_columns", array('filters' => $filters, 'Form' => $Form, 'active_columns' => $view['view_columns'])); ?>
        </div>
        <div class="search-box"><input id="lead-table-search" type="text" placeholder="Search Leads"></div>
        <div class='view-action-buttons' id="view_action_buttons" style="display:none;">
            <button class="ib-button" id="update_view_button">Update View</button>
            <div class="ib_new_view-form">
                <input type="text" name="view_name" id="new_view_view_name" placeholder="View Name" style="display: none;">
                <button class='ib-button ib_cancel' id="cancel_create_new_view_btn" style="display: none;">Cancel</button>
                <button class="ib-button green" id="create_new_view_btn"><span class="fa fa-plus"></span> New View</button>
            </div>
        </div>
        <div id="leads-table-view">
            <table cellpadding="0" cellspacing="0" class="ib_data-tables leads-listing" style="display:none;">
                <thead>
                    <?php foreach ($filters['static_fields'] as $token => $values): ?>
                    <th data-field="<?php echo $token; ?>"><?php echo $values["label"]; ?></th>
                <?php endforeach; ?>
                <?php
                if (@$filters['custom_fields']):
                    foreach ($filters['custom_fields'] as $token => $values):
                        ?>
                        <th data-field="<?php echo $token; ?>"><?php echo $values["label"]; ?></th>
                        <?php
                    endforeach;
                endif;
                ?>
                <th class="ib_tools">&nbsp;</th>
                <th>&nbsp;</th>
                </thead>
                <tbody>
                    <?php
                    foreach ($leads as $lead):
                        $edit_url = "admin.php?page={$post_type}&section=lead&id=" . $lead->lead_id;
                        $archive_url = "admin.php?page={$post_type}&section=archive&id=" . $lead->lead_id;
                        $archived = ($lead->trashed()) ? "ib_lead-archived" : "";
                        ?>
                        <tr lead-id="<?php echo $lead->lead_id; ?>" lead-name="<?php echo $lead->lead_name; ?>" class="<?php echo $archived; ?>">
                            <?php
                            foreach ($filters['static_fields'] as $token => $values):
                                $class = ($token == "lead_name") ? "ib_lead-name" : "";
                                ?>
                                <td class="<?php echo $class; ?>">
                                    <?php
                                    switch ($token):
                                        case "lead_first_name":
                                        case "lead_last_name":
                                        case "lead_name":
                                        case "lead_email":
                                            echo "<a href=\"{$edit_url}\">" . $lead->$token . "</a>";
                                            break;
                                        case "created_at":
                                        case "updated_at":
                                            echo "<span class=\"ib-sortable-date\">" . $Date->format("YmdHis", $lead->$token) . "</span>";
                                            echo $Date->format(BREW_WP_DATE_FORMAT . " " . BREW_WP_TIME_FORMAT, $lead->$token);
                                            break;
                                        case "lead_dob":
                                            if ($lead->$token == "0000-00-00"):
                                                echo "&nbsp;";
                                            else:
                                                echo "<span class=\"ib-sortable-date\">" . $Date->format("Ymd", $lead->$token) . "</span>";
                                                echo $Date->format(BREW_WP_DATE_FORMAT, $lead->$token);
                                            endif;
                                            break;
                                        case "assigned_to":
                                            //echo $Form->select("assigned_to",$users,array("selected"=>$lead->$token));
                                            echo @$users[$lead->$token];
                                            break;
                                        case "type_id":
                                            $types = array(1 => "Prospect", 2 => "Lead", 3 => "Customer", 4 => "Affiliate");
                                            /* echo $Form->select("type_id",array(
                                              1=>"Prospect",
                                              2=>"Lead",
                                              3=>"Customer"),array("selected"=>$lead->$token)); */
                                            echo $types[$lead->$token];
                                            break;
                                        default;
                                            echo $lead->$token;
                                            break;
                                    endswitch;
                                    ?>
                                </td>
                                <?php
                            endforeach;
                            if (@$filters['custom_fields']):
                                $leadData = $lead->leadData()->get();
                                foreach ($filters['custom_fields'] as $token => $values):
                                    ?>
                                    <td><?php
                                        $value = "&nbsp;";
                                        if (count($leadData) > 0):
                                            foreach ($leadData as $field):
                                                if ($field->data_term == $token):
                                                    $value = $field->data_value;
                                                    if ($values['type'] == "date"):
                                                        echo "<span class=\"ib-sortable-date\">" . $Date->format("Ymd000000", $value) . "</span>";
                                                        $value = $Date->format(BREW_WP_DATE_FORMAT, $value);
                                                    endif;
                                                    break;
                                                endif;
                                            endforeach;
                                        endif;
                                        echo $value;
                                        ?></td>
                                    <?php
                                endforeach;
                            endif
                            ?>
                            <td class="ib_tools">
                                <a href="<?php echo $edit_url; ?>" class="ib_icon-link fa fa-eye ib_tipsy" title="View Lead"></a>
                                <a href="<?php echo $archive_url; ?>" class="ib_icon-link fa fa-archive ib_archive-lead ib_tipsy" title="Archive Lead"></a>
                                <a href="#" class="ib_icon-link fa fa-undo ib_restore-lead ib_tipsy" title="Restore Lead"></a>
                                <a href="#" class="ib_icon-link ib_add-activity">
                                    <span class="fa fa-plus"></span>
                                    <ul class="ib_action-menu triangle-isosceles">
                                        <li data-action="comment"><span class="fa fa-comment"> </span> Add Comment</li>
                                        <li data-action="phone"><span class="fa fa-phone-square"> </span> Add Phone Call</li>
                                        <li data-action="email"><span class="fa fa-envelope"> </span> Send Email</li>
                                    </ul>
                                </a>
                            </td>
                            <td class="details-control">
                                <span class="fa fa-chevron-right ib_tipsy" title="Recent History"></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div id="ib_activity_dialog" title="New Activity">
        <?php
        echo $Form->create("ib_lead_activity");
        echo $Form->hidden("LeadActivity.history_id", "");
        echo $Form->hidden("LeadActivity.lead_id", "0");
        echo $Form->hidden("LeadActivity.activity_type", "", array("id" => "lead_activity_type"));
        echo $Form->textarea("LeadActivity.comment", array('div' => false, 'rows' => 10));
        echo $Form->end();
        ?>
    </div>
    <div id="ib_email_dialog" title="Send Email To Lead:">
        <div id="ib_email_dialog_body">
            <?php
            echo $Form->create("ib_send_lead_email", array('class' => "ib-form"));
            wp_nonce_field('ib_send_lead_email_form');
            echo $Form->textarea("LeadEmail.message_body", array('style' => "display:none", "div" => false));
            echo $Form->hidden('LeadEmail.lead_id', "0");
            ?>
            <?php
            echo $Form->radio('LeadEmail.email_type', array(
                'email' => "Choose Previously Created Email",
                'custom' => "Custom Email"));
            ?>
            <div id="ib_choose_email">
                <label>Email:</label>
                <?php echo $Form->select('LeadEmail.email_id', $emails, array('empty' => "Choose One")); ?>
            </div>
            <div id="ib_choose_template" style="display:none;">
                <label>Template:</label>
                <?php echo $Form->select('LeadEmail.email_template_id', $templates, array('empty' => "Choose One")); ?>
                <label>Subject:</label>
                <?php echo $Form->text('LeadEmail.email_subject'); ?>
                <?php
                $Form->wpEditor("LeadEmail.email_body", array(
                    'textarea_rows' => '10'), array(
                    'div' => false));
                ?>
            </div>
            <?php echo $Form->end(); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $("#admin-leads-list").ib_leadsList({
            filters:<?php echo json_encode($Layout->utf8ize($filters)); ?>,
            views:<?php echo json_encode($Layout->utf8ize($views)); ?>,
            active_view: "<?php echo $active_view; ?>"
        });

    });
</script>
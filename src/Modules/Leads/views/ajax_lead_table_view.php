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
                        case "lead_name":
                            echo "<a href=\"{$edit_url}\">" . $lead->$token . "</a><br><span class=\"ib_email\">" . $lead->lead_email . "</span>";
                            break;
                        case "created_at":
                        case "updated_at":
                            echo "<span class=\"ib-sortable-date\">" . $Date->format("YmdHis", $lead->$token) . "</span>";
                            echo $Date->format(BREW_WP_DATE_FORMAT . " " . BREW_WP_TIME_FORMAT, $lead->$token, true);
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
                            $types = array(1 => "Prospect", 2 => "Lead", 3 => "Customer");
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
                                        $value = $Date->format(BREW_WP_DATE_FORMAT, $value, true);
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